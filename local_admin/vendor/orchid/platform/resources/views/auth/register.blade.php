@extends('platform::auth')
@section('title',__('Create your account'))
@section('content')
    <h1 class="h4 text-black mb-4">{{__('Create your account')}}</h1>

    <form class="m-t-md"
          role="form"
          method="POST"
          data-controller="form"
          data-action="form#submit"
          data-form-button-animate="#button-login"
          data-form-button-text="{{ __('Loading...') }}"
          action="/admin/create">
        @csrf

        @includeWhen(!$isLockUser,'platform::auth.registerform')
    </form>
@endsection