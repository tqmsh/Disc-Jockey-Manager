<?php

namespace App\Orchid\Screens;

use App\Models\BeautyGroup;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;
use App\Orchid\Layouts\ViewBeautyGroupLayout;

class ViewBeautyGroupScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'beautyGroups' => BeautyGroup::latest()->paginate(10)
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Beauty Groups';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
                
                Link::make('Add New')
                    ->route('platform.beauty-groups.create')
                    ->icon('plus'),
    
                Button::make('Delete Selected Beauty Groups')
                    ->method('deleteBeautyGroups')
                    ->icon('trash')
                    ->confirm('Are you sure you want to delete the selected beauty groups?')
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
            ViewBeautyGroupLayout::class
        ];
    }

    public function redirect(){
        if(request('type') == 'edit'){
            return redirect()->route('platform.beauty-groups.edit', request('beauty_group_id'));
        } else if(request('type') == 'members'){
            return redirect()->route('platform.beauty-groups.members', request('beauty_group_id'));
        }
    }

    public function deleteBeautyGroups(){
        BeautyGroup::whereIn('id', request('beautyGroups'))->delete();
        Toast::success('Selected Beauty Groups Deleted Successfully');
    }

}
