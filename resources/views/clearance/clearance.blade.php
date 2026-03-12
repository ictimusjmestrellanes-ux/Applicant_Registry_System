<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mayor's Clearance - {{ strtoupper($applicant->first_name) . ' ' . strtoupper($applicant->middle_name ?? '') . ' ' . strtoupper($applicant->last_name) . ' ' . strtoupper($applicant->suffix ?? '')}}</title>
    <style>
        /* Document Setup */
        @page {
            size: 8.5in 14in; /* Legal Size */
            margin: 0.5in;
        }

        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 7.5in;
            margin: 0 auto;
            position: relative;
        }

        /* Header Styles */
        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header img {
            width: 100px; /* Adjust size based on your logo asset */
            height: auto;
            margin-bottom: 10px;
        }

        .republic-text {
            font-size: 11pt;
            margin: 0;
        }

        .office-text {
            font-weight: bold;
            font-size: 13pt;
            margin-top: 15px;
            text-decoration: underline;
        }

        /* Title */
        .document-title {
            text-align: center;
            font-weight: bold;
            font-size: 18pt;
            margin: 40px 0;
            letter-spacing: 2px;
        }

        /* Body Paragraphs */
        .content-body {
            text-align: justify;
            margin-bottom: 20px;
        }

        .content-body p {
            text-indent: 0.5in;
            margin-bottom: 20px;
        }

        .highlight-name {
            font-weight: bold;
            text-transform: uppercase;
        }

        /* Information Table (Bottom Left) */
        .info-table {
            width: 300px;
            margin-top: 40px;
            font-size: 10pt;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 2px 0;
            vertical-align: top;
        }

        /* Signatures (Bottom Right) */
        .signature-section {
            float: right;
            width: 350px;
            text-align: center;
            margin-top: 40px;
        }

        .signature-name {
            font-weight: bold;
            font-size: 12pt;
            text-transform: uppercase;
            margin-top: 50px;
            border-bottom: 1px solid #000;
            display: inline-block;
            min-width: 250px;
        }

        .signature-title {
            display: block;
            font-size: 11pt;
        }

        .authority-text {
            font-size: 10pt;
            font-style: italic;
            margin-bottom: 10px;
        }

        /* Footer */
        .footer-note {
            clear: both;
            margin-top: 100px;
            font-size: 9pt;
        }

        .address-footer {
            text-align: center;
            border-top: 1px solid #000;
            padding-top: 10px;
            font-size: 8pt;
            position: absolute;
            bottom: 0.2in;
            width: 100%;
        }

        /* Print optimization */
        @media print {
            .no-print { display: none; }
            body { -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body>

<div class="container">
    {{-- Header Section [cite: 5, 6] --}}
    <div class="header">
        {{-- Place your logo image in public/images/logo.png --}}
        <img src="{{ asset('images/imus-logo.png') }}" alt="City of Imus Logo">
        <p class="republic-text">Republic of the Philippines </p>
        <p class="republic-text">Province of Cavite </p>
        <p class="republic-text">City of Imus </p>
        <p class="office-text">OCM-PUBLIC EMPLOYMENT SERVICE OFFICE </p>
    </div>

    {{-- Document Title [cite: 9] --}}
    <div class="document-title">
        MAYOR'S CLEARANCE
    </div>

    {{-- Main Content Paragraphs [cite: 10, 11, 12, 13, 14, 15, 16] --}}
    <div class="content-body">
        <p>
            This is to certify that <span class="highlight-name">Mr./Ms. <strong>{{ strtoupper($applicant->first_name) . ' ' . strtoupper($applicant->middle_name ?? '') . ' ' . strtoupper($applicant->last_name) . ' ' . strtoupper($applicant->suffix ?? '')}}</strong></span>,
            a resident of <strong>{{ strtoupper($applicant->address_line) }}, {{ strtoupper($applicant->barangay) }}, {{ strtoupper($applicant->city) }}, {{ strtoupper($applicant->province) }}</strong>,
            is a person of good moral character and has never been accused of, indicted for, or tried for the violation of any law, 
            ordinance, or regulation before any court or tribunal.
        </p>

        <p>
            The information contained in this certification is based on the documents submitted before this Office. 
            The bearer of this document understands that providing false representations herein constitutes an act of fraud.
            False, misleading, or incomplete information will result in the revocation of this certification. 
        </p>

        <p>
            This certification is issued upon the request of <span class="highlight-name">Mr./Ms. {{ $applicant->last_name}}</span> in support
            of his/her application with the <span class="highlight-name">{{ $applicant->hired_company}}</span>. 
        </p>

        <p>
            Issued this <span class="highlight-name">{{ strtoupper(date('jS \D\a\y \o\f F Y')) }}</span>
            in the City of Imus, Cavite. 
        </p>
    </div>

    {{-- Bottom Section Wrapper --}}
    <div style="margin-top: 50px;">
        {{-- Metadata Table [cite: 20] --}}
        <table class="info-table">
            <tr>
                <td width="40%">O.R No.:</td>
                <td><strong>{{ $applicant->or_no ?? '3122309' }}</strong></td>
            </tr>
            <tr>
                <td>Issued on:</td>
                <td>{{ strtoupper(date('F d, Y')) }} </td>
            </tr>
            <tr>
                <td>Issued in:</td>
                <td>CITY OF IMUS </td>
            </tr>
            <tr>
                <td>PESO Control No.:</td>
                <td>{{ date('Y') }}-{{ $applicant->control_no ?? '0011' }} </td>
            </tr>
        </table>

        {{-- Signatures [cite: 22, 23, 24] --}}
        <div class="signature-section">
            <span class="signature-name">ALEX L. ADVINCULA</span>
            <span class="signature-title">City Mayor </span>

            <div style="margin-top: 30px;">
                <p class="authority-text">By authority of the City Mayor: </p>
                <span class="signature-name">ATTY. TRICIA MARIE VILLALUZ-BARZAGA</span>
                <span class="signature-title">Chief of Staff</span>
            </div>
        </div>
    </div>

    {{-- Notes [cite: 32, 33] --}}
    <div class="footer-note">
        <p>Note: This certification is valid only for three (3) months from the date of issuance. </p>
        <p><strong>Not valid without Official Dry Seal.</strong></p>
    </div>

    {{-- Fixed Footer Address [cite: 34] --}}
    <div class="address-footer">
        New Imus City Government Center, Imus Boulevard, Malagasang I-G, City of Imus, Cavite<br>
        Telephone: (046) 888-99-10 to 12 loc: 241 | Email: peso@cityofimus.gov.ph
    </div>
</div>

{{-- Print button for browser testing --}}
<div class="no-print" style="position: fixed; top: 20px; right: 20px;">
    <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer; background: #0284c7; color: white; border: none; border-radius: 5px;">
        Print Clearance
    </button>
</div>

</body>
</html>