<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>System Overview Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1 { font-size: 20px; margin-bottom: 5px; }
        h2 { font-size: 16px; margin-top: 20px; margin-bottom: 10px; border-bottom: 2px solid #333; padding-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .text-right { text-align: right; }
        .summary-box { background: #f9f9f9; padding: 10px; margin-bottom: 15px; }
        .header { text-align: center; margin-bottom: 30px; }
        .footer { text-align: center; margin-top: 30px; font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Benue State Agricultural System</h1>
        <h2>System Overview Report</h2>
        <p>Generated: {{ $generated_at }}</p>
    </div>

    <div class="summary-box">
        <h2>Summary Statistics</h2>
        <table>
            <tr>
                <td><strong>Total Farmers:</strong></td>
                <td class="text-right">{{ number_format($summary['total_farmers']) }}</td>
                <td><strong>Female:</strong></td>
                <td class="text-right">{{ number_format($summary['female_farmers']) }}</td>
            </tr>
            <tr>
                <td><strong>Total Farms:</strong></td>
                <td class="text-right">{{ number_format($summary['total_farms']) }}</td>
                <td><strong>Total Hectares:</strong></td>
                <td class="text-right">{{ number_format($summary['total_hectares'], 2) }}</td>
            </tr>
            <tr>
                <td><strong>Cooperatives:</strong></td>
                <td class="text-right">{{ number_format($summary['total_cooperatives']) }}</td>
                <td><strong>Active Resources:</strong></td>
                <td class="text-right">{{ number_format($summary['active_resources']) }}</td>
            </tr>
        </table>
    </div>

    <h2>LGA Summary</h2>
    <table>
        <thead>
            <tr>
                <th>LGA</th>
                <th class="text-right">Farmers</th>
                <th class="text-right">Farms</th>
                <th class="text-right">Hectares</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lga_summary as $lga)
            <tr>
                <td>{{ $lga->name }}</td>
                <td class="text-right">{{ number_format($lga->farmers) }}</td>
                <td class="text-right">{{ number_format($lga->farms) }}</td>
                <td class="text-right">{{ number_format($lga->hectares, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Benue State Smart Agricultural System and Data Management | Powered by BDIC</p>
    </div>
</body>
</html>