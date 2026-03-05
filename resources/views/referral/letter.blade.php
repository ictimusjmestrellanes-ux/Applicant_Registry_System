<!DOCTYPE html>
<html>

<head>
    <title>Referral Letter</title>

    <style>
        /* A4 PAGE SETUP */
        @page {
            size: A4;
            margin: 25mm;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 14px;
            line-height: 1.6;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            margin: auto;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .green {
            color: #008000;
        }

        .content {
            margin-top: 30px;
        }

        .signature {
            margin-top: 60px;
        }

        /* Hide button when printing */
        .no-print {
            margin-bottom: 20px;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="page">

        <!-- PRINT BUTTON -->
        <div class="no-print">
            <button onclick="window.print()">Print Referral Letter</button>
        </div>

        <div class="center">
            <h3>Republic of the Philippines</h3>
            <h4>Province of Cavite</h4>
            <h4>City of Imus</h4>
            <h3 class="green">OCM – PUBLIC EMPLOYMENT SERVICE OFFICE</h3>
            <h2>REFERRAL LETTER</h2>
        </div>

        <div class="content">

            <p><strong>Date:</strong> {{ now()->format('F d, Y') }}</p>

            <p class="bold">
                MR./MS. <strong>{{ strtoupper($applicant->first_name) . ' ' . strtoupper($applicant->middle_name ?? '') . ' ' . strtoupper($applicant->last_name) . ' ' . strtoupper($applicant->suffix ?? '')}}</strong>
            </p>

            <p>Dear Sir/Madam,</p>

            <p>
                Warmest greetings of Public Service!
            </p>

            <p>
                In line with the jobs/employment assistance to the people of Cavite,
                may I personally refer to you
                <strong>
                    {{ 
                        strtoupper($applicant->first_name) . ' ' . strtoupper($applicant->middle_name ?? '') . ' ' . strtoupper($applicant->last_name) . ' ' . strtoupper($applicant->suffix ?? '')
                    }}</strong>
                of <strong>{{ strtoupper($applicant->address_line) }}, {{ strtoupper($applicant->barangay) }}, {{ strtoupper($applicant->city) }}, {{ strtoupper($applicant->province) }}</strong>
            </p>

            <p>
                Mr./Ms. <strong>{{ strtoupper($applicant->last_name) }}</strong> is a person of good moral character,
                diligent and well-qualified for employment.
            </p>

            <p>
                Attached, please find his/her credentials for your immediate reference.
            </p>

            <p>
                May you grant his/her employment the soonest possible time.
            </p>

            <div class="signature">
                <p>Respectfully yours,</p>

                <br><br>

                <p class="bold">
                    CECIL C. FOZ <br>
                    City Government Department Head I
                </p>
            </div>

        </div>

    </div>

</body>

</html>