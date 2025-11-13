<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Farmer Analytics Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1 { font-size: 20px; margin-bottom: 5px; }
        h2 { font-size: 16px; margin-top: 20px; margin-bottom: 10px; border-bottom: 2px solid #333; padding-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .text-right { text-align: right; }
        .header { text-align: center; margin-bottom: 30px; }
        .footer { text-align: center; margin-top: 30px; font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Benue State Agricultural System</h1>
        <h2>Farmer Analytics Report</h2>
        <p>Generated: {{ $generated_at }}</p>
    </div>

    <h2>Demographics Summary</h2>
    <table>
        <tr>
            <td><strong>Total Active Farmers:</strong></td>
            <td class="text-right">{{ number_format($demographics['total']) }}</td>
        </tr>
        <tr>
            <td><strong>Female Farmers:</strong></td>
            <td class="text-right">{{ number_format($demographics['gender']['Female']['count'] ?? 0) }} ({{ $demographics['gender']['Female']['percentage'] ?? 0 }}%)</td>
        </tr>
        <tr>
            <td><strong>Male Farmers:</strong></td>
            <td class="text-right">{{ number_format($demographics['gender']['Male']['count'] ?? 0) }} ({{ $demographics['gender']['Male']['percentage'] ?? 0 }}%)</td>
        </tr>
    </table>

    <h2>Farmers by LGA</h2>
    <table>
        <thead>
            <tr>
                <th>LGA</th>
                <th class="text-right">Active</th>
                <th class="text-right">Female</th>
                <th class="text-right">Male</th>
                <th class="text-right">Youth</th>
            </tr>
        </thead>
        <tbody>
            @foreach($by_lga as $lga)
            <tr>
                <td>{{ $lga->lga }}</td>
                <td class="text-right">{{ number_format($lga->active) }}</td>
                <td class="text-right">{{ number_format($lga->female) }}</td>
                <td class="text-right">{{ number_format($lga->male) }}</td>
                <td class="text-right">{{ number_format($lga->youth) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Benue State Smart Agricultural System and Data Management | Powered by BDIC</p>
    </div>
</body>
</html>