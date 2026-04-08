<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;

class ApplicantController extends Controller
{
    public function create()
    {
        return view('applicants.create');
    }

    public function store(Request $request)
    {
        $request->validate([

            // Applicant Personal Information
            'first_name' => 'required',
            'last_name' => 'required',
            'contact_no' => 'required',
            'gender' => 'required',
            'civil_status' => 'required',
            'pwd' => 'required',
            'four_ps' => 'required',
            'address_line' => 'required',
            'province' => 'required',
            'city' => 'required',
            'barangay' => 'required',
            'educational_attainment' => 'required',
            'position_hired' => 'required',
            'first_time_job_seeker' => 'required',
        ]);

        $applicant = Applicant::create($request->all());

        ActivityLogger::log(
            'applicant',
            'created',
            'Created a new applicant record.',
            $applicant,
            ActivityLogger::diff([], $applicant->only($applicant->getFillable())),
            $request->user()
        );

        return redirect()
            ->route('applicants.edit', $applicant->id)
            ->with('created_success', true);
    }

    public function index(Request $request)
    {
        $search = trim((string) $request->search);
        $perPageInput = strtolower((string) $request->query('per_page', '10'));
        $allowedPerPage = [10, 20, 30, 40, 50, 60, 70, 80, 90, 100];

        $query = $this->buildApplicantSearchQuery($search)
            ->with(['permit', 'clearance', 'referral']);

        if ($perPageInput === 'all') {
            $total = (clone $query)->count();
            $perPage = max($total, 1);
        } else {
            $perPage = (int) $perPageInput;

            if (! in_array($perPage, $allowedPerPage, true)) {
                $perPage = 10;
            }
        }

        $applicants = $query
            ->paginate($perPage)
            ->withQueryString();

        return view('applicants.index', compact('applicants', 'search'));
    }

    public function export(Request $request)
    {
        $search = trim((string) $request->search);

        $applicants = $this->buildApplicantSearchQuery($search)
            ->orderBy('id')
            ->get();

        $rows = [];

        foreach ($applicants as $applicant) {
            $rows[] = [
                'Full Name' => trim(implode(' ', array_filter([
                    (string) $applicant->first_name,
                    (string) ($applicant->middle_name ?? ''),
                    (string) $applicant->last_name,
                    (string) ($applicant->suffix ?? ''),
                ]))),
                'Age' => $applicant->age !== null ? (string) $applicant->age : '',
                'Contact No' => (string) ($applicant->contact_no ?? ''),
                'Gender' => (string) ($applicant->gender ?? ''),
                'Civil Status' => (string) ($applicant->civil_status ?? ''),
                'PWD' => (string) ($applicant->pwd ?? ''),
                '4Ps' => (string) ($applicant->four_ps ?? ''),
                'Address' => trim(implode(' ', array_filter([
                    (string) $applicant->address_line ?? '',
                    (string) $applicant->barangay ?? '',
                    (string) $applicant->city ?? '',
                    (string) $applicant->province ?? '',
                ]))),
                'Educational Attainment' => (string) ($applicant->educational_attainment ?? ''),
                'Hiring Company' => (string) ($applicant->hiring_company ?? ''),
                'Position Hired' => (string) ($applicant->position_hired ?? ''),
                'First Time Job Seeker' => (string) ($applicant->first_time_job_seeker ?? ''),
            ];
        }

        $filePath = $this->createApplicantsExportXlsx($rows);
        $fileName = 'applicants-export-'.now()->format('Y-m-d-His').'.xlsx';

        return response()->download(
            $filePath,
            $fileName,
            ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
        )->deleteFileAfterSend(true);
    }

    public function edit($id)
    {
        $applicant = Applicant::with(['permit', 'clearance', 'referral'])->findOrFail($id);
        $activityLogs = $applicant->activityLogs()
            ->with('causer')
            ->paginate(10, ['*'], 'activity_page')
            ->withQueryString();

        return view('applicants.edit', compact('applicant', 'activityLogs'));
    }

    public function update(Request $request, $id)
    {
        $applicant = Applicant::findOrFail($id);
        $before = $applicant->only($applicant->getFillable());

        /*
        |--------------------------------------------------------------------------
        | 1. UPDATE PERSONAL INFORMATION
        |--------------------------------------------------------------------------
        */

        $applicant->update([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'suffix' => $request->suffix,
            'age' => $request->age,
            'contact_no' => $request->contact_no,
            'gender' => $request->gender,
            'civil_status' => $request->civil_status,
            'pwd' => $request->pwd,
            'four_ps' => $request->four_ps,
            'address_line' => $request->address_line,
            'province' => $request->province,
            'city' => $request->city,
            'barangay' => $request->barangay,
            'educational_attainment' => $request->educational_attainment,
            'hiring_company' => $request->hiring_company,
            'position_hired' => $request->position_hired,
            'first_time_job_seeker' => $request->first_time_job_seeker,
        ]);

        $changes = ActivityLogger::diff($before, $applicant->fresh()->only($applicant->getFillable()));

        if (! empty($changes)) {
            ActivityLogger::log(
                'applicant',
                'updated',
                'Updated applicant information.',
                $applicant,
                $changes,
                $request->user()
            );
        }

        return redirect()
            ->route('applicants.edit', $applicant->id)
            ->with('success', 'Applicant updated successfully.');
    }

