<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Abattoir Analytics Report</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            line-height: 1.6;
            color: #333;
            font-size: 12px;
        }
        .container {
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 10px;
        }
        h1 {
            font-size: 20px;
            margin-bottom: 5px;
        }
        h2 {
            font-size: 16px;
            margin-top: 20px;
            margin-bottom: 10px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        .summary-box {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 20px;
        }
        .summary-item {
            display: inline-block;
            width: 49%;
            margin-bottom: 10px;
        }
        .summary-item strong {
            display: block;
            margin-bottom: 3px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .footer {
            margin-top: 30px;
            font-size: 10px;
            text-align: center;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Abattoir Analytics Report</h1>
            <p>
                Generated on: {{ now()->format('F d, Y H:i') }}<br>
                Report Period: {{ $startDate->format('F d, Y') }} to {{ $endDate->format('F d, Y') }}
            </p>
            @if($abattoir)
                <p>Abattoir: {{ $abattoir->name }}</p>
            @endif
            @if($lga)
                <p>LGA: {{ $lga }}</p>
            @endif
        </div>

        <div class="summary-box">
            <h2>Summary Statistics</h2>
            <div class="summary-item">
                <strong>Registered Livestock:</strong> {{ number_format($registeredLivestock) }}
            </div>
            <div class="summary-item">
                <strong>Slaughter Operations:</strong> {{ number_format($slaughterOperations) }}
            </div>
        </div>

        <h2>Daily Slaughter Operations</h2>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Count</th>
                    <th>Total Weight (kg)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dailyStats as $stat)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($stat->date)->format('Y-m-d') }}</td>
                    <td>{{ number_format($stat->count) }}</td>
                    <td>{{ number_format($stat->total_weight, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align: center;">No data available for this period</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <h2>Species Breakdown</h2>
        <table>
            <thead>
                <tr>
                    <th>Species</th>
                    <th>Count</th>
                    <th>Total Weight (kg)</th>
                    <th>Average Weight (kg)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($speciesStats as $stat)
                <tr>
                    <td>{{ ucfirst($stat->species) }}</td>
                    <td>{{ number_format($stat->count) }}</td>
                    <td>{{ number_format($stat->total_weight, 2) }}</td>
                    <td>{{ number_format($stat->count > 0 ? $stat->total_weight / $stat->count : 0, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center;">No data available for this period</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="footer">
            <p>This report was generated automatically by the Abattoir Management System.</p>
        </div>
    </div>
</body>
</html>