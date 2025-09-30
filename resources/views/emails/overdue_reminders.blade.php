<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Reminders Summary</title>
    <style>
        /* Base Styles */
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f9fafb;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }

        /* Header Section */
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 40px;
            text-align: center;
        }

        .logo {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }

        /* Content Section */
        .content {
            padding: 40px;
        }

        .greeting {
            font-size: 18px;
            margin-bottom: 25px;
            color: #4a5568;
        }

        .summary-box {
            background-color: #f8fafc;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 30px;
            border-left: 4px solid #667eea;
        }

        .category {
            margin-bottom: 30px;
        }

        .category-title {
            font-size: 20px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e2e8f0;
        }

        .items-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px;
            margin-bottom: 12px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            border-left: 4px solid #e2e8f0;
            transition: all 0.2s ease;
        }

        .item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .item-info {
            flex: 1;
        }

        .item-title {
            font-weight: 600;
            margin-bottom: 5px;
            color: #2d3748;
        }

        .item-due {
            font-size: 14px;
            color: #718096;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.2s ease;
            box-shadow: 0 2px 5px rgba(102, 126, 234, 0.3);
        }

        .button:hover {
            background-color: #5a6fd8;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(102, 126, 234, 0.4);
        }

        /* Status Colors */
        .overdue {
            border-left-color: #fc8181;
        }

        .overdue .item-title {
            color: #e53e3e;
        }

        .due-today {
            border-left-color: #f6ad55;
        }

        .due-today .item-title {
            color: #dd6b20;
        }

        .upcoming {
            border-left-color: #68d391;
        }

        .upcoming .item-title {
            color: #38a169;
        }

        /* Footer */
        .footer {
            background-color: #f1f5f9;
            padding: 25px 40px;
            text-align: center;
            font-size: 14px;
            color: #718096;
            border-top: 1px solid #e2e8f0;
        }

        .footer p {
            margin: 5px 0;
        }

        .app-link {
            color: #667eea;
            text-decoration: none;
        }

        /* Responsive */
        @media (max-width: 600px) {
            .header, .content, .footer {
                padding: 25px 20px;
            }

            .item {
                flex-direction: column;
                align-items: flex-start;
            }

            .button {
                margin-top: 10px;
                align-self: flex-end;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Header -->
    <div class="header">
        <div class="logo">Entroly</div>
        <h1>Your Reminders Summary</h1>
    </div>

    <!-- Content -->
    <div class="content">
        <p class="greeting">Hello {{ $user->name }},</p>

        <div class="summary-box">
            <p>Here's a summary of your items that need attention:</p>
        </div>

        @foreach ($overdueItems as $type => $items)
            @if (!empty($items))
                <div class="category">
                    <h2 class="category-title">{{ ucfirst($type) }}</h2>
                    <ul class="items-list">
                        @foreach ($items as $item)
                            @php
                                $today = \Carbon\Carbon::today();
                                $dueDate = \Carbon\Carbon::parse($item['due_date']);
                                $diff = $today->diffInDays($dueDate, false);
                                $class = $diff < 0 ? 'overdue' : ($diff === 0 ? 'due-today' : 'upcoming');
                            @endphp
                            <li class="item {{ $class }}">
                                <div class="item-info">
                                    <div class="item-title">{{ $item['title'] }}</div>
                                    <div class="item-due">Due on <strong>{{ $item['due_date'] }}</strong></div>
                                </div>
                                <a class="button" href="{{ $item['link'] }}">View</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        @endforeach
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>This is an automated reminder from <a href="#" class="app-link">Entroly</a>.</p>
        <p>Please follow up on your items promptly.</p>
    </div>
</div>
</body>
</html>
