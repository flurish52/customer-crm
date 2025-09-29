<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Quote {{ $quote->quote_number }}</title>
    <style>
        /* Base Styles */
        @page {
            margin: 3mm;
            size: A4;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            color: #004d4d;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }

        h1, h2, h3 {
            margin: 0 0 10px 0;
            font-weight: 600;
        }

        h1 {
            font-size: 24px;
            color: #006666;
        }

        h2 {
            font-size: 18px;
            color: #004d4d;
            border-bottom: 2px solid #008080;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }

        h3 {
            font-size: 15px;
            color: #006666;
            margin-bottom: 8px;
        }

        p {
            margin: 0 0 8px 0;
        }

        /* Header Section */
        .header {
            border-bottom: 2px solid #008080;
            margin-bottom: 25px;
            padding-bottom: 15px;
            position: relative;
        }

        .quote-meta {
            display: table;
            width: 100%;
            margin-top: 10px;
        }

        .quote-meta p {
            display: table-cell;
            width: 33.33%;
            margin: 0;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-draft { background: #008080; color: white; }
        .status-sent { background: #009999; color: white; }
        .status-accepted { background: #00b3b3; color: white; }
        .status-declined { background: #004d4d; color: white; }

        /* Section Styles */
        .section {
            margin-bottom: 25px;
        }

        /* Info Table */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border-radius: 5px;
            overflow: hidden;
        }

        .info-table td {
            width: 33.33%;
            vertical-align: top;
            padding: 15px;
            border: 1px solid #cce6e6;
            background: #e6f7f7;
        }

        .info-table h3 {
            color: #008080;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .items-table th,
        .items-table td {
            border: 1px solid #cce6e6;
            padding: 10px;
            text-align: left;
        }

        .items-table th {
            background: #008080;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
        }

        .items-table tr:nth-child(even) {
            background: #e6f7f7;
        }

        .items-table tr:hover {
            background: #ccf2f2;
        }

        /* Totals Table */
        .totals-table {
            width: 40%;
            float: right;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .totals-table td {
            padding: 8px 10px;
            border: none;
        }

        .totals-table tr:last-child {
            border-top: 2px solid #008080;
            font-weight: bold;
            font-size: 14px;
        }

        .totals-table tr:last-child td {
            padding-top: 12px;
        }

        /* Utility Classes */
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }

        /* Terms Section */
        .terms-section {
            clear: both;
            margin-top: 40px;
            padding: 15px;
            background: #e6f7f7;
            border-left: 4px solid #008080;
            border-radius: 0 5px 5px 0;
        }

        /* Footer */
        .footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #cce6e6;
            font-size: 11px;
            color: #004d4d;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Quote #{{ $quote->quote_number }}</h1>
    <div class="quote-meta">
        <p><strong>Date:</strong> {{ $quote->created_at->format('d M, Y') }}</p>
        <p class="text-center"><strong>Status:</strong> <span class="status-badge status-{{ $quote->status }}">{{ ucfirst($quote->status) }}</span></p>
        <p class="text-right"><strong>Currency:</strong> {{ $quote->currency }}</p>
    </div>
</div>

<div class="section">
    <h2>Details</h2>
    <table class="info-table">
        <tr>
            <td>
                <h3>Business</h3>
                @php $biz = json_decode($quote->business_snapshot); @endphp
                <p class="text-bold">{{ $quote->business->business_name ?? $biz->name }}</p>
                <p>{{ $quote->business->business_email ?? $biz->email }}</p>
                <p>{{ $quote->business->business_phone ?? $biz->phone }}</p>
                <p>{{ $quote->business->business_address ?? '' }}</p>
            </td>
            <td>
                <h3>Client</h3>
                @php $cli = json_decode($quote->client_snapshot); @endphp
                <p class="text-bold">{{ $quote->client->name ?? $cli->name }}</p>
                <p>{{ $quote->client->email ?? $cli->email }}</p>
                <p>{{ $quote->client->phone ?? $cli->phone }}</p>
                <p>{{ $quote->client->company ?? '' }}</p>
            </td>
            <td>
                <h3>Job</h3>
                <p><strong>Title:</strong> {{ $quote->job->job_title ?? '' }}</p>
                <p><strong>Description:</strong> {{ $quote->job->description ?? '' }}</p>
                <p><strong>Status:</strong> {{ $quote->job->status ?? '' }}</p>
                <p><strong>Amount:</strong> {{ number_format($quote->job->amount ?? 0, 2) }} {{ $quote->currency }}</p>
                <p><strong>Due:</strong> {{ $quote->job->due_date ?? '' }}</p>
            </td>
        </tr>
    </table>
</div>

<div class="section">
    <h2>Items</h2>
    <table class="items-table">
        <thead>
        <tr>
            <th>Description</th>
            <th class="text-center">Qty</th>
            <th class="text-right">Unit Price</th>
            <th class="text-right">Total</th>
        </tr>
        </thead>
        <tbody>
        @foreach($quote->items as $item)
            <tr>
                <td>{{ $item->description }}</td>
                <td class="text-center">{{ $item->quantity }}</td>
                <td class="text-right">{{ number_format($item->unit_price ?? 0, 2) }}</td>
                <td class="text-right">{{ number_format(($item->quantity * ($item->unit_price ?? 0)), 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="section">
    <table class="totals-table">
        <tr>
            <td class="text-right">Subtotal:</td>
            <td class="text-right">{{ number_format($quote->subtotal, 2) }}</td>
        </tr>
        <tr>
            <td class="text-right">Tax:</td>
            <td class="text-right">{{ number_format($quote->tax, 2) }}</td>
        </tr>
        <tr>
            <td class="text-right">Discount:</td>
            <td class="text-right">{{ number_format($quote->discount, 2) }}</td>
        </tr>
        <tr>
            <td class="text-right text-bold">Total:</td>
            <td class="text-right text-bold">{{ number_format($quote->total, 2) }} {{ $quote->currency }}</td>
        </tr>
    </table>
</div>

<div class="terms-section">
    <h3>Terms & Conditions</h3>
    <p>{{ $quote->terms ?? 'Standard payment terms apply. Please make payment within 30 days of invoice date.' }}</p>
</div>

<div class="footer">
    <p>Generated on {{ date('d M, Y') }}</p>
</div>

</body>
</html>
