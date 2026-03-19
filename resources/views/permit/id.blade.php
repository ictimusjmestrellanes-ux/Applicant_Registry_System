<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <style>
        @page {
            size: A4;
            margin: 10mm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
        }

        /* A4 container */
        .print-page {
            width: 210mm;
            min-height: 297mm;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: flex-start;
        }

        /* PERMIT CARD */
        .permit-card {
            width: 6in;
            height: 4in;
            border: 2px solid #000;
            display: flex;
            margin: 10px;
        }

        /* LEFT SIDE */
        .left-panel {
            width: 50%;
            background: #e9f4ea;
            padding: 10px;
        }

        .header {
            text-align: center;
        }

        .header img {
            width: 40px;
        }

        .title {
            font-weight: bold;
            font-size: 16px;
            color: #2f7f3c;
        }

        .subtitle {
            font-size: 11px;
        }

        .photo-box {
            width: 110px;
            height: 120px;
            background: #ccc;
            margin: 8px auto;
        }

        .name-row {
            margin-top: 6px;
        }

        .label {
            background: #4caf50;
            color: #fff;
            padding: 3px 6px;
            font-size: 10px;
            font-weight: bold;
        }

        .name {
            font-weight: bold;
            border-bottom: 1px solid #000;
            display: inline-block;
            width: 70%;
        }

        .signature {
            border-bottom: 1px solid #000;
        }

        .mayor {
            text-align: center;
            font-size: 10px;
            margin-top: 6px;
        }

        /* RIGHT SIDE */

        .right-panel {
            width: 50%;
            padding: 8px;
            border-left: 2px solid #000;
        }

        .field {
            margin-bottom: 5px;
        }

        .field-title {
            background: #1c4f73;
            color: #fff;
            font-size: 9px;
            padding: 2px 4px;
            font-weight: bold;
        }

        .field-value {
            border-bottom: 1px solid #000;
            font-size: 11px;
            font-weight: bold;
        }

        /* DOCUMENTARY BOX */

        .doc-box {
            border: 2px solid red;
            margin-top: 6px;
            padding: 4px;
        }

        .doc-title {
            text-align: center;
            color: red;
            font-weight: bold;
            font-size: 11px;
        }

        .doc-row {
            display: flex;
            justify-content: space-between;
            font-size: 10px;
        }
    </style>

</head>

<body>

    <div class="print-page">
        @php
            $permit = optional($applicant->permit);
        @endphp
        <div class="permit-card">

            <!-- LEFT -->
            <div class="left-panel">

                <div class="header">

                    <img src="{{ public_path('logo1.png') }}">
                    <img src="{{ public_path('logo2.png') }}">

                    <div style="font-size:9px">
                        Republic of the Philippines<br>
                        Province of Cavite<br>
                        City Government of Imus<br>
                        Public Employment Service Office
                    </div>

                    <div class="title">
                        MAYOR'S PERMIT TO WORK
                    </div>

                    <div class="subtitle">
                        is hereby given to
                    </div>

                </div>

                {{-- PHOTO --}}
                <div class="photo-box">
                    <img src="{{ public_path('storage/' . $applicant->photo) }}" style="width:100%;height:100%;">
                </div>

                {{-- PESO ID --}}
                <div style="text-align:center;font-size:9px;">
                    PESO ID NO.
                    <strong>{{ $permit->peso_id_no ?? 'N/A' }}</strong>
                </div>

                {{-- NAME --}}
                <div class="name-row">
                    <span class="label">NAME</span>
                    <span class="name">
                        {{ strtoupper($applicant->first_name . ' ' . $applicant->middle_name . ' ' . $applicant->last_name) }}
                    </span>
                </div>

                {{-- SIGNATURE --}}
                <div class="name-row">
                    <span class="label">SIGNATURE</span>
                    <span class="signature"></span>
                </div>

                {{-- MAYOR --}}
                <div class="mayor">
                    <b>HON. ALEX L. ADVINCULA</b><br>
                    CITY MAYOR
                </div>

            </div>


            <!-- RIGHT -->
            <div class="right-panel">

                {{-- EMPLOYER --}}
                <div class="field">
                    <div class="field-title">EMPLOYER'S NAME / ADDRESS</div>
                    <div class="field-value">
                        {{$applicant->hiring_company ?? 'N/A' }}
                    </div>
                </div>

                {{-- EMPLOYEE ADDRESS --}}
                <div class="field">
                    <div class="field-title">EMPLOYEE ADDRESS</div>
                    <div class="field-value">
                        {{ $applicant->address_line }},
                        {{ $applicant->barangay }},
                        {{ $applicant->city }},
                        {{ $applicant->province }}
                    </div>
                </div>

                {{-- COMMUNITY TAX --}}
                <div class="field">
                    <div class="field-title">COMMUNITY TAX NO.</div>
                    <div class="field-value">
                        {{ $permit->community_tax_no ?? 'N/A' }}
                    </div>
                </div>

                {{-- ISSUED ON --}}
                <div class="field">
                    <div class="field-title">ISSUED ON</div>
                    <div class="field-value">
                        {{ $permit->permit_issued_on ? \Carbon\Carbon::parse($permit->permit_issued_on)->format('F d, Y') : 'N/A' }}
                    </div>
                </div>

                {{-- ISSUED AT --}}
                <div class="field">
                    <div class="field-title">ISSUED AT</div>
                    <div class="field-value">
                        {{ $permit->permit_issued_in ?? 'IMUS, CAVITE' }}
                    </div>
                </div>

                {{-- OR NUMBER --}}
                <div class="field">
                    <div class="field-title">O.R NO.</div>
                    <div class="field-value">
                        {{ $permit->permit_or_no ?? 'N/A' }}
                    </div>
                </div>

                {{-- PERMIT DATE --}}
                <div class="field">
                    <div class="field-title">DATED</div>
                    <div class="field-value">
                        {{ $permit->permit_date ? \Carbon\Carbon::parse($permit->permit_date)->format('F d, Y') : 'N/A' }}
                    </div>
                </div>

                {{-- EXPIRATION --}}
                <div class="field">
                    <div class="field-title">THIS PERMIT EXPIRES ON</div>
                    <div class="field-value">
                        {{ $permit->expires_on ? \Carbon\Carbon::parse($permit->expires_on)->format('F d, Y') : 'N/A' }}
                    </div>
                </div>

                {{-- DOCUMENTARY --}}
                <div class="doc-box">

                    <div class="doc-title">
                        DOCUMENTARY STAMP TAX PAID
                    </div>

                    <div style="text-align:center;font-weight:bold;">
                        {{ $permit->permit_doc_stamp_control_no ?? 'N/A' }}
                    </div>

                    {{-- GOR SERIAL --}}
                    <div class="doc-row">
                        <div>GOR SERIAL NO.</div>
                        <div>{{ $permit->permit_or_no ?? 'N/A' }}</div>
                    </div>

                    {{-- DATE OF PAYMENT --}}
                    <div class="doc-row">
                        <div>DATE OF PAYMENT</div>
                        <div>
                            {{ $permit->permit_date_of_payment ? \Carbon\Carbon::parse($permit->permit_date_of_payment)->format('F d, Y') : 'N/A' }}
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>

</body>

</html>