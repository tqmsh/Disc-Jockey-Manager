<?php

namespace App\Orchid\Screens;

use App\Orchid\Layouts\DressEditLayout;
use App\Models\Dress;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Alert;
use Illuminate\Http\Request;

class EditDressScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Create/Edit Dress';

    /**
     * Display header description.
     *
     * @var string|null
     */
    public $description = '';

    private $dress;


    /**
     * Query data.
     *
     * @param Dress $dress
     * @return array
     */
    public function query(Dress $dress): array
    {
        $this->exists = $dress->exists;

        if ($this->exists) {
            $this->dress = $dress;
            $this->dress->colours = implode("\n", $dress->colours);
            $this->dress->sizes = implode("\n", $dress->sizes);
            $this->dress->images = implode("\n", $dress->images);
        }
        return [
            'dress' => $this->dress
        ];
    }

    /**
     * Button commands.
     *
     * @return Button[]
     */
    public function commandBar(): array
    {
        $buttons = [
            Button::make('Back to Dress List')
                ->icon('arrow-left')
                ->method('goBack'),
            Button::make('Save')
                ->icon('check')
                ->type(Color::SUCCESS())
                ->method('save'),
        ];

        if ($this->dress != null) {
            $buttons[] = Button::make('Delete')
                ->method('remove')
                ->confirm('Are you sure you want to delete this dress?')
                ->parameters([
                    'id' => $this->dress->id,
                ])
                ->type(Color::DANGER())
                ->icon('trash');
        }

        return $buttons;
    }

    /**
     * Views.
     *
     * @return Layout[]
     */
    public function layout(): array
    {
        return [
            DressEditLayout::class
        ];
    }

    /**
     * @param Dress $dress
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Dress $dress, Request $request)
    {
        $dressData = $request->get('dress');
        $userId = auth()->id(); // Get the currently authenticated user's id

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
            'description' => 'nullable|string',
            'colours' => 'nullable|string',
            'sizes' => 'nullable|string',
            'images' => 'nullable|string',
            'url' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        unset($dressData['brand']);

        $dressData['colours'] = Dress::splitAndTrimNonEmpty($dressData['colours'], '\n');
        $dressData['sizes'] = Dress::splitAndTrimNonEmpty($dressData['sizes'], '\n');
        $dressData['images'] = Dress::splitAndTrimNonEmpty($dressData['images'], '\n');

        $dressData['user_id'] = $userId;

        $dress->fill($dressData)->save();

        Alert::info('You have successfully created or updated the dress.');

        return redirect()->route('platform.dresses');
    }

    public function remove(Dress $dress, Request $request)
    {
        $dress = Dress::findOrFail($request->get('id'));
        $dress->delete();
        Alert::info('You have successfully deleted the dress.');
        return redirect()->route('platform.dresses');
    }

    public function goBack()
    {
        return redirect()->route('platform.dresses');
    }
}
