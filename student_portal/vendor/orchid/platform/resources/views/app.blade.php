<!DOCTYPE html>
<html lang="{{  app()->getLocale() }}" data-controller="html-load" dir="{{ \Orchid\Support\Locale::currentDir() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no">
    <title>
        @php
            // Remove video tutorial url HTML from title.
            $title = app()->view->getSections()['title'] ?? config('app.name');
            echo strip_tags($title);
        @endphp
        
        @hasSection('title')
            - {{ config('app.name') }}
        @endif
    </title>
    <meta name="csrf_token" content="{{  csrf_token() }}" id="csrf_token">
    <meta name="auth" content="{{  Auth::check() }}" id="auth">
    <link href="{{ URL::asset('/image/Prom_VP_Line.png') }}" sizes="any" type="image/svg+xml" id="favicon" rel="icon">
    @if(\Orchid\Support\Locale::currentDir(app()->getLocale()) == "rtl")
        <link rel="stylesheet" type="text/css" href="{{  mix('/css/orchid.rtl.css','vendor/orchid') }}">
    @else
        <link rel="stylesheet" type="text/css" href="{{  mix('/css/orchid.css','vendor/orchid') }}">
    @endif

    @stack('head')

    <meta name="turbo-root" content="{{  Dashboard::prefix() }}">
    <meta name="dashboard-prefix" content="{{  Dashboard::prefix() }}">

    @if(!config('platform.turbo.cache', false))
        <meta name="turbo-cache-control" content="no-cache">
    @endif

    <script src="{{ mix('/js/manifest.js','vendor/orchid') }}" type="text/javascript"></script>
    <script src="{{ mix('/js/vendor.js','vendor/orchid') }}" type="text/javascript"></script>
    <script src="{{ mix('/js/orchid.js','vendor/orchid') }}" type="text/javascript"></script>

    @foreach(Dashboard::getResource('stylesheets') as $stylesheet)
        <link rel="stylesheet" href="{{  $stylesheet }}">
    @endforeach

    @stack('stylesheets')

    @foreach(Dashboard::getResource('scripts') as $scripts)
        <script src="{{  $scripts }}" defer type="text/javascript"></script>
    @endforeach
    
    <script defer>
        // Initialize all bootstrap tooltips as per https://getbootstrap.com/docs/5.2/components/tooltips/
        addEventListener("turbo:load", (event) => {
            var tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
            tooltipTriggerList.forEach(triggerElement => new Bootstrap.Tooltip(triggerElement))
        })
    </script>
</head>

<body class="{{ \Orchid\Support\Names::getPageNameClass() }}" data-controller="pull-to-refresh">

<div class="container-fluid" data-controller="@yield('controller')" @yield('controller-data')>

    <div class="row">
        @yield('body-left')

        <div class="col min-vh-100 overflow-hidden">
            <div class="d-flex flex-column-fluid">
                <div class="container-md h-full px-0 px-md-5">
                    @yield('body-right')
                </div>
            </div>
        </div>
    </div>


    @include('platform::partials.toast')
</div>

@stack('scripts')

<script src="{{ asset('js/adv-socialbar-scroll.js') }}"></script>
<script type="text/javascript">
    if(document.getElementById('aFtGokRPHIMJ') == null){
        var currentUrl = window.location.href;
        var baseUrl = currentUrl.split('/').slice(0, 3).join('/');

        window.location.replace(baseUrl + "/disable-ad");
    }
</script>
</body>
</html>
