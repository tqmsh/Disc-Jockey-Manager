<?php

namespace App\Orchid\Screens;

use App\Models\UniversalExpenseRevenue;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class CreateUniversalExpenseRevenueScreen extends Screen
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
        return 'Add a New Expense or Revenue';
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
                ->method('createExpenseRevenue'),
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
                    ->required(),
                Select::make('type')
                    ->title('Type')
                    ->options([
                        1 => 'Expense',
                        2 => 'Revenue',
                    ])
                    ->required(),
            ])
        ];
    }

    public function createExpenseRevenue(Request $request) {
        $validated = $request->validate([
            'name' => 'required|unique:universal_expenses_revenues|max:255',
            'type' => 'required|integer|in:1,2',
        ]);
        $createdModel = UniversalExpenseRevenue::create($validated);
        $expense_revenue_str = $createdModel->type == 1 ? 'Expense' : 'Revenue';
        Toast::success("$expense_revenue_str '$createdModel->name' added succesfully");
    }
}
