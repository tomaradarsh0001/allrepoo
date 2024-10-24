<!DOCTYPE html>
<html>
<head>
    <title>Appointment Rejected</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #d9534f;
        }
        .appointment-card {
            background-color: #f8d7da;
            border-left: 4px solid #d9534f;
            padding: 15px;
            margin-top: 20px;
            border-radius: 8px;
        }
        .appointment-card p {
            margin: 0;
            color: #721c24;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #888888;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <h1>Your Appointment has been Rejected</h1>
        <p>Dear {{ $appointment->name }},</p>
        <p>We regret to inform you that your appointment has been canceled. Please find the details of the canceled appointment below:</p>
        
        <div class="appointment-card">
            <p><strong>Appointment ID:</strong> {{ $appointment->unique_id }}</p>
            <p><strong>Date:</strong> {{ $appointment->meeting_date->format('Y-m-d') }}</p>
            <p><strong>Time:</strong> {{ $appointment->meeting_timeslot }}</p>
            <p><strong>Purpose:</strong> {{ $appointment->meeting_purpose }}</p>
            <p><strong>Reason for Cancellation:</strong> {{ $appointment->remark }}</p>
        </div>

        <p>We apologize for any inconvenience this may have caused. If you have any questions or need further assistance, please do not hesitate to contact our support team.</p>
        
        <p>Best regards,</p>
        <p>The Land and Development Team</p>

        <div class="footer">
            <p>This is an automated message, please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
