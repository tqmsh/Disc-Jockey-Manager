<?php

namespace App\Orchid\Screens;

use App\Models\School;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class EditSchoolScreen extends Screen
{

    public $school;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(School $school): iterable
    {
        return [
            'school' => $school
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Edit: ' . $this->school->school_name;
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Submit')
                ->icon('check')
                ->method('update'),

            Button::make('Delete School')
                ->icon('trash')
                ->method('delete')
                ->confirm(__('Are you sure you want to delete this school?')),


            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.school.list')
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
                Input::make('school_name')
                    ->title('School Name')
                    ->type()
                    ->required()
                    ->value($this->school->school_name)
                    ->horizontal(),

                Input::make('country')
                    ->title('Country')
                    ->required()
                    ->horizontal()
                    ->value($this->school->country),

                Input::make('state_province')
                    ->title('State/Province')
                    ->horizontal()
                    ->value($this->school->state_province),

                Input::make('school_board')
                    ->title('School Board')
                    ->horizontal()
                    ->value($this->school->school_board),

                Input::make('address')
                    ->title('Address')
                    ->type('text')
                    ->required()
                    ->value($this->school->address)
                    ->horizontal()
                    ->placeholder('Ex. 2381 Ogilvie Rd'),

                Input::make('zip_postal')
                    ->title('Zip/Postal')
                    ->type('text')
                    ->required()
                    ->value($this->school->zip_postal)
                    ->horizontal()
                    ->placeholder('Ex. K1J 7N4'),

                Input::make('phone_number')
                    ->title('Phone Number')
                    ->type('text')
                    ->mask('(999) 999-9999')
                    ->required()
                    ->value($this->school->phone_number)
                    ->horizontal()
                    ->placeholder('Ex. (613) 859-5863'),

                Input::make('fax')
                    ->title('Fax Number')
                    ->type('number')
                    ->horizontal()
                    ->value($this->school->fax)
                    ->placeholder('Ex. 546879123'),

                Input::make('teacher_name')
                    ->title('Teacher name')
                    ->type('text')
                    ->required()
                    ->value($this->school->teacher_name)
                    ->horizontal()
                    ->placeholder('Ex. John Doe'),

                Input::make('teacher_email')
                    ->title('Teacher Email')
                    ->type('email')
                    ->required()
                    ->value($this->school->teacher_email)
                    ->horizontal()
                    ->placeholder('Ex. johndoe@gmail.com'),

                Input::make('teacher_cell')
                    ->title('Teacher Contact Number')
                    ->type('text')
                    ->mask('(999) 999-9999')
                    ->required()
                    ->value($this->school->teacher_cell)
                    ->horizontal()
                    ->placeholder('Ex. (613) 852-4563'),                
            ]),
        ];
    }
    
    public function update(School $school, Request $request)
    {
        //!PUT ALL THIS CODE IN A TRY CATCH

        //check for duplicate email
        if(count(School::whereNot('id', $school->id)->where('teacher_email', $request->input('teacher_email'))->get()) == 0){

            //email not changed
            $school->fill($request->all())->save();

            Alert::success('You have successfully updated ' . $request->input('school_name') . '.');

            return redirect()->route('platform.school.list');
          
        }else{
            //duplicate email found
            Alert::error('Teacher email already exists.');
        }
    }

    public function delete(School $school)
    {
        //!PUT ALL THIS CODE IN A TRY CATCH
        $school->delete();

        Alert::info('You have successfully deleted the school.');

        return redirect()->route('platform.school.list');
    }
}
