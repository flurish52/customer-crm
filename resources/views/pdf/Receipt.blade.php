<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #{{ $receipt['receipt_number'] }}</title>
    <style>
        @page {
            size: 180mm 250mm;
            margin: 1mm 5mm 5mm 5mm;
        }

        @font-face {
            font-family: 'Poppins';
            src: url("{{ storage_path('fonts/Poppins-Regular.ttf') }}") format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'Poppins';
            src: url("{{ storage_path('fonts/Poppins-Bold.ttf') }}") format('truetype');
            font-weight: bold;
            font-style: normal;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            width: 150mm;
            margin: 0 auto;
            padding: 0;
            color: #2d3748;
            background-color: #ffffff;
            line-height: 1.4;
            font-size: 12px;
        }

        .container {
            width: 100%;
            max-width: 90%;
            margin: 0 auto;
            background: white;
            padding: 25px;
            box-sizing: border-box;
        }

        /* Header section */
        .header {
            background: teal;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 25px;
            padding: 20px 0;
            border-bottom: 2px solid #e2e8f0;
            position: relative;
        }

        .header h1 {
            color: white;
            font-size: 24px;
            font-weight: bold;
            margin: 0 0 8px 0;
            letter-spacing: 0.5px;
        }

        .header p {
            color: white;
            margin: 4px 0;
        }

        .payment-amount {
            font-size: 30px;
            font-weight: bold;
            margin: 15px 0;
            color: white;
            letter-spacing: 0.5px;
        }

        .status {
            display: inline-block;
            font-size: 12px;
            font-weight: bold;
            padding: 6px 12px;
            margin-top: 8px;
            border-radius: 20px;
            color: white;
            background: #38a169;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        /* Summary cards */
        .summary-container {
            width: 100%;
            margin: 20px 0;
            display: table;
            border-collapse: separate;
            border-spacing: 10px;
        }

        .summary-card {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            padding: 15px 10px;
            border-radius: 8px;
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            vertical-align: middle;
            box-shadow: 0 2px 4px rgba(0,0,0,0.03);
        }

        .summary-card.primary {
            background: #ebf8ff;
            border-color: #bee3f8;
        }

        .summary-card h3 {
            font-size: 12px;
            font-weight: normal;
            color: #718096;
            margin: 0 0 8px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .summary-card .amount {
            font-size: 18px;
            font-weight: bold;
            color: #2d3748;
        }

        /* Details section */
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #2d3748;
            margin: 25px 0 15px 0;
            padding-bottom: 8px;
            border-bottom: 1px solid #e2e8f0;
        }

        .details-table {
            font-weight: bold;
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        .details-table tr {
            font-weight: bold;
            border-bottom: 1px solid #f1f1f1;
        }

        .details-table tr:last-child {
            font-weight: bold;
            border-bottom: none;
        }

        .details-table td {
            padding: 10px 5px;
            font-size: 13px;
            vertical-align: top;
        }

        .detail-label {
            font-weight: bold;
            color: #718096;
            width: 40%;
        }

        .detail-value {
            font-weight: bold;
            text-align: right;
            color: #2d3748;
            width: 60%;
        }

        /* Footer */
        .footer {
            font-weight: bold;
            text-align: center;
            font-size: 12px;
            color: #718096;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }

        .footer p {
            margin: 5px 0;
        }

        .note {
            font-style: italic;
            margin: 15px 0;
            padding: 10px;
            background: #f7fafc;
            border-radius: 6px;
            border-left: 3px solid #bee3f8;
        }

        /* Decorative elements */
        .decoration {
            font-weight: bold;
            position: absolute;
            top: 0;
            right: 0;
            opacity: 0.1;
            font-size: 60px;
            transform: rotate(15deg);
        }

        .mt-20 {
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <div class="decoration">✓</div>
        <h1>Payment Successful</h1>
        <p>{{ $receipt['business'] }} received your payment</p>
        <p class="payment-amount">{{ $receipt['currency'] }} {{ $receipt['payment_amount'] }}</p>
        <span class="status">COMPLETED</span>
    </div>

    <div class="summary-container">
        <div class="summary-card primary">
            <h3>Total Amount</h3>
            <div class="amount">{{ $receipt['currency'] }} {{ $receipt['total_amount'] }}</div>
        </div>

        <div class="summary-card primary">
            <h3>Total Paid</h3>
            <div class="amount">{{ $receipt['currency'] }} {{ $receipt['total_paid'] }}</div>
        </div>

        <div class="summary-card">
            <h3>Balance</h3>
            <div class="amount">{{ $receipt['currency'] }} {{ $receipt['remaining_balance'] }}</div>
        </div>
    </div>

    <h2 class="section-title">Receipt Details</h2>
    <table class="details-table">
        <tr>
            <td class="detail-label">Receipt Number</td>
            <td class="detail-value">{{ $receipt['receipt_number'] }}</td>
        </tr>
        <tr>
            <td class="detail-label">Invoice Number</td>
            <td class="detail-value">{{ $receipt['invoice_number'] }}</td>
        </tr>
        <tr>
            <td class="detail-label">Business</td>
            <td class="detail-value">{{ $receipt['business'] }}</td>
        </tr>
        <tr>
            <td class="detail-label">Customer</td>
            <td class="detail-value">{{ $receipt['customer'] }}</td>
        </tr>
        <tr>
            <td class="detail-label">Job/Service</td>
            <td class="detail-value">{{ $receipt['job'] }}</td>
        </tr>
        <tr>
            <td class="detail-label">Payment Method</td>
            <td class="detail-value">{{ $receipt['method'] }}</td>
        </tr>
        <tr>
            <td class="detail-label">Payment Date</td>
            <td class="detail-value">{{ $receipt['date'] }}</td>
        </tr>
    </table>

    <div class="footer">
        <div class="note">
            {{ $receipt['note'] ?: 'Thank you for your business!' }}
        </div>
        <p>{{ $receipt['business'] }}</p>
        <p class="mt-20">
            This receipt was generated on {{ \Carbon\Carbon::now()->format('M d, Y') }}
        </p>
    </div>
</div>
</body>
</html>
