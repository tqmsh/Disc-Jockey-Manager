<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\User;
use App\Models\School;
use Orchid\Screen\Screen;
use App\Models\Localadmin;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;

class EditLocaladminScreen extends Screen
{
    public $localadmin;
    public $school;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Localadmin $localadmin): iterable
    {
        return [
            'localadmin' => $localadmin
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Edit Local Admin: ' . $this->localadmin->firstname . ' ' . $this->localadmin->lastname;
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

            Button::make('Delete Local Admin')
                ->icon('trash')
                ->method('delete')
                ->confirm(__('Are you sure you want to delete this local admin?')),

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
        $this->school = School::find($this->localadmin->school_id);

        return [
                Layout::rows([

                Input::make('firstname')
                    ->title('First Name')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->value($this->localadmin->firstname),

                Input::make('lastname')
                    ->title('Last Name')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->value($this->localadmin->lastname),

                Input::make('email')
                    ->title('Email')
                    ->type('email')
                    ->required()
                    ->horizontal()
                    ->value($this->localadmin->email),

                Input::make('phonenumber')
                    ->title('Phone Number')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->mask('(999) 999-9999')
                    ->placeholder('Ex. (613) 859-5863')
                    ->value($this->localadmin->phonenumber),

                Select::make('school')
                    ->title('School')
                    ->required()
                    ->horizontal()
                    ->empty('Start typing to Search...')
                    ->fromModel(School::class, 'school_name', 'school_name')
                    ->value($this->school->school_name),
                
                Select::make('country')
                    ->title('Country')
                    ->empty('Start typing to Search...')
                    ->required()
                    ->horizontal()
                    ->fromModel(School::class, 'country', 'country')
                    ->value($this->school->country),

                Select::make('state_province')
                    ->title('State/Province')
                    ->horizontal()
                    ->empty('Start typing to Search...')
                    ->fromModel(School::class, 'state_province', 'state_province')
                    ->value($this->school->state_province),

                Select::make('county')
                    ->title('County')
                    ->empty('Start typing to Search...')
                    ->horizontal()
                    ->fromModel(School::class, 'county', 'county')
                    ->value($this->school->county),
                
                Select::make('city_municipality')
                    ->title('City/Municipality')
                    ->empty('Start typing to Search...')
                    ->horizontal()
                    ->fromModel(School::class, 'city_municipality', 'city_municipality')
                    ->value($this->school->city_municipality),
            ]),
        ];
    }
    
    public function update(Localadmin $localadmin, Request $request)
    {
        
        try{

            $localadminTableFields = $this->getLocalAdminFields($request);

            $userTableFields = $this->getUserFields($request);

            //check for duplicate email
            if($this->validEmail($request, $localadmin)){

                //email not changed
                $localadmin->update($localadminTableFields);
                
                User::where('id', $localadmin->user_id)->update($userTableFields);
                
                Toast::success('You have successfully updated ' . $request->input('firstname') . ' ' . $request->input('lastname') . '.');

                return redirect()->route('platform.localadmin.list');
            
            }else{

                //duplicate email found
                Toast::error('Email already exists.');
            }

        }catch(Exception $e){

            Alert::error('There was an error editing this local admin. Error Code: ' . $e->getMessage());
        }
    } 

    public function delete(Localadmin $localadmin)
    {
        try{

            User::where('id', $localadmin->user_id)->delete();

            Toast::info('You have successfully deleted the Local Admin.');

            return redirect()->route('platform.localadmin.list');

        }catch(Exception $e){

            Alert::error('There was an error deleting this local admin. Error Code: ' . $e->getMessage());
        }
    }

    //check for duplicate emails
    private function validEmail($request, $localadmin){
        return count(User::whereNot('id', $localadmin->user_id)->where('email', $request->input('email'))->get()) == 0;
    }

    //this functions returns the values that need to be inserted in the localadmin table in the db
    private function getLocalAdminFields($request){
        $school_query = School::where('school_name', $request->input('school'))
            ->where('state_province', $request->input('state_province'))
            ->where('country', $request->input('country'));

        if ($request->input('country') == 'USA') {
            $school_query = $school_query->where('county', $request->input('county'));
        } else {
            $school_query = $school_query->where('city_municipality', $request->input('city_municipality'));
        }
        $school = $school_query->first();
                            
        if(is_null($school)){
            throw New Exception('You are trying to enter a invalid school');
        }

        $localadminTableFields = [
            'firstname' => $request->input('firstname'),
            'lastname' => $request->input('lastname'),
            'email' => $request->input('email'),
            'phonenumber' => $request->input('phonenumber'),
            'school' => $request->input('school'),
            'school_id' => $school->id
        ];
        
        return $localadminTableFields;
    }

    //this functions returns the values that need to be inserted in the user table in the db
    private function getUserFields($request){
        $userTableFields = [
            'firstname' => $request->input('firstname'),
            'lastname' => $request->input('lastname'),
            'email' => $request->input('email'),
            'country' => $request->input('country'),
            'phonenumber' => $request->input('phonenumber'),
        ];
        
        return $userTableFields;
    }
}
