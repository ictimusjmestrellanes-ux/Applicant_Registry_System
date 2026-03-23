<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Mayor's Permit ID</title>

    <style>
        @page {
            size: A4;
            margin: 10mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            color: #11253d;
            background: #ffffff;
        }

        .print-page {
            width: 210mm;
            min-height: 297mm;
            padding: 12mm 8mm;
        }

        .cards-wrap {
            display: flex;
            gap: 14mm;
            justify-content: center;
            align-items: flex-start;
            flex-wrap: wrap;
        }

        .permit-id {
            width: 86mm;
            height: 54mm;
            border-radius: 14px;
            overflow: hidden;
            border: 1.6px solid #163b67;
            background: #ffffff;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            position: relative;
        }

        .permit-id.front {
            background:
                radial-gradient(circle at top right, rgba(16, 185, 129, 0.18), transparent 26%),
                linear-gradient(180deg, #ffffff 0%, #f2f8ff 100%);
        }

        .permit-id.back {
            background:
                linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        }

        .top-band {
            background: linear-gradient(135deg, #0f3e73, #1f5fa0);
            color: #ffffff;
            padding: 7px 9px 8px;
        }

        .top-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }

        .gov-copy {
            flex: 1;
            line-height: 1.18;
        }

        .gov-copy .tiny {
            font-size: 6.4px;
            opacity: 0.95;
        }

        .gov-copy .title {
            margin-top: 3px;
            font-size: 10.5px;
            font-weight: 700;
            letter-spacing: 0.04em;
        }

        .logo-stack {
            display: flex;
            gap: 4px;
            align-items: center;
        }

        .logo-box {
            width: 18px;
            height: 18px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.14);
            border: 1px solid rgba(255, 255, 255, 0.26);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .logo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .front-body {
            padding: 8px 9px 9px;
        }

        .front-main {
            display: grid;
            grid-template-columns: 24mm 1fr;
            gap: 8px;
            align-items: start;
        }

        .photo-box {
            width: 24mm;
            height: 28mm;
            border-radius: 8px;
            overflow: hidden;
            background: #e7eef7;
            border: 1px solid #b9cbe0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6a7f95;
            font-size: 7px;
            text-align: center;
        }

        .photo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .permit-chip {
            display: inline-block;
            padding: 3px 7px;
            border-radius: 999px;
            background: #dbeafe;
            color: #1d4ed8;
            font-size: 6.6px;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .name-block {
            margin-bottom: 5px;
        }

        .name-label,
        .mini-label {
            font-size: 6.1px;
            color: #5b728c;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 700;
        }

        .name-value {
            margin-top: 2px;
            font-size: 11.4px;
            font-weight: 700;
            line-height: 1.18;
            color: #11253d;
            text-transform: uppercase;
        }

        .front-meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5px 7px;
            margin-top: 4px;
        }

        .meta-box {
            padding: 4px 5px;
            border-radius: 8px;
            background: #f4f8fc;
            border: 1px solid #d9e5f1;
            min-height: 28px;
        }

        .meta-value {
            margin-top: 2px;
            font-size: 7.3px;
            font-weight: 700;
            line-height: 1.2;
            color: #18324f;
            text-transform: uppercase;
        }

        .signature-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin-top: 7px;
            align-items: end;
        }

        .signature-box {
            font-size: 6.1px;
            color: #51677f;
        }

        .signature-line {
            height: 13px;
            border-bottom: 1px solid #7e93aa;
            margin-bottom: 2px;
        }

        .mayor-box {
            text-align: center;
        }

        .mayor-name {
            font-size: 7px;
            font-weight: 700;
            color: #0f3e73;
            text-transform: uppercase;
        }

        .mayor-role {
            font-size: 5.9px;
            color: #5d6e82;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .back-body {
            padding: 8px 9px 9px;
        }

        .back-title {
            margin-bottom: 7px;
            font-size: 8.8px;
            font-weight: 700;
            color: #0f3e73;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 5px;
        }

        .detail-row {
            padding-bottom: 4px;
            border-bottom: 1px solid #dce6f0;
        }

        .detail-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .detail-value {
            margin-top: 2px;
            font-size: 7.4px;
            font-weight: 700;
            color: #1c3149;
            line-height: 1.25;
            text-transform: uppercase;
        }

        .stamp-box {
            margin-top: 7px;
            padding: 6px 7px;
            border: 1.5px solid #cf2e2e;
            border-radius: 10px;
            background: #fff7f7;
        }

        .stamp-title {
            font-size: 6.3px;
            font-weight: 700;
            color: #c21f1f;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            text-align: center;
            margin-bottom: 3px;
        }

        .stamp-number {
            text-align: center;
            font-size: 9.5px;
            font-weight: 700;
            color: #9f1515;
            margin-bottom: 4px;
        }

        .stamp-meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5px;
        }

        .stamp-meta .detail-value {
            font-size: 6.5px;
        }
    </style>
</head>

