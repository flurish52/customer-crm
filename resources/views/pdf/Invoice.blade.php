<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #004d4d;
            margin: 0;
            padding: 15px;
            font-size: 13px;
            line-height: 1.3;
            background-color: #e6f7f7;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 6px;
        }
        .header { display: table; width: 100%; margin-bottom: 20px; }
        .header-section { display: table-cell; vertical-align: top; }
        .business-info { width: 60%; }
        .invoice-info { width: 40%; text-align: right; }
        .logo { max-height: 60px; margin-bottom: 10px; }
        .business-name { font-size: 20px; font-weight: bold; color: #008080; margin: 5px 0; }
        .invoice-title { font-size: 24px; font-weight: bold; color: #008080; margin: 0 0 15px 0; }
        .section { margin-bottom: 20px; }
        .section-title { font-size: 16px; font-weight: bold; color: #008080; border-bottom: 2px solid #b2dfdb; padding-bottom: 5px; margin-bottom: 12px; }
        .details-grid { display: table; width: 100%; }
        .details-column { display: table-cell; width: 50%; vertical-align: top; padding-right: 15px; }
        .invoice-table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        .invoice-table th { background-color: #008080; color: white; padding: 10px; text-align: left; font-weight: bold; }
        .invoice-table td { padding: 10px; border-bottom: 1px solid #b2dfdb; }
        .text-right { text-align: right; }
        .totals { width: 300px; margin-left: auto; margin-top: 20px; }
        .total-row { display: table; width: 100%; margin-bottom: 8px; }
        .total-label, .total-value { display: table-cell; padding: 5px 0; }
        .total-value { text-align: right; font-weight: bold; }
        .grand-total { border-top: 2px solid #008080; font-size: 16px; padding-top: 8px; margin-top: 8px; }
        .status { display: inline-block; padding: 5px 10px; border-radius: 4px; font-size: 12px; font-weight: bold; color: #fff; margin-top: 5px; }
        .status-paid { background: #009999; }
        .status-pending { background: #00b3b3; }
        .status-overdue { background: #006666; }
        .status-cancelled { background: #004d4d; }
        .notes { margin-top: 30px; padding: 15px; background: #e0f7f7; border-left: 4px solid #008080; border-radius: 4px; }
        .notes-title { font-weight: bold; margin-bottom: 5px; color: #008080; }
        .footer { margin-top: 30px; padding-top: 15px; border-top: 1px solid #b2dfdb; text-align: center; color: #004d4d; font-size: 12px; }

        .table-container {width: 100%;font-family: Arial, sans-serif;}
        .table-header {display: flex;justify-content: space-between;margin-bottom: 10px;}
        .table-header h2 {font-size: 18px;font-weight: bold;color: #004d4d;}
        .table-header span {font-size: 14px;color: #006666;}
        table {width: 100%;border-collapse: collapse;font-size: 14px;}
        thead tr {background: #b2dfdb;color: #004d4d;}
        th, td {border: 1px solid #b2dfdb;padding: 8px;text-align: left; }
        tbody tr:nth-child(even) {background: #e0f7f7;}
        tfoot tr {background: #cce6e6;font-weight: bold;}
        .totals {display: flex;justify-content: flex-end; gap: 20px;}
    </style>
</head>
<body>
<div class="container">
    <!-- Header Section -->
    <div class="header">
        <div class="header-section business-info">
            @if($business->logo)
                <img src="{{ public_path('storage/'.$business->logo) }}" alt="{{ $business->name }} logo" class="logo">
            @endif
            <div class="business-name">{{ $business->name }}</div>
            <div>{{ $business->address }}</div>
            <div>
                @if($business->phone) {{ $business->phone }} @endif
                @if($business->phone && $business->email) | @endif
                @if($business->email) {{ $business->email }} @endif
            </div>
        </div>
        <div class="header-section invoice-info">
            <div class="invoice-title">INVOICE</div>
            <div><strong>Invoice #:</strong> {{ $invoice->invoice_number }}</div>
            <div><strong>Date:</strong> {{ \Carbon\Carbon::parse($invoice->issue_date)->format('M d, Y') }}</div>
            <div><strong>Due Date:</strong> {{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}</div>
            <div>
                <strong>Status:</strong>
                <span class="status
                    @if(strtoupper($invoice->status) == 'PAID') status-paid
                    @elseif(strtoupper($invoice->status) == 'PENDING') status-pending
                    @elseif(strtoupper($invoice->status) == 'OVERDUE') status-overdue
                    @elseif(strtoupper($invoice->status) == 'CANCELLED') status-cancelled
                    @endif">
                    {{ strtoupper($invoice->status) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Client & Job Details -->
    <div class="section">
        <div class="details-grid">
            <div class="details-column">
                <div class="section-title">Bill To</div>
                <div><strong>{{ $customer->name }}</strong></div>
                @if($customer->company) <div>{{ $customer->company }}</div> @endif
                @if($customer->phone) <div>{{ $customer->phone }}</div> @endif
                @if($customer->email) <div>{{ $customer->email }}</div> @endif
            </div>
            <div class="details-column">
                <div class="section-title">Job Details</div>
                @if($job)
                    @if($job->title) <div><strong>{{ $job->title }}</strong></div> @endif
                    @if($job->description) <div>{{ $job->description }}</div> @endif
                    @if($job->start_date || $job->due_date)
                        <div>
                            @if($job->start_date) Start start: {{ \Carbon\Carbon::parse($job->start_date)->format('M d, Y') }} @endif
                            @if($job->due_date) Delivery date: {{ \Carbon\Carbon::parse($job->due_date)->format('M d, Y') }} @endif
                        </div>
                    @endif
                    @if($job->status) <div>Status: {{ strtoupper($job->status) }}</div> @endif
                @endif
            </div>
        </div>
    </div>

    <!-- Invoice Items -->
    <div class="section">
        <div class="section-title">Invoice Items</div>
        <table class="invoice-table">
            <thead>
            <tr>
                <th style="width: 40%;">Item</th>
                <th style="width: 25%;">Description</th>
                <th style="width: 10%; text-align: center;">Qty</th>
                <th style="width: 12%; text-align: right;">Unit Price</th>
                <th style="width: 13%; text-align: right;">Total</th>
            </tr>
            </thead>
            <tbody>
            @foreach($invoice->items as $item)
                <tr>
                    <td><strong>{{ $item->name }}</strong></td>
                    <td>{{ $item->description }}</td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td style="text-align: right;"> {{ $invoice->currency }} {{ number_format($item->unit_price, 2) }}</td>
                    <td style="text-align: right;"> {{ $invoice->currency }} {{ number_format($item->total, 2) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <!-- Totals Section -->
    <div class="totals">
        <div class="total-row">
            <div class="total-label">Subtotal:</div>
            <div class="total-value"> {{ $invoice->currency }} {{ number_format($invoice->subtotal, 2) }}</div>
        </div>
        <div class="total-row">
            <div class="total-label">Tax:</div>
            <div class="total-value"> {{ $invoice->currency }} {{ number_format($invoice->tax, 2) }}</div>
        </div>
        <div class="total-row">
            <div class="total-label">Discount:</div>
            <div class="total-value"> {{ $invoice->currency }} {{ number_format($invoice->discount, 2) }}</div>
        </div>
        <div class="total-row grand-total">
            <div class="total-label">TOTAL:</div>
            <div class="total-value">{{ $invoice->currency }} {{ number_format($invoice->total, 2) }}</div>
        </div>
    </div>

    <!-- Notes Section -->
    @if($invoice->notes)
        <div class="notes">
            <div class="notes-title">Notes</div>
            <div>{{ $invoice->notes }}</div>
        </div>
    @endif
    @if(count($payments) > 0)

    <div class="w-full">
        <!-- Header -->
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-800">Payments Made</h2>
            <span class="text-sm text-gray-500" style="text-align: right">{{ count($payments) }} Records</span>
        </div>
        <hr>
        <!-- Table -->
        <div class="overflow-x-auto bg-white shadow rounded-lg">
            <table class="min-w-full text-sm">
                <tfoot>
                <tr class="bg-gray-50 font-bold text-gray-800">
                    <td colspan="2">Total Paid:</td>
                    <td colspan="3" class="text-right">{{ $invoice->currency }} {{ number_format($totalPaid, 2) }}</td>
                </tr>
                <tr class="bg-gray-50 font-bold text-gray-800">
                    <td colspan="2">Balance:</td>
                    <td colspan="3" class="text-right"> {{ $invoice->currency }} {{ number_format($balance, 2) }}</td>
                </tr>
                </tfoot>

            </table>
        </div>
    </div>
    @endif
    <!-- Footer -->
    <div class="footer">
        <div>{{ $business->name }} | {{ $business->email }} | {{ $business->phone }}</div>
        <div>This invoice was generated on {{ \Carbon\Carbon::now()->format('M d, Y') }}</div>
    </div>
</div>
</body>
</html>
