@guest
    <p>Crafted with <span class="text-danger">♥</span> By the developers of Prom Planner</p>
@else

    <div class="text-center user-select-none">
        <p class="small m-0">
             <a href='https://www.linkedin.com/in/jordanstjacques/'>Designed by: Jordan St Jacques</a> <br>
             <a href='https://www.linkedin.com/in/farhan-khan-/'>Developed by: Farhan Khan</a> <br> {{ __('The application code is published under the MIT license.') }} 2016 - {{date('Y')}} <br>            <a href="http://orchid.software" target="_blank" rel="noopener">
                {{ __('Version') }}: {{\Orchid\Platform\Dashboard::VERSION}}
            </a>
        </p>
    </div>
@endguest
