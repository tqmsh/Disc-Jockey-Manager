<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use App\Models\Events; 
use App\Models\Staffs; 
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;

class ViewStaffLayout extends Table
{
    protected $target = 'staffs';

    protected function columns(): iterable
    {
        return [
            TD::make('checkboxes')
                ->render(function (Staffs $staff){
                    return CheckBox::make('staffs[]')
                        ->value($staff->id)
                        ->checked(false);
                }),

            TD::make('first_name', 'First Name')
                ->render(function (Staffs $staff) {
                    return Link::make($staff->first_name)
                        ->route('platform.student.edit', $staff);
                }),
            TD::make('last_name', 'Last Name')
                ->render(function (Staffs $staff) {
                    return Link::make($staff->last_name)
                        ->route('platform.student.edit', $staff);
                }),
            TD::make('position', 'Position')
                ->render(function (Staffs $staff) {
                    return $staff->position;
                }),
            TD::make('gender', 'Gender')
                ->render(function (Staffs $staff) {
                    return $staff->gender;
                }),
            TD::make('email', 'Email')->width('105')
                ->render(function (Staffs $staff) {
                    // Add three dots after 25 characters (which is the average email length).
                    $email = strlen($staff->email) <= 25 ? $staff->email : substr($staff->email, 0, 25) . '...';

                    return Link::make($email)
                        ->route('platform.student.edit', $staff);
                }),
            TD::make('cell', 'Phone Number')
                ->render(function (Staffs $staff) {
                    return $staff->cell;
                }),
            TD::make('age', 'Age')
                ->render(function (Staffs $staff) {
                    return $staff->age;
                }),
            TD::make()
                ->render(function (Staffs $staff) {
                    return Button::make('Edit')
                        ->type(Color::PRIMARY())
                        ->method('redirect', ['staff'=> $staff->id, 'type' => 'staffs'])
                        ->icon('pencil');
                }),
        ];
    }
}
