<?php

namespace App\Orchid\Screens;


use Exception;
use Orchid\Screen\TD;
use App\Models\Events;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use App\Models\Localadmin;
use Illuminate\Http\Request;
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

class ViewPromActualScreen extends Screen
{
   
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
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Prom Actual';
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
                            return $curRevenue->actual;
                        }
                    }),
                TD::make('edit_Rev', 'Edit Item')
                    ->render(function(UniversalExpenseRevenue $revenue){
                        $curRevenue = $this->accRevenues->where('universal_id', $revenue->id)->first();
                        if($curRevenue !=null){
                            return Link::make('Edit')->route('platform.actual.edit', ['event_id' => $this->event->id, 'id' => $curRevenue->id])->type(Color::PRIMARY())->icon('pencil');
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
                            return $curExpense->actual;
                        }
                    }),
                TD::make('edit_Exp', 'Edit Item')
                    ->render(function(UniversalExpenseRevenue $expense){
                        $curExpense = $this->accExpenses->where('universal_id', $expense->id)->first();
                        if($curExpense !=null){
                            return Link::make('Edit')->route('platform.actual.edit', ['event_id' => $this->event->id, 'id' => $curExpense->id])->type(Color::PRIMARY())->icon('pencil');
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
                        ->route('platform.actual.viewPDF.switch', ['event_id' => $this->event])
                        ->type(Color::SECONDARY()),

                    Link::make('Download PDF')
                        ->icon('cloud-download')
                        ->route('platform.actual.downloadPDF.switch', ['event_id' => $this->event])
                        ->type(Color::PRIMARY()),

                    Button::make('Close out event')
                        ->method('closeEvent', ['event' => $this->event])
                        ->icon('close')->canSee($this->open)
                        ->type(Color::DANGER()),
                ])->autoWidth(),
            ]),
        ];

    }

    public function calcNetIncome(): int{
        $revenues = $this->accRevenues->sum('actual');
        $expenses = $this->accExpenses->sum('actual');
        return $revenues-$expenses;
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

    public function createView(Events $event){
        return redirect()->route('platform.actual.viewPDF', ['event_id' => $event->id]);
    }

    public function createPDF(Events $event){
        return redirect()->route('platform.actual.downloadPDF', ['event_id' => $event->id]);
    }

    public function closeEvent(Events $event): void{
        $event->open = false;
        $event->save();
        Alert::success('Your events profit has been closed.');
    }

    public function updateEntry(Request $request, Events $event)
    {
        $entry_id = $request->get('name');

        try{

            //if the table id is not empty
            if(!empty($entry_id)){

                //get the table from the db
                $account = ActualExpenseRevenue::where([['universal_id', $entry_id], ['event_id', $event->id]])->first();
                if($account != null){
                    $account->last_updated_user_id = Auth::user()->id;

                    $account->actual = ($request->get('amount') == null || $request->get('amount') == 0) ? $account->actual : ($account->actual + $request->get('amount'));
                    
                    //save the table
                    $account->save();
                }
                else{
                    $formFields = ['universal_id' => $request->name, 'event_id' => $event->id, 'type' => UniversalExpenseRevenue::where('id', $request->name)->first()->type, 'actual' => $request->amount];
                    ActualExpenseRevenue::create($formFields);
                }
                Toast::success('Account updated succesfully');



            }else{
                Toast::warning('Please select an account in order to edit it');
            }

        }catch(Exception $e){
            Alert::error('There was a error trying to edit the account. Error Message: ' . $e->getMessage());
        }
    }
}
