@extends('layouts.app')

@section('template_title')
    {{ $user->name }}'s Profile
@endsection

@section('template_fastload_css')
    #map-canvas{
        min-height: 300px;
        height: 100%;
        width: 100%;
    }
@endsection

@php
    $currentUser = Auth::user()
@endphp

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">My Profile Info</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Profiles</a></li>
                        <li class="breadcrumb-item active">{{ $user->name}}</li>
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
                <div class="col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">{{ trans('profile.showProfileTitle',['username' => $user->name]) }}</h4>

                                <img class="card-img-top img-fluid" src="@if ($user->profile->avatar_status == 1) {{ $user->profile->avatar }} @else {{ Gravatar::get($user->email) }} @endif" alt="{{ $user->name }}">
                                <div class="card-body">
                                    <h4 class="card-title font-size-16 mt-0">About Me</h4>
                                    <p class="card-text">{{auth()->user()->first_name}} {{auth()->user()->last_name}}</p>
                                    <p class="card-text">{{auth()->user()->department}}</p>
                                    <p class="card-text">{{auth()->user()->position}}</p>
                                    <p class="card-text">{{auth()->user()->mobile}}</p>
                                </div>

                        </div>
                    </div>
                </div>

                <div class="col-xl-9">
                    <div class="card">
                        <div class="card-body">
                    <dl class="user-info">
                        <dt>
                            <strong>{{ trans('profile.showProfileUsername') }}</strong>
                        </dt>
                        <dd>
                            {{ $user->name }}
                        </dd>
                        <hr>
                        <dt>
                            <strong>Paynumber</strong>
                        </dt>
                        <dd>
                            {{ $user->paynumber }}
                        </dd>
                        <hr>

                        @if ($user->email && ($currentUser->id == $user->id || $currentUser->hasRole('admin')))
                            <dt>
                                <strong>{{ trans('profile.showProfileEmail') }}</strong>
                            </dt>
                            <dd>
                                {{ $user->email }}
                            </dd>
                        @endif
                        <hr>
                        <dt>
                            <strong>Allocation Type</strong>
                        </dt>
                        <dd>
                            {{ $user->allocation }}
                        </dd>
                        <hr>
                        <dt>
                            <strong>Allocation Size</strong>
                        </dt>
                        <dd>
                            {{ $user->alloc_size }}
                        </dd>
                        <hr>
                        @if ($user->profile)

                            @if ($user->profile->location)
                                <dt>
                                    <strong>{{ trans('profile.showProfileLocation') }}</strong>
                                </dt>
                                <dd>
                                    {{ $user->profile->location }} <br />

                                    @if(config('settings.googleMapsAPIStatus'))
                                        Latitude: <span id="latitude"></span> / Longitude: <span id="longitude"></span> <br />

                                        <div id="map-canvas"></div>
                                    @endif
                                </dd> <hr>
                            @endif

                            @if ($user->profile->bio && ($currentUser->id == $user->id || $currentUser->hasRole('admin')))
                                <dt>
                                    <strong>{{ trans('profile.showProfileBio') }}</strong>
                                </dt>
                                <dd>
                                    {{ $user->profile->bio }}
                                </dd>  <hr>
                            @endif

                            @if ($user->profile->twitter_username)
                                <dt>
                                    <strong>{{ trans('profile.showProfileTwitterUsername') }}</strong>
                                </dt>
                                <dd>
                                    {!! HTML::link('https://twitter.com/'.$user->profile->twitter_username, $user->profile->twitter_username, array('class' => 'twitter-link', 'target' => '_blank')) !!}
                                </dd>  <hr>
                            @endif

                            @if ($user->profile->github_username)
                                <dt>
                                    <strong>{{ trans('profile.showProfileGitHubUsername') }}</strong>
                                </dt>
                                <dd>
                                    {!! HTML::link('https://github.com/'.$user->profile->github_username, $user->profile->github_username, array('class' => 'github-link', 'target' => '_blank')) !!}
                                </dd>  <hr>
                            @endif
                        @endif

                    </dl>
                        </div>
                    </div>

                    @if ($user->profile)
                        @if ($currentUser->id == $user->id)
                            {!! HTML::icon_link(URL::to('/profile/'.$currentUser->name.'/edit'), 'fa fa-fw fa-cog', trans('titles.editProfile'), array('class' => 'btn btn-small btn-info btn-block')) !!}
                        @endif
                    @else
                        <p>
                            {{ trans('profile.noProfileYet') }}
                        </p>
                        {!! HTML::icon_link(URL::to('/profile/'.$currentUser->name.'/edit'), 'fa fa-fw fa-plus ', trans('titles.createProfile'), array('class' => 'btn btn-small btn-info btn-block')) !!}
                    @endif
                </div>

            </div>
        </div>
    </div>

@endsection

@section('footer_scripts')

    @if(config('settings.googleMapsAPIStatus'))
        @include('scripts.google-maps-geocode-and-map')
    @endif

@endsection
