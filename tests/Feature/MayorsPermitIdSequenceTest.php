<?php

namespace Tests\Feature;

use App\Models\Applicant;
use App\Models\MayorsPermit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MayorsPermitIdSequenceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_starts_2026_permit_id_numbers_at_07930(): void
    {
        Applicant::create([
            'first_name' => 'Test',
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

        $this->assertSame('2026-07930', MayorsPermit::generateNextPesoIdNo(2026));
    }

    public function test_it_continues_after_the_highest_existing_2026_number(): void
    {
        $applicant = Applicant::create([
            'first_name' => 'Test',
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

        MayorsPermit::create([
            'applicant_id' => $applicant->id,
            'peso_id_no' => '2026-07935',
        ]);

        $this->assertSame('2026-07936', MayorsPermit::generateNextPesoIdNo(2026));
    }
}
