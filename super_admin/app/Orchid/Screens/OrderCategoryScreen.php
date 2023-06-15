<?php

namespace App\Orchid\Screens;

use App\Models\Campaign;
use App\Models\Categories;
use App\Models\Region;
use App\Models\Vendors;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Cropper;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Psy\Exception\ErrorException;

class OrderCategoryScreen extends Screen
{
    public $categoriesNum = 0;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        $this->categoriesNum =  count(Categories::where('status', 1)->get()->toArray());
        return [];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Order Categories';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [

            Button::make('Save Order')
                ->icon('check')
                ->method('saveOrder'),

            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.category.list')
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        // TODO: Make order dropdowns have values removed and added automatically
        $layout_parts = [];
        for ($i=0; $i<$this->categoriesNum; $i++){
            $id = Categories::where('status', 1)->where("order_num", $i + 1)->first();
            if (is_null($id)){
                $id = 0;
            } else{
                $id = $id->id;
            }
            $temp = Select::make("select".$i)
                ->title('Order #'.($i+1))
                ->empty('No category selected')
                ->fromQuery(Categories::where('status', 1), "name")
                ->value($id);
            $layout_parts[] = $temp;
        }
        return [
            Layout::rows($layout_parts)
        ];
    }

    function array_has_dupes($array): bool
    {
        return count($array) !== count(array_unique($array));
    }

    public function saveOrder(Request $request){
        $order = [];
        $categoriesNum =  count(Categories::where('status', 1)->get()->toArray());
        for ($i=0; $i<$categoriesNum; $i++){
            $order[] = $request->input('select'.$i);
        }
        if ($this->array_has_dupes($order)){
            Toast::error("No Duplicates allowed.");
        }
        else{
            for ($i=0; $i<count($order); $i++){
                $category = Categories::where("id", $order[$i])->first();
                $category->order_num= ($i+1);
                $category->save();
            }
        }
        return redirect()->route('platform.category.list');
    }
}
