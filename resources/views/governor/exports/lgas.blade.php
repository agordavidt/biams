<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>LGA Analytics Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; }
        h1 { font-size: 20px; margin-bottom: 5px; }
        h2 { font-size: 16px; margin-top: 20px; margin-bottom: 10px; border-bottom: 2px solid #333; padding-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .text-right { text-align: right; }
        .header { text-align: center; margin-bottom: 30px; }
        .footer { text-align: center; margin-top: 30px; font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Benue State Agricultural System</h1>
        <h2>LGA Analytics Report</h2>
        <p>Generated: {{ $generated_at }}</p>
    </div>

    <h2>LGA Comparison</h2>
    <table>
        <thead>
            <tr>
                <th>LGA</th>
                <th class="text-right">Farmers</th>
                <th class="text-right">Female</th>
                <th class="text-right">Male</th>
                <th class="text-right">Youth</th>
                <th class="text-right">Farms</th>
                <th class="text-right">Hectares</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lga_comparison as $lga)
            <tr>
                <td>{{ $lga->name }}</td>
                <td class="text-right">{{ number_format($lga->total_farmers) }}</td>
                <td class="text-right">{{ number_format($lga->female_farmers) }}</td>
                <td class="text-right">{{ number_format($lga->male_farmers) }}</td>
                <td class="text-right">{{ number_format($lga->youth_farmers) }}</td>
                <td class="text-right">{{ number_format($lga->total_farms) }}</td>
                <td class="text-right">{{ number_format($lga->total_hectares, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Performance Ranking</h2>
    <table>
        <thead>
            <tr>
                <th>Rank</th>
                <th>LGA</th>
                <th class="text-right">Farmers</th>
                <th class="text-right">Applications</th>
                <th class="text-right">Success Rate</th>
            </tr>
        </thead>
        <tbody>
            @foreach($performance_ranking as $index => $lga)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $lga['lga'] }}</td>
                <td class="text-right">{{ number_format($lga['farmers']) }}</td>
                <td class="text-right">{{ number_format($lga['applications']) }}</td>
                <td class="text-right">{{ $lga['success_rate'] }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Benue State Smart Agricultural System and Data Management | Powered by BDIC</p>
    </div>
</body>
</html>