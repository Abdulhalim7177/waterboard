<?php

namespace App\Traits;

use App\Models\Audit;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
    protected static function bootAuditable()
    {
        // Handle creation
        static::created(function ($model) {
            $user = self::getAuthenticatedUser();
            Audit::create([
                'auditable_type' => get_class($model),
                'auditable_id' => $model->id,
                'user_id' => $user?->id,
                'user_type' => $user ? get_class($user) : null,
                'event' => 'created',
                'new_values' => json_encode($model->getAttributes()),
            ]);
        });

        // Handle updates
        static::updated(function ($model) {
            $user = self::getAuthenticatedUser();
            $changes = $model->getDirty();
            if ($changes) {
                Audit::create([
                    'auditable_type' => get_class($model),
                    'auditable_id' => $model->id,
                    'user_id' => $user?->id,
                    'user_type' => $user ? get_class($user) : null,
                    'event' => 'updated',
                    'old_values' => json_encode(array_intersect_key($model->getOriginal(), $changes)),
                    'new_values' => json_encode($changes),
                ]);
            }
        });
    }

    /**
     * Get the authenticated user from all guards.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    protected static function getAuthenticatedUser()
    {
        return Auth::guard('staff')->user() ??
               Auth::guard('customer')->user() ??
               Auth::guard('vendor')->user();
    }

    /**
     * Log a custom audit event (e.g., approved, rejected, delete_requested).
     *
     * @param string $event
     * @param array $data
     * @param mixed $related
     * @return void
     */
    public function logAuditEvent($event, $data = [], $related = null)
    {
        $user = self::getAuthenticatedUser();
        $oldValues = isset($data['old']) ? $data['old'] : [];
        $newValues = isset($data['new']) ? $data['new'] : $data;

        Audit::create([
            'auditable_type' => get_class($this),
            'auditable_id' => $this->id,
            'user_id' => $user?->id,
            'user_type' => $user ? get_class($user) : null,
            'event' => $event,
            'old_values' => !empty($oldValues) ? json_encode($oldValues) : null,
            'new_values' => !empty($newValues) ? json_encode($newValues) : null,
            'related_type' => $related ? get_class($related) : null,
            'related_id' => $related?->id,
        ]);
    }
}