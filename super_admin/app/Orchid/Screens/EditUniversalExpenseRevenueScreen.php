<?php

namespace App\Orchid\Screens;

use App\Models\UniversalExpenseRevenue;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class EditUniversalExpenseRevenueScreen extends Screen
{
    public $expenseRevenue;
    public $typeStr;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(UniversalExpenseRevenue $expenseRevenue): iterable
    {
        return [
            'expenseRevenue' => $expenseRevenue,
            'typeStr' => $expenseRevenue->type == 1 ? 'Expense' : 'Revenue',
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        // chaining the arrows for name doesn't work in double-quoted string for some reason
        return "Edit Universal $this->typeStr: " . $this->expenseRevenue->name;
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
            Button::make("Delete $this->typeStr")
                ->icon('trash')
                ->method('delete')
                ->confirm(__('Are you sure you want to delete this ' . strtolower($this->typeStr) . '?')),
            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.universal-expense-revenue.list'),
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
                    ->title('Name')
                    ->type('text')
                    ->required()
                    ->value($this->expenseRevenue->name),
                Select::make('type')
                    ->title('Type')
                    ->options([
                        1 => 'Expense',
                        2 => 'Revenue',
                    ])
                    ->required()
                    ->value($this->expenseRevenue->type),
            ])
        ];
    }
    
    public function update(UniversalExpenseRevenue $expenseRevenue, Request $request) {
        $validated = $request->validate([
            'name' => [
                'required',
                'max:255',
                Rule::unique('universal_expenses_revenues')->ignore($expenseRevenue),
            ],
            'type' => 'required|integer|in:1,2',
        ]);
        $expenseRevenue->update($validated);
        $typeStr = $expenseRevenue->type == 1 ? 'Expense' : 'Revenue';
        Toast::success("$typeStr '$expenseRevenue->name' edited succesfully");
    }

    public function delete(UniversalExpenseRevenue $expenseRevenue) {
        $name = $expenseRevenue->name;
        $typeStr = $expenseRevenue->type == 1 ? 'Expense' : 'Revenue';
        $expenseRevenue->delete();
        Toast::success("$typeStr '$name' deleted succesfully");
        return redirect()->route('platform.universal-expense-revenue.list');
    }
}
