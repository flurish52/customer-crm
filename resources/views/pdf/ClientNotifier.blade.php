<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Job Started Notification</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f7f7f7;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 20px;
        }
        .header {
            text-align: center;
            padding-bottom: 15px;
            border-bottom: 1px solid #e2e8f0;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #2d3748;
        }
        .content {
            margin-top: 20px;
            font-size: 14px;
            color: #2d3748;
            line-height: 1.5;
        }
        .footer {
            margin-top: 25px;
            font-size: 12px;
            text-align: center;
            color: #718096;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>{{ $businessName}}</h1>
    </div>
    <div class="content">
        <p>Hello {{ $userName }},</p>
        <p>Work on your job “{{ $jobTitle }}” has now started.</p>
        <p>You will be updated as progress is made.</p>
        <p>Thank you,</p>
        <p>{{ $businessName }}</p>
    </div>
    <div class="footer">
{{--        <p>This is an automated message. Please do not reply.</p>--}}
    </div>
</div>
</body>
</html>
