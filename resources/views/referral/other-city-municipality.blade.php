<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mayor's Referral Letter Outside Imus</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo_peso.png') }}">

    <style>
        @page {
            size: A4;
            margin: 0;
        }

        :root {
            --page-width: 210mm;
            --page-height: 297mm;
            --text: #111111;
            --green: #0a9c39;
            --blue: #173b8f;
            --soft-gray: rgba(17, 17, 17, 0.08);
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            background: #eef2f7;
            color: var(--text);
            font-family: Arial, Helvetica, sans-serif;
            font-size: 13.5px;
            line-height: 1.5;
        }

        .no-print {
            width: var(--page-width);
            margin: 18px auto 0;
            text-align: right;
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

        .page {
            position: relative;
            width: var(--page-width);
            min-height: var(--page-height);
            margin: 14px auto 24px;
            padding: 17mm 18mm 18mm;
            background: #fff;
            background-image: url("{{ asset('images/other-cities.jpg') }}");
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
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

        .content {
            position: relative;
            z-index: 1;
        }

        .top-mark {
            position: absolute;
            top: 8mm;
            right: 10mm;
        }

        .top-mark img {
            width: 34mm;
            height: auto;
            display: block;
        }

        .header {
            text-align: center;
            margin-top: 2mm;
        }

        .city-seal {
            width: 33mm;
            height: 33mm;
            margin: 0 auto 6px;
            object-fit: contain;
        }

        .header .gov-line {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 15px;
            line-height: 1.25;
        }

        .header .divider {
            width: 34mm;
            margin: 5px auto 8px;
            border: 0;
            border-top: 1.5px solid #333;
        }

        .header .office-name {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 18px;
            font-weight: 800;
            letter-spacing: 0.6px;
            color: var(--green);
            text-transform: uppercase;
        }

        .letter {
            margin-top: 55mm;
        }

        .ref-no,
        .date-line,
        .recipient,
        .salutation,
        .closing,
        .signature {
            margin: 0;
        }

        .ref-no {
            margin-bottom: 1mm;
            font-style: italic;
        }

        .date-line {
            margin-bottom: 8mm;
        }

        .recipient {
            line-height: 1.45;
            
            font-weight: 700;
        }

        .recipient strong {
            display: inline-block;
            font-weight: 700;
            text-transform: uppercase;
        }

        .salutation {
            margin-top: 6mm;
            margin-bottom: 6mm;
        }

        .body p {
            margin: 0 0 5mm;
            text-align: justify;
        }
        
        .greet {
            font-style: italic;
        }

        .closing {
            margin-top: 2mm;
            margin-bottom: 12mm;
        }

        .signature {
            line-height: 1.35;
        }

        .signature .name {
            display: inline-block;
            font-weight: 700;
            text-transform: uppercase;
        }

        .signature-row {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            gap: 12px;
            width: 100%;
        }

        .signature-role {
            display: inline-block;
        }

        .signature-seal-note {
            font-size: 11px;
            font-style: italic;
            text-align: right;
            white-space: nowrap;
        }

        .copy{
            font-size: 11px;
            font-style: italic;
        }

        @media print {
            body {
                background: #fff;
            }

            .no-print {
                display: none;
            }

            .page {
                margin: 0;
                box-shadow: none;
            }
        }
    </style>
</head>

<body>
    @php
        $referral = $applicant->referral;

        $applicantName = trim(collect([
            $applicant->first_name,
            $applicant->middle_name ? strtoupper(substr(trim($applicant->middle_name), 0, 1)).'.' : null,
            $applicant->last_name,
            $applicant->suffix,
        ])->filter()->implode(' '));

        $applicantAddress = strtoupper(trim(collect([
            $applicant->address_line,
            $applicant->barangay,
            $applicant->city,
            $applicant->province,
        ])->filter()->implode(', ')));

        $recipientName = strtoupper($referral->ref_recipient);
        $recipientOffice = 'City Mayor';
        $recipientCityGov = $referral->ref_city_gov;
        $recipientAddress = $referral->ref_company_address;
        $orcl = $referral->ref_ocrl;
        $letterDate = now()->format('F d, Y');
        $residentName = strtoupper($applicantName);
        $residentAddress = $applicantAddress;
        $companyName = strtoupper($applicant->hiring_company);
        $surname = strtoupper($applicant->last_name);
        $salutationName = trim(collect(explode(' ', preg_replace('/\s+/', ' ', $recipientName)))->last());
    @endphp

    <div class="no-print">
        <button onclick="window.print()">Print Referral Letter Outside Imus</button>
    </div>

    <div class="page">
        <div class="content">
            <div class="letter">
                <u class="ref-no">PESO-IMUS-OCRL{{ $orcl }}</u>

                <p class="date-line">{{ $letterDate }}</p>

                <p class="recipient"><strong>HON. {{ $recipientName }}</strong></p>
                <p class="recipient">{{ $recipientOffice }}</p>
                <p class="recipient">{{ $recipientCityGov }}</p>
                <p class="recipient">{!! nl2br(e($recipientAddress)) !!}</p>

                <p class="salutation"><strong>Dear Mayor {{ ucwords(strtolower($salutationName)) }}:</strong></p>

                <div class="body">
                    <p class="greet">Warmest greetings of Public Service!</p>

                    <p>
                        On behalf of the City Mayor of Imus, <strong>HON. ALEX "AA" L. ADVINCULA</strong>, I am writing to formally
                        endorse the employment application of Mr./Ms. <strong>{{ $residentName }}</strong> of <strong>{{ $residentAddress }}</strong>.
                    </p>

                    <p>
                        Mr./Ms. <strong>{{ $surname }}</strong> is an aspiring professional seeking to join the workforce of
                        <strong>{{ $companyName }}</strong>. As part of our office's mission to facilitate sustainable employment for our
                        constituents, we have vetted his/her application and believe his/her qualifications make
                        him/her a suitable candidate for your consideration.
                    </p>

                    <p>
                        We would be grateful for any assistance or favorable consideration your office could extend to
                        him/her. We remain committed to supporting the career growth of our residents and fostering
                        productive relationships with neighboring cities and industries.
                    </p>

                    <p>
                        Thank you for your partnership in promoting inclusive growth and employment.
                    </p>
                </div>

                <p class="closing">Respectfully yours,</p>

                <p class="signature">
                    <span class="name">CECILE C. FOZ</span><br>
                    <span class="signature-row">
                        <span class="signature-role">City Government Department Head I</span>
                        <span class="signature-seal-note">Not Valid Without Official Dry Seal</span>
                    </span>
                    <br>
                    <br>
                    <span class="copy">/PESOfile2026</span><br>
                    <span class="copy">/au.cg.</span><br>
                    <span class="copy">/1st copy</span><br>
                </p>
            </div>
        </div>
    </div>
</body>

</html>
