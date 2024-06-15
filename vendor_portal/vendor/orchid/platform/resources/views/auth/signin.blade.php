<div class="mb-3">

    <label class="form-label" style="font-size: 1.3rem; color: white;">
        {{ __('Email address') }}
    </label>

    {!! \Orchid\Screen\Fields\Input::make('email')->type('email')->required()->tabindex(1)->autofocus()->autocomplete('email')->inputmode('email')->placeholder(__('Enter your email')) !!}
</div>

<div class="mb-4">
    <label class="form-label" style="font-size: 1.3rem; color: white;">
        {{ __('Password') }}
    </label>

    {!! \Orchid\Screen\Fields\Password::make('password')->required()->autocomplete('current-password')->tabindex(2)->placeholder(__('Enter your password')) !!}
</div>

<div class="row align-items-center">
    <div class="col-md-6 col-xs-12" id="remember-me-container">
        <label class="form-check d-flex align-items-center" style="gap:8px;">
            <input type="hidden" name="remember">
            <input type="checkbox" name="remember" value="true" class="form-check-input"
                {{ !old('remember') || old('remember') === 'true' ? 'checked' : '' }}>
            <span class="form-check-label" style="font-size: 1.3rem; color: rgb(224, 224, 224);"> {{ __('Remember Me') }}</span>
        </label>
    </div>
    <div class="col-md-6 col-xs-12">
        <button id="button-login" type="submit" class="btn btn-default btn-block" tabindex="3">
            <x-orchid-icon path="login" class="small me-2" />
            {{ __('Login') }}
        </button>
    </div>
    <a href="/admin/register" class="form-label text-base mt-4 align-items-center" style="font-size:0.9rem">Don't have an account? Register here.</a>
</div>

<style>
    @media screen and (max-width:768px) {
        #remember-me-container {
            margin-bottom: 1.5rem;
        }
    }

    .mb-3 input[type='email'],
    .mb-4 input[type='password'],
    div[data-controller="password"] {
        max-width: 100% !important;
        width: 100% !important;
    }
</style>