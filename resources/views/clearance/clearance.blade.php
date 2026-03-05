<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <style>
    @page {
        size: legal portrait;
        margin: 60px 70px;
    }

    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 13px;
        position: relative;
    }

    .header {
        text-align: center;
    }

    .seal {
        width: 100px;
    }

    .peso-title {
        color: #0a8f3c;
        font-weight: bold;
        margin-top: 8px;
        font-size: 15px;
    }

    .title {
        text-align: center;
        font-size: 22px;
        font-weight: bold;
        text-decoration: underline;
        margin-top: 35px;
        margin-bottom: 35px;
    }

    .content {
        text-align: justify;
        line-height: 1.8;
        margin-top: 10px;
    }

    .signature-section {
        margin-top: 70px;
        text-align: right;
    }

    .red-box {
        border: 2px solid red;
        padding: 12px;
        font-size: 12px;
        margin-top: 40px;
        width: 280px;
        float: right;
    }

    .footer {
        position: fixed;
        bottom: 40px;
        left: 0;
        right: 0;
        text-align: center;
        font-size: 11px;
    }

    .green-bar {
        position: fixed;
        bottom: 0;
        left: 0;
        height: 15px;
        width: 100%;
        background: #0a8f3c;
    }
</style>
</head>

<body>

    {{-- Watermark Statue Image (optional) --}}
    {{-- <img src="{{ public_path('images/statue.png') }}" class="watermark"> --}}

    <div class="header">
        <img src="{{ public_path('images/imus-seal.png') }}" class="seal">

        <div>
            Republic of the Philippines<br>
            Province of Cavite<br>
            City of Imus
        </div>

        <div class="peso-title">
            OCM - PUBLIC EMPLOYMENT SERVICE OFFICE
        </div>
    </div>

    <div class="title">
        MAYOR’S CLEARANCE
    </div>

    <div class="content">
        This is to certify that Mr./Ms.
        <strong>{{ strtoupper($applicant->name) }}</strong>,
        a resident of
        <strong>{{ strtoupper($applicant->address) }}</strong>,
        is a person of good moral character and has never been accused of,
        indicted for, or tried for the violation of any law, ordinance,
        or regulation before any court or tribunal.
        <br><br>

        The information contained in this certification is based on the documents
        submitted before this Office. The bearer of this document understands that
        providing false representations herein constitutes an act of fraud.
        False, misleading, or incomplete information will result in the revocation
        of this certification.
        <br><br>

        This certification is issued upon the request of Mr./Ms.
        <strong>{{ strtoupper($applicant->lastname) }}</strong>
        in support of his/her application with the
        <strong>{{ strtoupper($applicant->employer ?? 'ARMED FORCES OF THE PHILIPPINES') }}</strong>.
        <br><br>

        Issued this <strong>{{ now()->format('jS') }} day of {{ now()->format('F Y') }}</strong>
        in the City of Imus, Cavite.
    </div>

    <div class="signature-section">
        <strong>ALEX L. ADVINCULA</strong><br>
        City Mayor
        <br><br>

        By authority of the City Mayor:
        <br><br>

        <strong>ATTY. TRICIA MARIE VILLALUZ-BARZAGA</strong><br>
        Chief of Staff
    </div>

    <div class="red-box">
        DOCUMENTARY STAMP TAX PAID<br>
        CGI-26047356<br><br>

        CONTROL NUMBER<br>
        {{ rand(100000, 999999) }}<br><br>

        OR SERIAL NUMBER<br>
        {{ rand(100000, 999999) }}
        &nbsp;&nbsp;&nbsp;
        DATE OF PAYMENT<br>
        {{ now()->format('F d, Y') }}
    </div>

    <div style="margin-top: 50px; font-size:11px;">
        O.R No.: {{ rand(100000, 999999) }}<br>
        Issued on: {{ now()->format('F d, Y') }}<br>
        Issued in: CITY OF IMUS<br>
        PESO Control No.: {{ now()->format('Y') }}-{{ rand(1000, 9999) }}
    </div>

    <div class="footer">
        New Imus City Government Center, Imus Boulevard, Malagasang I-G,
        City of Imus, Cavite<br>
        Telephone: (046) 888-9110 to 12 loc. 241
        Email: peso@cityofimus.gov.ph
    </div>

    <div class="green-bar"></div>

</body>

</html>