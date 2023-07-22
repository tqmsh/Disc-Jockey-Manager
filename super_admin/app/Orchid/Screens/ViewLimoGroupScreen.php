<?php

namespace App\Orchid\Screens;

use App\Models\LimoGroup;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;
use App\Orchid\Layouts\ViewLimoGroupLayout;

class ViewLimoGroupScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'limoGroups' => LimoGroup::latest()->paginate(10)
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Limo Groups';
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
                    ->route('platform.limo-groups.create')
                    ->icon('plus'),
    
                Button::make('Delete Selected Limo Groups')
                    ->method('deleteLimoGroups')
                    ->icon('trash')
                    ->confirm('Are you sure you want to delete the selected Limo Groups?')
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
            ViewLimoGroupLayout::class
        ];
    }

    public function redirect(){
        if(request('type') == 'edit'){
            return redirect()->route('platform.limo-groups.edit', request('limo_group_id'));
        } else if(request('type') == 'members'){
            return redirect()->route('platform.limo-groups.members', request('limo_group_id'));
        }
    }

    public function deleteLimoGroups(){
        $limoGroups = LimoGroup::whereIn('id', request('limoGroups'))->get();
        foreach($limoGroups as $limoGroup){
            $limoGroup->delete();
        }
        Toast::success('Selected Limo Groups Deleted Successfully');
    }

}
