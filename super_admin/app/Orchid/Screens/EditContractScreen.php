<?php

namespace App\Orchid\Screens;

use App\Models\Contract;
use Exception;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class EditContractScreen extends Screen
{
    public $contract;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Contract $contract): iterable
    {
        return [
            'contract' => $contract,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Edit: '. $this->contract->title;
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Update')
                ->icon('check')
                ->method('save'),
            Button::make('Delete Contract')
                ->icon('trash')
                ->method('delete')
                ->confirm(__('Are you sure you want to delete this contract?')),
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
                Input::make('title')
                    ->title('Title')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. Hot Dogs Contract')
                    ->value($this->contract->title),
                Input::make('url')
                    ->title('URL')
                    ->type('url')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. https://hotdogs.com/contract')
                    ->value($this->contract->url),
                Input::make('state_province')
                    ->title('State/Province')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. Arkansas')
                    ->value($this->contract->state_province),
                TextArea::make('description')
                    ->title('Description')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. This is a contract...')
                    ->rows(12)
                    ->value($this->contract->description),
                Button::make('Update')
                    ->icon('check')
                    ->method('save'),
            ]),
        ];
    }

    /**
     * Save the changes made to the contract.
     */
    public function save(Contract $contract, Request $request) {
        try {
            $contractFields = $request->validate([
                'title' => 'required|max:255|unique:contracts',
                'url' => 'required|max:255|url',
                'state_province' => 'required|max:255',
                'description' => 'required|max:65535',
            ]);
            $contract->update($contractFields);
            Toast::success('Contract updated succesfully!');
            return redirect()->route('platform.contract.list');
        } catch (Exception $e) {
            Alert::error('There was an error adding the contract. Error code: ' . $e->getMessage());
        }
    }

    /**
     * Delete the contract.
     */
    public function delete(Contract $contract) {
        try {
            $contract->delete();
            Toast::info('The contract was deleted succesfully.');
            return redirect()->route('platform.contract.list');
        } catch (Exception $e) {
            Alert::error('There was an error deleting the contract. Error code: ' . $e->getMessage());
        }
    }
}
