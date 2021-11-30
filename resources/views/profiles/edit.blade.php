@extends('layouts.app')

@section('template_title')
    {{ trans('profile.templateTitle') }}
@endsection

@section('template_linked_css')

    <!-- Plugins css-->
    <link href="{{asset('assets/libs/dropzone/min/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Edit My Profile</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Profile</a></li>
                        <li class="breadcrumb-item active">Editing {{auth()->user()->name}}</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">

                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- end page title end breadcrumb -->
    <div class="page-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            @if ($user->profile)
                                @if (Auth::user()->id == $user->id)
                            <h4 class="header-title">{{auth()->user()->first_name}}'s profile</h4>
                            <p class="card-title-desc">Change my profile and account info </p>

                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="nav flex-column nav-pills" role="tablist" aria-orientation="vertical">
                                        <a class="nav-link active mb-2" id="v-pills-left-profile-tab" data-toggle="pill" href="#v-pills-left-profile" role="tab" aria-controls="v-pills-left-profile" aria-selected="false">
                                            <i class="fas fa-user mr-1"></i> Profile
                                        </a>
                                        <a class="nav-link  mb-2" id="v-pills-left-home-tab" data-toggle="pill" href="#v-pills-left-home" role="tab" aria-controls="v-pills-left-home" aria-selected="true">
                                            <i class="fas fa-home mr-1"></i> {{ trans('profile.editAccountTitle') }}
                                        </a>

                                        <a class="nav-link" id="v-pills-left-setting-tab" data-toggle="pill" href="#v-pills-left-setting" role="tab" aria-controls="v-pills-left-setting" aria-selected="false">
                                            <i class="fas fa-cog mr-1"></i> {{ trans('profile.editAccountAdminTitle') }}

                                        </a>
                                    </div>
                                </div>
                                <div class="col-sm-9">
                                    <div class="tab-content mt-4 mt-sm-0">

                                        <div class="tab-pane fade show active" id="v-pills-left-profile" role="tabpanel" aria-labelledby="v-pills-left-profile-tab">
                                            <div class="row mb-1">
                                                <div class="col-sm-12">
                                                    <div id="avatar_container">
                                                        <div class="collapseOne card-collapse collapse @if($user->profile->avatar_status == 0) show @endif">
                                                            <div class="card-body">
                                                                <img src="{{  Gravatar::get($user->email) }}" alt="{{ $user->name }}" class="user-avatar">
                                                            </div>
                                                        </div>
                                                        <div class="collapseTwo card-collapse collapse @if($user->profile->avatar_status == 1) show @endif">
                                                            <div class="card-body">
                                                                <div class="dz-preview"></div>
                                                                {!! Form::open(array('route' => 'avatar.upload', 'method' => 'POST', 'name' => 'avatarDropzone','id' => 'avatarDropzone', 'class' => 'form single-dropzone dropzone single', 'files' => true)) !!}
                                                                <img id="user_selected_avatar" class="user-avatar" src="@if ($user->profile->avatar != NULL) {{ url($user->profile->avatar) }} @endif" alt="{{ $user->name }}">
                                                                {!! Form::close() !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {!! Form::model($user->profile, ['method' => 'PATCH', 'route' => ['profile.update', $user->name], 'id' => 'user_profile_form', 'class' => 'form-horizontal', 'role' => 'form', 'enctype' => 'multipart/form-data']) !!}
                                            {{ csrf_field() }}
                                            <div class="row">
                                                <div class="col-10 offset-1 col-sm-10 offset-sm-1 mb-1">
                                                    <div class="row" data-toggle="buttons">
                                                        <div class="col-6 col-xs-6 right-btn-container">
                                                            <label class="btn btn-primary @if($user->profile->avatar_status == 0) active @endif btn-block btn-sm" data-toggle="collapse" data-target=".collapseOne:not(.show), .collapseTwo.show">
                                                                <input type="radio" name="avatar_status" id="option1" autocomplete="off" value="0" @if($user->profile->avatar_status == 0) checked @endif> Use Gravatar
                                                            </label>
                                                        </div>
                                                        <div class="col-6 col-xs-6 left-btn-container">
                                                            <label class="btn btn-primary @if($user->profile->avatar_status == 1) active @endif btn-block btn-sm" data-toggle="collapse" data-target=".collapseOne.show, .collapseTwo:not(.show)">
                                                                <input type="radio" name="avatar_status" id="option2" autocomplete="off" value="1" @if($user->profile->avatar_status == 1) checked @endif> Use My Image
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group has-feedback {{ $errors->has('location') ? ' has-error ' : '' }}">
                                                {!! Form::label('location', trans('profile.label-location') , array('class' => 'col-12 control-label')); !!}
                                                <div class="col-12">
                                                    {!! Form::text('location', old('location'), array('id' => 'location', 'class' => 'form-control', 'placeholder' => trans('profile.ph-location'))) !!}
                                                    <span class="glyphicon {{ $errors->has('location') ? ' glyphicon-asterisk ' : ' glyphicon-pencil ' }} form-control-feedback" aria-hidden="true"></span>
                                                    @if ($errors->has('location'))
                                                        <span class="help-block">
                                                                    <strong>{{ $errors->first('location') }}</strong>
                                                                </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group has-feedback {{ $errors->has('bio') ? ' has-error ' : '' }}">
                                                {!! Form::label('bio', trans('profile.label-bio') , array('class' => 'col-12 control-label')); !!}
                                                <div class="col-12">
                                                    {!! Form::textarea('bio', old('bio'), array('id' => 'bio', 'class' => 'form-control', 'placeholder' => trans('profile.ph-bio'))) !!}
                                                    <span class="glyphicon glyphicon-pencil form-control-feedback" aria-hidden="true"></span>
                                                    @if ($errors->has('bio'))
                                                        <span class="help-block">
                                                                    <strong>{{ $errors->first('bio') }}</strong>
                                                                </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group has-feedback {{ $errors->has('twitter_username') ? ' has-error ' : '' }}">
                                                {!! Form::label('twitter_username', trans('profile.label-twitter_username') , array('class' => 'col-12 control-label')); !!}
                                                <div class="col-12">
                                                    {!! Form::text('twitter_username', old('twitter_username'), array('id' => 'twitter_username', 'class' => 'form-control', 'placeholder' => trans('profile.ph-twitter_username'))) !!}
                                                    <span class="glyphicon glyphicon-pencil form-control-feedback" aria-hidden="true"></span>
                                                    @if ($errors->has('twitter_username'))
                                                        <span class="help-block">
                                                                    <strong>{{ $errors->first('twitter_username') }}</strong>
                                                                </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group margin-bottom-2">
                                                <div class="col-12">
                                                    {!! Form::button(
                                                        '<i class="fa fa-fw fa-save" aria-hidden="true"></i> ' . trans('profile.submitButton'),
                                                         array(
                                                            'id'                => 'confirmFormSave',
                                                            'class'             => 'btn btn-success disabled',
                                                            'type'              => 'button',
                                                            'data-target'       => '#confirmForm',
                                                            'data-modalClass'   => 'modal-success',
                                                            'data-toggle'       => 'modal',
                                                            'data-title'        => trans('modals.edit_user__modal_text_confirm_title'),
                                                            'data-message'      => trans('modals.edit_user__modal_text_confirm_message')
                                                    )) !!}

                                                </div>
                                            </div>
                                            {!! Form::close() !!}
                                        </div>

                                        <div class="tab-pane fade" id="v-pills-left-home" role="tabpanel" aria-labelledby="v-pills-left-home-tab">
                                            {!! Form::model($user, array('action' => array('ProfilesController@updateUserAccount', $user->id), 'method' => 'PUT', 'id' => 'user_basics_form')) !!}

                                            {!! csrf_field() !!}

                                            <div class="pt-4 pr-3 pl-2 form-group has-feedback row {{ $errors->has('name') ? ' has-error ' : '' }}">
                                                {!! Form::label('name', trans('forms.create_user_label_username'), array('class' => 'col-md-3 control-label')); !!}
                                                <div class="col-md-9">
                                                    <div class="input-group">
                                                        {!! Form::text('name', $user->name, array('id' => 'name', 'class' => 'form-control', 'placeholder' => trans('forms.create_user_ph_username'),'readonly')) !!}

                                                    </div>
                                                    @if($errors->has('name'))
                                                        <span class="help-block">
                                                                    <strong>{{ $errors->first('name') }}</strong>
                                                                </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="pt-4 pr-3 pl-2 form-group has-feedback row {{ $errors->has('paynumber') ? ' has-error ' : '' }}">
                                                {!! Form::label('paynumber', 'Paynumber', array('class' => 'col-md-3 control-label')); !!}
                                                <div class="col-md-9">
                                                    <div class="input-group">
                                                        {!! Form::text('paynumber', $user->paynumber, array('id' => 'paynumber', 'class' => 'form-control', 'placeholder' => 'e.g. 25','readonly')) !!}

                                                    </div>
                                                    @if($errors->has('paynumber'))
                                                        <span class="help-block">
                                                                    <strong>{{ $errors->first('paynumber') }}</strong>
                                                                </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="pr-3 pl-2 form-group has-feedback row {{ $errors->has('email') ? ' has-error ' : '' }}">
                                                {!! Form::label('email', trans('forms.create_user_label_email'), array('class' => 'col-md-3 control-label')); !!}
                                                <div class="col-md-9">
                                                    <div class="input-group">
                                                        {!! Form::text('email', $user->email, array('id' => 'email', 'class' => 'form-control', 'placeholder' => trans('forms.create_user_ph_email'))) !!}

                                                    </div>
                                                    @if ($errors->has('email'))
                                                        <span class="help-block">
                                                                    <strong>{{ $errors->first('email') }}</strong>
                                                                </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="pr-3 pl-2 form-group has-feedback row {{ $errors->has('first_name') ? ' has-error ' : '' }}">
                                                {!! Form::label('first_name', trans('forms.create_user_label_firstname'), array('class' => 'col-md-3 control-label')); !!}
                                                <div class="col-md-9">
                                                    <div class="input-group">
                                                        {!! Form::text('first_name', $user->first_name, array('id' => 'first_name', 'class' => 'form-control', 'placeholder' => trans('forms.create_user_ph_firstname'))) !!}

                                                    </div>
                                                    @if($errors->has('first_name'))
                                                        <span class="help-block">
                                                                    <strong>{{ $errors->first('first_name') }}</strong>
                                                                </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="pr-3 pl-2 form-group has-feedback row {{ $errors->has('last_name') ? ' has-error ' : '' }}">
                                                {!! Form::label('last_name', trans('forms.create_user_label_lastname'), array('class' => 'col-md-3 control-label')); !!}
                                                <div class="col-md-9">
                                                    <div class="input-group">
                                                        {!! Form::text('last_name', $user->last_name, array('id' => 'last_name', 'class' => 'form-control', 'placeholder' => trans('forms.create_user_ph_lastname'))) !!}

                                                    </div>
                                                    @if($errors->has('last_name'))
                                                        <span class="help-block">
                                                                    <strong>{{ $errors->first('last_name') }}</strong>
                                                                </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="pr-3 pl-2 form-group has-feedback row {{ $errors->has('mobile') ? ' has-error ' : '' }}">
                                                {!! Form::label('mobile', 'Mobile Number', array('class' => 'col-md-3 control-label')); !!}
                                                <div class="col-md-9">
                                                    <div class="input-group">
                                                        {!! Form::text('mobile', $user->mobile, array('id' => 'mobile', 'class' => 'form-control', 'placeholder' => 'e.g. 0773418009')) !!}
                                                    </div>
                                                    @if($errors->has('mobile'))
                                                        <span class="help-block">
                                                                    <strong>{{ $errors->first('mobile') }}</strong>
                                                                </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="pr-3 pl-2 form-group has-feedback row {{ $errors->has('extension') ? ' has-error ' : '' }}">
                                                {!! Form::label('extension', 'Extension', array('class' => 'col-md-3 control-label')); !!}
                                                <div class="col-md-9">
                                                    <div class="input-group">
                                                        {!! Form::text('extension', $user->extension, array('id' => 'extension', 'class' => 'form-control', 'placeholder' => 'e.g. 264')) !!}
                                                    </div>
                                                    @if($errors->has('extension'))
                                                        <span class="help-block">
                                                                    <strong>{{ $errors->first('extension') }}</strong>
                                                                </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="pr-3 pl-2 form-group has-feedback row {{ $errors->has('speeddial') ? ' has-error ' : '' }}">
                                                {!! Form::label('speeddial', 'Speed Dial', array('class' => 'col-md-3 control-label')); !!}
                                                <div class="col-md-9">
                                                    <div class="input-group">
                                                        {!! Form::text('speeddial', $user->speeddial, array('id' => 'speeddial', 'class' => 'form-control', 'placeholder' => 'e.g. 16500')) !!}
                                                    </div>
                                                    @if($errors->has('speeddial'))
                                                        <span class="help-block">
                                                                    <strong>{{ $errors->first('speeddial') }}</strong>
                                                                </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-md-9 offset-md-3">
                                                    {!! Form::button(
                                                        '<i class="fa fa-fw fa-save" aria-hidden="true"></i> ' . trans('profile.submitProfileButton'),
                                                         array(
                                                            'class'             => 'btn btn-success disabled',
                                                            'id'                => 'account_save_trigger',
                                                            'disabled'          => true,
                                                            'type'              => 'button',
                                                            'data-submit'       => trans('profile.submitProfileButton'),
                                                            'data-target'       => '#confirmForm',
                                                            'data-modalClass'   => 'modal-success',
                                                            'data-toggle'       => 'modal',
                                                            'data-title'        => trans('modals.edit_user__modal_text_confirm_title'),
                                                            'data-message'      => trans('modals.edit_user__modal_text_confirm_message')
                                                    )) !!}
                                                </div>
                                            </div>
                                            {!! Form::close() !!}
                                        </div>

                                        <div class="tab-pane fade" id="v-pills-left-setting" role="tabpanel" aria-labelledby="v-pills-left-setting-tab">
                                            <ul class="account-admin-subnav nav nav-pills nav-justified margin-bottom-3 margin-top-1">
                                                <li class="nav-item bg-info">
                                                    <a data-toggle="pill" href="#changepw" class="nav-link warning-pill-trigger text-white active" aria-selected="true">
                                                        {{ trans('profile.changePwPill') }}
                                                    </a>
                                                </li>
                                                <li class="nav-item bg-info">
                                                    <a data-toggle="pill" href="#deleteAccount" class="nav-link danger-pill-trigger text-white">
                                                        {{ trans('profile.deleteAccountPill') }}
                                                    </a>
                                                </li>
                                            </ul>
                                            <div class="tab-content">

                                                <div id="changepw" class="tab-pane fade show active">

                                                    <h3 class="margin-bottom-1 text-center text-warning">
                                                        {{ trans('profile.changePwTitle') }}
                                                    </h3>

                                                    {!! Form::model($user, array('action' => array('ProfilesController@updateUserPassword', $user->id), 'method' => 'PUT', 'autocomplete' => 'new-password')) !!}

                                                    <div class="pw-change-container margin-bottom-2">

                                                        <div class="form-group has-feedback row {{ $errors->has('password') ? ' has-error ' : '' }}">
                                                            {!! Form::label('password', trans('forms.create_user_label_password'), array('class' => 'col-md-3 control-label')); !!}
                                                            <div class="col-md-9">
                                                                {!! Form::password('password', array('id' => 'password', 'class' => 'form-control ', 'placeholder' => trans('forms.create_user_ph_password'), 'autocomplete' => 'new-password')) !!}
                                                                @if ($errors->has('password'))
                                                                    <span class="help-block">
                                                                                <strong>{{ $errors->first('password') }}</strong>
                                                                            </span>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="form-group has-feedback row {{ $errors->has('password_confirmation') ? ' has-error ' : '' }}">
                                                            {!! Form::label('password_confirmation', trans('forms.create_user_label_pw_confirmation'), array('class' => 'col-md-3 control-label')); !!}
                                                            <div class="col-md-9">
                                                                {!! Form::password('password_confirmation', array('id' => 'password_confirmation', 'class' => 'form-control', 'placeholder' => trans('forms.create_user_ph_pw_confirmation'))) !!}
                                                                <span id="pw_status"></span>
                                                                @if ($errors->has('password_confirmation'))
                                                                    <span class="help-block">
                                                                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                                                                            </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-md-9 offset-md-3">
                                                            {!! Form::button(
                                                                '<i class="fa fa-fw fa-save" aria-hidden="true"></i> ' . trans('profile.submitPWButton'),
                                                                 array(
                                                                    'class'             => 'btn btn-warning',
                                                                    'id'                => 'pw_save_trigger',
                                                                    'disabled'          => true,
                                                                    'type'              => 'button',
                                                                    'data-submit'       => trans('profile.submitButton'),
                                                                    'data-target'       => '#confirmForm',
                                                                    'data-modalClass'   => 'modal-warning',
                                                                    'data-toggle'       => 'modal',
                                                                    'data-title'        => trans('modals.edit_user__modal_text_confirm_title'),
                                                                    'data-message'      => trans('modals.edit_user__modal_text_confirm_message')
                                                            )) !!}
                                                        </div>
                                                    </div>
                                                    {!! Form::close() !!}

                                                </div>

                                                <div id="deleteAccount" class="tab-pane fade">
                                                    <h3 class="margin-bottom-1 text-center text-danger">
                                                        {{ trans('profile.deleteAccountTitle') }}
                                                    </h3>
                                                    <p class="margin-bottom-2 text-center">
                                                        <i class="fa fa-exclamation-triangle fa-fw" aria-hidden="true"></i>
                                                        <strong>Deleting</strong> your account is <u><strong>permanent</strong></u> and <u><strong>cannot</strong></u> be undone.
                                                        <i class="fa fa-exclamation-triangle fa-fw" aria-hidden="true"></i>
                                                    </p>
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-sm-6 offset-sm-3 margin-bottom-3 text-center">

                                                            {!! Form::model($user, array('action' => array('ProfilesController@deleteUserAccount', $user->id), 'method' => 'DELETE')) !!}

                                                            <div class="btn-group btn-group-vertical margin-bottom-2 custom-checkbox-fa" data-toggle="buttons">
                                                                <label class="btn no-shadow" for="checkConfirmDelete" >
                                                                    <input type="checkbox" name='checkConfirmDelete' id="checkConfirmDelete">
                                                                    <i class="fa fa-square-o fa-fw fa-2x"></i>
                                                                    <i class="fa fa-check-square-o fa-fw fa-2x"></i>
                                                                    <span class="margin-left-2"> Confirm Account Deletion</span>
                                                                </label>
                                                            </div>

                                                            {!! Form::button(
                                                                '<i class="fa fa-trash-o fa-fw" aria-hidden="true"></i> ' . trans('profile.deleteAccountBtn'),
                                                                array(
                                                                    'class'             => 'btn btn-block btn-danger',
                                                                    'id'                => 'delete_account_trigger',
                                                                    'disabled'          => true,
                                                                    'type'              => 'button',
                                                                    'data-toggle'       => 'modal',
                                                                    'data-submit'       => trans('profile.deleteAccountBtnConfirm'),
                                                                    'data-target'       => '#confirmForm',
                                                                    'data-modalClass'   => 'modal-danger',
                                                                    'data-title'        => trans('profile.deleteAccountConfirmTitle'),
                                                                    'data-message'      => trans('profile.deleteAccountConfirmMsg')
                                                                )
                                                            ) !!}

                                                            {!! Form::close() !!}

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                @else
                                    <p>{{ trans('profile.notYourProfile') }}</p>
                                @endif
                            @else
                                <p>{{ trans('profile.noProfileYet') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('modals.modal-form')

@endsection

@section('footer_scripts')

    @include('scripts.form-modal-script')

    @if(config('settings.googleMapsAPIStatus'))
        @include('scripts.gmaps-address-lookup-api3')
    @endif

    {{--@include('scripts.user-avatar-dz')--}}
    <!-- dropzone js -->
    <script src="{{asset('assets/libs/dropzone/min/dropzone.min.js')}}"></script>

    <script type="text/javascript">

        $('.dropdown-menu li a').click(function() {
            $('.dropdown-menu li').removeClass('active');
        });

        $('.profile-trigger').click(function() {
            $('.panel').alterClass('card-*', 'card-default');
        });

        $('.settings-trigger').click(function() {
            $('.panel').alterClass('card-*', 'card-info');
        });

        $('.admin-trigger').click(function() {
            $('.panel').alterClass('card-*', 'card-warning');
            $('.edit_account .nav-pills li, .edit_account .tab-pane').removeClass('active');
            $('#changepw')
                .addClass('active')
                .addClass('in');
            $('.change-pw').addClass('active');
        });

        $('.warning-pill-trigger').click(function() {
            $('.panel').alterClass('card-*', 'card-warning');
        });

        $('.danger-pill-trigger').click(function() {
            $('.panel').alterClass('card-*', 'card-danger');
        });

        $('#user_basics_form').on('keyup change', 'input, select, textarea', function(){
            $('#account_save_trigger').attr('disabled', false).removeClass('disabled').show();
        });

        $('#user_profile_form').on('keyup change', 'input, select, textarea', function(){
            $('#confirmFormSave').attr('disabled', false).removeClass('disabled').show();
        });

        $('#checkConfirmDelete').change(function() {
            var submitDelete = $('#delete_account_trigger');
            var self = $(this);

            if (self.is(':checked')) {
                submitDelete.attr('disabled', false);
            }
            else {
                submitDelete.attr('disabled', true);
            }
        });

        $("#password_confirmation").keyup(function() {
            checkPasswordMatch();
        });

        $("#password, #password_confirmation").keyup(function() {
            enableSubmitPWCheck();
        });

        $('#password, #password_confirmation').hidePassword(true);

        $('#password').password({
            shortPass: 'The password is too short',
            badPass: 'Weak - Try combining letters & numbers',
            goodPass: 'Medium - Try using special charecters',
            strongPass: 'Strong password',
            containsUsername: 'The password contains the username',
            enterPass: false,
            showPercent: false,
            showText: true,
            animate: true,
            animateSpeed: 50,
            username: false, // select the username field (selector or jQuery instance) for better password checks
            usernamePartialMatch: true,
            minimumLength: 6
        });

        function checkPasswordMatch() {
            var password = $("#password").val();
            var confirmPassword = $("#password_confirmation").val();
            if (password != confirmPassword) {
                $("#pw_status").html("Passwords do not match!");
            }
            else {
                $("#pw_status").html("Passwords match.");
            }
        }

        function enableSubmitPWCheck() {
            var password = $("#password").val();
            var confirmPassword = $("#password_confirmation").val();
            var submitChange = $('#pw_save_trigger');
            if (password != confirmPassword) {
                submitChange.attr('disabled', true);
            }
            else {
                submitChange.attr('disabled', false);
            }
        }

    </script>

@endsection
