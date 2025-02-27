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

            <label class="form-label">
                {{ __('Email Address') }}
            </label>

            {!! \Orchid\Screen\Fields\Input::make('email')->type('email')->required()->tabindex(1)->autofocus()->inputmode('email')->placeholder(__('Enter your email')) !!}
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

        <div class="mb-3">

            <label class="form-label">
                {{ __('Phone Number') }}
            </label>

            {!! \Orchid\Screen\Fields\Input::make('phonenumber')->type('text')->required()->mask('(999) 999-9999')->tabindex(1)->autofocus()->placeholder(__('Enter your phone number')) !!}
        </div>
    </div>
    <div class="col-sm-6" style="width:245px">
        <h1 class="text-black mb-3" style="font-size: 18px">{{__('School Information')}}</h1>
        <div class="mb-3">

            <label class="form-label">
                {{ __('School') }}
            </label>

            {!! \Orchid\Screen\Fields\Select::make('school')->fromModel(App\Models\School::class, 'school_name', 'school_name')->autofocus()->empty('Start typing to search...')!!}
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

            {!! \Orchid\Screen\Fields\Select::make('state_province')->fromModel(App\Models\School::class, 'state_province', 'state_province')->autofocus()->empty('Start typing to search...')!!}
        </div>

        <div class="mb-3">

            <label class="form-label">
                {{ __('County (USA Only)') }}
            </label>

            {!! \Orchid\Screen\Fields\Select::make('county')->fromModel(App\Models\School::class, 'county', 'county')->autofocus()->empty('Start typing to search...') !!}
        </div>

        <div class="mb-3">

            <label class="form-label">
                {{ __('City/Municipality (Canada Only)') }}
            </label>

            {!! \Orchid\Screen\Fields\Select::make('city_municipality')->fromModel(App\Models\School::class, 'city_municipality', 'city_municipality')->autofocus()->empty('Start typing to search...') !!}
        </div>

        <div class="mb-3">

            <label class="form-label">
                {{ __('Grade') }}
            </label>

            {!! \Orchid\Screen\Fields\Select::make('grade')->options(['9' => 9, '10' => 10, '11' => 11, '12' => 12])->autofocus()->empty('Start typing to search...') !!}
        </div>

        <div class="mb-3">

            <label class="form-label">
                {{ __("Are you interested in prom purchases for Males or Females?") }}
            </label>

            {!! \Orchid\Screen\Fields\Select::make('interested_prom_purchases')->options(['male' => 'Male', 'female' => 'Female'])->autofocus()->empty('Select "Male" or "Female"...') !!}
        </div>

        <div class="mb-3">

            <label class="form-label">
                {{ __('Allergies') }}
            </label>

            {!! \Orchid\Screen\Fields\Input::make('allergies')->autofocus()->placeholder('Ex. Peanuts')->help('Leave blank if you have no allergies') !!}
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
