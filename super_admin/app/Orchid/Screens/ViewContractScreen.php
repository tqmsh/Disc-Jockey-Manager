<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\Contract;
use App\Orchid\Layouts\ViewContractLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ViewContractScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'contracts' => Contract::latest()->filter(request(['title', 'state_province',]))->paginate(10),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Contracts';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Add New Contracts')
                ->icon('plus')
                ->route('platform.contract.create'),
            Button::make('Delete Selected Contracts')
                ->icon('trash')
                ->method('deleteContracts')
                ->confirm(__('Are you sure you want to delete the selected contracts?')),
            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.contract.list'),
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
                    Select::make('title')
                        ->title('Title')
                        ->empty('No Selection')
                        ->help('Type in boxes to search')
                        ->fromModel(Contract::class, 'title', 'title'),
                    Select::make('state_province')
                        ->title('State/Province')
                        ->empty('No Selection')
                        ->fromModel(Contract::class, 'state_province', 'state_province'),
                ]),

                Button::make('Filter')
                    ->icon('filter')
                    ->method('filter')
                    ->type(Color::DEFAULT()),
            ]),

            ViewContractLayout::class,
        ];
    }

    public function filter() {
        return redirect()->route('platform.contract.list', request(['title', 'state_province',]));
    }

    public function redirect($contract){
        return redirect()->route('platform.contract.edit', $contract);
    }

    public function deleteContracts(Request $request) {
        $contracts = $request->get('contracts');
        try {  
            if (!empty($contracts)) {
                Contract::whereIn('id', $contracts)->delete();
                Toast::success('Selected contracts deleted successfully.');
            } else {
                Alert::warning('Please select contracts in order to delete them.');
            }
        } catch (Exception $e) {
            Alert::error('There was an error trying to delete the selected contracts. Error message:' . $e->getMessage());
        }
    }
}
