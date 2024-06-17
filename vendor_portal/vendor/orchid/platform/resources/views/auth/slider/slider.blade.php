<section class="login_slider">
    @php
        $ads = \App\Models\LoginAds::where('portal', 4)->get();
    @endphp

    <div class="login_slider_container" data-lgs-slider>
        @if($ads->isNotEmpty())
            <button class='login_slider_button lgs_prev' data-slider-button='prev'>&#8656;</button>
            <button class='login_slider_button lgs_next' data-slider-button='next'>&#8658;</button>
        @endif
        <span data-slider-position></span>
        <ul data-lgs-slides>
            @foreach($ads as $ad)
                <li class='login_slide' @if($loop->index == 0) data-active @endif>
                    <a href="{{ $ad->campaign->website }}" target="_blank">
                        <img id="slider-image" src="{{ $ad->campaign->image }}" alt="{{ $ad->title }} Image">
                    </a>

                    <div class='lgs_text_container'>
                        <span class='lgs_title'>{{ $ad->title }}</span>
                        <span class='lgs_subtitle'>{{ $ad->subtitle }}</span>
                    </div>
            
                    
                    <a href="{{ $ad->campaign->website }}" target="_blank">
                        <button class='lgs_button'>{{ $ad->button_title }}</button>
                    </a>
                </li> 
            @endforeach

            @if($ads->isEmpty())
                <div style="height: 100vh; width:100%; background-color:black; display:flex; justify-content:center; align-items:center; position:relative;">
                    <div id="pp_noa_container" style="display: flex; align-items: center; gap: 32px;">
                        <img style="width: 100px;" src="{{ asset('image/Prom_VP_Line.png') }}" alt="Prom Planner Logo">
                        <span style="color: white; font-size: 4.5rem; text-align: center;">Prom Planner</span>
                    </div>

                    <span style="font-size:1rem; color:white; text-align:left; position: absolute; bottom: 0.3rem; left: 1rem; opacity: 0.5;">Ads coming soon.</span>
                </div>
            @endif
        </ul>
    </div>
</section>

@include('platform::auth.styles.slider_styles')