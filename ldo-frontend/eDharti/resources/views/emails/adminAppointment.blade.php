<!DOCTYPE html>
<html>
<head>
    <title>New Appointment Notification</title>
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
            color: #FF5722;
        }
        p {
            font-size: 16px;
            line-height: 1.5;
        }
        .appointment-details {
            margin-top: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border-left: 4px solid #FF5722;
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
        .btn {
            display: inline-block;
            padding: 10px 15px;
            margin-top: 20px;
            background-color: #FF5722;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="container">
            <h1>New Appointment Notification</h1>
            <p>Hello Admin,</p>
            <p>A new appointment has been scheduled in the system. Please review the details below and take the necessary actions to confirm and prepare for the appointment.</p>

            <div class="appointment-details">
                <p><strong>Name:</strong> {{ $appointment->name }}</p>
                <p><strong>Email:</strong> {{ $appointment->email }}</p>
                <p><strong>Mobile:</strong> {{ $appointment->mobile }}</p>
                <p><strong>Appointment ID:</strong> {{ $appointment->unique_id }}</p>
                <p><strong>Date:</strong> {{ $appointment->meeting_date }}</p>
                <p><strong>Time:</strong> {{ $appointment->meeting_timeslot }}</p>
                <p><strong>Purpose of Visit:</strong> {{ $appointment->meeting_purpose }}</p>
                <p><strong>Description:</strong> {{ $appointment->meeting_description }}</p>
            </div>

            <p><strong>Next Steps:</strong></p>
            <ul>
                <li>Verify the details of the appointment and confirm with the visitor if necessary.</li>
                <li>Prepare any required documentation or resources needed for the meeting.</li>
                <li>Ensure that the meeting space is ready and that all involved staff members are informed.</li>
            </ul>

            <p>If you need to view more details or make adjustments, please log in to the admin panel.</p>

            <!-- <a href="{{ url('/admin/appointments') }}" class="btn">View Appointment in Admin Panel</a> -->

            <div class="footer">
                <p>This is an automated message, please do not reply to this email. If you need assistance, contact IT support or the relevant department.</p>
            </div>
        </div>
    </div>
</body>
</html>