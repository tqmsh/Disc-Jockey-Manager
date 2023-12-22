<!DOCTYPE html>
<html>
<head>
    <title>Budget Income Statement</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        hr {
            height:2px;
            border-width:0;
            background-color:#00A4BD;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h1>Income Statement</h1>
    <hr>
    <h3>Revenue</h3>
    <table>
        @php 
           $totalRev = $budgets->where('type', '2')->sum('budget');
        @endphp
        @foreach ($budgets as $budget)
            @if ($budget->type == 2)
                @php
                    $account = $accounts->where('id', $budget->universal_id)->toArray(); 
                    $account = reset($account); // reset() takes first and only index off array, this is done to avoid array index errors later
                @endphp
                <tr>
                    <td style="width:500px">{{$account['name']}}</td>
                    @if ($budget->budget != 0)
                        <td style="width:165px">${{number_format($budget->budget, 2, '.')}}</td>
                    @else
                        <td style="width:165px">-</td>
                    @endif
                </tr>
            @endif
        @endforeach
    </table>
    <hr style="margin-top: 20px;">
    <h3>Expenses</h3>
    <table>
        @php 
            $totalExp = $budgets->where('type', '1')->sum('budget');
        @endphp
        @foreach ($budgets as $budget)
            @if ($budget->type == 1)
                @php
                    $account = $accounts->where('id', $budget->universal_id)->toArray(); 
                    $account = reset($account); // reset() takes first and only index off array, this is done to avoid array index errors later
                @endphp
                <tr>
                    <td style="width:500px">{{$account['name']}}</td>
                    @if ($budget->budget != 0)
                        <td style="width:165px">${{number_format($budget->budget, 2, '.')}}</td>
                    @else
                        <td style="width:165px">-</td>
                    @endif
                </tr>
            @endif
        @endforeach
    </table>
    <hr style="margin-top: 20px;">
    <div style="clear: both">
        <h3 style="float: left">Net Profit (Loss)</h3>
        @php
            $netP = $totalRev - $totalExp;  
        @endphp
        @if($netP >= 0)
            <h4 style="float: right; margin-right:130px">${{number_format($netP, 2, '.')}}</h4>
        @else
            <h4 style="float: right">&#40;${{number_format($netP, 2, '.')}}&#41;</h4>
        @endif
    </div>
</body>
</html>