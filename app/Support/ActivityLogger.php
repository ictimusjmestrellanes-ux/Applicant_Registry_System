<?php

namespace App\Support;

use App\Models\ActivityLog;
use App\Models\Applicant;
use App\Models\User;

class ActivityLogger
{
    public static function log(
        string $module,
        string $action,
        string $description,
        ?Applicant $applicant = null,
        ?array $changes = null,
        ?User $causer = null,
    ): ActivityLog {
        return ActivityLog::create([
            'applicant_id' => $applicant?->id,
            'causer_id' => $causer?->id,
            'module' => $module,
            'action' => $action,
            'description' => $description,
            'changes' => empty($changes) ? null : $changes,
        ]);
    }

    public static function diff(array $before, array $after): array
    {
        $changes = [];

        foreach ($after as $field => $newValue) {
            $oldValue = $before[$field] ?? null;

            if (self::normalize($oldValue) === self::normalize($newValue)) {
                continue;
            }

            $changes[$field] = [
                'before' => self::displayValue($oldValue),
                'after' => self::displayValue($newValue),
            ];
        }

        return $changes;
    }

    protected static function normalize(mixed $value): mixed
    {
        return match (true) {
            is_string($value) => trim($value),
            default => $value,
        };
    }

    protected static function displayValue(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if (is_bool($value)) {
            return $value ? 'Yes' : 'No';
        }

        if (is_string($value) && str_contains($value, '/')) {
            return basename($value);
        }

        $stringValue = trim((string) $value);

        return $stringValue === '' ? null : $stringValue;
    }
}
