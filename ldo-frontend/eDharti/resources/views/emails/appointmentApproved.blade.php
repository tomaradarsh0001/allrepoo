<!DOCTYPE html>
<html>
<head>
    <title>Appointment Approved</title>
</head>
<body>
    <h1>Your Appointment has been Approved</h1>
    <p>Dear {{ $appointment->name }},</p>
    <p>We are pleased to inform you that your appointment (ID: {{ $appointment->unique_id }}) has been approved.</p>
    <p>Details of your appointment:</p>
    <ul>
        <li>Date: {{ $appointment->meeting_date->format('Y-m-d') }}</li>
        <li>Time: {{ $appointment->meeting_timeslot }}</li>
        <li>Purpose: {{ $appointment->meeting_purpose }}</li>
    </ul>
    <p>Thank you for choosing our service.</p>
</body>
</html>
