<div class="layout v-md-center">
    <header class="d-none d-md-block col-xs-12 col-md p-0">
        <h1 class="m-0 fw-light h3 text-black">Prom Planner</h1>
        <small class="text-muted" title="How to contact us">How To Contact Us</small>
    </header>
    <nav class="col-xs-12 col-md-auto ms-auto p-0">
        <ul class="nav command-bar d-flex align-items-center">
            <li class="mx-2" id="reportabug">
                <a class="btn btn-lg btn-link fw-bold" href="{{ route('platform.bug-reports.list') }}">
                    Report a Bug
                </a>
            </li>
            <li class="mx-2" id="roadmap">
                <a class="btn btn-lg btn-link fw-bold" href="https://trello.com/b/QXQ87ydM/prom-planner-schools" target="_blank">
                    Roadmap
                </a>
            </li>

            <li class="mx-2" id="startbutton">
                <a class="btn btn-lg btn-link fw-bold" id="start" onclick="tour.start()">Start tour</a>
            </li>


            <li class="mx-lg-2" id="socials">
                <a class="btn btn-icon" href="https://www.facebook.com/promplannerapp" target="_blank">
                    {{-- .commandbar .btn style width: 100% overrides btn-icon width: 34px so we restore it on svg element --}}
                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" width="34px" viewBox="0 0 320 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"/></svg>
                </a>
            </li>



        </ul>
    </nav>
</div>