<body>
    @php
        $permit = optional($applicant->permit);
        $fullName = trim(collect([
            $applicant->first_name,
            $applicant->middle_name,
            $applicant->last_name,
            $applicant->suffix,
        ])->filter()->implode(' '));
        $address = trim(collect([
            $applicant->address_line,
            $applicant->barangay,
            $applicant->city,
            $applicant->province,
        ])->filter()->implode(', '));
        $logo1 = public_path('logo1.png');
        $logo2 = public_path('logo2.png');
        $photoPath = !empty($applicant->photo) ? public_path('storage/' . $applicant->photo) : null;
    @endphp

    <div class="print-page">
        <div class="cards-wrap">
            <div class="permit-id front">
                <div class="top-band">
                    <div class="top-row">
                        <div class="gov-copy">
                            <div class="tiny">
                                Republic of the Philippines<br>
                                Province of Cavite<br>
                                City Government of Imus
                            </div>
                            <div class="title">MAYOR'S PERMIT TO WORK</div>
                        </div>

                        <div class="logo-stack">
                            <div class="logo-box">
                                @if(file_exists($logo1))
                                    <img src="{{ $logo1 }}" alt="Logo 1">
                                @endif
                            </div>
                            <div class="logo-box">
                                @if(file_exists($logo2))
                                    <img src="{{ $logo2 }}" alt="Logo 2">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="front-body">
                    <div class="front-main">
                        <div class="photo-box">
                            @if($photoPath && file_exists($photoPath))
                                <img src="{{ $photoPath }}" alt="Applicant Photo">
                            @else
                                2x2 PHOTO
                            @endif
                        </div>

                        <div>
                            <div class="permit-chip">PESO ID NO. {{ $permit->peso_id_no ?? 'N/A' }}</div>

                            <div class="name-block">
                                <div class="name-label">Permit Holder</div>
                                <div class="name-value">{{ strtoupper($fullName ?: 'N/A') }}</div>
                            </div>

                            <div class="front-meta">
                                <div class="meta-box">
                                    <div class="mini-label">Position</div>
                                    <div class="meta-value">{{ $applicant->position_hired ?? 'N/A' }}</div>
                                </div>
                                <div class="meta-box">
                                    <div class="mini-label">Status</div>
                                    <div class="meta-value">Authorized Worker</div>
                                </div>
                                <div class="meta-box">
                                    <div class="mini-label">Valid Until</div>
                                    <div class="meta-value">
                                        {{ $permit->expires_on ? \Carbon\Carbon::parse($permit->expires_on)->format('M d, Y') : 'N/A' }}
                                    </div>
                                </div>
                                <div class="meta-box">
                                    <div class="mini-label">Issued On</div>
                                    <div class="meta-value">
                                        {{ $permit->permit_issued_on ? \Carbon\Carbon::parse($permit->permit_issued_on)->format('M d, Y') : 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="signature-row">
                        <div class="signature-box">
                            <div class="signature-line"></div>
                            Signature of Permit Holder
                        </div>

                        <div class="mayor-box">
                            <div class="mayor-name">HON. ALEX L. ADVINCULA</div>
                            <div class="mayor-role">City Mayor</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="permit-id back">
                <div class="top-band">
                    <div class="top-row">
                        <div class="gov-copy">
                            <div class="tiny">Public Employment Service Office</div>
                            <div class="title">PERMIT DETAILS</div>
                        </div>

                        <div class="logo-stack">
                            <div class="logo-box">
                                @if(file_exists($logo1))
                                    <img src="{{ $logo1 }}" alt="Logo 1">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="back-body">
                    <div class="back-title">Official Information</div>

                    <div class="detail-grid">
                        <div class="detail-row">
                            <div class="mini-label">Employer / Hiring Company</div>
                            <div class="detail-value">{{ $applicant->hiring_company ?? 'N/A' }}</div>
                        </div>

                        <div class="detail-row">
                            <div class="mini-label">Employee Address</div>
                            <div class="detail-value">{{ strtoupper($address ?: 'N/A') }}</div>
                        </div>

                        <div class="detail-row">
                            <div class="mini-label">Community Tax No.</div>
                            <div class="detail-value">{{ $permit->community_tax_no ?? 'N/A' }}</div>
                        </div>

                        <div class="detail-row">
                            <div class="mini-label">Issued At</div>
                            <div class="detail-value">{{ $permit->permit_issued_in ?? 'IMUS, CAVITE' }}</div>
                        </div>

                        <div class="detail-row">
                            <div class="mini-label">O.R. No. / Permit Date</div>
                            <div class="detail-value">
                                {{ $permit->permit_or_no ?? 'N/A' }}
                                /
                                {{ $permit->permit_date ? \Carbon\Carbon::parse($permit->permit_date)->format('M d, Y') : 'N/A' }}
                            </div>
                        </div>
                    </div>

                    <div class="stamp-box">
                        <div class="stamp-title">Documentary Stamp Tax Paid</div>
                        <div class="stamp-number">{{ $permit->permit_doc_stamp_control_no ?? 'N/A' }}</div>

                        <div class="stamp-meta">
                            <div>
                                <div class="mini-label">GOR Serial No.</div>
                                <div class="detail-value">{{ $permit->permit_or_no ?? 'N/A' }}</div>
                            </div>
                            <div>
                                <div class="mini-label">Date of Payment</div>
                                <div class="detail-value">
                                    {{ $permit->permit_date_of_payment ? \Carbon\Carbon::parse($permit->permit_date_of_payment)->format('M d, Y') : 'N/A' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
