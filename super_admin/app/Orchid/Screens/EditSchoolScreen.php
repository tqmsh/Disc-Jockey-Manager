<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\User;
use App\Models\Region;
use App\Models\School;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;

class EditSchoolScreen extends Screen
{

    public $school;
    public $user;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(School $school): iterable
    {
        return [
            'school' => $school,
            'user' => User::where('id', $school->teacher_id)->first(),
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

                Select::make('region_id')
                    ->title('Region')
                    ->required()
                    ->horizontal()
                    ->empty('Start typing to Search...')
                    ->fromQuery(Region::query(), 'name')
                    ->value($this->school->region_id),

                Input::make('state_province')
                    ->title('State/Province')
                    ->horizontal()
                    ->value($this->school->state_province),

                Input::make('city_municipality')
                    ->title('City/Municipality')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->value($this->school->city_municipality)
                    ->placeholder('Ex. Ottawa'),

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


                Input::make('metropolitan_region')
                    ->title('Metropolitan Region')
                    ->type('text')
                    ->horizontal()
                    ->value($this->school->metropolitan_region)
                    ->placeholder('Ex. Greater Ottawa Metropolitan Area'),

                Input::make('county')
                    ->title('County')
                    ->type('text')
                    ->horizontal()
                    ->value($this->school->county)
                    ->placeholder('Ex. Suffolk County'),

                Input::make('website')
                    ->title('Website')
                    ->type('text')
                    ->horizontal()
                    ->value($this->school->website)
                    ->placeholder('Ex. https://colonelby.com'),

                Input::make('school_data')
                    ->title('School Data')
                    ->type('text')
                    ->horizontal()
                    ->value($this->school->school_data)
                    ->placeholder('Ex. 546879123'),

                Input::make('firstname')
                    ->title('Teacher First Name')
                    ->type('text')
                    ->horizontal()
                    ->value((is_null($this->user)) ? 'N/A' : $this->user->firstname)
                    ->placeholder('Ex. John'),

                Input::make('lastname')
                    ->title('Teacher Last Name')
                    ->type('text')
                    ->horizontal()
                    ->value((is_null($this->user)) ? 'N/A' : $this->user->lastname)
                    ->placeholder('Ex. Doe'),

                Input::make('teacher_email')
                    ->title('Teacher Email')
                    ->type('email')
                    ->horizontal()
                    ->value((is_null($this->user)) ? 'NA@NA.com' : $this->user->email)
                    ->placeholder('Ex. johndoe@gmail.com'),

                Input::make('teacher_cell')
                    ->title('Teacher Contact Number')
                    ->type('text')
                    ->mask('(999) 999-9999')
                    ->horizontal()
                    ->value((is_null($this->user)) ? '' : $this->user->phonenumber)
                    ->placeholder('Ex. (613) 852-4563'),

                Input::make('total_students')
                    ->title('Total Students')
                    ->type('number')
                    ->horizontal()
                    ->value($this->school->total_students)
                    ->placeholder('Ex. 1024'),

                Button::make('Update')
                    ->icon('check')
                    ->method('update')
                    ->type(Color::DEFAULT()),
            ]),
        ];
    }

    public function update(School $school, Request $request)
    {
        try{

            //check for duplicate schools
            if($this->validSchool($request, $school) && $this->validEmail($request, $school)){

                $school->fill($request->except([
                    'firstname',
                    'lastname',
                    'teacher_email',
                    'teacher_cell',
                ]))->save();

                $user = User::where('id', $school->teacher_id)->first();
                if (!is_null($user)) {
                    $user->fill([
                        'name' => $request['firstname'] . $request['lastname'],
                        'firstname' => $request['firstname'],
                        'lastname' => $request['lastname'],
                        'email' => $request['teacher_email'],
                        'phonenumber' => $request['teacher_cell'],
                        'country' => $request['country'],
                    ])->save();
                } else {
                    $user = User::create([
                        'name' => $request['firstname'] . $request['lastname'],
                        'firstname' => $request['firstname'],
                        'lastname' => $request['lastname'],
                        'email' => $request['teacher_email'],
                        'phonenumber' => $request['teacher_cell'],
                        'country' => $request['country'],
                        'role' => 5,
                    ]);
                    $school->teacher_id = $user->id;
                }

                Toast::success('You have successfully updated ' . $request->input('school_name') . '.');

                return redirect()->route('platform.school.list');

            }else{

                //duplicate school found
                Toast::error('School already exists or we have found duplicate emails with another user.');
            }

        }catch(Exception $e){

            Alert::error('There was an error editing this school. Error Code: ' . $e->getMessage());
        }
    }

    public function delete(School $school)
    {
        try{

            $school->delete();

            User::where('id', $school->teacher_id)->delete();

            Toast::info('You have successfully deleted the school.');

            return redirect()->route('platform.school.list');

        }catch(Exception $e){

            Alert::error('There was an error deleting this school. Error Code: ' . $e->getMessage());
        }
    }

    //check for duplicate emails
    private function validEmail($request, $school){
        return count(User::whereNot('id', $school->teacher_id)->where('email', $request->input('teacher_email'))->get()) == 0;
    }

    //this method checks for duplicate schools
    private function validSchool($request, $school){

        return count(School::whereNot('id', $school->id)->where('school_name', $request->input('school_name'))->where('county', $request->input('county'))->where('state_province', $request->input('state_province'))->where('country', $request->input('country'))->get()) == 0;
    }
}
