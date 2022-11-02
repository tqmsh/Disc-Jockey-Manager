<?php

namespace App\Orchid\Screens;

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
                ->method('delete'),

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
        $this->school = $this->localadmin->getSchool($this->localadmin->school);

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
                    ->fromModel(School::class, 'school_name', 'school_name')
                    ->value($this->localadmin->school),

                Select::make('country')
                    ->title('Country')
                    ->required()
                    ->horizontal()
                    ->fromModel(School::class, 'country', 'country')
                    ->value($this->localadmin->getUser($this->localadmin->email)->value('country')),


                Select::make('state_province')
                    ->title('State/Province')
                    ->horizontal()
                    ->fromModel(School::class, 'state_province', 'state_province')
                    ->value($this->school->value('state_province')),


                Select::make('school_board')
                    ->title('School Board')
                    ->horizontal()
                    ->fromModel(School::class, 'school_board', 'school_board')
                    ->value($this->school->value('school_board')),
             
            ]),
        ];
    }
    public function update(Localadmin $localadmin, Request $request)
    {
        //!PUT ALL THIS CODE IN A TRY CATCH
        //!MAKE SURE THE SCHOOL AND SCHOOL BOARD MATCH

        $localadminTableFields = [
            'firstname' => $request->input('firstname'),
            'lastname' => $request->input('lastname'),
            'email' => $request->input('email'),
            'phonenumber' => $request->input('phonenumber'),
            'school' => $request->input('school'),
        ];

        $userTableFields = [
            'firstname' => $request->input('firstname'),
            'lastname' => $request->input('lastname'),
            'email' => $request->input('email'),
            'country' => $request->input('country'),
        ];

        //check for duplicate email
        if(count(User::whereNot('id', $localadmin->user_id)->where('email', $request->input('email'))->get()) == 0){

            
            //email not changed
            $localadmin->update($localadminTableFields);
            
            User::where('id', $localadmin->user_id)->update($userTableFields);
            
            Alert::success('You have successfully updated ' . $request->input('firstname') . ' ' . $request->input('lastname') . '.');

            return redirect()->route('platform.localadmin.list');
          
        }else{
            //duplicate email found
            Alert::error('Email already exists.');
        }
    } 

    public function delete(Localadmin $localadmin)
    {
        //!PUT ALL THIS CODE IN A TRY CATCH
        $localadmin->delete();

        Alert::info('You have successfully deleted the Local Admin.');

        return redirect()->route('platform.localadmin.list');
    }
}
