<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Production Analytics Report</title>
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
        <h2>Production Analytics Report</h2>
        <p>Generated: {{ $generated_at }}</p>
    </div>

    <h2>Farms Overview</h2>
    <table>
        <tr>
            <td><strong>Total Farms:</strong></td>
            <td class="text-right">{{ number_format($farms_overview['total_farms']) }}</td>
            <td><strong>Total Hectares:</strong></td>
            <td class="text-right">{{ number_format($farms_overview['total_hectares'], 2) }}</td>
        </tr>
    </table>

    <h2>Crop Production</h2>
    <table>
        <thead>
            <tr>
                <th>Crop Type</th>
                <th class="text-right">Farm Count</th>
                <th class="text-right">Expected Yield (kg)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($crop_production['by_crop_type'] as $crop)
            <tr>
                <td>{{ $crop->crop_type }}</td>
                <td class="text-right">{{ number_format($crop->farm_count) }}</td>
                <td class="text-right">{{ number_format($crop->total_expected_yield, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Livestock Production</h2>
    <table>
        <thead>
            <tr>
                <th>Animal Type</th>
                <th class="text-right">Farm Count</th>
                <th class="text-right">Total Animals</th>
            </tr>
        </thead>
        <tbody>
            @foreach($livestock_production['by_animal_type'] as $animal)
            <tr>
                <td>{{ $animal->animal_type }}</td>
                <td class="text-right">{{ number_format($animal->farm_count) }}</td>
                <td class="text-right">{{ number_format($animal->total_animals) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Benue State Smart Agricultural System and Data Management | Powered by BDIC</p>
    </div>
</body>
</html>