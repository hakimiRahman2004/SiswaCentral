<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Core\Response;
use App\Repositories\StudentSubscriptionRepository;
use App\Repositories\NotificationRepository;

class StudentSubscriptionController extends Controller
{
    private StudentSubscriptionRepository $subRepo;
    private NotificationRepository $notificationRepo;

    public function __construct()
    {
        parent::__construct();
        $this->subRepo = new StudentSubscriptionRepository();
        $this->notificationRepo = new NotificationRepository();
    }

    public function index(): void
    {
        $plans = $this->subRepo->getPlans();
        $subscription = $this->subRepo->getActiveSubscription(Auth::id());
        $history = $this->subRepo->getHistory(Auth::id());
        $expired = $this->subRepo->getRecentlyExpired(Auth::id());

        $this->view('student/subscription', [
            'title' => 'Subscription',
            'plans' => $plans,
            'subscription' => $subscription,
            'history' => $history,
            'expired' => $expired,
        ]);
    }

    public function subscribe(Request $request): void
    {
        $planId = (int) $request->input('plan_id');
        $plan = $this->subRepo->findPlanById($planId);

        if (!$plan) {
            Session::flash('error', 'Invalid plan');
            Response::back();
        }

        $activeSub = $this->subRepo->getActiveSubscription(Auth::id());
        if ($activeSub && $activeSub['plan_id'] == $planId) {
            Session::flash('error', 'You are already subscribed to this plan');
            Response::back();
        }

        $startDate = date('Y-m-d');
        $endDate = date('Y-m-d', strtotime("+{$plan['duration_days']} days"));

        if ($activeSub) {
            $this->subRepo->update($activeSub['id'], ['status' => 'cancelled']);
        }

        $subId = $this->subRepo->create([
            'user_id' => Auth::id(),
            'plan_id' => $plan['id'],
            'status' => 'pending',
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        // Free plan — activate immediately
        if ($plan['price'] <= 0) {
            $this->subRepo->update($subId, ['status' => 'active']);
            Session::flash('success', 'Basic plan activated');
            Response::redirect('/subscription');
        }

        // Paid plan — redirect to ToyyibPay
        $this->redirectToToyyibPay($subId, $plan);
    }

    private function redirectToToyyibPay(int $subId, array $plan): void
    {
        $toyyibKey = env('TOYYIBPAY_SECRET_KEY', '');
        $toyyibCategory = env('TOYYIBPAY_CATEGORY', '');
        $toyyibUrl = 'https://dev.toyyibpay.com/';
        $user = Auth::user();

        $db = \App\Core\Database::connect();
        $stmt = $db->prepare("SELECT phone FROM student_profiles WHERE user_id = ?");
        $stmt->execute([Auth::id()]);
        $profile = $stmt->fetch();
        $phone = $profile['phone'] ?? '';

        $externalRef = 'STU-' . $subId . '-' . time();

        $postData = [
            'userSecretKey' => $toyyibKey,
            'categoryCode' => $toyyibCategory,
            'billName' => 'SISWACentral ' . $plan['name'] . ' Plan',
            'billDescription' => 'Student ' . $plan['name'] . ' subscription - ' . $plan['duration_days'] . ' days',
            'billPriceSetting' => 1,
            'billPayorInfo' => 1,
            'billAmount' => (int) ($plan['price'] * 100),
            'billReturnUrl' => config('app.url') . '/subscription/success',
            'billCallbackUrl' => config('app.url') . '/subscription/callback',
            'billExternalReferenceNo' => $externalRef,
            'billTo' => $user['name'] ?? 'Student',
            'billEmail' => $user['email'] ?? '',
            'billPhone' => $phone,
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

                // Store transaction with both references
                $db = \App\Core\Database::connect();
                $stmt = $db->prepare(
                    "INSERT INTO student_transactions (user_id, subscription_id, amount, payment_status, bill_code, toyyib_bill_code) VALUES (?, ?, ?, 'pending', ?, ?)"
                );
                $stmt->execute([Auth::id(), $subId, $plan['price'], $externalRef, $toyyibBillCode]);

                Response::redirect($toyyibUrl . $toyyibBillCode);
            }
        }

        // Debug: log what went wrong
        error_log("ToyyibPay HTTP $httpCode, curl_error: $error, response: " . ($result ?? 'null'));
        Session::flash('error', 'Payment gateway error. Please try again.');
        $this->subRepo->update($subId, ['status' => 'cancelled']);
        Response::back();
    }

    public function callback(Request $request): void
    {
        $refNo = $request->input('billExternalReferenceNo');
        $billCode = $request->input('billcode');
        $statusId = $request->input('status_id');

        if (!$refNo && !$billCode) {
            http_response_code(400);
            echo 'Missing reference';
            return;
        }

        $db = \App\Core\Database::connect();

        // Try external ref first, then ToyyibPay billcode
        if ($refNo) {
            $stmt = $db->prepare("SELECT * FROM student_transactions WHERE bill_code = ?");
            $stmt->execute([$refNo]);
        } else {
            $stmt = $db->prepare("SELECT * FROM student_transactions WHERE toyyib_bill_code = ?");
            $stmt->execute([$billCode]);
        }
        $transaction = $stmt->fetch();

        if (!$transaction) {
            http_response_code(404);
            echo 'Transaction not found';
            return;
        }

        // Avoid re-processing
        if ($transaction['payment_status'] === 'success') {
            echo 'OK';
            return;
        }

        $isSuccess = $statusId === '1';
        $newStatus = $isSuccess ? 'success' : 'failed';

        $stmt = $db->prepare("UPDATE student_transactions SET payment_status = ?, paid_at = ? WHERE id = ?");
        $stmt->execute([$newStatus, $isSuccess ? date('Y-m-d H:i:s') : null, $transaction['id']]);

        if ($isSuccess && $transaction['subscription_id']) {
            $stmt = $db->prepare("UPDATE student_subscriptions SET status = 'active' WHERE id = ?");
            $stmt->execute([$transaction['subscription_id']]);

            $this->notificationRepo->create([
                'user_id' => $transaction['user_id'],
                'type' => 'subscription',
                'title' => 'Premium Plan Activated',
                'message' => 'Your SISWACentral Premium subscription is now active. Enjoy unlimited applications and smart matching!',
            ]);
        }

        echo 'OK';
    }

    public function success(Request $request): void
    {
        $refNo = $request->get('billExternalReferenceNo');
        $billCode = $request->get('billcode');
        $statusId = $request->get('status_id');

        if ($statusId === '1' && ($refNo || $billCode)) {
            $db = \App\Core\Database::connect();

            if ($refNo) {
                $stmt = $db->prepare("SELECT * FROM student_transactions WHERE bill_code = ?");
                $stmt->execute([$refNo]);
            } else {
                $stmt = $db->prepare("SELECT * FROM student_transactions WHERE toyyib_bill_code = ?");
                $stmt->execute([$billCode]);
            }
            $transaction = $stmt->fetch();

            if ($transaction && $transaction['payment_status'] !== 'success') {
                $stmt = $db->prepare("UPDATE student_transactions SET payment_status = 'success', paid_at = NOW() WHERE id = ?");
                $stmt->execute([$transaction['id']]);

                if ($transaction['subscription_id']) {
                    $stmt = $db->prepare("UPDATE student_subscriptions SET status = 'active' WHERE id = ?");
                    $stmt->execute([$transaction['subscription_id']]);
                }
            }
        }

        Session::flash('success', 'Payment successful! Your Premium plan is now active.');
        Response::redirect('/subscription');
    }

    public function cancel(Request $request): void
    {
        $subscription = $this->subRepo->getActiveSubscription(Auth::id());
        if ($subscription) {
            $this->subRepo->update($subscription['id'], ['status' => 'cancelled']);

            $basic = $this->subRepo->getDefaultPlan();
            if ($basic) {
                $this->subRepo->create([
                    'user_id' => Auth::id(),
                    'plan_id' => $basic['id'],
                    'status' => 'active',
                    'start_date' => date('Y-m-d'),
                    'end_date' => date('Y-m-d', strtotime('+30 days')),
                ]);
            }

            Session::flash('success', 'Subscription cancelled. Reverted to the Basic plan.');
        }
        Response::redirect('/subscription');
    }
}
