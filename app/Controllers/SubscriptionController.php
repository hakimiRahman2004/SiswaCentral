<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Core\Response;
use App\Repositories\ProviderRepository;
use App\Repositories\SubscriptionRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\NotificationRepository;

class SubscriptionController extends Controller
{
    private ProviderRepository $providerRepo;
    private SubscriptionRepository $subRepo;
    private PaymentRepository $paymentRepo;
    private NotificationRepository $notificationRepo;

    public function __construct()
    {
        parent::__construct();
        $this->providerRepo = new ProviderRepository();
        $this->subRepo = new SubscriptionRepository();
        $this->paymentRepo = new PaymentRepository();
        $this->notificationRepo = new NotificationRepository();
    }

    private function getProvider(): array
    {
        return $this->providerRepo->findByUserId(Auth::id());
    }

    public function index(): void
    {
        $provider = $this->getProvider();
        $plans = $this->subRepo->getAllPlans();
        $currentSub = $this->subRepo->findActiveByProvider($provider['id']);
        $history = $this->subRepo->findByProvider($provider['id']);

        $this->view('provider/subscription', [
            'title' => 'Subscription',
            'plans' => $plans,
            'currentSub' => $currentSub,
            'history' => $history,
        ]);
    }

    public function subscribe(Request $request): void
    {
        $provider = $this->getProvider();
        $planId = $request->input('plan_id');
        $plan = $this->subRepo->findPlanById($planId);

        if (!$plan) {
            Session::flash('error', 'Invalid plan');
            Response::back();
        }

        $startDate = date('Y-m-d');
        $endDate = date('Y-m-d', strtotime("+{$plan['duration_days']} days"));

        $subId = $this->subRepo->create([
            'provider_id' => $provider['id'],
            'plan_id' => $plan['id'],
            'status' => 'pending',
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        // Free plan — cancel old sub, activate immediately
        if ($plan['price'] <= 0) {
            $active = $this->subRepo->findActiveByProvider($provider['id']);
            if ($active) {
                $this->subRepo->update($active['id'], ['status' => 'cancelled']);
            }
            $this->subRepo->update($subId, ['status' => 'active']);
            Session::flash('success', 'Free plan activated');
            Response::redirect('/provider/subscription');
        }

        // Paid plan — don't cancel old sub yet (user might abandon payment)
        // Old sub stays active until payment callback confirms
        $this->redirectToToyyibPay($subId, $plan);
    }

    private function redirectToToyyibPay(int $subId, array $plan): void
    {
        $toyyibKey = env('TOYYIBPAY_SECRET_KEY', '');
        $toyyibCategory = env('TOYYIBPAY_CATEGORY', '');
        $toyyibUrl = 'https://dev.toyyibpay.com/';
        $user = Auth::user();
        $provider = $this->getProvider();

        // Our internal reference
        $externalRef = 'PRV-' . $subId . '-' . time();

        $postData = [
            'userSecretKey' => $toyyibKey,
            'categoryCode' => $toyyibCategory,
            'billName' => 'SISWACentral ' . $plan['name'] . ' Plan',
            'billDescription' => $provider['company_name'] . ' - ' . $plan['name'] . ' subscription',
            'billPriceSetting' => 1,
            'billPayorInfo' => 1,
            'billAmount' => (int) ($plan['price'] * 100),
            'billReturnUrl' => config('app.url') . '/provider/subscription/success',
            'billCallbackUrl' => config('app.url') . '/provider/subscription/callback',
            'billExternalReferenceNo' => $externalRef,
            'billTo' => $user['name'] ?? '',
            'billEmail' => $user['email'] ?? '',
            'billPhone' => $provider['phone'] ?? '',
        ];

        $ch = curl_init($toyyibUrl . 'index.php/api/createBill');
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($postData),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($httpCode === 200 && $result) {
            $response = json_decode($result, true);
            if (isset($response[0]['BillCode'])) {
                $toyyibBillCode = $response[0]['BillCode'];

                // Store payment record with both references
                $this->paymentRepo->create([
                    'subscription_id' => $subId,
                    'provider_id' => $provider['id'],
                    'bill_code' => $externalRef,
                    'toyyib_bill_code' => $toyyibBillCode,
                    'amount' => $plan['price'],
                    'status' => 'pending',
                ]);

                Response::redirect($toyyibUrl . $toyyibBillCode);
            }
        }

        // Payment gateway error — cancel subscription
        $this->subRepo->update($subId, ['status' => 'cancelled']);
        Session::flash('error', 'Payment gateway error. Please try again.');
        Response::back();
    }

    public function callback(Request $request): void
    {
        // ToyyibPay sends: billcode, status_id, billExternalReferenceNo
        $refNo = $request->input('billExternalReferenceNo');
        $billCode = $request->input('billcode');
        $statusId = $request->input('status_id');

        if (!$refNo && !$billCode) {
            http_response_code(400);
            echo 'Missing reference';
            return;
        }

        // Try external ref first, then ToyyibPay billcode
        $payment = $refNo
            ? $this->paymentRepo->findByBillCode($refNo)
            : $this->paymentRepo->findByToyyibBillCode($billCode);

        if (!$payment) {
            http_response_code(404);
            echo 'Payment not found';
            return;
        }

        // Avoid re-processing
        if ($payment['status'] === 'success') {
            echo 'OK';
            return;
        }

        $isSuccess = $statusId === '1';
        $newStatus = $isSuccess ? 'success' : 'failed';

        $this->paymentRepo->update($payment['id'], [
            'status' => $newStatus,
            'paid_at' => $isSuccess ? date('Y-m-d H:i:s') : null,
        ]);

        if ($isSuccess && $payment['subscription_id']) {
            $this->subRepo->update($payment['subscription_id'], ['status' => 'active']);

            // Cancel any other active sub for this provider (the just-activated one is newer)
            $newSub = $this->subRepo->findById($payment['subscription_id']);
            if ($newSub) {
                $activeSubs = $this->subRepo->findByProvider($newSub['provider_id']);
                foreach ($activeSubs as $old) {
                    if ($old['id'] != $payment['subscription_id'] && $old['status'] === 'active') {
                        $this->subRepo->update($old['id'], ['status' => 'cancelled']);
                    }
                }

                $provider = $this->providerRepo->findById($newSub['provider_id']);
                $this->notificationRepo->create([
                    'user_id' => $provider['user_id'] ?? Auth::id(),
                    'type' => 'subscription',
                    'title' => 'Payment Received',
                    'message' => 'Your ' . ($newSub['plan_name'] ?? 'subscription') . ' payment has been confirmed. Your plan is now active.',
                ]);
            }
        }

        echo 'OK';
    }

    public function success(Request $request): void
    {
        $refNo = $request->get('billExternalReferenceNo');
        $billCode = $request->get('billcode');
        $statusId = $request->get('status_id');

        if ($statusId === '1' && ($refNo || $billCode)) {
            $payment = $refNo
                ? $this->paymentRepo->findByBillCode($refNo)
                : $this->paymentRepo->findByToyyibBillCode($billCode);

            if ($payment && $payment['status'] !== 'success') {
                $this->paymentRepo->update($payment['id'], [
                    'status' => 'success',
                    'paid_at' => date('Y-m-d H:i:s'),
                ]);
                if ($payment['subscription_id']) {
                    $this->subRepo->update($payment['subscription_id'], ['status' => 'active']);

                    // Cancel any other active sub for this provider
                    $newSub = $this->subRepo->findById($payment['subscription_id']);
                    if ($newSub) {
                        $activeSubs = $this->subRepo->findByProvider($newSub['provider_id']);
                        foreach ($activeSubs as $old) {
                            if ($old['id'] != $payment['subscription_id'] && $old['status'] === 'active') {
                                $this->subRepo->update($old['id'], ['status' => 'cancelled']);
                            }
                        }
                    }
                }
            }
        }

        Session::flash('success', 'Payment successful! Your subscription is now active.');
        Response::redirect('/provider/subscription');
    }

    public function cancel(): void
    {
        $provider = $this->getProvider();
        $active = $this->subRepo->findActiveByProvider($provider['id']);
        if ($active && ($active['plan_price'] ?? 0) > 0) {
            $this->subRepo->update($active['id'], ['status' => 'cancelled']);

            $starter = $this->subRepo->getAllPlans();
            $freePlan = null;
            foreach ($starter as $p) {
                if (($p['price'] ?? 999) == 0) { $freePlan = $p; break; }
            }
            if ($freePlan) {
                $this->subRepo->create([
                    'provider_id' => $provider['id'],
                    'plan_id' => $freePlan['id'],
                    'status' => 'active',
                    'start_date' => date('Y-m-d'),
                    'end_date' => date('Y-m-d', strtotime('+30 days')),
                ]);
            }

            Session::flash('success', 'Subscription cancelled. Reverted to the Starter plan.');
        }
        Response::redirect('/provider/subscription');
    }
}
