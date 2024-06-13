<section class="login_slider">
    <div class="login_slider_container" data-lgs-slider>
        <button class='login_slider_button lgs_prev' data-slider-button='prev'>&#8656;</button>
        <button class='login_slider_button lgs_next' data-slider-button='next'>&#8658;</button>
        <span data-slider-position></span>
        <ul data-lgs-slides>
            @foreach(\App\Models\LoginAds::where('portal', 4)->get() as $ad)
                <li class='login_slide' @if($loop->index == 0) data-active @endif>
                    <a href="{{ $ad->campaign->website }}" target="_blank">
                        <img src="{{ $ad->campaign->image }}" alt="{{ $ad->title }} Image">
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
        </ul>
    </div>
</section>

@include('platform::auth.styles.slider_styles')