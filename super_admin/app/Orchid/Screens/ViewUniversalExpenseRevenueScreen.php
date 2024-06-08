<?php

namespace App\Orchid\Screens;

use App\Models\UniversalExpenseRevenue;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ViewUniversalExpenseRevenueScreen extends Screen
{
    public $expenses, $revenues;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'expenses' => UniversalExpenseRevenue::where('type', 1)->get(),
            'revenues' => UniversalExpenseRevenue::where('type', 2)->get(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Universal Expenses and Revenues';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Add New Expenses and Revenues')
                ->icon('plus')
                ->route('platform.universal-expense-revenue.create'),
            Button::make('Delete Selected Expenses and Revenues')
                ->icon('trash')
                ->method('deleteExpensesRevenues')
                ->confirm(__('Are you sure you want to delete the selected expenses and revenues?')),
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
            Layout::table('expenses', [
                TD::make('checkboxes')
                    ->render(function (UniversalExpenseRevenue $expense){
                        return CheckBox::make('selectedExpensesRevenues[]')
                            ->value($expense->id)
                            ->checked(false);
                    }),

                TD::make('name', 'Name')
                    ->render(function (UniversalExpenseRevenue $expense) {
                        return Link::make($expense->name)
                            ->route('platform.universal-expense-revenue.edit', $expense);
                    }),
                TD::make('created_at', 'Created At')
                    ->render(function (UniversalExpenseRevenue $expense) {
                        return Link::make($expense->created_at->toDateTimeString())
                            ->route('platform.universal-expense-revenue.edit', $expense);
                    }),
                TD::make('updated_at', 'Updated At')
                    ->render(function (UniversalExpenseRevenue $expense) {
                        return Link::make($expense->updated_at->toDateTimeString())
                            ->route('platform.universal-expense-revenue.edit', $expense);
                    }),
                TD::make()
                    ->render(function (UniversalExpenseRevenue $expense) {
                        return Link::make('Edit')
                            ->type(Color::PRIMARY())
                            ->route('platform.universal-expense-revenue.edit', $expense)
                            ->icon('pencil');
                    }),
            ])->title('Expenses'),

            Layout::table('revenues', [
                TD::make('checkboxes')
                    ->render(function (UniversalExpenseRevenue $revenue){
                        return CheckBox::make('selectedExpensesRevenues[]')
                            ->value($revenue->id)
                            ->checked(false);
                    }),

                TD::make('name', 'Name')
                    ->render(function (UniversalExpenseRevenue $revenue) {
                        return Link::make($revenue->name)
                            ->route('platform.universal-expense-revenue.edit', $revenue);
                    }),
                TD::make('created_at', 'Created At')
                    ->render(function (UniversalExpenseRevenue $revenue) {
                        return Link::make($revenue->created_at->toDateTimeString())
                            ->route('platform.universal-expense-revenue.edit', $revenue);
                    }),
                TD::make('updated_at', 'Updated At')
                    ->render(function (UniversalExpenseRevenue $revenue) {
                        return Link::make($revenue->updated_at->toDateTimeString())
                            ->route('platform.universal-expense-revenue.edit', $revenue);
                    }),
                TD::make()
                    ->render(function (UniversalExpenseRevenue $revenue) {
                        return Link::make('Edit')
                            ->type(Color::PRIMARY())
                            ->route('platform.universal-expense-revenue.edit', $revenue)
                            ->icon('pencil');
                    }),
            ])->title('Revenues'),
        ];
    }

    public function deleteExpensesRevenues(Request $request) {
        $expensesRevenues = $request->get('selectedExpensesRevenues');
        if (!empty($expensesRevenues)) {
            UniversalExpenseRevenue::whereIn('id', $expensesRevenues)->delete();
            Toast::success('Selected expenses and revenues deleted succesfully');
        } else {
            Toast::warning('You must select expenses and/or revenues in order to delete them');
        }
    }
}
