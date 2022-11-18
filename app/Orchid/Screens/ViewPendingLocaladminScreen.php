<?php

namespace App\Orchid\Screens;

use App\Models\User;
use App\Models\School;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use App\Models\Localadmin;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use App\Orchid\Layouts\ViewPendingLocaladminLayout;

class ViewPendingLocaladminScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'pending_localadmins' => Localadmin::latest('localadmins.created_at')
                                    ->filter(request(['country', 'state_province', 'school', 'school_board']))
                                    ->where('localadmins.account_status', 0)->paginate(10)
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Pending Local Admins';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Accept Selected Local Admins')
                ->icon('plus')
                ->method('deleteLocaladmins')
                ->confirm(__('Are you sure you want to accept the selected local admins?'))
                ->route('platform.school.create'),

            Button::make('Delete Selected Local Admins')
                ->icon('trash')
                ->method('deleteLocaladmins')
                ->confirm(__('Are you sure you want to delete the selected local admins?')),
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
                    
                    Select::make('school')
                        ->title('School')
                        ->empty('No selection')
                        ->fromModel(School::class, 'school_name', 'school_name'),

                    Select::make('country')
                        ->title('Country')
                        ->empty('No selection')
                        ->fromModel(User::class, 'country', 'country'),

                    Select::make('school_board')
                        ->title('School Board')
                        ->empty('No selection')
                        ->fromModel(School::class, 'school_board', 'school_board'),

                    Select::make('state_province')
                        ->title('State/Province')
                        ->empty('No selection')
                        ->fromModel(School::class, 'state_province', 'state_province'),
                ]),
                
                Button::make('Filter')
                    ->icon('filter')
                    ->method('filter')
                    ->type(Color::DEFAULT()),
            ]),

            ViewPendingLocaladminLayout::class
        ];
    }

    public function filter(Request $request){
        return redirect('/admin/pendinglocaladmins?' 
                    .'&school=' . $request->get('school')
                    .'&country=' . $request->get('country')
                    .'&school_board=' . $request->get('school_board')
                    .'&state_province=' . $request->get('state_province'));
    }
}
