<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\Events;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Fields\TextArea;
use Orchid\Support\Facades\Layout;
use App\Models\ActualExpenseRevenue;
use App\Models\UniversalExpenseRevenue;
use Orchid\Support\Facades\Alert;
use Illuminate\Support\Facades\Auth;

class ViewPromBudgetScreen extends Screen
{

    public $profit;
    public $event;
    public $table;
    public $budget;
    public $type; 
    public $open;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Events $event): iterable
    {

        return [
            'event' => $event,
            'table' => UniversalExpenseRevenue::all(),
            'budget' => ActualExpenseRevenue::where('event_id', $event->id)->get(),
            'open' => $event->open,
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
                ->route('platform.budget.list', ['event_id' => $this->event]),

            Link::make('Actual')
                ->icon('wallet')
                ->route('platform.actual.list', ['event_id' => $this->event]),
                
            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.event.list')
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
                        ->placeholder(0),

                    Input::make('price')
                        ->type('number')
                        ->title('Price per ticket')  
                        ->placeholder(0), 
                ]),
                
                Button::make('Calculate')
                    ->icon('calculator-alt')
                    ->method('calculate'),
            ])->title('Calculator'),

            Layout::rows([
                /*DropDown::make('Type')
                    ->title('Type of Entry')
                    ->list([
                        Button::make('Expense')
                            ->method('expense')
                            ->icon('pencil'),
                        Button::make('Revenue')
                            ->method('revenue')
                            ->icon('trash'),
                ]),*/
                Select::make('type')
                    ->title('Accounts')
                    ->options([
                        'expense'   => 'Expense',
                        'revenue' => 'Revenue',
                ]),

                Select::make('name')
                    ->title('Accounts')
                    ->fromModel(UniversalExpenseRevenue::where('type', '2'), 'name'),

                Input::make('amount')
                    ->title('Dollar amount')
                    ->type('number')
                    ->placeholder('0')
                    ->help('Enter the dollar amount of your entry'),

                TextArea::make('notes')
                    ->title('Extra notes')
                    ->rows(5),  

                Button::make('Add Entry')
                    ->icon('plus')
                    ->method('updateEntry')
                    ->type(Color::DEFAULT()),
            ])->title('Item entry')->canSee($this->open),
            Layout::rows([
                // Put in revenues here
                // Follow below format
                Input::make('ticketrevenue')
                    ->title($this->table[0]->name)
                    ->placeholder($this->budget->where('universal_id', $this->table[0]->id)[0]->budget)
                    ->readonly()
                    ->horizontal(),

                Input::make('fundraising')
                    ->title($this->table[1]->name)
                    ->placeholder($this->budget->where('universal_id', $this->table[1]->id)[1]->budget)
                    ->readonly()
                    ->horizontal(),


            ])->title('Revenues'),
            Layout::rows([
                // Put in expenses here
                // Follow below format
                /*Input::make('expenses')
                    ->title($this->table[expense_id]->name)
                    ->placeholder($this->budget->where('universal_id', $this->table[expense_id]->id)[expense_id]->budget)
                    ->readonly()
                    ->horizontal(),*/

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
                    Button::make('Save to PDF')
                        ->method('save', ['event' => $this->event])
                        ->icon('save-alt'),

                    Button::make('Download PDF')
                        ->method('download', ['event' => $this->event])
                        ->icon('cloud-download'),
                ]),
            ]),
        ];
    }

    public function calculate(): void{
        $attendees =  intval(request('attendees'));
        $price =  intval(request('price'));
        $this->profit = $attendees * $price;
        Toast::warning('Profit = ' . $this->profit);
    }

    public function calcNetIncome(): int{
        $revenues = $this->budget->where('type', '2')->sum('budget');
        $expenses = $this->budget->where('type', '1')->sum('budget');
        return $revenues-$expenses;
    }

    public function save(Events $event){
        return redirect()->route('platform.budget.viewPDF', ['event_id' => $event->id]);
    }

    public function download(Events $event){
        return redirect()->route('platform.budget.downloadPDF', ['event_id' => $event->id]);
    }


    public function updateEntry(Request $request, Events $event)
    {
        $entry_id = $request->get('name');

        try{

            //if the table id is not empty
            if(!empty($entry_id)){

                //get the table from the db
                $account = ActualExpenseRevenue::where([['universal_id', $entry_id], ['event_id', $event->id]])->get();
                $account[0]->last_updated_user_id = Auth::user()->id;

                $account[0]->budget = ($request->get('amount') == null || $request->get('amount') <= 0) ? $account[0]->budget : ($account[0]->budget + $request->get('amount'));
                
                //save the table
                $account[0]->save();

                Toast::success('Account updated succesfully');


            }else{
                Toast::warning('Please select an account in order to edit it');
            }

        }catch(Exception $e){
            Alert::error('There was a error trying to edit the account. Error Message: ' . $e->getMessage());
        }
    }
}
