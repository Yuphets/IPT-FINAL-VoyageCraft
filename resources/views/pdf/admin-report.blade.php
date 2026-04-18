<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admin Report - Travel Itinerary Planner</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #4F46E5;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #4F46E5;
            margin: 0;
            font-size: 28px;
        }
        .header p {
            color: #666;
            margin: 5px 0 0;
        }
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        .stat-card {
            display: table-cell;
            width: 33.33%;
            padding: 15px;
            text-align: center;
            border: 1px solid #e5e7eb;
            background-color: #f9fafb;
        }
        .stat-number {
            font-size: 36px;
            font-weight: bold;
            color: #4F46E5;
        }
        .stat-label {
            font-size: 14px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .section-title {
            font-size: 20px;
            color: #1f2937;
            margin: 30px 0 15px;
            border-bottom: 1px solid #d1d5db;
            padding-bottom: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th {
            background-color: #f3f4f6;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #374151;
            border-bottom: 2px solid #9ca3af;
        }
        td {
            padding: 10px 12px;
            border-bottom: 1px solid #e5e7eb;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-admin {
            background-color: #e9d5ff;
            color: #6b21a8;
        }
        .badge-user {
            background-color: #e5e7eb;
            color: #374151;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>✈️ Travel Itinerary Planner</h1>
        <p>Administrative Report</p>
        <p>Generated on {{ now()->format('F j, Y \a\t g:i A') }}</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ $totalUsers }}</div>
            <div class="stat-label">Total Users</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $totalItineraries }}</div>
            <div class="stat-label">Total Itineraries</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $activeItineraries }}</div>
            <div class="stat-label">Public Itineraries</div>
        </div>
    </div>

    <h2 class="section-title">Top 5 Most Active Users</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>User Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Itineraries Created</th>
                <th>Joined</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topUsers as $index => $user)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if($user->hasRole('admin'))
                            <span class="badge badge-admin">Admin</span>
                        @else
                            <span class="badge badge-user">User</span>
                        @endif
                    </td>
                    <td>{{ $user->itineraries_count }}</td>
                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2 class="section-title">Summary</h2>
    <table>
        <tr>
            <th>Metric</th>
            <th>Value</th>
        </tr>
        <tr>
            <td>Average Itineraries per User</td>
            <td>{{ $totalUsers > 0 ? round($totalItineraries / $totalUsers, 2) : 0 }}</td>
        </tr>
        <tr>
            <td>Public vs Private Ratio</td>
            <td>
                @php
                    $privateCount = $totalItineraries - $activeItineraries;
                    $publicPercent = $totalItineraries > 0 ? round(($activeItineraries / $totalItineraries) * 100) : 0;
                @endphp
                {{ $publicPercent }}% Public / {{ 100 - $publicPercent }}% Private
            </td>
        </tr>
    </table>

    <div class="footer">
        This report is automatically generated and contains system statistics as of the generation date.
        For internal administrative use only.
    </div>
</body>
</html>
