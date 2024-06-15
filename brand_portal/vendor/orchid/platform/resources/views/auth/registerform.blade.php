@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="row" style="width:490px">
    <div class="col-sm-6" style="width:245px">
        <h1 class="text-black mb-3" style="font-size: 18px">{{__('Personal Information')}}</h1>
        <div class="mb-3">
            
            <label class="form-label">
                {{ __('First Name') }}
            </label>
            
            {!! \Orchid\Screen\Fields\Input::make('firstname')->type('text')->required()->tabindex(1)->autofocus()->autocomplete('firstname')->placeholder(__('Enter your first name')) !!}
        </div>

        <div class="mb-3">
            
            <label class="form-label">
                {{ __('Last Name') }}
            </label>
            
            {!! \Orchid\Screen\Fields\Input::make('lastname')->type('text')->required()->tabindex(1)->autofocus()->placeholder(__('Enter your last name')) !!}
        </div>

        <div class="mb-3">

            <label class="form-label">
                {{ __('Username') }}
            </label>

            {!! \Orchid\Screen\Fields\Input::make('name')->type('text')->required()->tabindex(1)->autofocus()->placeholder(__('Enter your username')) !!}
        </div>

        <div class="mb-3">
            <label class="form-label w-100">
                {{ __('Password') }}
            </label>

            {!! \Orchid\Screen\Fields\Password::make('password')->required()->tabindex(2)->autofocus()->placeholder(__('Create your password')) !!}
        </div>

        <div class="mb-3">
            <label class="form-label w-100">
                {{ __('Confirm Password') }}
            </label>

            {!! \Orchid\Screen\Fields\Password::make('password_confirmation')->required()->autofocus()->tabindex(2)->placeholder(__('Enter your password again')) !!}
        </div>
    </div>
    <div class="col-sm-6" style="width:245px">
        <h1 class="text-black mb-3" style="font-size: 18px">{{__('Business Information')}}</h1>
        <div class="mb-3">

            <label class="form-label">
                {{ __('Company Name') }}
            </label>

            {!! \Orchid\Screen\Fields\Input::make('company_name')->type('text')->required()->tabindex(1)->autofocus()->placeholder(__('Enter your company name')) !!}
        </div>

        <div class="mb-3">

            <label class="form-label">
                {{ __('Company Website') }}
            </label>

            {!! \Orchid\Screen\Fields\Input::make('website')->type('url')->tabindex(1)->autofocus()->placeholder(__('Enter your company website')) !!}
        </div>

        <div class="mb-3">

            <label class="form-label">
                {{ __('Company Email') }}
            </label>

            {!! \Orchid\Screen\Fields\Input::make('email')->type('email')->required()->tabindex(1)->autofocus()->inputmode('email')->placeholder(__('Enter your company email')) !!}
        </div>

        <div class="mb-3">

            <label class="form-label">
                {{ __('Phone Number') }}
            </label>

            {!! \Orchid\Screen\Fields\Input::make('phonenumber')->type('text')->required()->mask('(999) 999-9999')->tabindex(1)->autofocus()->placeholder(__('Enter your company phone number')) !!}
        </div>

        <div class="mb-3">

            <label class="form-label">
                {{ __('Address') }}
            </label>

            {!! \Orchid\Screen\Fields\Input::make('address')->type('text')->required()->tabindex(1)->autofocus()->placeholder(__('Enter your company address')) !!}
        </div>

        <div class="mb-3">

            <label class="form-label">
                {{ __('Category') }}
            </label>

            {!! \Orchid\Screen\Fields\Select::make('category_id')->fromQuery(App\Models\Categories::query()->where('status', 1), 'name')->autofocus()->empty('Start typing to search...') !!}
        </div>

        <div class="mb-3">

            <label class="form-label">
                {{ __('Country') }}
            </label>

            {!! \Orchid\Screen\Fields\Select::make('country')->options(['Canada' => 'Canada', 'USA'=>'USA'])->autofocus()->empty('Start typing to search...') !!}
        </div>


        <div class="mb-3">

            <label class="form-label">
                {{ __('State/Province') }}
            </label>

            {!! \Orchid\Screen\Fields\Select::make('state_province')->fromModel(App\Models\School::class, 'state_province', 'state_province')->allowAdd()->autofocus()->empty('Start typing to search...')!!}
        </div>

        <div class="mb-3">

            <label class="form-label">
                {{ __('City') }}
            </label>

            {!! \Orchid\Screen\Fields\Input::make('city')->type('text')->required()->tabindex(1)->autofocus()->placeholder(__('Enter your city')) !!}
        </div>

        <div class="mb-3">

            <label class="form-label">
                {{ __('Zip/Postal') }}
            </label>

            {!! \Orchid\Screen\Fields\Input::make('zip_postal')->type('text')->required()->tabindex(1)->autofocus()->placeholder(__('Enter your Zip/Postal Code')) !!}
        </div>
    </div>    
    
    <div id="button-div">
        <button id="button-login" type="submit" class="btn btn-default btn-block" tabindex="3">
            <x-orchid-icon path="check" class="small me-2" />
            {{ __('Register') }}
        </button>
        <a href="/admin/login" class="form-label text-base mt-4 align-items-center" style="font-size:0.9rem">Already have an account? Sign-in here.</a>
    </div>
</div>
