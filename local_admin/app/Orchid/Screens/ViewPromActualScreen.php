<?php

namespace App\Orchid\Screens;


use Exception;
use Illuminate\Support\Facades\Auth;
use App\Models\UniversalExpenseRevenue;
use App\Models\ActualExpenseRevenue;
use Orchid\Platform\Http\Controllers\AsyncController;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use App\Models\Events;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Support\Color;
use Orchid\Screen\Fields\TextArea;
use Orchid\Support\Facades\Alert;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Toast;
use App\Orchid\Layouts\TypeListener;

class ViewPromActualScreen extends Screen
{
   
    public $event;
    public $actual;
    public $table;
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
            'actual' => ActualExpenseRevenue::where('event_id', $event->id)->get(),
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
                    ->required()
                    ->options([
                        'expense' => 'Expense',
                        'revenue' => 'Revenue',
                    ]),

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
                    ->placeholder($this->actual->where('universal_id', $this->table[0]->id)[0]->actual)
                    ->readonly()
                    ->horizontal(),

                Input::make('fundraising')
                    ->title($this->table[1]->name)
                    ->placeholder($this->actual->where('universal_id', $this->table[1]->id)[1]->actual)
                    ->readonly()
                    ->horizontal(),


            ])->title('Revenues'),
            Layout::rows([
                // Put in expenses here
                // Follow below format
                /*Input::make('expenses')
                    ->title($this->table[expense_id]->name)
                    ->placeholder($this->actual->where('universal_id', $this->table[expense_id]->id)[expense_id]->actual)
                    ->readonly()
                    ->horizontal(),*/

            ])->title('Expenses'),

            Layout::rows([
                Input::make('netIncome')
                    ->title('Net Income')
                    ->placeholder($this->calcNetIncome())
                    ->readonly()
                    ->horizontal(),

                /*Button::make('Submit')
                    ->method('buttonClickProcessing')
                    ->type(Color::DEFAULT()),*/

            ])->title('Net Income'),

            Layout::rows([
                Group::make([
                    Button::make('Save to PDF')
                        ->method('save')
                        ->icon('save-alt'),
                    Button::make('Download PDF')
                        ->method('download', ['event' => $this->event])
                        ->icon('cloud-download'),
                    Button::make('Close out event')
                        ->method('closeEvent', ['event' => $this->event])
                        ->icon('close'),
                ]),
            ]),
        ];

    }

    public function calcNetIncome(): int{
        $revenues = $this->actual->where('type', '2')->sum('actual');
        $expenses = $this->actual->where('type', '1')->sum('actual');
        return $revenues-$expenses;
    }

    public function save(Events $event){
        return redirect()->route('platform.actual.viewPDF', ['event_id' => $event->id]);
    }

    public function download(Events $event){
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
                $account = ActualExpenseRevenue::where([['universal_id', $entry_id], ['event_id', $event->id]])->get();
                $account[0]->last_updated_user_id = Auth::user()->id;

                $account[0]->actual = ($request->get('amount') == null || $request->get('amount') <= 0) ? $account[0]->actual : ($account[0]->actual + $request->get('amount'));
                
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
