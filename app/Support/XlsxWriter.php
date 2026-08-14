<?php

namespace App\Support;

use RuntimeException;

class XlsxWriter
{
    /**
     * Build a .xlsx file from sheets.
     *
     * @param  string  $tempPrefix  prefix for the temp file name
     * @param  array<string, array{rows?: array<int, array<int, mixed>>, widths?: array<int, array{int, int, int}>}>  $sheets
     * @return string absolute path of the generated xlsx file
     */
    public static function create(string $tempPrefix, array $sheets): string
    {
        if ($sheets === []) {
            throw new RuntimeException('At least one sheet is required.');
        }

        $sheetNames = array_keys($sheets);

        $tempFile = tempnam(sys_get_temp_dir(), $tempPrefix.'_');
        $xlsxPath = $tempFile.'.xlsx';

        if (file_exists($tempFile)) {
            unlink($tempFile);
        }

        if ($xlsxPath === false) {
            throw new RuntimeException('Unable to create export file.');
        }

        $zip = new \ZipArchive;

        if ($zip->open($xlsxPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            throw new RuntimeException('Unable to create export file.');
        }

        try {
            $zip->addFromString('[Content_Types].xml', self::contentTypesXml(count($sheets)));
            $zip->addFromString('_rels/.rels', self::rootRelsXml());
            $zip->addFromString('xl/workbook.xml', self::workbookXml($sheetNames));
            $zip->addFromString('xl/_rels/workbook.xml.rels', self::workbookRelsXml(count($sheets)));
            $zip->addFromString('xl/styles.xml', self::stylesXml());
            $zip->addFromString('docProps/core.xml', self::corePropertiesXml());
            $zip->addFromString('docProps/app.xml', self::appPropertiesXml());

            foreach (array_values($sheets) as $sheetIndex => $sheet) {
                $zip->addFromString(
                    'xl/worksheets/sheet'.($sheetIndex + 1).'.xml',
                    self::worksheetDocument($sheet['rows'] ?? [], $sheet['widths'] ?? [])
                );
            }
        } finally {
            $zip->close();
        }

        return $xlsxPath;
    }

    private static function worksheetDocument(array $rows, array $widths): string
    {
        $sheetData = '';
        $maxColumns = 0;

        foreach ($rows as $rowIndex => $row) {
            $sheetData .= self::worksheetXml($rowIndex, $row);
            $maxColumns = max($maxColumns, count($row));
        }

        $dimension = $maxColumns > 0
            ? '<dimension ref="A1:'.self::excelColumnName($maxColumns).count($rows).'"/>'
            : '<dimension ref="A1"/>';

        $cols = '';

        if ($maxColumns > 0) {
            $cols = '<cols>';

            if ($widths === []) {
                $cols .= '<col min="1" max="'.$maxColumns.'" width="18" customWidth="1"/>';
            } else {
                foreach ($widths as [$min, $max, $width]) {
                    $cols .= '<col min="'.$min.'" max="'.$max.'" width="'.$width.'" customWidth="1"/>';
                }
            }

            $cols .= '</cols>';
        }

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"'
            .' xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            .$dimension
            .'<sheetViews><sheetView workbookViewId="0"/></sheetViews>'
            .'<sheetFormatPr defaultRowHeight="15"/>'
            .$cols
            .'<sheetData>'.$sheetData.'</sheetData>'
            .'</worksheet>';
    }

    private static function worksheetXml(int $rowIndex, array $row): string
    {
        $cells = '';

        foreach ($row as $columnIndex => $value) {
            $cellReference = self::excelColumnName($columnIndex + 1).($rowIndex + 1);
            $styleIndex = $rowIndex === 0 ? ' s="1"' : '';

            $cells .= '<c r="'.$cellReference.'" t="inlineStr"'.$styleIndex.'><is><t>'
                .self::escapeXml($value)
                .'</t></is></c>';
        }

        return '<row r="'.($rowIndex + 1).'">'.$cells.'</row>';
    }

    private static function contentTypesXml(int $sheetCount): string
    {
        $overrides = '';

        for ($index = 1; $index <= $sheetCount; $index++) {
            $overrides .= '<Override PartName="/xl/worksheets/sheet'.$index.'.xml"'
                .' ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>';
        }

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            .'<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            .'<Default Extension="xml" ContentType="application/xml"/>'
            .'<Override PartName="/xl/workbook.xml"'
            .' ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            .$overrides
            .'<Override PartName="/xl/styles.xml"'
            .' ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
            .'<Override PartName="/docProps/core.xml"'
            .' ContentType="application/vnd.openxmlformats-package.core-properties+xml"/>'
            .'<Override PartName="/docProps/app.xml"'
            .' ContentType="application/vnd.openxmlformats-officedocument.extended-properties+xml"/>'
            .'</Types>';
    }

    private static function rootRelsXml(): string
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

    private static function workbookXml(array $sheetNames): string
    {
        $sheets = '';

        foreach ($sheetNames as $index => $name) {
            $sheetId = $index + 1;
            $sheets .= '<sheet name="'.self::escapeXml(self::sanitizeSheetName($name)).'"'
                .' sheetId="'.$sheetId.'" r:id="rId'.$sheetId.'"/>';
        }

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"'
            .' xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            .'<sheets>'.$sheets.'</sheets>'
            .'</workbook>';
    }

    private static function workbookRelsXml(int $sheetCount): string
    {
        $relationships = '';

        for ($index = 1; $index <= $sheetCount; $index++) {
            $relationships .= '<Relationship Id="rId'.$index.'"'
                .' Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet"'
                .' Target="worksheets/sheet'.$index.'.xml"/>';
        }

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            .$relationships
            .'<Relationship Id="rId'.($sheetCount + 1).'"'
            .' Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles"'
            .' Target="styles.xml"/>'
            .'</Relationships>';
    }

    private static function stylesXml(): string
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

    private static function corePropertiesXml(): string
    {
        $timestamp = now()->utc()->format('Y-m-d\TH:i:s\Z');

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<cp:coreProperties xmlns:cp="http://schemas.openxmlformats.org/package/2006/metadata/core-properties"'
            .' xmlns:dc="http://purl.org/dc/elements/1.1/"'
            .' xmlns:dcterms="http://purl.org/dc/terms/"'
            .' xmlns:dcmitype="http://purl.org/dc/dcmitype/"'
            .' xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">'
            .'<dc:title>Applicant Registry System Export</dc:title>'
            .'<dc:creator>Applicant Registry System</dc:creator>'
            .'<cp:lastModifiedBy>Applicant Registry System</cp:lastModifiedBy>'
            .'<dcterms:created xsi:type="dcterms:W3CDTF">'.$timestamp.'</dcterms:created>'
            .'<dcterms:modified xsi:type="dcterms:W3CDTF">'.$timestamp.'</dcterms:modified>'
            .'</cp:coreProperties>';
    }

    private static function appPropertiesXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<Properties xmlns="http://schemas.openxmlformats.org/officeDocument/2006/extended-properties"'
            .' xmlns:vt="http://schemas.openxmlformats.org/officeDocument/2006/docPropsVTypes">'
            .'<Application>Applicant Registry System</Application>'
            .'</Properties>';
    }

    private static function excelColumnName(int $index): string
    {
        $name = '';

        while ($index > 0) {
            $index--;
            $name = chr(65 + ($index % 26)).$name;
            $index = intdiv($index, 26);
        }

        return $name;
    }

    private static function escapeXml(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }

    private static function sanitizeSheetName(string $name): string
    {
        $name = preg_replace('/[\\[\\]:*?\/\\\\]/', '', $name);

        return mb_substr($name, 0, 31);
    }
}