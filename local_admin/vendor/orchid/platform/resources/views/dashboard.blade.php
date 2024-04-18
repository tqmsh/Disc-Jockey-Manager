@extends('platform::app')

@section('body-left')

    <div class="aside col-xs-12 col-md-2 bg-dark">
        <div class="d-md-flex align-items-start flex-column d-sm-block h-full">

            <header style="justify-content: space-between;" class="d-sm-flex d-md-block p-3 mt-md-4 w-100 d-flex align-items-center">
                <div class="d-flex align-items-center">
                    <a href="#" class="header-toggler d-md-none d-flex align-items-center"
                    data-bs-toggle="collapse"
                    data-bs-target="#headerMenuCollapse">
                        <x-orchid-icon path="menu" class="icon-menu"/>

                        <span class="ms-2">
                            @php
                            // Remove video tutorial url HTML from title.
                            $title = app()->view->getSections()['title'] ?? config('app.name');
                            echo strip_tags($title); 
                            @endphp
                        </span>
                    </a>

                    <!-- Add video tutorial question mark (for mobile displays) !-->
                    @if(DB::table('video_tutorials')->where('portal', 0)->where('route_name', request()->route()->getName())->exists())
                        <a style="margin-left:8px;" class="d-flex align-items-center d-md-none" target="_blank" href="{{DB::table('video_tutorials')->where('portal', 0)->where('route_name', request()->route()->getName())->first()->url}}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-question-circle" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286m1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94"/>
                            </svg>
                        </a>
                    @endif
                </div>

                <a class="header-brand" href="{{route('platform.example')}}">
                    @includeFirst([config('platform.template.header'), 'platform::header'])
                </a>
            </header>

            <nav class="collapse d-md-block w-100 mb-md-3" id="headerMenuCollapse">

                @include('platform::partials.search')

                @includeWhen(Auth::check(), 'platform::partials.profile')

                <ul class="nav flex-column mb-1 ps-0">
                    {!! Dashboard::renderMenu(\Orchid\Platform\Dashboard::MENU_MAIN) !!}
                </ul>

            </nav>

            <div class="h-100 w-100 position-relative to-top cursor d-none d-md-block mt-md-5 divider"
                 data-action="click->html-load#goToTop"
                 title="{{ __('Scroll to top') }}">
                <div class="bottom-left w-100 mb-2 ps-3">
                    <small>
                        <x-orchid-icon path="arrow-up" class="me-2"/>

                        {{ __('Scroll to top') }}
                    </small>
                </div>
            </div>

            <footer class="p-3 mb-2 m-t d-none d-lg-block w-100">
                @includeFirst([config('platform.template.footer'), 'platform::footer'])
            </footer>

        </div>
    </div>
@endsection

@section('body-right')

    <div class="mt-3 mt-md-4">
        @include('platform::socialsTopBar')
        @php
            $display_ads_query = \App\Models\DisplayAds::where('portal', 0)
                                    ->where('route_uri', str_replace('/{method?}', '', Illuminate\Support\Facades\Route::current()->uri()))
                                    ->where('ad_index', 0)
                                    ->where('region_id', \App\Models\School::where('id', \App\Models\Localadmin::where('user_id', auth()->user()->id)->first()->school_id)->first()->region_id);
        @endphp
        @if($display_ads_query->exists())
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
                $display_ad_display = $display_ad_square ? "display: flex; justify-content: flex-end; width:100%;" : "";
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
        
        @if(!is_null($notice))
            <div class="layout d-flex">
                <span class="text-info d-flex align-items-center me-3">
                    <x-orchid-icon path="circle"/>
                </span>
                <div>
                    <h2 class="h3 text-black font-bold">{{ $notice->title }}</h2>
                    @if(!is_null($notice->subtitle))
                        <small class="text-muted fw-bold">{{ $notice->subtitle }}</small>
                    @endif
                </div>
                @if(!is_null($notice->url))
                    <div class="nav command-bar ms-auto d-inline-flex">
                        <a class="btn btn-line" data-turbo="true" href="{{ $notice->url }}" target="_blank">
                            Read More
                            <x-orchid-icon path="arrow-right" class="ms-2"/>
                        </a>
                    </div>
                @endif
            </div>
        @endif

        @php
            $display_ads_query = \App\Models\DisplayAds::where('portal', 0)
                                    ->where('route_uri', str_replace('/{method?}', '', Illuminate\Support\Facades\Route::current()->uri()))
                                    ->where('ad_index', 1)
                                    ->where('region_id', \App\Models\School::where('id', \App\Models\Localadmin::where('user_id', auth()->user()->id)->first()->school_id)->first()->region_id);
        @endphp
        @if($display_ads_query->exists())
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
                $display_ad_display = $display_ad_square ? "display: flex; justify-content: flex-end; width:100%;" : "";
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
        
        @if(Breadcrumbs::has())
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb px-4 mb-2">
                    <x-tabuna-breadcrumbs
                        class="breadcrumb-item"
                        active="active"
                    />
                </ol>
            </nav>
        @endif

        <div class="@hasSection('navbar') @else d-none d-md-block @endif layout v-md-center">
            <header class="d-none d-md-block col-xs-12 col-md p-0">
                <h1 class="m-0 fw-light h3 text-black">@yield('title')</h1>
                <small class="text-muted" title="@yield('description')">@yield('description')</small>
            </header>
            <nav class="col-xs-12 col-md-auto ms-auto p-0">
                <ul class="nav command-bar justify-content-sm-end justify-content-start d-flex align-items-center">
                    @yield('navbar')
                </ul>
            </nav>
        </div>

        @include('platform::partials.alert')
        @yield('content')
    </div>
@endsection
<script type="text/javascript">
    function incrementCampaignClick(campaign_id) {
        var url = 'https://api.promplanner.app/api/campaign_click/' + encodeURIComponent(campaign_id);
        console.log(url);
        axios.put(url)
    };
</script>