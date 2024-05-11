<?php

namespace App\Orchid\Screens;

use App\Models\Guide;
use App\Models\Section;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class EditGuideSectionScreen extends Screen
{
    public $guide;
    public $section;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Guide $guide, Section $section): iterable
    {
        return [
            'guide' => $guide,
            'section' => $section,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Edit Section: ' . $this->section->section_name;
    }

    public function description(): ?string
    {
        return 'Guide: ' . $this->guide->guide_name;
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
                ->method('update'),

            Button::make('Delete Section')
                ->icon('trash')
                ->method('delete')
                ->confirm('Are you sure you want to delete this section?'),

            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.guideSection.list', ['guide' => $this->guide]),
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
                Input::make('ordering')
                    ->title('Ordering')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->value($this->section->ordering),

                Input::make('section_name')
                    ->title('Section Name')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->value($this->section->section_name),
            ])
        ];
    }

    public function update(Guide $guide, Section $section)
    {
        try {

            $fields = request()->validate([
                'ordering' => 'required|numeric',
                'section_name' => 'required',
            ]);

            if(Section::where('ordering',$fields['ordering'])->where('guide_id', $guide->id)->whereNot('id',$section->id)->exists()) {

                Toast::error('Ordering already exists');
                return redirect()->route('platform.guideSection.edit', ['guide' => $guide, 'section' => $section]);

            } else if(Section::where('section_name', $fields['section_name'])->where('guide_id', $guide->id)->whereNot('id',$section->id)->exists()) {

                Toast::error('Section name already exists');
                return redirect()->route('platform.guideSection.edit', ['guide' => $guide, 'section' => $section]);
            }

            $section->update([
                'ordering' =>$fields['ordering'],
                'section_name' => $fields['section_name'],
            ]);
        } catch (\Exception $e) {
            Toast::error($e->getMessage());
            return redirect()->route('platform.guideSection.edit', ['guide' => $guide, 'section' => $section]);
        }

        Toast::success('Section updated successfully.');

        return redirect()->route('platform.guideSection.list', ['guide' => $guide]);
    }

    public function delete(Guide $guide, Section $section)
    {
        try {
            $section->delete();
        } catch (\Exception $e) {
            Toast::error('Something went wrong');
            return redirect()->route('platform.guideSection.edit', ['guide' => $guide, 'section' => $section]);
        }

        Toast::success('Section deleted successfully.');

        return redirect()->route('platform.guideSection.list', ['guide' => $guide]);
    }
}
