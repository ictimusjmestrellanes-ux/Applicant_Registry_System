<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\MayorsClearance;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        $wasRecentlyCreated = ! $clearance->exists;
        $before = $clearance->exists ? $clearance->only($clearance->getFillable()) : [];

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
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $fileName = Str::slug($originalName, '_').'.'.$extension;
            $filePath = 'clearances/prosecutor/'.$fileName;

            if ($clearance->prosecutor_clearance) {
                Storage::disk('public')->delete($clearance->prosecutor_clearance);
            }

            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            $clearance->prosecutor_clearance = $file->storeAs(
                'clearances/prosecutor',
                $fileName,
                'public'
            );
        }

        // MUNICIPAL TRIAL COURT
        if ($request->hasFile('mtc_clearance')) {
            $file = $request->file('mtc_clearance');
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $fileName = Str::slug($originalName, '_').'.'.$extension;
            $filePath = 'clearances/mtc/'.$fileName;

            if ($clearance->mtc_clearance) {
                Storage::disk('public')->delete($clearance->mtc_clearance);
            }

            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            $clearance->mtc_clearance = $file->storeAs(
                'clearances/mtc',
                $fileName,
                'public'
            );
        }

        // REGIONAL TRIAL COURT
        if ($request->hasFile('rtc_clearance')) {
            $file = $request->file('rtc_clearance');
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $fileName = Str::slug($originalName, '_').'.'.$extension;
            $filePath = 'clearances/rtc/'.$fileName;

            if ($clearance->rtc_clearance) {
                Storage::disk('public')->delete($clearance->rtc_clearance);
            }

            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            $clearance->rtc_clearance = $file->storeAs(
                'clearances/rtc',
                $fileName,
                'public'
            );
        }

        // NBI CLEARANCE
        if ($request->hasFile('nbi_clearance')) {
            $file = $request->file('nbi_clearance');
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $fileName = Str::slug($originalName, '_').'.'.$extension;
            $filePath = 'clearances/nbi/'.$fileName;

            if ($clearance->nbi_clearance) {
                Storage::disk('public')->delete($clearance->nbi_clearance);
            }

            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            $clearance->nbi_clearance = $file->storeAs(
                'clearances/nbi',
                $fileName,
                'public'
            );
        }

        // BARANGAY CLEARANCE
        if ($request->hasFile('barangay_clearance')) {
            $file = $request->file('barangay_clearance');
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $fileName = Str::slug($originalName, '_').'.'.$extension;
            $filePath = 'clearances/barangay/'.$fileName;

            if ($clearance->barangay_clearance) {
                Storage::disk('public')->delete($clearance->barangay_clearance);
            }

            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            $clearance->barangay_clearance = $file->storeAs(
                'clearances/barangay',
                $fileName,
                'public'
            );
        }

        $clearance->clearance_or_no = $request->clearance_or_no;
        $clearance->clearance_issued_on = $request->clearance_issued_on;

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
        }

        $after = $clearance->fresh()->only($clearance->getFillable());
        $changes = ActivityLogger::diff($before, $after);

        if (! empty($changes)) {
            ActivityLogger::log(
                'clearance',
                $wasRecentlyCreated ? 'created' : 'updated',
                $wasRecentlyCreated ? 'Added mayor\'s clearance details for the applicant.' : 'Updated mayor\'s clearance details.',
                $applicant,
                $changes,
                $request->user()
            );
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

        ActivityLogger::log(
            'clearance',
            'generated',
            'Generated the mayor\'s clearance letter.',
            $applicant,
            null,
            request()->user()
        );

        return view('clearance.clearance', compact('applicant'));
    }
}
