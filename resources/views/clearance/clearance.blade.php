<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mayor's Clearance -
        {{ strtoupper(trim($applicant->first_name . ' ' . ($applicant->middle_name ?? '') . ' ' . $applicant->last_name . ' ' . ($applicant->suffix ?? ''))) }}
    </title>
    <style>
        @page {
            size: legal;
            margin: 0;
        }

        * {
            box-sizing: border-box;
        }

        :root {
            --page-width: 210mm;
            --page-height: 297mm;
            --text: #111111;
            --green: #0a9c39;
            --blue: #173b8f;
            --soft-gray: rgba(17, 17, 17, 0.08);
        }

        html,
        body {
            margin: 0;
            padding: 0;
            font-family: Tahoma, Geneva, Verdana, sans-serif;
            background: #eef2f7;
            color: var(--text);
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .page {
            position: relative;
            width: 8.5in;
            min-height: 13in;
            margin: 0 auto;
            background-image: url('{{ asset('images/legal_format1.jpg') }}');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center top;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.10);
        }

        .page::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.03), rgba(255, 255, 255, 0.10) 38%, rgba(255, 255, 255, 0.18) 100%);
            pointer-events: none;
        }

        .sheet {
            position: relative;
            min-height: 13in;
            padding: 2.25in 0.72in 1.3in 0.88in;
        }

        .top-left-mark {
            position: absolute;
            top: 0.35in;
            right: 0.42in;
            width: 1.35in;
            height: auto;
        }

        .title {
            margin: .7in 0 0.35in;
            text-align: center;
            font-size: 20pt;
            font-weight: 800;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            text-decoration: underline;
        }

        .body {
            font-size: 12pt;
            line-height: 1.75;
            text-align: justify;
        }

        .body p {
            margin: 0 0 0.15in;
            text-indent: 0.55in;
        }

        .fill {
            font-size: 12pt;
            font-weight: 700;
            text-transform: uppercase;
        }

        .issued-line {
            margin-top: 0.02in;
        }

        .signature-block {
            width: 3.3in;
            margin: 0.55in 0 0 auto;
            text-align: center;
            font-size: 12pt;
        }

        .signature-name-mayor {
            font-weight: 700;
            text-transform: uppercase;
        }

        .signature-name {
            font-size: 9pt;
            font-weight: 700;
            text-transform: uppercase;
        }

        .signature-title {
            margin-top: 0.03in;
        }

        .by-authority {
            margin: 0.4in 0 0.3in;
            text-align: center;
            font-size: 11pt;
        }

        .authority-line {
            width: 2.35in;
            margin: 0 auto 0.08in;
            border-top: 1px solid #222;
            height: 0;
        }

        .meta {
            display: fixed;
            font-size: 11pt;
            width: 3.75in;
            border: 1px solid #d00;
            padding: 0.12in 0.18in 0.14in;
            color: #d00;
            text-align: center;
            text-transform: uppercase;
        }

        .meta-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 0.28in;
            margin-top: 0.10in;
        }

        .meta-heading {
            font-size: 11pt;
            line-height: 1.1;
        }

        .meta-control-value {
            width: fit-content;
            margin: 0.06in auto 0;
            padding: 0 0.14in 0.03in;
            border-bottom: 1px solid #d00;
            font-size: 11pt;
            font-weight: 700;
            line-height: 1.1;
        }

        .meta-subheading {
            margin-top: 0.05in;
            font-size: 10pt;
            line-height: 1.1;
        }

        .meta-grid {
            display: flex;
            justify-content: space-between;
            gap: 0.28in;
            margin-top: 0.16in;
        }

        .meta-cell {
            flex: 1;
        }

        .meta-cell-value {
            width: fit-content;
            min-width: 1.32in;
            margin: 0 auto;
            padding: 0 0.08in 0.03in;
            border-bottom: 1px solid #d00;
            font-size: 9pt;
            font-weight: 700;
            line-height: 1.1;
        }

        .meta-cell-label {
            margin-top: 0.05in;
            font-size: 9.5pt;
            line-height: 1.1;
        }

        .peso-note {
            display: fixed;
            width: 3.3in;
            font-size: 11pt;
            line-height: 1.65;
        }

        .footer-note {
            display: fixed;
            position: absolute;
            left: 0.88in;
            right: 0.72in;
            bottom: 1.3in;
            font-size: 10pt;
            line-height: 1.45;
        }

        .footer-note p {
            margin: 0;
        }

        .no-print {
            width: var(--page-width);
            margin: 18px auto 0;
            text-align: right;
            margin-bottom: 18px;
        }

        .no-print button {
            border: 0;
            border-radius: 999px;
            padding: 10px 18px;
            background: var(--blue);
            color: #fff;
            font-size: 13px;
            cursor: pointer;
        }

        .csg {
            font-style: italic;
            display: inline-block;
            margin-top: 0.22in;
            font-size: 7pt;
        }

        @media print {
            body {
                background: transparent;
            }

            .no-print {
                display: none;
            }

            .page {
                margin: 0;
            }
        }
    </style>
