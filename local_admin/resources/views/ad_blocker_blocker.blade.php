<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Please disable your ad blocker</title>
    @include('ad_blocker_blocker_style')
</head>
<body class="h-screen">
    <div class="min-h-full w-full flex items-center justify-center lg:p-12" style="background-color: rgba(0, 0, 0, 0.75);">
        <div class="h-max max-w-[55rem] min-w-[8rem] border-2 rounded-xl flex items-center justify-between flex-col p-8 sm:p-12" style="background-color: rgba(0, 0, 0, 0.788);">
            <div class="flex gap-4 items-center h-max mb-8 flex-col sm:flex-row">
                <img class="h-12" src="{{ URL::asset("image/Prom_VP_Line.png") }}" alt="promplanner-logo">
                <span class="text-white font-semibold text-[2rem] text-center">Prom Planner</span>
            </div>
            
            <span class="text-white text-center mb-12 text-[1.3rem] sm:text-[2rem]">Please disable your ad blocker to continue using Prom Planner.</span>

            <div class="flex flex-col items-center w-full">
                <span class="text-[1.3rem] text-gray-400 text-center sm:text-[1.5rem]">Find out how to disable your ad blocker:</span>
                <div class="flex flex-wrap gap-4 mt-4 items-center justify-center">
                    <a target="_blank" href="https://helpcenter.getadblock.com/hc/en-us/articles/9738518103059-How-to-disable-AdBlock-on-specific-sites">
                        <button class="h-14 w-36 border-2 rounded-lg text-xl text-white">AdBlock</button>
                    </a>

                    <a target="_blank" href="https://help.adblockplus.org/hc/en-us/articles/360062860513-Remove-a-website-from-the-allowlist">
                        <button class="h-14 w-36 border-2 rounded-lg text-xl text-white">Adblock Plus</button>
                    </a>

                    <a target="_blank" href="https://github.com/gorhill/uBlock/wiki/How-to-mark-a-web-site-as-trusted#click-the-big-power-button">
                        <button class="h-14 w-36 border-2 rounded-lg text-xl text-white">uBlock Origin</button>
                    </a>
                </div>
            </div>
            <span class="text-center text-gray-400 mt-6 w-full">If your ad blocker isn't listed here, please consult your ad blocker's resources/wiki page to learn how to disable ads on a website.</span>
        </div>
    </div>

    <script src="{{ asset("js/adv-socialbar-scroll.js")}}" defer></script>
    <script src="{{ asset("js/da-redirector.js")}}" defer></script>
</body>
</html>