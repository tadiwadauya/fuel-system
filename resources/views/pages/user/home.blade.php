@extends('layouts.app')

@section('template_title')
    {{ Auth::user()->name }}'s' Homepage
@endsection

@section('template_fastload_css')
@endsection


    @section('content')
        <div class="page-title-box">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <h4 class="page-title mb-1">Hello, {{auth()->user()->first_name}}</h4>

                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">

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
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="card">
                                    @if(auth()->user()->allocation == 'Allocation')
                                        <div class="card-body">
                                            <h4 class="header-title">Allocation Size</h4>
                                            <p class="card-title-desc">Capacity of your fuel allocation.</p>

                                            <div class="text-center" dir="ltr">
                                                <label>
                                                    <input data-plugin="knob" data-width="225" data-height="225" data-min="0"
                                                           data-fgColor="#00a7e1" data-displayPrevious=true data-angleOffset=0
                                                           data-angleArc=360 data-readOnly=true value="{{$percentAllocation}}" data-thickness=".1" />
                                                </label>
                                            </div>
                                            <h4 class="header-title text-center text-info">You have used {{$usedAllocation}}L out of your {{$currentAllocation->alloc_size ?? 0}}L  {{date('F Y')}} allocation
                                            </h4>
                                        </div> <!-- end card-body-->
                                    @else
                                        <div class="card-body">
                                            <h4 class="header-title">Allocation Size</h4>
                                            <p class="card-title-desc">Capacity of your fuel allocation.</p>

                                            <h4 class="header-title text-center text-info">You are currently set as a Non-allocation user. Please contact Diesel Department, if this is an error.</h4>
                                        </div> <!-- end card-body-->
                                    @endif
                                </div> <!-- end card-->
                            </div> <!-- end col -->

                            <div class="col-xl-6">
                                <div class="card">
                                    <div class="card-body">

                                        <h4 class="header-title">Fuel Allocations Distribution</h4>
                                        <p class="card-title-desc mb-4">For {{date('F Y')}}</p>

                                        <div id="morris-donut-example" class="morris-chart" dir="ltr"></div>

                                    </div> <!-- end card-body-->
                                </div> <!-- end card-->
                            </div> <!-- end col -->
                        </div><!-- end row -->
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
@section('footer_scripts')
    <!-- Plugin Js-->
    <script src="{{asset('assets/libs/morris.js/morris.min.js')}}"></script>
    <script src="{{asset('assets/libs/raphael/raphael.min.js')}}"></script>

    <script src="{{asset('assets/libs/jquery-knob/jquery.knob.min.js')}}"></script>

    <script src="{{asset('assets/js/pages/jquery-knob.init.js')}}"></script>

    <!-- demo js-->
    <script>
        var currentPeturu = {!! $currentPetrol[0]->currentPetrol ?? 0!!};
        var currentDhiziri = {!! $currentDiesel[0]->currentDiesel ?? 0!!};

        $(function () {
            "use strict";
            $("#morris-area-example").length && Morris.Area({
                element: "morris-area-example",
                lineColors: ["#3051d3", "#7c8a96"],
                data: [{y: "2013", a: 80, b: 100},
                    {y: "2014", a: 110, b: 130},
                    {y: "2015", a: 90, b: 110},
                    {y: "2016",a: 120,b: 140},
                    {y: "2017", a: 110, b: 125},
                    {y: "2018", a: 170, b: 190},
                    {y: "2019", a: 120, b: 140}
                ],
                xkey: "y",
                ykeys: ["a", "b"],
                hideHover: "auto",
                gridLineColor: "rgba(108, 120, 151, 0.1)",
                resize: !0,
                fillOpacity: .4,
                lineWidth: 2,
                labels: ["Series A", "Series B"]
            }),

            $("#morris-donut-example").length && Morris.Donut({
                element: "morris-donut-example",
                resize: !0,
                colors: ["#4c87cc", "#121742"],
                data: [{label: "Petrol", value: currentPeturu},
                    {label: "Diesel", value: currentDhiziri}]
            })
        });
    </script>
@endsection
