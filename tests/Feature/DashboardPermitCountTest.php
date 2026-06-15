<?php

namespace Tests\Feature;

use App\Models\Applicant;
use App\Models\MayorsPermit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardPermitCountTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_total_permit_count_ignores_archived_applicants(): void
    {
        $admin = User::factory()->admin()->create();

        $activeApplicant = Applicant::create([
            'first_name' => 'Active',
            'middle_name' => null,
            'last_name' => 'Applicant',
            'suffix' => null,
            'age' => 25,
            'contact_no' => '09123456789',
            'gender' => 'Male',
            'civil_status' => 'Single',
            'pwd' => null,
            'four_ps' => null,
            'address_line' => 'Sample Street',
            'province' => 'Cavite',
            'city' => 'Imus City',
            'barangay' => 'Bucandala',
            'educational_attainment' => null,
            'hiring_company' => null,
            'position_hired' => null,
            'first_time_job_seeker' => null,
        ]);

        $archivedApplicant = Applicant::create([
            'first_name' => 'Archived',
            'middle_name' => null,
            'last_name' => 'Applicant',
            'suffix' => null,
            'age' => 28,
            'contact_no' => '09987654321',
            'gender' => 'Female',
            'civil_status' => 'Single',
            'pwd' => null,
            'four_ps' => null,
            'address_line' => 'Archive Street',
            'province' => 'Cavite',
            'city' => 'Imus City',
            'barangay' => 'Bucandala',
            'educational_attainment' => null,
            'hiring_company' => null,
            'position_hired' => null,
            'first_time_job_seeker' => null,
        ]);

        MayorsPermit::create(['applicant_id' => $activeApplicant->id]);
        MayorsPermit::create(['applicant_id' => $archivedApplicant->id]);

        $archivedApplicant->delete();

        $this->assertSame(2, MayorsPermit::count());
        $this->assertSame(
            1,
            MayorsPermit::query()
                ->whereHas('applicant', function ($query) {
                    $query->withoutTrashed();
                })
                ->count()
        );
    }
}
