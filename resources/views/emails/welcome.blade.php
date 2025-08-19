<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Zelvra</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', Arial, sans-serif;
            background-color: #f7fafc;
            color: #4a5568;
            margin: 0;
            padding: 20px 0;
            line-height: 1.6;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }

        .header {
            background: linear-gradient(135deg, #0e7490 0%, #0d9488 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
            position: relative;
        }

        .logo {
            font-size: 32px;
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 10px;
            display: inline-block;
        }

        .header::after {
            content: '';
            position: absolute;
            bottom: -20px;
            left: 0;
            width: 100%;
            height: 40px;
            background: linear-gradient(135deg, rgba(14, 116, 144, 0.1) 0%, rgba(13, 148, 136, 0.1) 100%);
            clip-path: polygon(0 0, 100% 0, 100% 20%, 50% 100%, 0 20%);
        }

        .welcome-icon {
            width: 80px;
            height: 80px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .welcome-icon svg {
            width: 40px;
            height: 40px;
            fill: white;
        }

        .content {
            padding: 50px 40px 40px;
        }

        h1 {
            color: #0e7490;
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        p {
            font-size: 16px;
            margin-bottom: 20px;
            color: #4a5568;
        }

        .highlight {
            color: #0d9488;
            font-weight: 500;
        }

        .button-container {
            text-align: center;
            margin: 30px 0;
        }

        .button {
            display: inline-block;
            background: linear-gradient(135deg, #0e7490 0%, #0d9488 100%);
            color: white;
            padding: 14px 35px;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(13, 148, 136, 0.3);
        }

        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(13, 148, 136, 0.4);
        }

        .features {
            display: flex;
            flex-wrap: wrap;
            margin: 40px -15px 30px;
        }

        .feature {
            flex: 1 0 calc(50% - 30px);
            margin: 15px;
            padding: 20px;
            background-color: #f8fafc;
            border-radius: 12px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .feature:hover {
            transform: translateY(-5px);
        }

        .feature-icon {
            width: 50px;
            height: 50px;
            margin: 0 auto 15px;
            background: linear-gradient(135deg, rgba(14, 116, 144, 0.1) 0%, rgba(13, 148, 136, 0.1) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .feature-icon svg {
            width: 24px;
            height: 24px;
            fill: #0d9488;
        }

        .feature h3 {
            font-size: 16px;
            margin-bottom: 10px;
            color: #0e7490;
        }

        .feature p {
            font-size: 14px;
            margin-bottom: 0;
        }

        .footer {
            background-color: #f1f5f9;
            padding: 30px;
            text-align: center;
            font-size: 14px;
            color: #64748b;
        }

        .social-links {
            margin-bottom: 20px;
        }

        .social-link {
            display: inline-block;
            margin: 0 10px;
            color: #0d9488;
            text-decoration: none;
            font-weight: 500;
        }

        .copyright {
            margin-top: 20px;
            font-size: 12px;
        }

        @media (max-width: 600px) {
            .content {
                padding: 40px 25px 30px;
            }

            .features {
                flex-direction: column;
            }

            .feature {
                flex: 1 0 100%;
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <div class="welcome-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
            </svg>
        </div>
        <div class="logo">Zelvra</div>
    </div>

    <div class="content">
        <h1>Welcome aboard, {{ $name ?? 'User' }}!</h1>
        <p>We're absolutely thrilled to welcome you to the Zelvra family. You've taken the first step toward transforming how you manage your customers and jobs.</p>
        <p>At Zelvra, we're committed to providing you with tools that make your workflow <span class="highlight">effortless, efficient, and enjoyable</span>.</p>

        <div class="features">
            <div class="feature">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                </div>
                <h3>Streamlined Management</h3>
                <p>Organize customers and jobs in one place</p>
            </div>

            <div class="feature">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                    </svg>
                </div>
                <h3>Time Saving Tools</h3>
                <p>Automate repetitive tasks and save hours</p>
            </div>
        </div>

        <p>Ready to dive in? Your dashboard is waiting for you with everything set up and ready to go.</p>

        <div class="button-container">
            <a href="{{ $dashboardUrl ?? '#' }}" class="button">Launch Your Dashboard</a>
        </div>

        <p>If you have any questions or need assistance, our support team is just an email away. We're here to help you succeed!</p>
    </div>

    <div class="footer">
        <div class="social-links">
            <a href="#" class="social-link">Help Center</a>
            <a href="#" class="social-link">Contact Support</a>
            <a href="#" class="social-link">Twitter</a>
        </div>
        <p>Zelvra - Transforming customer and job management</p>
        <p class="copyright">&copy; {{ date('Y') }} Zelvra. All rights reserved.</p>
    </div>
</div>
</body>
</html>
