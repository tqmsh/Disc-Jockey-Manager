<?php

namespace App\Orchid\Screens;

use App\Orchid\Layouts\DressEditLayout;
use App\Models\Dress;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Toast;

class EditDressScreen extends Screen
{
    public string $name = 'Create/Edit Dresses';
    public ?string $description = '';
    public ?Dress $dress;

    /**
     * Query the Dress model.
     *
     * @param Dress $dress
     * @return array
     */
    public function query(Dress $dress): array
    {
        // If it exists, format the data
        if ($dress->exists) {
            $this->name = 'Edit Dress';
            $this->description = 'Edit an existing dress.';
            $dress->colours = implode("\n", $dress->colours);
            $dress->sizes = implode("\n", $dress->sizes);
            $dress->images = implode("\n", $dress->images);
        } else {
            $this->name = 'Create Dress';
            $this->description = 'Create a new dress';
        }

        return ['dress' => $dress];
    }

    /**
     * Define the buttons to display.
     *
     * @return Button[]
     */
    public function commandBar(): array
    {
        $buttons = [
            Link::make('Back to Dress List')
                ->icon('arrow-left')
                ->route('platform.dresses'),
            Button::make('Save')
                ->icon('check')
                ->type(Color::SUCCESS())
                ->method('save'),
        ];

        // If the user is editing an existing dress, add the Delete and Preview buttons
        if ($this->dress->exists) {
            $buttons[] = Button::make('Delete')->method('remove')
                ->confirm('Are you sure you want to delete this dress?')
                ->parameters(['id' => $this->dress->id])
                ->type(Color::DANGER())
                ->icon('trash');

            $buttons[] = Button::make('Preview')->icon('eye')
                ->method('gotoPreview', ['dress' => $this->dress])
                ->confirm('This will discard your unsaved changes. Continue?')
                ->type(Color::PRIMARY());
        }

        return $buttons;
    }

    /**
     * Define the layout to use.
     *
     * @return array
     */
    public function layout(): array
    {
        return [DressEditLayout::class];
    }

    /**
     * Save the dress data.
     *
     * @param Dress $dress
     * @param Request $request
     * @return RedirectResponse
     */
    public function save(Dress $dress, Request $request)
    {
        $dressData = $request->get('dress');
        $userId = Auth::id();

        // Validate the main dress data
        $validator = Validator::make($dressData, [
            'model_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('dresses')->where(function ($query) use ($userId) {
                    return $query->where('user_id', $userId);
                })->ignore($dress->id),
            ],
            'model_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:50000',
            'colours' => 'nullable|string|max:50000',
            'sizes' => 'nullable|string|max:50000',
            'images' => 'nullable|string|max:50000',
            'url' => 'nullable|url',
        ]);
        // If validation fails, redirect back with errors
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Ensure that all URLs in the images field are valid
        $dressData['images'] = Dress::splitAndTrimNonEmpty($dressData['images'], "\n");
        $imageValidator = Validator::make([
            'images' => $dressData['images'],
        ], [
            'images.*' => 'url',
        ]);
        // If image URL validation fails, redirect back with errors
        if ($imageValidator->fails()) {
            return back()->withErrors($imageValidator)->withInput();
        }

        // Process and save the dress data
        unset($dressData['brand']);
        $dressData['colours'] = Dress::splitAndTrimNonEmpty($dressData['colours'], "\n");
        $dressData['sizes'] = Dress::splitAndTrimNonEmpty($dressData['sizes'], "\n");
        $dressData['user_id'] = $userId;
        $exists = $dress->exists;
        $dress->fill($dressData)->save();

        // Provide user with a success message
        Toast::info($exists ? 'You have successfully updated the dress.' : 'You have successfully created the dress.');
        return back();
    }

    /**
     * Delete the dress data.
     *
     * @param Dress $dress
     * @param Request $request
     * @return RedirectResponse
     */
    public function remove(Dress $dress, Request $request)
    {
        $dress->delete();
        Toast::success('You have successfully deleted the dress.');
        return redirect()->route('platform.dresses');
    }

    /**
     * Redirect to the dress detail page.
     *
     * @param $dress
     * @return RedirectResponse
     */
    public function gotoPreview($dress)
    {
        return redirect()->route('platform.dresses.detail', ['dress' => $dress]);
    }
}
