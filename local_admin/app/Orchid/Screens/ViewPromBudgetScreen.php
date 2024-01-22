<?php

namespace App\Orchid\Screens;

use Exception;
use Orchid\Screen\TD;
use App\Models\Events;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use App\Models\Localadmin;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use App\Models\ActualExpenseRevenue;
use Illuminate\Support\Facades\Auth;
use App\Models\UniversalExpenseRevenue;

class ViewPromBudgetScreen extends Screen
{

    private $profit;

    public $event;

    // UniversalExpenseRevenue
    public $uniRevenues;

    // UniversalExpenseRevenue
    public $uniExpenses;

    // ActualExpenseRevenue
    public $accRevenues;

    // ActualExpenseRevenue
    public $accExpenses;

    public $open;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Events $event): iterable
    {
        abort_if(Localadmin::where('user_id', Auth::user()->id)->first()->school_id != $event->school_id, 403, 'You are not authorized to view this page.');
        return [
            'event' => $event,
            'uniRevenues' => UniversalExpenseRevenue::where('type', 2)->get(),
            'uniExpenses' => UniversalExpenseRevenue::where('type', 1)->get(),
            'accRevenues' => ActualExpenseRevenue::where('event_id', $event->id)->where('type', '2')->get(),
            'accExpenses' => ActualExpenseRevenue::where('event_id', $event->id)->where('type', '1')->get(),
            'open' => $event->open,
            $this->createView($event),
            $this->createPDF($event),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): string
    {
        return 'Prom Budget';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Budget')
                ->icon('book-open')
                ->route('platform.budget.list', ['event_id' => $this->event])
                ->type(Color::DARK()),

            Link::make('Actual')
                ->icon('wallet')
                ->route('platform.actual.list', ['event_id' => $this->event])
                ->type(Color::PRIMARY()),
                
            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.profit.list')
                ->type(Color::DEFAULT()),
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::rows([

                Group::make([
                    Input::make('attendees')
                        ->type('number')
                        ->title('Number of Attendees')
                        ->mask([
                            'mask' => '999999999999.99',
                            'numericInput' => true
                            ]) 
                        ->placeholder(0),

                    Input::make('price')
                        ->type('number')
                        ->title('Price per ticket')
                        ->mask([
                            'mask' => '999999999999.99',
                            'numericInput' => true
                            ]) 
                        ->placeholder(0), 
                ]),
                
                Button::make('Calculate')
                    ->icon('calculator-alt')
                    ->method('calculate')
                    ->type(Color::SECONDARY()),
            ])->title('Calculator'),

            Layout::table('uniRevenues', [
                TD::make('account_name', 'Account Name')
                    ->render(function (UniversalExpenseRevenue $revenue) {
                        if($this->accRevenues !=null){
                            return $revenue->name;
                        }
                    }),
                TD::make('balance', 'Balance')
                    ->render(function (UniversalExpenseRevenue $revenue) {
                        $curRevenue = $this->accRevenues->where('universal_id', $revenue->id)->first();
                        if($curRevenue !=null){
                            return $curRevenue->budget;
                        }
                    }),
                TD::make('edit_Rev', 'Edit Item')
                    ->render(function(UniversalExpenseRevenue $revenue){
                        $curRevenue = $this->accRevenues->where('universal_id', $revenue->id)->first();
                        if($curRevenue !=null){
                            return Link::make('Edit')->route('platform.budget.edit', ['event_id' => $this->event->id, 'id' => $curRevenue->id])->type(Color::PRIMARY())->icon('pencil');
                        }
                        else{
                            return Button::make('Create')->method('createItem', ['itemID' => $revenue->id])->type(Color::PRIMARY())->icon('pencil');
                        }
                    })->canSee($this->event->open), 
            ])->title('Revenues'),

            Layout::table('uniExpenses', [
                TD::make('account_name', 'Account Name')
                    ->render(function (UniversalExpenseRevenue $expense) {
                        if($this->accExpenses !=null){
                            return $expense->name;
                        }
                    }),
                TD::make('balance', 'Balance')
                    ->render(function (UniversalExpenseRevenue $expense) {
                        $curExpense = $this->accExpenses->where('universal_id', $expense->id)->first();
                        if($curExpense !=null){
                            return $curExpense->budget;
                        }
                    }),
                TD::make('edit_Exp', 'Edit Item')
                    ->render(function(UniversalExpenseRevenue $expense){
                        $curExpense = $this->accExpenses->where('universal_id', $expense->id)->first();
                        if($curExpense !=null){
                            return Link::make('Edit')->route('platform.budget.edit', ['event_id' => $this->event->id, 'id' => $curExpense->id])->type(Color::PRIMARY())->icon('pencil');
                        }
                        else{
                            return Button::make('Create')->method('createItem', ['itemID' => $expense->id])->type(Color::PRIMARY())->icon('pencil');
                        }
                    })->canSee($this->event->open),
            ])->title('Expenses'),

            Layout::rows([
                Input::make('netIncome')
                    ->title('Net Income')
                    ->placeholder($this->calcNetIncome())
                    ->readonly()
                    ->horizontal(),
            ])->title('Net Income'),

            Layout::rows([
                Group::make([
                    Link::make('Save to PDF')
                        ->icon('save-alt')
                        ->route('platform.budget.viewPDF.switch', ['event_id' => $this->event])
                        ->type(Color::SECONDARY()),

                    Link::make('Download PDF')
                        ->icon('cloud-download')
                        ->route('platform.budget.downloadPDF.switch', ['event_id' => $this->event])
                        ->type(Color::PRIMARY()),
                ])->autoWidth(),
            ]),
        ];
    }

    public function createItem(Events $event): void{
        $itemID = request()->query('itemID');
        try{
            //if the table id is not empty
            if(!empty($itemID)){
                $formFields = ['universal_id' => $itemID, 'event_id' => $event->id, 'type' => UniversalExpenseRevenue::where('id', $itemID)->first()->type];
                ActualExpenseRevenue::create($formFields);
                Toast::success('Item successfully created!');
            }else{
                Toast::warning('Please select an account in order to create it');
            }

        }catch(Exception $e){
            Alert::error('There was a error trying to edit the account. Error Message: ' . $e->getMessage());
        }
    }

    public function calculate(): void{
        $attendees =  intval(request('attendees'));
        $price =  intval(request('price'));
        $this->profit = $attendees * $price;
        Toast::info('Profit = ' . $this->profit);
    }

    public function calcNetIncome(): int{
        $revenues = $this->accRevenues->sum('budget');
        $expenses = $this->accExpenses->sum('budget');
        return $revenues-$expenses;
    }

    public function createView(Events $event){
        return redirect()->route('platform.budget.viewPDF', ['event_id' => $event->id]);
    }

    public function createPDF(Events $event){
        return redirect()->route('platform.budget.downloadPDF', ['event_id' => $event->id]);
    }
}
