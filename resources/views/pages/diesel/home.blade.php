<?php
/**
 * Created by PhpStorm for WhelsonFuelSystem
 * User: Vincent Guyo
 * Date: 7/24/2020
 * Time: 9:04 AM
 */
?>
@extends('layouts.app')

@section('template_title')
    Welcome {{ Auth::user()->name }}
@endsection

@section('head')
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Hello, {{auth()->user()->first_name}}</h4>

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
                            <h5 class="header-title mb-4">{{date('Y')}}  Fuel Allocations Summary By Month</h5>
                            <div id="yearly-sale-chart" class="apex-charts"></div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h5 class="header-title mb-4">{{date('Y')}} Cash Sales Summary By Month</h5>
                            <div id="cash-sale-chart" class="apex-charts"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="header-title">Recent Allocation Transactions</h4>
                            <p class="card-title-desc">Last 6 Transactional dates with their respective litres issued on the particular date.</p>

                            <div class="table-responsive">
                                <table class="table table-striped mb-0">
                                    <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Petrol</th>
                                        <th>Diesel</th>
                                        <th>Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($days as $day)
                                        <tr>
                                            <td>{{$day->date}}</td>
                                            <td>{{$day->Peturu}}</td>
                                            <td>{{$day->Dhiziri}}</td>
                                            <td>{{$day->count}}</td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>

                        </div> <!-- end card-body-->
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
            </div> <!-- end row -->

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="header-title mb-4">{{date('Y')}} Special Allocations (e.g. Lockdown Duties or any other allocation) Summary By Month</h5>
                            <div id="stock-issues-chart" class="apex-charts"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-6">
                    <div class="card">
                        @if(auth()->user()->allocation == 'Allocation')
                            <div class="card-body">
                                <h4 class="header-title">My Allocation Size</h4>
                                <p class="card-title-desc">Capacity of your fuel allocation.</p>

                                <div class="text-center" dir="ltr">
                                    <label>
                                        <input data-plugin="knob" data-width="200" data-height="200" data-min="0"
                                               data-fgColor="#00a7e1" data-displayPrevious=true data-angleOffset=0
                                               data-angleArc=360 data-readOnly=true value="{{$percentAllocation}}" data-thickness=".1" />
                                    </label>
                                </div>
                                <h4 class="header-title text-center text-info">You have used {{$usedAllocation}}L out of your {{$currentAllocation->alloc_size ?? 0}}L  {{date('F Y')}} allocation.
                                </h4>
                            </div> <!-- end card-body-->
                        @else
                            <div class="card-body">
                                <h4 class="header-title">My Allocation Size</h4>
                                <p class="card-title-desc">Capacity of your fuel allocation.</p>

                                <h4 class="header-title text-center text-info">You are currently set as a Non-allocation user. Please contact Diesel Department, if this is an error.</h4>
                            </div> <!-- end card-body-->
                        @endif
                    </div> <!-- end card-->
                </div> <!-- end col -->

                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="header-title">My Fuel Allocations Distribution</h4>
                            <p class="card-title-desc mb-4">For {{date('F Y')}}</p>

                            <div id="my-morris-donut" class="morris-chart" dir="ltr"></div>

                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div> <!-- end col -->
            </div><!-- end row -->
        </div>
    </div>
