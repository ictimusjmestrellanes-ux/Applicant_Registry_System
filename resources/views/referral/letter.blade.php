<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Referral Letter Whitin Imus</title>
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
            --blue: #173b8f;
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
            justify-content: center;
        }

        .page::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.03), rgba(255, 255, 255, 0.10) 38%, rgba(255, 255, 255, 0.18) 100%);
            pointer-events: none;
        }

        .content {
            position: relative;
            z-index: 1;
        }

        .letter {
            margin-top: 50mm;
        }

        .letter-title,
        .ref-no,
        .date-line,
        .recipient,
        .salutation,
        .closing,
        .signature {
            margin: 0;
        }

        .letter-title {
            margin-bottom: 5mm;
            font-weight: 700;
            text-transform: uppercase;
            text-align: center;
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
            margin: 0 0 3mm;
            text-align: justify;
        }

        .greet {
            font-style: italic;
        }

        .closing {
            margin-top: 7mm;
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
            gap: 5px;
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

        $recipientName = strtoupper($referral->ref_employer_name);
        $recipientTitle = $referral->ref_position;
        $companyName = strtoupper($referral->ref_hired_company);
        $recipientAddress = $referral->ref_place;
        $letterDate = now()->format('F d, Y');
        $letterYear = now()->format('Y');
        $referralNumber = $referral->ref_imus_ocrl;
        $residentName = strtoupper($applicantName);
        $residentAddress = $applicantAddress;
        $surname = strtoupper($applicant->last_name);
        $salutationName = trim(collect(explode(' ', preg_replace('/\s+/', ' ', $recipientName)))->last());
    @endphp

    <div class="no-print">
        <button onclick="window.print()">Print Referral Letter Within Imus</button>
    </div>

    <div class="page">
        <div class="content">`
            <div class="letter">
                <p class="letter-title" style="font-size: 20px">Referral Letter</p>

                <p class="ref-no">PESO-OCRL{{ $referralNumber }}</p>

                <p class="date-line">{{ $letterDate }}</p>

                <p class="recipient"><strong>{{ $recipientName }}</strong></p>
                <p class="recipient">{{ $recipientTitle }}</p>
                <p class="recipient">{{ $companyName }}</p>
                <p class="recipient">{{ $recipientAddress }}</p>

                <p class="salutation">Dear Mr./Ms. {{ ucwords(strtolower($salutationName)) }},</p>

                <div class="body">
                    <p class="greet">Warmest greetings of Public Service!</p>

                    <p>
                        In line with the jobs/employment assistance to the people of Cavite, may I personally refer to
                        you, Mr./Ms. <strong>{{ $residentName }}</strong> of <strong>{{ $residentAddress }}</strong>, for employment.
                    </p>

                    <p>
                        Mr./Ms. <strong>{{ $surname }}</strong> is a person of good moral character, diligent and well-qualified for the
                        position he/she is applying for. He/She will make an exemplary employee at your company once
                        given the opportunity for employment.
                    </p>

                    <p>
                        Attached, please find his/her credentials for your immediate reference.
                    </p>

                    <p>
                        May you grant his/her employment the soonest possible time. Thank you in anticipation of your
                        favorable response on this matter.
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
                    <span class="copy">/PESOfile{{ $letterYear }}</span><br>
                    <span class="copy">/au.cg.</span><br>
                    <span class="copy">/1st copy</span><br>
                </p>
            </div>
        </div>
    </div>
</body>

</html>
