<?php

namespace App\Helpers;

use App\Core\Auth;
use App\Repositories\AuditLogRepository;

class AuditLog
{
    public static function log(string $action, string $entityType, ?int $entityId = null, ?string $description = null, ?array $oldValues = null, ?array $newValues = null): void
    {
        $repo = new AuditLogRepository();
        $repo->create([
            'user_id' => Auth::id(),
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
        ]);
    }
}
