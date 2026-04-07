<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Mayor's Permit ID</title>
    <style>
        @page {
            size: A4;
            margin: 12mm;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            background: #ffffff;
            font-family: Calibri, 'Candara', 'Segoe UI', 'Optima', 'Arial', sans-serif;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        :root {
            --page-width: 210mm;
            --page-height: 297mm;
            --text: #111111;
            --green: #0a9c39;
            --blue: #173b8f;
            --soft-gray: rgba(17, 17, 17, 0.08);
        }

        .print-page {
            width: var(--page-width);
            min-height: var(--page-height);
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: flex-start;
        }

        .id-sheet {
            position: relative;
            width: 6in;
            height: 4in;
            margin: 28px auto 0;
            background-image: url('{{ asset('images/permit_ID1.png') }}');
            background-repeat: no-repeat;
            background-position: center;
            background-size: 100% 100%;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.10);
        }

        .id-sheet::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.03), rgba(255, 255, 255, 0.10) 38%, rgba(255, 255, 255, 0.18) 100%);
            pointer-events: none;
        }

        .field {
            position: absolute;
            color: #000000;
            font-weight: 700;
            text-transform: uppercase;
            line-height: 1.15;
            word-break: break-word;
        }

        .photo {
            position: absolute;
            left: 17.3%;
            top: 23.8%;
            width: 16.7%;
            height: 24.2%;
            object-fit: cover;
        }

        .peso-id {
            left: 23.5%;
            top: 191.39px;
            width: 25.8%;
            font-size: 9px;
            position: absolute;
        }

        .front-name {
            left: 15.6%;
            top: 56.6%;
            width: 32%;
            font-size: 8pt;
        }

        .employer {
            left: 53.6%;
            top: 16.5%;
            width: 42%;
            font-size: 9pt;
        }

        .address {
            left: 53.6%;
            top: 31%;
            width: 42%;
            font-size: 8pt;
        }

        .community-tax {
            left: 77%;
            top: 37.2%;
            width: 35.6%;
            font-size: 7pt;
        }

        .issued-on {
            left: 77%;
            top: 42.8%;
            width: 35.6%;
            font-size: 7pt;
        }

        .issued-at {
            left: 77%;
            top: 48.5%;
            width: 35.6%;
            font-size: 7pt;
        }

        .official-receipt {
            left: 77%;
            top: 54.3%;
            width: 35.6%;
            font-size: 7pt;
        }

        .dated {
            left: 77%;
            top: 59.9%;
            width: 35.6%;
            font-size: 7pt;
        }

        .expires {
            left: 77%;
            top: 65.6%;
            width: 35.6%;
            font-size: 7pt;
        }

        .stamp-control {
            left: 60.3%;
            top: 79.5%;
            width: 29.5%;
            font-size: 8pt;
            color: #b12222;
            text-align: center;
            font-family: Tahoma, Geneva, Verdana, sans-serif;
        }

        .stamp-gor {
            left: 56.2%;
            top: 86.5%;
            width: 14.6%;
            font-size: 6.1pt;
            color: #b12222;
            text-align: center;
            font-family: Tahoma, Geneva, Verdana, sans-serif;
        }

        .stamp-payment {
            left: 79.4%;
            top: 86.5%;
            width: 15%;
            font-size: 6.1pt;
            color: #b12222;
            text-align: center;
            font-family: Tahoma, Geneva, Verdana, sans-serif;
        }

        .no-print {
            width: var(--page-width);
            margin: 18px auto;
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

        @media print {
            body {
                background: #fff;
            }

            .id-sheet {
                box-shadow: none;
            }

            .print-page {
                width: auto;
                min-height: auto;
                margin: 0;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    @php
        $permit = optional($applicant->permit);
        $fullName = strtoupper(trim(collect([
            $applicant->first_name,
            $applicant->middle_name ? strtoupper(substr(trim($applicant->middle_name), 0, 1)).'.' : null,
            $applicant->last_name,
            $applicant->suffix,
        ])->filter()->implode(' ')));
        $address = strtoupper(trim(collect([
            $applicant->barangay,
            $applicant->city,
            $applicant->province,
        ])->filter()->implode(', ')));
        $employer = strtoupper($applicant->hiring_company ?: $applicant->hired_company ?: 'N/A');
        $photoPath = ! empty($applicant->photo) ? public_path('storage/'.$applicant->photo) : null;
        $issuedOn = $permit->permit_issued_on ? strtoupper(\Carbon\Carbon::parse($permit->permit_issued_on)->format('F j, Y')) : 'N/A';
        $issuedAt = strtoupper($permit->permit_issued_at ?? 'N/A');
        $permitDate = $permit->permit_date ? strtoupper(\Carbon\Carbon::parse($permit->permit_date)->format('F j, Y')) : 'N/A';
        $expiresOn = $permit->expires_on ? strtoupper(\Carbon\Carbon::parse($permit->expires_on)->format('F j, Y')) : 'N/A';
        $paymentDate = $permit->permit_date_of_payment ? strtoupper(\Carbon\Carbon::parse($permit->permit_date_of_payment)->format('F j, Y')) : 'N/A';
    @endphp
    <div class="no-print">
        <button onclick="window.print()">Print Permit ID</button>
    </div>

    <div class="print-page">
        <div class="id-sheet">
            @if($photoPath && file_exists($photoPath))
                <img src="{{ $photoPath }}" alt="Applicant Photo" class="photo">
            @endif

            <div class="field peso-id">OP{{ strtoupper($permit->peso_id_no ?? 'N/A') }}</div>
            <div class="field front-name">{{ $fullName }}</div>

            <div class="field employer">{{ $employer }}</div>
            <div class="field address">{{ $address }}</div>
            <div class="field community-tax">{{ strtoupper($permit->community_tax_no ?? 'N/A') }}</div>
            <div class="field issued-on">{{ $issuedOn }}</div>
            <div class="field issued-at">{{ $issuedAt }}</div>
            <div class="field official-receipt">{{ strtoupper($permit->permit_or_no ?? 'N/A') }}</div>
            <div class="field dated">{{ $permitDate }}</div>
            <div class="field expires">{{ $expiresOn }}</div>

            <div class="field stamp-control">CGI-{{ strtoupper($permit->permit_doc_stamp_control_no ?? 'N/A') }}</div>
            <div class="field stamp-gor">{{ strtoupper($permit->permit_or_no ?? 'N/A') }}</div>
            <div class="field stamp-payment">{{ $paymentDate }}</div>
        </div>
    </div>

    
</body>
</html>
