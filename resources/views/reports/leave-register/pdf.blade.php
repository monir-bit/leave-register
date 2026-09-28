<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Leave Register Report</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 1in;
        }

        @font-face {
            font-family: 'Hind Siliguri';
            src: url('data:font/truetype;base64,{{ $banglaFontRegular }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'Hind Siliguri';
            src: url('data:font/truetype;base64,{{ $banglaFontBold }}') format('truetype');
            font-weight: bold;
            font-style: normal;
        }

        body {
            font-family: 'Hind Siliguri', sans-serif;
            font-size: 11px;
            color: #000;
        }

        .text-center {
            text-align: center;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .report-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .field-row {
            margin-bottom: 8px;
        }

        .field-label {
            font-weight: bold;
        }

        .underline {
            border-bottom: 1px solid #000;
            display: inline-block;
            padding-bottom: 1px;
        }

        .section-title {
            font-weight: bold;
            font-size: 12px;
            margin-top: 18px;
            margin-bottom: 6px;
        }

        table.report-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table.report-table th,
        table.report-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            font-size: 10px;
            text-align: left;
        }

        table.report-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="text-center company-name">Ananda Bazar Media Limited</div>
    <div class="text-center report-title">Leave Register Report</div>

    <div class="field-row">
        <span class="field-label">Employee Name:</span>
        <span class="underline" style="min-width: 400px;">{{ $employee->name }}</span>
    </div>

    <table style="width: 100%; border-collapse: collapse; margin-bottom: 8px;">
        <tr>
            <td style="width: 40%; white-space: nowrap;">
                <span class="field-label">Designation:</span>
                <span class="underline">{{ $employee->designation }}</span>
            </td>
            <td style="width: 30%; white-space: nowrap;">
                <span class="field-label">Department:</span>
                <span class="underline">{{ $employee->department }}</span>
            </td>
            <td style="width: 30%; white-space: nowrap;">
                <span class="field-label">Joining Date:</span>
                <span class="underline">{{ $employee->date_of_joining->format('d-m-Y') }}</span>
            </td>
        </tr>
    </table>

    <div class="section-title">Leave Applied in Days</div>

    <div class="field-row">
        <span class="field-label">Year:</span> {{ $year }}
    </div>

    <table class="report-table">
        <thead>
            <tr>
                <th>Leave Type</th>
                <th>Applied</th>
                <th>Available (This Year)</th>
                <th>Carried Forward</th>
                <th>Total Available</th>
                <th>Remaining</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($leaveTypeSummary as $summary)
                <tr>
                    <td>{{ $summary['name'] }}</td>
                    <td>{{ $summary['applied'] }}</td>
                    <td>{{ $summary['available_this_year'] }}</td>
                    <td>{{ $summary['carried_forward'] }}</td>
                    <td>{{ $summary['total_available'] }}</td>
                    <td>{{ $summary['remaining'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Leave Records</div>

    <table class="report-table">
        <thead>
            <tr>
                <th>Year</th>
                <th>Month</th>
                <th>Leave Type</th>
                <th>From Date</th>
                <th>To Date</th>
                <th>Days</th>
                <th>Reason</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($leaveRegisters as $leaveRegister)
                <tr>
                    <td>{{ $leaveRegister->from_date->format('Y') }}</td>
                    <td>{{ $leaveRegister->from_date->format('M') }}</td>
                    <td>{{ $leaveRegister->leaveType?->name }}</td>
                    <td>{{ $leaveRegister->from_date->format('d-m-Y') }}</td>
                    <td>{{ $leaveRegister->to_date->format('d-m-Y') }}</td>
                    <td>{{ $leaveRegister->days }}</td>
                    <td>{{ $leaveRegister->reason }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No leave records found for {{ $year }}.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
