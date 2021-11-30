<?php
/**
 * Created by PhpStorm for WhelsonFuelSystem
 * User: Vincent Guyo
 * Date: 4/27/2020
 * Time: 3:35 PM
 */
?>
@extends('layouts.app')

@section('template_title')
    Modify Job Title
@endsection

@section('template_linked_css')
    <link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Job Titles</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/jobtitles')}}">Job Titles</a></li>
                        <li class="breadcrumb-item active">Edit Job Title</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url('/jobtitles')}}" type="button">
                                <i class="mdi mdi-keyboard-backspace mr-1"></i>Back to job titles
                            </a>
                        </div>
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
                            {!! Form::open(array('route' => ['jobtitles.update', $jobTitle->id], 'method' => 'PUT', 'role' => 'form', 'class' => 'needs-validation')) !!}

                            {!! csrf_field() !!}

                            <div class="form-group has-feedback row {{ $errors->has('department') ? ' has-error ' : '' }}">
                                {!! Form::label('department', 'Department', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <select class="custom-select form-control dynamic" name="department" id="department">
                                            <option value="{{ $jobTitle->department }}">{{ $jobTitle->department }}</option>
                                            @if ($departments)
                                                @foreach($departments as $department)
                                                    <option value="{{ $department->department }} ">{{ $department->department }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    @if ($errors->has('department'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('department') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('jobtitle') ? ' has-error ' : '' }}">
                                {!! Form::label('jobtitle', 'Job Title', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('jobtitle', $jobTitle->jobtitle, array('id' => 'jobtitle', 'class' => 'form-control', 'placeholder' => 'e.g. Attachee')) !!}
                                        <div class="input-group-append">
                                            <label class="input-group-text" for="jobtitle">
                                                <i class="fa fa-fw fa-id-badge" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                    </div>
                                    @if ($errors->has('jobtitle'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('jobtitle') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-12 col-sm-6">
                                    {!! Form::button(trans('forms.save-changes'), array('class' => 'btn btn-success btn-block margin-bottom-1 mt-3 mb-2 btn-save','type' => 'button', 'data-toggle' => 'modal', 'data-target' => '#confirmSave', 'data-title' => trans('modals.edit_user__modal_text_confirm_title'), 'data-message' => trans('modals.edit_user__modal_text_confirm_message'))) !!}
                                </div>
                            </div>
                            {!! Form::close() !!}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('modals.modal-save')
    @include('modals.modal-delete')
@endsection

@section('footer_scripts')
    @include('scripts.delete-modal-script')
    @include('scripts.save-modal-script')
    @include('scripts.check-changed')


    <script src="{{ asset('js/select2.min.js')}}"></script>

    <script type="text/javascript">
        $("#department").select2({
            placeholder: 'Please select Department',
            allowClear:true,
        });

    </script>

@endsection
