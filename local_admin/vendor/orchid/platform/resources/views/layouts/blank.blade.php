@foreach($manyForms as $key => $column)
    @php
        $current_ad_index = 2;
    @endphp

    @foreach(\Illuminate\Support\Arr::wrap($column) as $i => $item)
        @php
            $display_ads_query = \App\Models\DisplayAds::where('portal', 0)
                                    ->where('route_name', request()->route()->getName())
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
                $display_ad_width = $display_ad_square ? "width: 311.51px; height: 311.51px;" : "width: 100%; height:auto;";
                $display_ad_display = $display_ad_square ? "display: flex; justify-content: flex-end; width:100%;" : "";
            @endphp

            @if($display_ad_image_url !== null)
                <div style="{{$display_ad_min_width}} {{$display_ad_margin}} {{$display_ad_max_width}} margin-bottom: 0.75rem; {{$display_ad_display}}">
                    <a target="_blank" href="{{$campaign->website}}">
                        <img style="{{$display_ad_width}} max-height:{{boolval($display_ads_query->first()->square) ? 311.51 : 90}}px;" src="{{$display_ad_image_url}}" alt="">
                    </a>
                </div>
            @endif
        @endif


        {!! $item ?? '' !!}

        @php
            $display_ads_query = DB::table('display_ads')
                                    ->where('portal', 0)
                                    ->where('route_name', request()->route()->getName())
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
                $display_ad_width = $display_ad_square ? "width: 311.51px; height: 311.51px;" : "width: 100%; height:auto;";
                $display_ad_display = $display_ad_square ? "display: flex; justify-content: flex-end; width:100%;" : "";
            @endphp

            @if($display_ad_image_url !== null)
                <div style="{{$display_ad_min_width}} {{$display_ad_margin}} {{$display_ad_max_width}} margin-bottom: 0.75rem; {{$display_ad_display}}">
                    <a target="_blank" href="{{$campaign->website}}">
                        <img style="{{$display_ad_width}} max-height:{{boolval($display_ads_query->first()->square) ? 311.51 : 90}}px;" src="{{$display_ad_image_url}}" alt="">
                    </a>
                </div>
            @endif
        @endif

        @php
            if($item->getName() !== "platform::layouts.modal") {
                $current_ad_index++;
            }
        @endphp
    @endforeach
@endforeach