    public function destroy($id)
    {
        $applicant = Applicant::findOrFail($id);
        $applicantName = trim($applicant->first_name.' '.$applicant->last_name);

        $applicant->delete(); // Moves to Archive

        ActivityLogger::log(
            'applicant',
            'archived',
            'Archived applicant record.',
            $applicant,
            [
                'status' => [
                    'before' => 'Active',
                    'after' => 'Archived',
                ],
                'applicant_name' => [
                    'before' => $applicantName,
                    'after' => $applicantName,
                ],
            ],
            request()->user()
        );

        return redirect()->route('applicants.archive')
            ->with('success', 'Applicant Archived');
    }

    public function archive(Request $request)
    {
        $search = trim((string) $request->search);

        $applicants = Applicant::onlyTrashed()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('first_name', 'like', "%{$search}%")
                        ->orWhere('middle_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('contact_no', 'like', "%{$search}%")
                        ->orWhere('barangay', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%");
                });
            })
            ->latest('deleted_at')
            ->paginate(10)
            ->withQueryString();

        return view('applicants.archive', compact('applicants', 'search'));
    }

    public function restore($id)
    {
        $applicant = Applicant::withTrashed()->findOrFail($id);
        $applicantName = trim($applicant->first_name.' '.$applicant->last_name);
        $applicant->restore();

        ActivityLogger::log(
            'applicant',
            'restored',
            'Restored applicant record from archive.',
            $applicant,
            [
                'status' => [
                    'before' => 'Archived',
                    'after' => 'Active',
                ],
                'applicant_name' => [
                    'before' => $applicantName,
                    'after' => $applicantName,
                ],
            ],
            request()->user()
        );

        return redirect()->route('applicants.index')
            ->with('success', 'Applicant restored successfully.');
    }

    private function buildApplicantSearchQuery(string $search)
    {
        return Applicant::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('first_name', 'like', "%{$search}%")
                        ->orWhere('middle_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('contact_no', 'like', "%{$search}%")
                        ->orWhere('gender', 'like', "%{$search}%");
                });
            });
    }

    private function createApplicantsExportXlsx(array $rows): string
    {
        $headers = [
            'OR no.',
            'Full Name',
            'Age',
            'Contact No',
            'Gender',
            'Civil Status',
            'PWD',
            '4Ps',
            'Address',
            'Educational Attainment',
            'Hiring Company',
            'Position Hired',
            'First Time Job Seeker',
        ];

        $sheetRows = [$headers];

        foreach ($rows as $row) {
            $sheetRows[] = array_map(
                fn ($header) => (string) ($row[$header] ?? ''),
                $headers
            );
        }

        $tempFile = tempnam(sys_get_temp_dir(), 'applicants_export_');
        $xlsxPath = $tempFile.'.xlsx';

        if (file_exists($tempFile)) {
            unlink($tempFile);
        }

        $zip = new \ZipArchive;

        if ($zip->open($xlsxPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            abort(500, 'Unable to create export file.');
        }

        $zip->addFromString('[Content_Types].xml', $this->buildContentTypesXml());
        $zip->addFromString('_rels/.rels', $this->buildRootRelsXml());
        $zip->addFromString('xl/workbook.xml', $this->buildWorkbookXml());
        $zip->addFromString('xl/_rels/workbook.xml.rels', $this->buildWorkbookRelsXml());
        $zip->addFromString('xl/worksheets/sheet1.xml', $this->buildWorksheetXml($sheetRows));
        $zip->addFromString('xl/styles.xml', $this->buildStylesXml());
        $zip->addFromString('docProps/core.xml', $this->buildCorePropertiesXml());
        $zip->addFromString('docProps/app.xml', $this->buildAppPropertiesXml());
        $zip->close();

        return $xlsxPath;
    }

    private function buildWorksheetXml(array $rows): string
    {
        $sheetData = '';

        foreach ($rows as $rowIndex => $row) {
            $sheetData .= '<row r="'.($rowIndex + 1).'">';

            foreach ($row as $columnIndex => $value) {
                $cellReference = $this->excelColumnName($columnIndex + 1).($rowIndex + 1);
                $styleIndex = $rowIndex === 0 ? ' s="1"' : '';

                $sheetData .= '<c r="'.$cellReference.'" t="inlineStr"'.$styleIndex.'><is><t>'
                    .$this->escapeXml($value)
                    .'</t></is></c>';
            }

            $sheetData .= '</row>';
        }

        $lastColumn = $this->excelColumnName(count($rows[0]));
        $lastRow = count($rows);

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"'
            .' xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            .'<dimension ref="A1:'.$lastColumn.$lastRow.'"/>'
            .'<sheetViews><sheetView workbookViewId="0"/></sheetViews>'
            .'<sheetFormatPr defaultRowHeight="15"/>'
            .'<cols>'
            .'<col min="1" max="1" width="10" customWidth="1"/>'
            .'<col min="2" max="5" width="18" customWidth="1"/>'
            .'<col min="6" max="6" width="10" customWidth="1"/>'
            .'<col min="7" max="11" width="16" customWidth="1"/>'
            .'<col min="12" max="15" width="22" customWidth="1"/>'
            .'<col min="16" max="20" width="24" customWidth="1"/>'
            .'</cols>'
            .'<sheetData>'.$sheetData.'</sheetData>'
            .'</worksheet>';
    }

    private function buildContentTypesXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            .'<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            .'<Default Extension="xml" ContentType="application/xml"/>'
            .'<Override PartName="/xl/workbook.xml"'
            .' ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            .'<Override PartName="/xl/worksheets/sheet1.xml"'
            .' ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
            .'<Override PartName="/xl/styles.xml"'
            .' ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
            .'<Override PartName="/docProps/core.xml"'
            .' ContentType="application/vnd.openxmlformats-package.core-properties+xml"/>'
            .'<Override PartName="/docProps/app.xml"'
            .' ContentType="application/vnd.openxmlformats-officedocument.extended-properties+xml"/>'
            .'</Types>';
    }

    private function buildRootRelsXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            .'<Relationship Id="rId1"'
            .' Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument"'
            .' Target="xl/workbook.xml"/>'
            .'<Relationship Id="rId2"'
            .' Type="http://schemas.openxmlformats.org/package/2006/relationships/metadata/core-properties"'
            .' Target="docProps/core.xml"/>'
            .'<Relationship Id="rId3"'
            .' Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/extended-properties"'
            .' Target="docProps/app.xml"/>'
            .'</Relationships>';
    }

    private function buildWorkbookXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"'
            .' xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            .'<sheets><sheet name="Applicants" sheetId="1" r:id="rId1"/></sheets>'
            .'</workbook>';
    }

    private function buildWorkbookRelsXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            .'<Relationship Id="rId1"'
            .' Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet"'
            .' Target="worksheets/sheet1.xml"/>'
            .'<Relationship Id="rId2"'
            .' Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles"'
            .' Target="styles.xml"/>'
            .'</Relationships>';
    }

    private function buildStylesXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            .'<fonts count="2">'
            .'<font><sz val="11"/><name val="Calibri"/><family val="2"/></font>'
            .'<font><b/><sz val="11"/><name val="Calibri"/><family val="2"/></font>'
            .'</fonts>'
            .'<fills count="2">'
            .'<fill><patternFill patternType="none"/></fill>'
            .'<fill><patternFill patternType="gray125"/></fill>'
            .'</fills>'
            .'<borders count="1"><border><left/><right/><top/><bottom/><diagonal/></border></borders>'
            .'<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
            .'<cellXfs count="2">'
            .'<xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>'
            .'<xf numFmtId="0" fontId="1" fillId="0" borderId="0" xfId="0" applyFont="1"/>'
            .'</cellXfs>'
            .'<cellStyles count="1"><cellStyle name="Normal" xfId="0" builtinId="0"/></cellStyles>'
            .'</styleSheet>';
    }

    private function buildCorePropertiesXml(): string
    {
        $timestamp = now()->utc()->format('Y-m-d\TH:i:s\Z');

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<cp:coreProperties xmlns:cp="http://schemas.openxmlformats.org/package/2006/metadata/core-properties"'
            .' xmlns:dc="http://purl.org/dc/elements/1.1/"'
            .' xmlns:dcterms="http://purl.org/dc/terms/"'
            .' xmlns:dcmitype="http://purl.org/dc/dcmitype/"'
            .' xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">'
            .'<dc:title>Applicants Export</dc:title>'
            .'<dc:creator>Applicant Registry System</dc:creator>'
            .'<cp:lastModifiedBy>Applicant Registry System</cp:lastModifiedBy>'
            .'<dcterms:created xsi:type="dcterms:W3CDTF">'.$timestamp.'</dcterms:created>'
            .'<dcterms:modified xsi:type="dcterms:W3CDTF">'.$timestamp.'</dcterms:modified>'
            .'</cp:coreProperties>';
    }

    private function buildAppPropertiesXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<Properties xmlns="http://schemas.openxmlformats.org/officeDocument/2006/extended-properties"'
            .' xmlns:vt="http://schemas.openxmlformats.org/officeDocument/2006/docPropsVTypes">'
            .'<Application>Applicant Registry System</Application>'
            .'</Properties>';
    }

    private function excelColumnName(int $index): string
    {
        $name = '';

        while ($index > 0) {
            $index--;
            $name = chr(65 + ($index % 26)).$name;
            $index = intdiv($index, 26);
        }

        return $name;
    }

    private function escapeXml(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }
}
