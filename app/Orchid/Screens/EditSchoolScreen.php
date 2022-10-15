<?php

namespace App\Orchid\Screens;

use App\Models\School;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
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
                ->icon('plus')
                ->method('update'),

            Button::make('Delete School')
                ->icon('trash')
                ->method('delete'),

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
                    ->type('text')
                    ->required()
                    ->value($this->school->country)
                    ->horizontal()
                    ->placeholder('Ex. Canada'),

                Input::make('state_province')
                    ->title('State/Province')
                    ->type('text')
                    ->required()
                    ->value($this->school->state_province)
                    ->horizontal()
                    ->placeholder('Ex. Ontario'),

                Input::make('school_board')
                    ->title('School Board')
                    ->type('text')
                    ->required()
                    ->value($this->school->school_board)
                    ->horizontal()
                    ->placeholder('Ex. OCDSB'),

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
        $school->fill($request->all())->save();

        Alert::info('You have successfully updated the school.');

        return redirect()->route('platform.school.list');
    }

    public function delete(School $school)
    {
        $school->delete();

        Alert::info('You have successfully deleted the school.');

        return redirect()->route('platform.school.list');
    }
}