</head>

<body>
    @php
        $clearance = optional($applicant->clearance);
        $fullName = trim($applicant->first_name . ' ' . ($applicant->middle_name ?? '') . ' ' . $applicant->last_name . ' ' . ($applicant->suffix ?? ''));
        $fullAddress = collect([
            $applicant->address_line,
            $applicant->barangay,
            $applicant->city,
            $applicant->province,
        ])->filter()->map(fn($value) => strtoupper(trim($value)))->implode(', ');
        $issuedOn = $clearance->clearance_issued_on
            ? \Illuminate\Support\Carbon::parse($clearance->clearance_issued_on)
            : now();
        $issuedText = strtoupper($issuedOn->format('F j, Y'));
        $issuedDayText = strtoupper($issuedOn->format('jS \d\a\y \o\f F Y'));
        $company = strtoupper($clearance->clearance_hired_company);
        $controlNo = strtoupper($clearance->clearance_peso_control_no);
        $docStampControlNo = strtoupper($clearance->clearance_doc_stamp_control_no);
        $paymentDate = $clearance->clearance_date_of_payment
            ? strtoupper(\Illuminate\Support\Carbon::parse($clearance->clearance_date_of_payment)->format('F j, Y'))
            : '________________';
        $gorSerialNo = strtoupper($clearance->clearance_or_no);
    @endphp
    <div class="no-print">
        <button onclick="window.print()">Print Clearance Letter</button>
    </div>
    <div class="page">
        <div class="sheet">
            {{-- <img src="{{ asset('images/Imus-Founding.png') }}" alt="Imus Founding Anniversary"
                class="top-left-mark"> --}}

            <h1 class="title">Mayor's Clearance</h1>

            <div class="body">
                <p>
                    This is to certify that Mr./Ms. <span class="fill"
                        style="text-decoration: underline;">{{ strtoupper($fullName) }}</span>, a resident of
                    <span class="fill" style="text-decoration: underline;">{{ $fullAddress }}</span>, is a person of
                    good moral character and has never been
                    accused of, indicted for, or tried for the violation of any law, ordinance, or regulation before any
                    court or tribunal.
                </p>

                <p>
                    The information contained in this certification is based on the documents submitted before this
                    Office. The bearer of this document understands that providing false representations herein
                    constitutes an act of fraud. False, misleading, or incomplete information will result in the
                    revocation of this certification.
                </p>

                <p>
                    This certification is issued upon the request of Mr./Ms. <span class="fill"
                        style="text-decoration: underline;">{{$applicant->last_name}}</span> in support of his/her
                    application with the <span class="fill" style="text-decoration: underline;">{{ $company }}</span>.
                </p>

                <p class="issued-line">
                    Issued this <span class="fill" style="text-decoration: underline;">{{ $issuedDayText }}</span> in
                    the City of
                    Imus, Cavite.
                </p>
            </div>

            <div class="signature-block">
                <div class="signature-name-mayor">ALEX L. ADVINCULA</div>
                <div class="signature-title">City Mayor</div>

                <p class="by-authority">By authority of the City Mayor:</p>
                <div class="authority-line"></div>
                <div class="signature-name">ATTY. TRICIA MARIE VILLALUZ-BARZAGA</div>
                <div class="signature-title">Chief of Staff</div>
            </div>

            <div class="meta-section">
                <div class="peso-note">
                    O.R No.: <span class="fill"
                        style="text-decoration: underline; font-size: 11pt;">{{ strtoupper($clearance->clearance_or_no) }}</span><br>
                    Issued on: <span class="fill"
                        style="text-decoration: underline; font-size: 11pt;">{{ $issuedText }}</span><br>
                    Issued in: <span class="fill" style="text-decoration: underline; font-size: 11pt;">CITY OF
                        IMUS</span><br>
                    PESO Control No.: <span class="fill"
                        style="text-decoration: underline; font-size: 11pt;">PESO-OCMC{{ $controlNo }}</span><br>
                    <span class="csg">Peso/csg2026</span>
                </div>

                <div class="meta">
                    <div class="meta-heading">Documentary Stamp Tax Paid</div>
                    <div class="meta-control-value">CGI-{{ $docStampControlNo }}</div>
                    <div class="meta-subheading">Control Number</div>

                    <div class="meta-grid">
                        <div class="meta-cell">
                            <div class="meta-cell-value">{{ $gorSerialNo }}</div>
                            <div class="meta-cell-label">GOR Serial Number</div>
                        </div>
                        <div class="meta-cell">
                            <div class="meta-cell-value">{{ $paymentDate }}</div>
                            <div class="meta-cell-label">Date of Payment</div>
                        </div>
                    </div>
                </div>
                <div class="footer-note">
                    <p>Note: This certification is valid only for three (3) months from the date of issuance.</p>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Not valid without Official Dry Seal.</p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>