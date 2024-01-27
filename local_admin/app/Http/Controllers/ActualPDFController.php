<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Models\UniversalExpenseRevenue;
use App\Models\ActualExpenseRevenue;

class ActualPDFController extends Controller
{
    public function viewPDF($event_id)
    {
        $accounts = UniversalExpenseRevenue::all();
        $actuals = ActualExpenseRevenue::where('event_id', $event_id)->get();
        $pdf = PDF::loadView('actualincomestatement' , array('accounts' =>  $accounts, 'actuals' =>  $actuals))
        ->setPaper('a4', 'portrait');
        return $pdf->stream();

    }

    public function downloadPDF($event_id)
    {
        $accounts = UniversalExpenseRevenue::all();
        $actuals = ActualExpenseRevenue::where('event_id', $event_id)->get();
        $pdf = PDF::loadView('actualincomestatement' , array('accounts' =>  $accounts, 'actuals' =>  $actuals))
        ->setPaper('a4', 'portrait');

        return $pdf->download('actual-income-statement.pdf');   
    }
}
