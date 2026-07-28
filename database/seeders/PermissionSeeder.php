<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'update_permit' => "Update Mayor's Permit to Work",
            'generate_permit' => "Generate Mayor's Permit to Work ID",
            'update_clearance' => "Update Mayor's Clearance",
            'generate_clearance' => "Generate Mayor's Clearance Letter",
            'update_referral' => "Update Mayor's Referral",
            'generate_referral' => "Generate Mayor's Referral Letter",
            'approve_document' => 'Approve Submitted Documents',
            'auto_approve_uploaded_files' => 'Auto-Approve Uploaded Files',
            'view_archive_applicants' => 'View Archived Applicants',
            'restore_archive_applicants' => 'Restore Archived Applicants',
            'view_duplicates' => 'View Duplicate Applicants',
        ];

        foreach ($permissions as $key => $label) {
            Permission::firstOrCreate(
                ['key' => $key],
                ['label' => $label]
            );
        }
    }
}
