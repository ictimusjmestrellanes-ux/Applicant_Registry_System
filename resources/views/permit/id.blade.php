<!DOCTYPE html>
<html>
<head>
    <title>Mayor's Permit to Work</title>

    <style>
        @page {
            size: A4;
            margin: 10mm;
            
        }

        body {
            font-family: Arial, sans-serif;
        }

        .container {
            display: flex;
            gap: 20px;
        }

        .card {
            height: 432px;   /* 6 inches */
            width: 288px;  /* 4 inches */
            border: 2px solid #000;
            padding: 15px;
            box-sizing: border-box;
        }

        .header-green {
            background: #4CAF50;
            color: white;
            text-align: center;
            padding: 15px;
            font-weight: bold;
        }

        .photo-box {
            width: 120px;
            height: 120px;
            border: 1px solid #000;
            margin: 20px auto;
        }

        .blue-box {
            background: #1e4e79;
            color: white;
            padding: 8px;
            margin-bottom: 8px;
            font-size: 13px;
        }

        .value {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .print-btn {
            margin-bottom: 20px;
        }

        @media print {
            .print-btn {
                display: none;
            }
        }
    </style>
</head>

<body>

<button class="print-btn" onclick="window.print()">Print Permit</button>

<div class="container">

    <!-- FRONT -->
    <div class="card">

        <div class="header-green">
            MAYOR'S PERMIT TO WORK
        </div>

        <div class="photo-box"></div>

        <p><strong>PESO NO.</strong> {{ now()->format('Y') }}-{{ $applicant->id }}</p>

        <p><strong>NAME:</strong><br>
            {{ strtoupper($applicant->name) }}
        </p>

        <p><strong>SIGNATURE:</strong></p>

        <br><br>

        <p>
            whose name, signature and photo appearing herein
            is permitted to work within the City of Imus.
        </p>

        <br><br>

        <p><strong>HON. ALEX L. ADVINCULA</strong><br>
            CITY MAYOR
        </p>

    </div>

    <!-- BACK -->
    <div class="card">

        <div class="blue-box">EMPLOYER'S NAME / ADDRESS</div>
        <div class="value">TO BE FILLED</div>

        <div class="blue-box">COMMUNITY TAX NO.</div>
        <div class="value">123456789</div>

        <div class="blue-box">ISSUED ON</div>
        <div class="value">{{ now()->format('m/d/Y') }}</div>

        <div class="blue-box">ISSUED AT</div>
        <div class="value">CITY OF IMUS</div>

        <div class="blue-box">PERMIT EXPIRES ON</div>
        <div class="value">
            {{ now()->addYear()->format('F d, Y') }}
        </div>

        <br><br>

        <div style="border:2px solid red; padding:10px;">
            DOCUMENTARY STAMP TAX PAID
        </div>

    </div>

</div>

</body>
</html>