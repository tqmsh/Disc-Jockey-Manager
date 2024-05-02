<?php

namespace App\Providers;

use App\Classes\DisplayAdData;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class DisplayAdServiceProvider extends ServiceProvider
{

    const DISPLAY_ADS_FILE_NAME = 'display_ads_data.csv';
    
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $display_ads_data = $this->getDisplayAdData();
        
        $this->app->singleton(DisplayAdData::class, function($app) use($display_ads_data) {       
            return new DisplayAdData($display_ads_data);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    // this function will convert the display ads csv file to an array
    private function getDisplayAdData(){
        $delimiter = ',';

        if (!Storage::disk('local')->exists(self::DISPLAY_ADS_FILE_NAME)){
            ddd('There has been an error finding the display ads data file.');
        }

        $header = null;
        $data = array();

        if (($handle = Storage::disk('local')->readStream(self::DISPLAY_ADS_FILE_NAME)) !== false){
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
