<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\BeautyGroup;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Fields\TextArea;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\DateTimer;
use Illuminate\Support\Facades\Auth;

class EditBeautyGroupScreen extends Screen
{
    public $beautyGroup;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(BeautyGroup $beautyGroup): iterable
    {
        abort_if($beautyGroup->creator_user_id != Auth::user()->id, 403, 'You are not authorized to view this page.');

        return [
            'beautyGroup' => $beautyGroup
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Edit: ' . $this->beautyGroup->name ;
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Update Beauty Group')
                ->icon('plus')
                ->method('updateBeautyGroup'),
            
            Button::make('Delete Beauty Group')
                ->icon('trash')
                ->confirm('WARNING: Deleting a beauty group will remove all the memebers in it. Are you sure you want to delete this beauty group?')
                ->method('deleteBeautyGroup'),
                
            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.beauty-groups')
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
                    ->title('Beauty Group Name')
                    ->placeholder('Enter a name for your beauty group')
                    ->horizontal()
                    ->required()
                    ->value($this->beautyGroup->name),

                DateTimer::make('date')
                    ->title('Date')
                    ->placeholder('Enter the date for your beauty group')
                    ->horizontal()
                    ->required()
                    ->value($this->beautyGroup->date),
                
                Input::make('pickup_location')
                    ->title('Pickup Location')
                    ->placeholder('Enter the pickup location for your beauty group')
                    ->horizontal()
                    ->required()
                    ->value($this->beautyGroup->pickup_location),
                
                Input::make('dropoff_location')
                    ->title('Dropoff Location')
                    ->placeholder('Enter the dropoff location for your beauty group')
                    ->horizontal()
                    ->required()
                    ->value($this->beautyGroup->dropoff_location),
                
                DateTimer::make('depart_time')
                    ->title('Depart Time')
                    ->placeholder('Enter the depart time for your beauty group')
                    ->enableTime()
                    ->horizontal()
                    ->required()
                    ->value($this->beautyGroup->depart_time),
                
                Datetimer::make('dropoff_time')
                    ->title('Dropoff Time')
                    ->placeholder('Enter the dropoff time for your beauty group')
                    ->enableTime()
                    ->horizontal()
                    ->required()
                    ->value($this->beautyGroup->dropoff_time),
                
                Input::make('capacity')
                    ->title('Capacity')
                    ->type('number')
                    ->placeholder('Enter the capacity for your beauty group')
                    ->horizontal()
                    ->value($this->beautyGroup->capacity),
                
                TextArea::make('notes')
                    ->title('Notes')
                    ->placeholder('Enter any notes for your beauty group')
                    ->help('Notes can be seen by all beauty group members')
                    ->rows(8)
                    ->horizontal()
                    ->value($this->beautyGroup->notes),                   
            ])
        ];
    }

    public function updateBeautyGroup(Request $request, BeautyGroup $beautyGroup){

        $fields = $request->validate([
            'name' => 'required',
            'date' => 'required',
            'capacity' => 'nullable',
            'pickup_location' => 'required',
            'dropoff_location' => 'required',
            'depart_time' => 'required',
            'dropoff_time' => 'required',
            'capacity' => 'required',
            'notes' => 'nullable',
        ]);

        if($fields['capacity'] < $beautyGroup->capacity){
            Toast::error('Error Updating Beauty Group. Capacity cannot be less than the number of members in the beauty group.');
            return;
        }

        try{
            $beautyGroup->update($fields);
            Toast::success('Beauty Group Updated');
            return redirect()->route('platform.beauty-groups');
        } catch(Exception $e){
            Toast::error('Error Updating Beauty Group. Error Code: ' . $e->getMessage());
        }
    }

    public function deleteBeautyGroup(BeautyGroup $beautyGroup){
        try{
            $beautyGroup->delete();
            Toast::success('Beauty Group Deleted');
            return redirect()->route('platform.beauty-groups');
        } catch(Exception $e){
            Toast::error('Error Deleting Beauty Group. Error Code: ' . $e->getMessage());
        }
    }
}
