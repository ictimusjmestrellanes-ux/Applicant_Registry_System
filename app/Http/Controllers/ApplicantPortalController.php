<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\MayorsClearance;
use App\Models\MayorsPermit;
use App\Models\MayorsReferral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ApplicantPortalController extends Controller
{
    public function showRegisterForm()
    {
        if ($this->authenticatedApplicantId()) {
            return redirect()->route('applicant.portal.index');
        }

        return view('applicant-portal.register', [
            'educationalAttainments' => config('educational_attainments', []),
        ]);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'applicant_code' => [
                'required',
                'string',
                'max:32',
                Rule::unique('applicants', 'applicant_code'),
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $applicantCode = strtoupper(trim($validated['applicant_code']));

        $applicant = new Applicant();
        $applicant->forceFill([
            'applicant_code' => $applicantCode,
            'portal_password' => Hash::make($validated['password']),
            'first_time_job_seeker' => 'NO',
            'first_name' => 'PENDING',
            'middle_name' => null,
            'last_name' => 'APPLICANT',
            'suffix' => null,
            'age' => 18,
            'contact_no' => '00000000000',
            'gender' => 'MALE',
            'civil_status' => 'SINGLE',
            'pwd' => 'NO',
            'four_ps' => 'NO',
            'address_line' => 'PENDING',
            'province' => 'PENDING',
            'city' => 'PENDING',
            'barangay' => 'PENDING',
            'educational_attainment' => 'PENDING',
            'hiring_company' => 'PENDING',
            'position_hired' => 'PENDING',
        ])->save();

        $applicant = $applicant->fresh();

        $request->session()->regenerate();
        $request->session()->put('applicant_portal_id', $applicant->id);

        return redirect()
            ->route('applicant.portal.index')
            ->with('success', 'Your applicant account has been created.')
            ->with('applicant_code', $applicant->applicant_code);
    }

    public function showLoginForm()
    {
        if ($this->authenticatedApplicantId()) {
            return redirect()->route('applicant.portal.index');
        }

        return view('applicant-portal.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'applicant_code' => ['required', 'string', 'max:32'],
            'password' => ['required', 'string', 'max:255'],
        ]);

        $applicantCode = strtoupper(trim($credentials['applicant_code']));

        $applicant = Applicant::query()
            ->whereRaw('UPPER(applicant_code) = ?', [$applicantCode])
            ->first();

        if (! $applicant || ! $this->passwordMatchesApplicantCode($applicant, $credentials['password'])) {
            return back()
                ->withErrors([
                    'applicant_code' => 'We could not verify that applicant ID and password.',
                ])
                ->onlyInput('applicant_code');
        }

        $request->session()->regenerate();
        $request->session()->put('applicant_portal_id', $applicant->id);

        return redirect()->route('applicant.portal.index');
    }

    public function index(Request $request)
    {
        $applicant = $this->resolveApplicantFromSession($request);

        if (! $applicant) {
            return redirect()->route('applicant.portal.login');
        }

        return view('applicant-portal.index', [
            'applicant' => $applicant,
            'permit' => $applicant->permit,
            'clearance' => $applicant->clearance,
            'referral' => $applicant->referral,
        ]);
    }

    public function personalInfo(Request $request)
    {
        $applicant = $this->resolveApplicantFromSession($request);

        if (! $applicant) {
            return redirect()->route('applicant.portal.login');
        }

        return view('applicant-portal.personal-info', [
            'applicant' => $applicant,
            'educationalAttainments' => config('educational_attainments', []),
        ]);
    }

    public function savePersonalInfo(Request $request)
    {
        $applicant = $this->resolveApplicantFromSession($request);

        if (! $applicant) {
            return redirect()->route('applicant.portal.login');
        }

        $validated = $request->validate([
            'first_time_job_seeker' => ['required', 'in:YES,NO'],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'suffix' => ['nullable', 'string', 'max:20'],
            'age' => ['required', 'integer', 'min:1', 'max:120'],
            'contact_no' => ['required', 'digits:11'],
            'gender' => ['required', 'in:MALE,FEMALE'],
            'civil_status' => ['required', 'in:SINGLE,MARRIED,WIDOWED'],
            'pwd' => ['required', 'in:YES,NO'],
            'four_ps' => ['required', 'in:YES,NO'],
            'address_line' => ['required', 'string', 'max:255'],
            'province' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'barangay' => ['required', 'string', 'max:255'],
            'educational_attainment' => ['required', 'string', 'max:255'],
            'hiring_company' => ['required', 'string', 'max:255'],
            'position_hired' => ['required', 'string', 'max:255'],
        ]);

        $applicant->update($validated);

        return redirect()
            ->route('applicant.portal.personal-info')
            ->with('success', 'Your personal information was saved successfully.');
    }

    public function requirements(Request $request)
    {
        $applicant = $this->resolveApplicantFromSession($request);

        if (! $applicant) {
            return redirect()->route('applicant.portal.login');
        }

        return view('applicant-portal.requirements', [
            'applicant' => $applicant,
            'permit' => $applicant->permit,
            'clearance' => $applicant->clearance,
            'referral' => $applicant->referral,
        ]);
    }

    public function update(Request $request)
    {
        $applicant = $this->resolveApplicantFromSession($request);

        if (! $applicant) {
            return redirect()->route('applicant.portal.login');
        }

        $validated = $request->validate([
            'applicant_code' => [
                'required',
                'string',
                'max:32',
                Rule::unique('applicants', 'applicant_code')->ignore($applicant->id),
            ],
            'first_time_job_seeker' => ['required', 'in:YES,NO'],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'suffix' => ['nullable', 'string', 'max:20'],
            'age' => ['required', 'integer', 'min:1', 'max:120'],
            'contact_no' => ['required', 'digits:11'],
            'gender' => ['required', 'in:MALE,FEMALE'],
            'civil_status' => ['required', 'in:SINGLE,MARRIED,WIDOWED'],
            'pwd' => ['required', 'in:YES,NO'],
            'four_ps' => ['required', 'in:YES,NO'],
            'address_line' => ['required', 'string', 'max:255'],
            'province' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'barangay' => ['required', 'string', 'max:255'],
            'educational_attainment' => ['required', 'string', 'max:255'],
            'hiring_company' => ['required', 'string', 'max:255'],
            'position_hired' => ['required', 'string', 'max:255'],
        ]);

        $validated['applicant_code'] = strtoupper(trim($validated['applicant_code']));

        $applicant->update($validated);

        return redirect()
            ->route('applicant.portal.index')
            ->with('success', 'Your applicant details were saved successfully.');
    }

    public function uploadRequirements(Request $request)
    {
        $applicant = $this->resolveApplicantFromSession($request);

        if (! $applicant) {
            return redirect()->route('applicant.portal.login');
        }

        $validated = $request->validate([
            'health_card' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:5120'],
            'permit_clearance_type' => ['nullable', 'in:nbi,police', 'required_with:permit_clearance_file'],
            'permit_clearance_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:5120'],
            'cedula' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:5120'],
            'referral_letter' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:5120'],
            'prosecutor_clearance' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:5120'],
            'mtc_clearance' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:5120'],
            'rtc_clearance' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:5120'],
            'nbi_clearance' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:5120'],
            'barangay_clearance' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:5120'],
            'resume' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:5120'],
            'biodata' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:5120'],
            'ref_barangay_clearance' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:5120'],
            'ref_police_clearance' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:5120'],
            'ref_nbi_clearance' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:5120'],
        ]);

        $permit = MayorsPermit::firstOrNew([
            'applicant_id' => $applicant->id,
        ]);
        $permitChanged = false;

        $selectedPermitClearanceType = $validated['permit_clearance_type'] ?? $permit->clearance_type ?? null;

        foreach ([
            'health_card' => 'permits/health_cards',
            'cedula' => 'permits/cedulas',
            'referral_letter' => 'permits/referral_letters',
        ] as $field => $directory) {
            if (! $request->hasFile($field)) {
                continue;
            }

            $permit->{$field} = $this->storeApplicantUpload(
                $request->file($field),
                $permit->{$field} ?? null,
                $directory,
                $applicant->id,
                $field
            );
            $permitChanged = true;
        }

        if ($request->hasFile('permit_clearance_file')) {
            $fileField = 'permit_clearance_file';
            $directory = $selectedPermitClearanceType === 'police'
                ? 'permits/permit_police_clearance'
                : 'permits/permit_nbi_clearance';
            $targetField = $selectedPermitClearanceType === 'police'
                ? 'permit_police_clearance'
                : 'permit_nbi_clearance';
            $otherField = $targetField === 'permit_nbi_clearance'
                ? 'permit_police_clearance'
                : 'permit_nbi_clearance';

            if (! empty($permit->{$otherField})) {
                Storage::disk('public')->delete($permit->{$otherField});
                $permit->{$otherField} = null;
            }

            $permit->{$targetField} = $this->storeApplicantUpload(
                $request->file($fileField),
                $permit->{$targetField} ?? null,
                $directory,
                $applicant->id,
                $targetField
            );
            $permit->clearance_type = $selectedPermitClearanceType;
            $permitChanged = true;
        }

        if ($permitChanged) {
            $permit->save();
        }

        $clearance = MayorsClearance::firstOrNew([
            'applicant_id' => $applicant->id,
        ]);
        $clearanceChanged = false;

        foreach ([
            'prosecutor_clearance' => 'clearances/prosecutor',
            'mtc_clearance' => 'clearances/mtc',
            'rtc_clearance' => 'clearances/rtc',
            'nbi_clearance' => 'clearances/nbi',
            'barangay_clearance' => 'clearances/barangay',
        ] as $field => $directory) {
            if (! $request->hasFile($field)) {
                continue;
            }

            $clearance->{$field} = $this->storeApplicantUpload(
                $request->file($field),
                $clearance->{$field} ?? null,
                $directory,
                $applicant->id,
                $field
            );
            $clearanceChanged = true;
        }

        if ($clearanceChanged) {
            $clearance->save();
        }

        $referral = MayorsReferral::firstOrNew([
            'applicant_id' => $applicant->id,
        ]);
        $referralChanged = false;

        foreach ([
            'resume' => 'referrals/resume',
            'biodata' => 'referrals/biodata',
            'ref_barangay_clearance' => 'referrals/barangay',
            'ref_police_clearance' => 'referrals/police',
            'ref_nbi_clearance' => 'referrals/nbi',
        ] as $field => $directory) {
            if (! $request->hasFile($field)) {
                continue;
            }

            $referral->{$field} = $this->storeApplicantUpload(
                $request->file($field),
                $referral->{$field} ?? null,
                $directory,
                $applicant->id,
                $field
            );
            $referralChanged = true;
        }

        if ($referralChanged) {
            $referral->save();
        }

        return redirect()
            ->route('applicant.portal.requirements')
            ->with('success', 'Your requirements were uploaded successfully.');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('applicant_portal_id');
        $request->session()->regenerateToken();

        return redirect()
            ->route('applicant.portal.login')
            ->with('success', 'You have been signed out.');
    }

    private function resolveApplicantFromSession(Request $request): ?Applicant
    {
        $applicantId = $request->session()->get('applicant_portal_id');

        if (! $applicantId) {
            return null;
        }

        return Applicant::with(['permit', 'clearance', 'referral'])->find($applicantId);
    }

    private function authenticatedApplicantId(): ?int
    {
        $applicantId = session('applicant_portal_id');

        return $applicantId ? (int) $applicantId : null;
    }

    private function passwordMatchesApplicantCode(Applicant $applicant, string $password): bool
    {
        $expectedPassword = (string) $applicant->applicant_code;

        if (! empty($applicant->portal_password)) {
            return Hash::check($password, $applicant->portal_password);
        }

        return hash_equals($expectedPassword, trim($password));
    }

    private function storeApplicantUpload(UploadedFile $file, ?string $existingPath, string $directory, int $applicantId, string $field): string
    {
        if (! empty($existingPath)) {
            Storage::disk('public')->delete($existingPath);
        }

        $originalName = trim((string) $file->getClientOriginalName());
        $safeName = preg_replace('/[<>:"\/\\\\|?*\x00-\x1F]/', '_', $originalName) ?: '';
        $fileName = $safeName !== '' ? $safeName : Str::slug($field, '_').'.'.$file->getClientOriginalExtension();

        return $file->storeAs($directory, $fileName, 'public');
    }
}
