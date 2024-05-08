<?php

namespace App\Classes;

use App\Models\DisplayAds;
use App\Models\Region;

class DisplayAdData {

    public function __construct(private array $display_ad_data){}

    public function isDisplayAdSpotUsed(int|string $portal, string $route_uri, int $ad_index, int $region_id) : bool {
        return DisplayAds::where('portal', (int)$portal)
                        ->where('route_uri', $route_uri)
                        ->where('ad_index', $ad_index)
                        ->where('region_id', $region_id)
                        ->exists();
    }

    public function getUnusedAdSpots() : array {
        return array_filter($this->display_ad_data, function(array $display_ad) {
            $regionId = Region::where('name', $display_ad['campaign_region'])->first()->id;
            return !$this->isDisplayAdSpotUsed($display_ad['portal'], $display_ad['route_uri'], $display_ad['ad_index'], $regionId);
        });
    }

    public function getAllAdSpots(array $filters = []) : array {
        $data = $this->display_ad_data;

        if(!empty($filters)) {

            if(isset($filters['view_open_spots'])) {
                $data = array_filter($data, function(array $ad_spot) use($filters) {
                    return $this->isDisplayAdSpotUsed($ad_spot['portal'], $ad_spot['route_uri'], $ad_spot['ad_index'], $filters['region_id']) == boolval($filters['view_open_spots']);
                });
            }
        }

        return $data;
    }
}