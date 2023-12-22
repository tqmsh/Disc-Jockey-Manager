<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Models\UniversalExpenseRevenue;
use App\Models\ActualExpenseRevenue;

class BudgetPDFController extends Controller
{
    public function viewPDF($event_id)
    {
        $accounts = UniversalExpenseRevenue::all();
        $budgets = ActualExpenseRevenue::where('event_id', $event_id)->get();
        $pdf = PDF::loadView('budgetincomestatement' , array('accounts' =>  $accounts, 'budgets' =>  $budgets))
        ->setPaper('a4', 'portrait');
        return $pdf->stream();

    }

    public function downloadPDF($event_id)
    {
        $accounts = UniversalExpenseRevenue::all();
        $budgets = ActualExpenseRevenue::where('event_id', $event_id)->get();
        $pdf = PDF::loadView('budgetincomestatement' , array('accounts' =>  $accounts, 'budgets' =>  $budgets))
        ->setPaper('a4', 'portrait');

        return $pdf->download('budget-income-statement.pdf');   
    }
}
