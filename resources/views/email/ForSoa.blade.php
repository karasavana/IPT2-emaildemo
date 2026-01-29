<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statement of Account</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .account-info {
            background: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .balance-info {
            background: #d4edda;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Statement of Account</h1>
        <p><strong>Date:</strong> {{ now()->format('F d, Y') }}</p>
        <p><strong>Account Number:</strong> {{ $account->account_number }}</p>
    </div>

    <div class="account-info">
        <h2>Account Information</h2>
        <p><strong>Customer Name:</strong> {{ $account->customer->name }}</p>
        <p><strong>Email:</strong> {{ $account->customer->email }}</p>
        <p><strong>Phone:</strong> {{ $account->customer->phone ?? 'N/A' }}</p>
        <p><strong>Address:</strong> {{ $account->customer->address ?? 'N/A' }}</p>
    </div>

    <div class="balance-info">
        <h2>Account Summary</h2>
        <p><strong>Principal Amount:</strong> ₱{{ number_format($account->principal_amount, 2) }}</p>
        <p><strong>Current Balance:</strong> ₱{{ number_format($account->balance, 2) }}</p>
        <p><strong>Interest Rate:</strong> {{ $account->interest_rate }}%</p>
        <p><strong>Monthly Payment:</strong> ₱{{ number_format($account->monthly_payment, 2) }}</p>
        <p><strong>Status:</strong> {{ ucfirst($account->status) }}</p>
        <p><strong>Start Date:</strong> {{ $account->start_date->format('F d, Y') }}</p>
        <p><strong>Maturity Date:</strong> {{ $account->maturity_date->format('F d, Y') }}</p>
    </div>

    @if($account->transactions->count() > 0)
    <h2>Recent Transactions</h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Transaction #</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Balance After</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($account->transactions->take(10) as $transaction)
            <tr>
                <td>{{ $transaction->transaction_date->format('M d, Y') }}</td>
                <td>{{ $transaction->transaction_number }}</td>
                <td>{{ ucfirst($transaction->type) }}</td>
                <td>₱{{ number_format($transaction->amount, 2) }}</td>
                <td>₱{{ number_format($transaction->balance_after, 2) }}</td>
                <td>{{ $transaction->notes ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="footer">
        <p>This is a computer-generated statement. For any questions or concerns, please contact our support team.</p>
        <p>Generated on: {{ now()->format('F d, Y h:i A') }}</p>
    </div>
</body>
</html>