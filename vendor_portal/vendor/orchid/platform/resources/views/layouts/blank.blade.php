@foreach($manyForms as $key => $column)
    @php
        $current_ad_index = 2;
    @endphp

    @foreach(\Illuminate\Support\Arr::wrap($column) as $i => $item)
        @php
            $display_ads_query = \App\Models\DisplayAds::where('portal', 2)
                                    ->where('route_uri', str_replace('/{method?}', '', Illuminate\Support\Facades\Route::current()->uri()))
                                    ->where('ad_index', $current_ad_index);
        @endphp

        @if($item->getName() !== "platform::layouts.modal" && $display_ads_query->exists())
            @php
                $campaign = $display_ads_query->first()->campaign;
                $campaign->increment('impressions');
                $campaign->save();

                // styles
                $display_ad_square = boolval($display_ads_query->first()->square);
                $display_ad_image_url = $campaign->image;
                $display_ad_min_width = $display_ad_square ? "" : "min-width:40%;";
                $display_ad_margin = $display_ad_square ? "" : "margin: 0 auto;";
                $display_ad_max_width = $display_ad_square ? "" : "max-width:485px; max-height:60px;";
                $display_ad_width = $display_ad_square ? "width: 400px; height: 400px;" : "width: 100%; height:auto;";
                $display_ad_display = $display_ad_square ? "display: flex; width:100%;" : "";
            @endphp

            @if($display_ad_image_url !== null)
                <div id="promplanner-propoganda" style="{{$display_ad_min_width}} {{$display_ad_margin}} {{$display_ad_max_width}} margin-bottom: 0.75rem; {{$display_ad_display}}">
                    <a target="_blank" href="{{$campaign->website}}" onclick="incrementCampaignClick({{$campaign->id}})">
                        <img style="{{$display_ad_width}} max-height:{{boolval($display_ads_query->first()->square) ? 400 : 60}}px;" src="{{$display_ad_image_url}}" alt="">
                    </a>
                </div>

                @if($display_ads_query->first()->square)
                    <style>

                        @media only screen and (max-width: 770px) {
                            #promplanner-propoganda {
                                justify-content: center !important; 
                                align-items: center !important;
                            }    
                        }

                        @media only screen and (max-width: 400px) {
                            #promplanner-propoganda img {
                                width: 300px !important;
                                height: 300px !important;
                            }
                        }

                        @media only screen and (max-width: 300px) {
                            #promplanner-propoganda img {
                                width: 200px !important;
                                height: 200px !important;
                            }
                        }
                    </style>
                @endif
            @endif
        @endif


        {!! $item ?? '' !!}

        @php
            $display_ads_query = \App\Models\DisplayAds::where('portal', 2)
                                    ->where('route_uri', str_replace('/{method?}', '', Illuminate\Support\Facades\Route::current()->uri()))
                                    ->where('ad_index', $current_ad_index + 1);
        @endphp

        @if($i == array_key_last(array_keys($column)) && $display_ads_query->exists())
            @php
                $campaign = $display_ads_query->first()->campaign;
                $campaign->increment('impressions');
                $campaign->save();

                // styles
                $display_ad_square = boolval($display_ads_query->first()->square);
                $display_ad_image_url = $campaign->image;
                $display_ad_min_width = $display_ad_square ? "" : "min-width:40%;";
                $display_ad_margin = $display_ad_square ? "" : "margin: 0 auto;";
                $display_ad_max_width = $display_ad_square ? "" : "max-width:485px; max-height:60px;";
                $display_ad_width = $display_ad_square ? "width: 400px; height: 400px;" : "width: 100%; height:auto;";
                $display_ad_display = $display_ad_square ? "display: flex; width:100%;" : "";
            @endphp

            @if($display_ad_image_url !== null)
                <div id="promplanner-propoganda" style="{{$display_ad_min_width}} {{$display_ad_margin}} {{$display_ad_max_width}} margin-bottom: 0.75rem; {{$display_ad_display}}">
                    <a target="_blank" href="{{$campaign->website}}" onclick="incrementCampaignClick({{$campaign->id}})">
                        <img style="{{$display_ad_width}} max-height:{{boolval($display_ads_query->first()->square) ? 400 : 60}}px;" src="{{$display_ad_image_url}}" alt="">
                    </a>
                </div>

                @if($display_ads_query->first()->square)
                    <style>

                        @media only screen and (max-width: 770px) {
                            #promplanner-propoganda {
                                justify-content: center !important; 
                                align-items: center !important;
                            }    
                        }

                        @media only screen and (max-width: 400px) {
                            #promplanner-propoganda img {
                                width: 300px !important;
                                height: 300px !important;
                            }
                        }

                        @media only screen and (max-width: 300px) {
                            #promplanner-propoganda img {
                                width: 200px !important;
                                height: 200px !important;
                            }
                        }
                    </style>
                @endif
            @endif
        @endif

        @php
            if($item->getName() !== "platform::layouts.modal") {
                $current_ad_index++;
            }
        @endphp
    @endforeach
@endforeach
<script type="text/javascript">
    function incrementCampaignClick(campaign_id) {
        var url = 'https://api.promplanner.app/api/campaign_click/' + encodeURIComponent(campaign_id);
        console.log(url);
        axios.put(url)
    };
</script>