@endsection
@section('footer_scripts')
    <script>
        $(function () {
            $('[data-plugin="knob"]').knob()
        });
        peturu = {!! json_encode($petrolAllocationsData) !!};
        dhiziri = {!! json_encode($dieselAllocationsdata) !!};
        var options = {
            chart: {height: 350, type: "area", toolbar: {show: 1}},
            colors: ["#3051d3", "#e4cc37"],
            dataLabels: {enabled: 1},
            series: [{name: "Petrol", data: peturu },
                {name: "Diesel", data: dhiziri}
            ],
            grid: {yaxis: {lines: {show: 1}}},
            stroke: {width: 3, curve: "stepline"},
            markers: {size: 0},
            xaxis: {
                categories: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
                title: {text: "Monthly Fuel Allocations Consumption By Month"}
            },
            fill: {type: "gradient", gradient: {shadeIntensity: 1, opacityFrom: .7, opacityTo: .9, stops: [0, 90, 100]}},
            legend: {position: "top", horizontalAlign: "right", floating: !0, offsetY: -25, offsetX: -5}
        };
        (chart = new ApexCharts(document.querySelector("#yearly-sale-chart"), options)).render();

    </script>

    <script>
        $(function () {
            $('[data-plugin="knob"]').knob()
        });
        peturu = {!! json_encode($petrolCashData) !!};
        dhiziri = {!! json_encode($dieselCashData) !!};
        var options = {
            chart: {height: 350, type: "area", toolbar: {show: 1}},
            colors: ["#121742", "#eb5b37"],
            dataLabels: {enabled: 1},
            series: [{name: "Petrol", data: peturu },
                {name: "Diesel", data: dhiziri}
            ],
            grid: {yaxis: {lines: {show: 1}}},
            stroke: {width: 3, curve: "stepline"},
            markers: {size: 0},
            xaxis: {
                categories: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
                title: {text: "Monthly Fuel Cash Sales Consumption By Month"}
            },
            fill: {type: "gradient", gradient: {shadeIntensity: 1, opacityFrom: .7, opacityTo: .9, stops: [0, 90, 100]}},
            legend: {position: "top", horizontalAlign: "right", floating: !0, offsetY: -25, offsetX: -5}
        };
        (chart = new ApexCharts(document.querySelector("#cash-sale-chart"), options)).render();

    </script>

    <script>
        $(function () {
            $('[data-plugin="knob"]').knob()
        });
        peturu = {!! json_encode($petrolStockData) !!};
        dhiziri = {!! json_encode($dieselStockData) !!};
        var options = {
            chart: {height: 350, type: "area", toolbar: {show: 1}},
            colors: ["#4c87cc", "#e32239"],
            dataLabels: {enabled: 1},
            series: [{name: "Petrol", data: peturu },
                {name: "Diesel", data: dhiziri}
            ],
            grid: {yaxis: {lines: {show: 1}}},
            stroke: {width: 3, curve: "stepline"},
            markers: {size: 0},
            xaxis: {
                categories: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
                title: {text: "Monthly Fuel Stock Issues (Special Allocations / Lockdown Duties) Consumption By Month"}
            },
            fill: {type: "gradient", gradient: {shadeIntensity: 1, opacityFrom: .7, opacityTo: .9, stops: [0, 90, 100]}},
            legend: {position: "top", horizontalAlign: "right", floating: !0, offsetY: -25, offsetX: -5}
        };
        (chart = new ApexCharts(document.querySelector("#stock-issues-chart"), options)).render();

    </script>

    <!-- Plugin Js-->
    <script src="{{asset('assets/libs/morris.js/morris.min.js')}}"></script>
    <script src="{{asset('assets/libs/jquery-knob/jquery.knob.min.js')}}"></script>

    <script src="{{asset('assets/js/pages/jquery-knob.init.js')}}"></script>
    <script src="{{asset('assets/libs/raphael/raphael.min.js')}}"></script>
    <!-- demo js-->
    <script>
        var currentPeturu = {!! $currentAllPetrol[0]->currentPetrol !!};
        var currentDhiziri = {!! $currentAllDiesel[0]->currentDiesel !!};

        var myCurrentPeturu = {!! $currentPetrol[0]->currentPetrol ?? 0!!} ;
        var myCurrentDhiziri = {!! $currentDiesel[0]->currentDiesel  ?? 0!!} ;

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
                data: [{label: "Petrol", value: currentPeturu}, {label: "Diesel", value: currentDhiziri}]
            })

            $("#my-morris-donut").length && Morris.Donut({
                element: "my-morris-donut",
                resize: !0,
                colors: ["#4c87cc", "#121742"],
                data: [{label: "Petrol", value: myCurrentPeturu}, {label: "Diesel", value: myCurrentDhiziri}]
            })
        });
    </script>
@endsection
