<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Referral Letter</title>

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
            font-family: "Times New Roman", Times, serif;
            font-size: 14.5px;
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
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.03), rgba(255, 255, 255, 0.10) 38%, rgba(255, 255, 255, 0.18) 100%);
            pointer-events: none;
        }

        .content {
            position: relative;
            z-index: 1;
        }

        .letter {
            margin-top: 60mm;
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
            margin-bottom: 7mm;
            font-weight: 700;
            text-transform: uppercase;
        }

        .ref-no {
            margin-bottom: 4mm;
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
            margin-top: 7mm;
            margin-bottom: 6mm;
        }

        .body p {
            margin: 0 0 5mm;
            text-align: justify;
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
            $applicant->middle_name,
            $applicant->last_name,
            $applicant->suffix,
        ])->filter()->implode(' '));

        $applicantAddress = strtoupper(trim(collect([
            $applicant->address_line,
            $applicant->barangay,
            $applicant->city,
            $applicant->province,
        ])->filter()->implode(' ')));

        $recipientName = strtoupper(trim(collect([
            $referral->ref_mayor_recipient_firstname,
            $referral->ref_mayor_recipient_middlename,
            $referral->ref_mayor_recipient_lastname,
        ])->filter()->implode(' ')) ?: 'MR. JOHN TEO ONG');

        $recipientTitle = 'President';
        $companyName = strtoupper($referral->ref_hired_company ?: 'ANNIES’ CANDY MANUFACTURING');
        $recipientAddress = $referral->ref_place ?: 'City of Imus, Cavite';
        $letterDate = now()->format('F d, Y');
        $referralNumber = $referral->ref_or_no ?: 'PESO-OCRL2026-098';
        $residentName = strtoupper($applicantName ?: 'AARON GABRIEL D. MIRANDA');
        $residentAddress = $applicantAddress ?: '271 ANABU I-B CITY OF IMUS CAVITE';
        $surname = strtoupper($applicant->last_name ?: 'MIRANDA');
        $salutationName = trim(collect(explode(' ', preg_replace('/\s+/', ' ', $recipientName)))->last() ?: 'ONG');
    @endphp

    <div class="no-print">
        <button onclick="window.print()">Print Referral Letter</button>
    </div>

    <div class="page">
        <div class="content">
            <div class="letter">
                <p class="letter-title">Referral Letter</p>

                <p class="ref-no">{{ $referralNumber }}</p>

                <p class="date-line">{{ $letterDate }}</p>

                <p class="recipient"><strong>{{ $recipientName }}</strong></p>
                <p class="recipient">{{ $recipientTitle }}</p>
                <p class="recipient">{{ $companyName }}</p>
                <p class="recipient">{{ $recipientAddress }}</p>

                <p class="salutation">Dear Mr. {{ ucwords(strtolower($salutationName)) }},</p>

                <div class="body">
                    <p>Warmest greetings of Public Service!</p>

                    <p>
                        In line with the jobs/employment assistance to the people of Cavite, may I personally refer to
                        you, Mr./Ms. {{ $residentName }} of {{ $residentAddress }}, for employment.
                    </p>

                    <p>
                        Mr./Ms. {{ $surname }} is a person of good moral character, diligent and well-qualified for the
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
                    City Government Department Head I
                </p>
            </div>
        </div>
    </div>
</body>

</html>
