<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Applicant;
use App\Models\MayorsClearance;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ClearanceController extends Controller
{
    public function update(Request $request, $id)
    {
        $applicant = Applicant::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | CREATE OR UPDATE CLEARANCE
        |--------------------------------------------------------------------------
        */

        $clearance = MayorsClearance::firstOrNew([
            'applicant_id' => $applicant->id,
        ]);

        $trackedFields = $this->trackedFields();
        $wasRecentlyCreated = ! $clearance->exists;
        $originalValues = $clearance->exists
            ? $clearance->only(array_keys($trackedFields))
            : [];

        /*
        |--------------------------------------------------------------------------
        | PREPARE FILE NAME
        |--------------------------------------------------------------------------
        */

        $fullName = Str::lower(Str::slug($applicant->last_name, '_'));

        /*
        |--------------------------------------------------------------------------
        | FILE UPLOADS
        |--------------------------------------------------------------------------
        */

        // PROSECUTOR CLEARANCE
        if ($request->hasFile('prosecutor_clearance')) {

            $file = $request->file('prosecutor_clearance');
            $extension = $file->getClientOriginalExtension();

            $fileName = 'PROSECUTOR_CLEARANCE_'.$fullName.'.'.$extension;

            $clearance->prosecutor_clearance = $file->storeAs(
                'clearances/prosecutor',
                $fileName,
                'public'
            );
        }

        // MUNICIPAL TRIAL COURT
        if ($request->hasFile('mtc_clearance')) {

            $file = $request->file('mtc_clearance');
            $extension = $file->getClientOriginalExtension();

            $fileName = 'MTC_CLEARANCE_'.$fullName.'.'.$extension;

            $clearance->mtc_clearance = $file->storeAs(
                'clearances/mtc',
                $fileName,
                'public'
            );
        }

        // REGIONAL TRIAL COURT
        if ($request->hasFile('rtc_clearance')) {

            $file = $request->file('rtc_clearance');
            $extension = $file->getClientOriginalExtension();

            $fileName = 'RTC_CLEARANCE_'.$fullName.'.'.$extension;

            $clearance->rtc_clearance = $file->storeAs(
                'clearances/rtc',
                $fileName,
                'public'
            );
        }

        // NBI CLEARANCE
        if ($request->hasFile('nbi_clearance')) {

            $file = $request->file('nbi_clearance');
            $extension = $file->getClientOriginalExtension();

            $fileName = 'NBI_CLEARANCE_'.$fullName.'.'.$extension;

            $clearance->nbi_clearance = $file->storeAs(
                'clearances/nbi',
                $fileName,
                'public'
            );
        }

        // BARANGAY CLEARANCE
        if ($request->hasFile('barangay_clearance')) {

            $file = $request->file('barangay_clearance');
            $extension = $file->getClientOriginalExtension();

            $fileName = 'BARANGAY_CLEARANCE_'.$fullName.'.'.$extension;

            $clearance->barangay_clearance = $file->storeAs(
                'clearances/barangay',
                $fileName,
                'public'
            );
        }

        $clearance->clearance_or_no = $request->clearance_or_no;
        $clearance->clearance_issued_on = $request->clearance_issued_on;
        $clearance->clearance_issued_in = $request->clearance_issued_in;

        $clearance->clearance_peso_control_no = $request->clearance_peso_control_no;
        $clearance->clearance_doc_stamp_control_no = $request->clearance_doc_stamp_control_no;
        $clearance->clearance_date_of_payment = $request->clearance_date_of_payment;

        $clearance->clearance_hired_company = $request->clearance_hired_company;

        /*
        |--------------------------------------------------------------------------
        | SAVE CLEARANCE
        |--------------------------------------------------------------------------
        */

        $clearance->save();

        $changes = $this->buildChanges($clearance, $originalValues, $trackedFields);

        if (empty($clearance->clearance_peso_control_no)) {
            $year = date('Y');

            $latest = MayorsClearance::whereYear('created_at', $year)
                ->whereNotNull('clearance_peso_control_no')
                ->orderBy('id', 'desc')
                ->first();

            $nextNumber = $latest
                ? ((int) substr($latest->clearance_peso_control_no, -4)) + 1
                : 1;

            $clearance->clearance_peso_control_no = $year.'-'.str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            $clearance->save(); // SAVE AGAIN with generated ID

            $changes[] = [
                'field' => 'clearance_peso_control_no',
                'label' => $trackedFields['clearance_peso_control_no'],
                'old' => $this->formatLogValue($originalValues['clearance_peso_control_no'] ?? null),
                'new' => $this->formatLogValue($clearance->clearance_peso_control_no),
            ];
        }

        if ($changes !== []) {
            $action = $wasRecentlyCreated ? 'created' : 'updated';
            $changedLabels = collect($changes)->pluck('label')->unique()->values()->all();

            ActivityLog::create([
                'applicant_id' => $applicant->id,
                'causer_id' => $request->user()?->id,
                'module' => 'mayors_clearance',
                'action' => $action,
                'description' => $this->buildDescription($action, $changedLabels),
                'changes' => $changes,
            ]);
        }

        return redirect()
            ->route('applicants.edit', $applicant->id)
            ->with('success', 'Clearance updated successfully.');
    }

    public function printLetter($id)
    {
        $applicant = Applicant::with('clearance')->findOrFail($id);

        if (! $applicant->clearance || ! $applicant->clearance->isComplete()) {
            return back()->with('error', 'Clearance is not complete.');
        }

        return view('clearance.clearance', compact('applicant'));
    }

    private function trackedFields(): array
    {
        return [
            'prosecutor_clearance' => 'Prosecutor Clearance',
            'mtc_clearance' => 'Municipal Trial Court Clearance',
            'rtc_clearance' => 'Regional Trial Court Clearance',
            'nbi_clearance' => 'NBI Clearance',
            'barangay_clearance' => 'Barangay Clearance',
            'clearance_or_no' => 'O.R. No.',
            'clearance_issued_on' => 'Issued On',
            'clearance_issued_in' => 'Issued In',
            'clearance_peso_control_no' => 'PESO Control No.',
            'clearance_doc_stamp_control_no' => 'Documentary Stamp Control No.',
            'clearance_date_of_payment' => 'Date of Payment',
            'clearance_hired_company' => 'Hired Company',
        ];
    }

    private function buildChanges(MayorsClearance $clearance, array $originalValues, array $trackedFields): array
    {
        $changes = [];

        foreach ($trackedFields as $field => $label) {
            $oldValue = $originalValues[$field] ?? null;
            $newValue = $clearance->{$field};

            if ($oldValue == $newValue) {
                continue;
            }

            $changes[] = [
                'field' => $field,
                'label' => $label,
                'old' => $this->formatLogValue($oldValue),
                'new' => $this->formatLogValue($newValue),
            ];
        }

        return $changes;
    }

    private function buildDescription(string $action, array $changedLabels): string
    {
        $prefix = $action === 'created'
            ? 'Created mayor\'s clearance record'
            : 'Updated mayor\'s clearance';

        if ($changedLabels === []) {
            return $prefix.'.';
        }

        return $prefix.' for '.implode(', ', $changedLabels).'.';
    }

    private function formatLogValue(mixed $value): string
    {
        if (blank($value)) {
            return 'Empty';
        }

        if (is_string($value) && str_contains($value, '/')) {
            return basename($value);
        }

        return (string) $value;
    }
}
