<!DOCTYPE html>
<html>
<head>
    <title>Appointment Scheduled</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .email-wrapper {
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border: 1px solid #ddd;
        }
        h1 {
            font-size: 24px;
            color: #4CAF50;
        }
        p {
            font-size: 16px;
            line-height: 1.5;
        }
        .appointment-details {
            margin-top: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border-left: 4px solid #4CAF50;
            border-radius: 5px;
        }
        .appointment-details p {
            margin: 0 0 10px;
        }
        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="container">
            <h1>Appointment Scheduled</h1>
            <p>Dear {{ $appointment->name }},</p>
            <p>Thank you for scheduling your appointment with us. We are pleased to inform you that your appointment has been scheduled as follows:</p>

            <div class="appointment-details">
                <p><strong>Appointment ID:</strong> {{ $appointment->unique_id }}</p>
                <p><strong>Date:</strong> {{ $appointment->meeting_date }}</p>
                <p><strong>Time:</strong> {{ $appointment->meeting_timeslot }}</p>
                <p><strong>Purpose of Visit:</strong> {{ $appointment->meeting_purpose }}</p>
            </div>

            <p>Your appointment has been scheduled, and it will be reviewed by our team. Please be aware that if any reason is found to cancel the appointment, we may do so, and you will be notified accordingly. If no issues arise, please prepare to attend at the scheduled time and date. We look forward to assisting you during your visit.</p>

            <p>Best regards,</p>
            <p>The Land and Development Team</p>

            <div class="footer">
                <p>If you have any questions, feel free to check our website for more information.</p>
                <p>This is an automated message, please do not reply to this email.</p>
            </div>
        </div>
    </div>
</body>
</html>

