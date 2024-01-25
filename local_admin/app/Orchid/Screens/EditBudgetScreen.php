<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\Events;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use App\Models\Localadmin;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Fields\TextArea;
use Orchid\Support\Facades\Layout;
use App\Models\ActualExpenseRevenue;
use Illuminate\Support\Facades\Auth;
use App\Models\UniversalExpenseRevenue;

class EditBudgetScreen extends Screen
{

    public $event;
    
    public $item;

    public $open;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Events $event, ActualExpenseRevenue $item): iterable
    {
        abort_if((!($event->open) || (Localadmin::where('user_id', Auth::user()->id)->first()->school_id != $event->school_id)), 403, 'You are not authorized to view this page.');
        return [
            'event' => $event,
            'item' => $item,
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
        return 'Edit Item Budget';
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
                ->route('platform.budget.edit', ['event_id' => $this->event->id, 'id' => $this->item->id])
                ->type(Color::DARK()),

            Link::make('Actual')
                ->icon('wallet')
                ->route('platform.actual.edit', ['event_id' => $this->event->id, 'id' => $this->item->id])
                ->type(Color::PRIMARY()),

            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.budget.list', ['event_id' => $this->event->id])
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
                Input::make('name')
                    ->title('Item Name')
                    ->placeholder(UniversalExpenseRevenue::where('id', $this->item->universal_id)->first()->name)
                    ->readonly(),

                Input::make('amount')
                    ->title('Dollar amount')
                    ->type('number')
                    ->value($this->item->budget)
                    ->help('Enter the dollar amount of your item'),

                TextArea::make('notes')
                    ->title('Extra notes')
                    ->value($this->item->notes)
                    ->rows(5),  

                Button::make('Update Item')
                    ->icon('pencil')
                    ->method('updateItem')
                    ->type(Color::PRIMARY()),
            ])->title('Item entry')->cansee($this->open),
        ];
    }

    public function updateItem(Events $event, ActualExpenseRevenue $item)
    {
        $amount = request('amount');
        try{
            //if the table id is not empty
            if(request()!=null){
                //get the table from the db
                if($item != null){
                    $item->last_updated_user_id = Auth::user()->id;
                    $item->budget = ($amount == null) ? $item->budget : ($amount);
                    $item->notes = request('notes');
                
                    //save the table
                    $item->save();
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
