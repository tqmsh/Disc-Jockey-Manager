<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\User;
use App\Models\School;
use Orchid\Screen\Screen;
use App\Models\Localadmin;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Fields\Password;
use Orchid\Support\Facades\Layout;

class CreateLocaladminScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Add a new Local Admin';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Add')
                ->icon('plus')
                ->method('createLocaladmin'),

            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.localadmin.list')
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

                Input::make('firstname')
                    ->title('First Name')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. John'),

                Input::make('lastname')
                    ->title('Last Name')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. Doe'),

                Input::make('name')
                    ->title('Username')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. KingKhan435'),

                Input::make('phonenumber')
                    ->title('Phone Number')
                    ->type('text')
                    ->mask('(999) 999-9999')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. (613) 859-5863'),

                Input::make('email')
                    ->title('Email')
                    ->type('email')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. johndoe@gmail.com'),

                Password::make('password')
                    ->title('Password')
                    ->type('password')
                    ->required()
                    ->horizontal(),

                Select::make('school')
                    ->title('School')
                    ->required()
                    ->empty('No Selection')
                    ->horizontal()
                    ->fromModel(School::class, 'school_name', 'school_name'),

                Select::make('country')
                    ->title('Country')
                    ->empty('No Selection')
                    ->required()
                    ->horizontal()
                    ->fromModel(School::class, 'country', 'country'),

                Select::make('state_province')
                    ->title('State/Province')
                    ->horizontal()
                    ->empty('No Selection')
                    ->fromModel(School::class, 'state_province', 'state_province'),

                Select::make('county')
                    ->title('County')
                    ->empty('No Selection')
                    ->required()
                    ->horizontal()
                    ->fromModel(School::class, 'county', 'county'),
            ]),
        ];
    }

    public function createLocaladmin(Request $request){

        try{

            $localAdminTableFields = $this->getLocalAdminFields($request);

            $userTableFields = $this->getUserFields($request);


            //check for duplicate email
            if($this->validEmail($request)){
                
                //no duplicates found
                User::create($userTableFields);
                User::where('email', $request->input('email'))->update(['permissions' => '{"platform.index":true}']);

                $localAdminTableFields['user_id'] = User::where('email', $request->input('email'))->get('id')->value('id');

                Localadmin::create($localAdminTableFields);
                
                Toast::success('Local Admin Added Succesfully');

                return redirect()->route('platform.localadmin.list');
            }else{
                //duplicate email found
                Toast::error('Email already exists.');
            }

        }catch(Exception $e){
            
            Alert::error('There was an error creating this local admin Error Code: ' . $e->getMessage());
        }
    }

    //check for duplicate emails
    private function validEmail($request){
        return count(User::where('email', $request->input('email'))->get()) == 0;
    }

    //this functions returns the values that need to be inserted in the localadmin table in the db
    private function getLocalAdminFields($request){

        try{

            $school_id = School::where('school_name', $request->input('school'))
                                ->where('county', $request->input('county'))
                                ->where('state_province', $request->input('state_province'))
                                ->where('country', $request->input('country'))
                                ->get('id')->value('id');

            if(is_null($school_id)){
                throw New Exception('You are trying to enter a invalid school');
            }

            $localadminTableFields = [
                'firstname' => $request->input('firstname'),
                'lastname' => $request->input('lastname'),
                'email' => $request->input('email'),
                'phonenumber' => $request->input('phonenumber'),
                'school' => $request->input('school'),
                'account_status' => 1,
                'user_id' => null,
                'school_id' => $school_id
            ];
            
            return $localadminTableFields;

        }catch(Exception $e){
            Alert::error('There was an error creating this local admin Error Code: ' . $e->getMessage);
        }

    }

    //this functions returns the values that need to be inserted in the user table in the db
    private function getUserFields($request){

        $userTableFields = [
            'firstname' => $request->input('firstname'),
            'lastname' => $request->input('lastname'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'name' => $request->input('name'),
            'country' => $request->input('country'),
            'account_status' => 1,
            'phonenumber' => $request->input('phonenumber'),
            'remember_token' => Str::random(10),
            'role' =>'localadmin',
        ];
        
        return $userTableFields;
    }

}
