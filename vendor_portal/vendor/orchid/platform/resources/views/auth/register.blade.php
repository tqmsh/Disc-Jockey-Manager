@extends('platform::app')

@section('body-left')

    <div class="bg-dark" style="max-width: 600px; padding:4rem;" id="signin-left">
        <div class="d-flex flex-column" style="height: 100%; justify-content:space-between;">
            <a id="pp_form-header" class="d-flex justify-content-center align-items-center mb-4" style="height:max-content; gap:16px;" href="{{Dashboard::prefix()}}">
                <img src="{{ URL::asset('/image/Prom_VP_Line.png') }}"  style="height: 40px;">
                <span style="font-size:2rem;">Prom Planner</span>
            </a>

            <div class="w-100 d-flex flex-column align-items-center" style="">
                <span style="font-size: 2rem; color: white; font-weight: semibold; text-align:center; margin-bottom: 2rem;">Create your account</span>

                <form class="m-t-md"
                    role="form"
                    method="POST"
                    data-controller="form"
                    data-action="form#submit"
                    data-form-button-animate="#button-login"
                    data-form-button-text="{{ __('Loading...') }}"
                    action="/admin/create">
                    @csrf

                    @include('platform::auth.registerform')
                </form>
            </div>

            <div></div>
            
        </div>
    </div>
@endsection

@section('body-right')
    @include('platform::auth.slider.slider')
    @include('platform::auth.styles.login')
    @include('platform::auth.styles.register')
@endsection