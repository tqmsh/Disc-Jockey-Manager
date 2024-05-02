<?php

namespace App\Orchid\Screens;

use App\Models\Campaign;
use App\Models\Categories;
use App\Models\DisplayAds;
use App\Models\Region;
use App\Models\User;
use App\Models\Vendors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Cropper;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Color;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class CreateDisplayAdScreen extends Screen
{

    private array $required_fields = ['route_uri', 'ad_index', 'portal', 'campaign_name', 'campaign_link', 'campaign_region', 'campaign_category', 'campaign_image', 'square'];
    
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
        return 'Create Display Ad';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.ad.list'),

            ModalToggle::make('Mass Import Display Ads')
                ->modal('massImportModal')
                ->method('massImportDisplayAds')
                ->icon('plus'),

            Button::make('Create Display Ad')
                ->icon('plus')
                ->method('createAd')
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
                Input::make('route_uri')
                    ->title('Route URI')
                    ->required()
                    ->horizontal()
                    ->placeholder('e.g., admin/events/campaigns/{display_ad}'),
            
                Input::make('ad_index')
                    ->title('Ad Index')
                    ->type('number')
                    ->mask([
                        'numericInput' => true
                    ])
                    ->required()
                    ->horizontal()
                    ->placeholder('What ad index?'),

                Select::make('portal')
                    ->title('Portal')
                    ->empty('What portal should this be in?')
                    ->required()
                    ->horizontal()
                    ->options([
                        0 => 'Local Admin',
                        1 => 'Student',
                        2 => 'Vendor'
                    ]),
                
                Select::make('vendor_user_id')
                    ->empty('Choose a vendor (optional)')
                    ->title('Vendor (optional)')
                    ->horizontal()
                    ->fromQuery(User::whereIn('id', Vendors::all()->pluck('user_id')), 'name'),

                Input::make('campaign_name')
                    ->title('Campaign Name')
                    ->placeholder('Enter your campaign name')
                    ->required()
                    ->horizontal(),

                Input::make('campaign_link')
                    ->title('Campaign URL')
                    ->type("url")
                    ->placeholder('https://placeholder.com')
                    ->required()
                    ->horizontal(),

                Select::make('campaign_region')
                    ->title("Region")
                    ->empty('Start typing to search...')
                    ->required()
                    ->fromModel(Region::class, 'name')
                    ->horizontal(),

                Select::make('category_id')
                    ->title('Category')
                    ->empty('Choose a category...')
                    ->required()
                    ->fromModel(Categories::class, 'name')
                    ->horizontal(),

                Input::make('campaign_image')
                    ->title('Campaign Image')
                    ->placeholder('https://example.com/image.png')
                    ->horizontal()
                    ->required(),

                CheckBox::make('square')
                    ->title('Is Square?')
                    ->horizontal(),
                ]),

                Layout::modal('massImportModal', [
                    Layout::rows([
                        Input::make('display_ads_csv')
                            ->type('file')
                            ->title('File must be in csv format. Ex. display_ads.csv')
                            ->help('The csv file MUST HAVE these fields and they need to be named accordingly to successfully import the display ads: <br>
                                • route_uri <br>
                                • ad_index <br>
                                • portal <br>
                                • campaign_name <br>
                                • campaign_link <br>
                                • campaign_region <br>
                                • campaign_category <br>
                                • campaign_image <br>
                                • square (0 for false, 1 for true) <br>
                                • vendor_user_id (optional) <br>'),
                            Link::make('Download Sample CSV')
                                ->icon('download')
                                ->href('/sample_display_ads_upload.csv')
                    ]),
                ])
                ->title('Mass Import Display Ads')
                ->applyButton('Import')
                ->withoutCloseButton()
        ];
    }

    public function createAd(Request $request) {
        try {
            // Check for existing route.
            if($this->validAd($request)) {
                // Create the campaign
                $campaign = Campaign::create([
                    "user_id" => $request->input('vendor_user_id') ?? 197, //!NEED TO OPTIMIZE THIS LATER
                    "category_id" => $request->input("category_id"),
                    "region_id" => $request->input("campaign_region"),
                    "title" => $request->input("campaign_name"),
                    "image" => $request->input("campaign_image"),
                    "website" => $request->input("campaign_link"),
                    "clicks" => 0,
                    "impressions" => 0,
                    'active' => 1
                ]);

                // Create the display ad
                DisplayAds::create([
                    'route_uri' => $request->input('route_uri'),
                    'ad_index' => $request->input('ad_index'),
                    'portal' => $request->input('portal'),
                    'campaign_id' => $campaign->id,
                    'region_id' => $request->input("campaign_region"),
                    'square' => intval($request->boolean('square'))
                ]);

                Toast::success("Successfully created an ad.");

                return to_route('platform.ad.list');
            } else {
                Toast::error("There's already an ad at that ad index in that route.");
            }
        } catch(\Exception $e) {
            Toast::error("There's been an error trying to create an ad. Error message: {$e->getMessage()}");
        }
    }

    public function massImportDisplayAds(Request $request) {
        try {
            $path = $this->validCSVFile($request);
            $display_ads = $this->csvToArray($path);

            foreach($display_ads as $i => $row) {
                $missing_rows = $this->findMissingRows($row);

                // Check for missing rows
                if(!empty($missing_rows)) {
                    throw new \Exception("Missing key(s) found at index {$i}: " . implode(', ', $missing_rows));
                }

                $region_id = Region::firstOrCreate(['name' => $row['campaign_region']])->id;
                $category_id = Categories::firstOrCreate(['name' => $row['campaign_category']])->id;

                if(!in_array('vendor_user_id', array_keys($row)) || $row['vendor_user_id'] == "") {
                    $row['vendor_user_id'] = 197; //!NEED TO OPTIMIZE THIS LATER
                }

                $c_query = Campaign::where('title', $row['campaign_name'])->where('region_id', $region_id);

                if(!$c_query->exists()) {
                    $campaign = Campaign::create([
                        'user_id' => $row['vendor_user_id'],
                        'title' => $row['campaign_name'],
                        'website' => $row['campaign_link'],
                        'region_id' => $region_id,
                        'category_id' => $category_id,
                        'image' => $row['campaign_image'],
                        'clicks' => 0,
                        'impressions' => 0,
                        'active' => 1
                    ]);
                }
                
                if(!DisplayAds::where('route_uri', $row['route_uri'])->where('ad_index', $row['ad_index'])->where('portal', $row['portal'])->exists()) {
                    DisplayAds::create([
                        'route_uri' => $row['route_uri'],
                        'ad_index' => $row['ad_index'],
                        'portal' => $row['portal'],
                        'campaign_id' => isset($campaign) ? $campaign->id : $c_query->first()->id,
                        'region_id' => $region_id,
                        'square' => $row['square']
                    ]);
                }
                
            }

            Toast::success("Successfully mass imported ads.");

            return to_route('platform.ad.list');
        } catch(\Exception $e) {
            Alert::error("There's been an error trying to mass import display ads. Error message: {$e->getMessage()}");
        }
    }

    private function validAd(Request $request) : bool {
        return !(DisplayAds::where('portal', $request->input('portal'))
                            ->where('route_uri', $request->input('route_uri'))
                            ->where('ad_index', $request->input('ad_index'))
                            ->where('region_id', $request->input("campaign_region"))
                            ->exists()
                );
    }

    private function findMissingRows(array $row) : array {
        return array_diff($this->required_fields, array_keys($row));
    }

    /**
     * Validates the uploaded file. Returns the file's real/absolute path.
     * @throws \Exception
     */
    private function validCSVFile(Request $request) : string {
        // No file uploaded
        if(is_null($request->file('display_ads_csv'))) {
            throw new \Exception('No file has been uploaded.');
        }

        $path = $request->file('display_ads_csv')->getRealPath();

        // Could not get file path because file doesn't exist.
        if($path == false) {
            throw new \Exception('An error has occured.');
        }

        $extension = $request->file('display_ads_csv')->extension();

        // Invalid file extension
        if(!in_array(strtolower($extension), ['csv', 'txt'])) {
            throw new \Exception('Invalid file extension has been uploaded. Please upload a .csv or .txt file.');
        }

        return $path;
    }

    //this function will convert the csv file to an array
    private function csvToArray($filename = '', $delimiter = ','){

        if (!file_exists($filename) || !is_readable($filename)){
            Alert::error('There has been an error finding this file.');
            return;
        }

        $header = null;
        $data = array();

        if (($handle = fopen($filename, 'r')) !== false){

            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false){
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }

            fclose($handle);
        }

        return $data;
    }
}
