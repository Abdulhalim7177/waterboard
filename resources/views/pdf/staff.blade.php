<!DOCTYPE html>
<html>
<head>
    <title>Staff List</title>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 18px;
        }
        
        .header p {
            margin: 5px 0;
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
            font-weight: bold;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Staff List</h1>
        <p>Generated on {{ date('F j, Y') }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Staff ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Mobile</th>
                <th>Department</th>
                <th>Rank</th>
                <th>Date of Appointment</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($staffs as $staff)
                <tr>
                    <td>{{ $staff->staff_id }}</td>
                    <td>{{ $staff ? trim($staff->first_name . ' ' . ($staff->middle_name ?? '') . ' ' . ($staff->surname ?? '')) : 'N/A' }}</td>
                    <td>{{ $staff->email }}</td>
                    <td>{{ $staff->mobile_no }}</td>
                    <td>{{ $staff->department ?? 'N/A' }}</td>
                    <td>{{ $staff->rank ?? 'N/A' }}</td>
                    <td>{{ $staff->date_of_first_appointment ? $staff->date_of_first_appointment->format('M j, Y') : 'N/A' }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $staff->status)) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        <p>Total Staff: {{ $staffs->count() }}</p>
    </div>
</body>
</html>