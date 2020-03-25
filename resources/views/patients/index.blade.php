@extends('layouts.app')

@section('head')
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

            <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }

            .sidebar {
                display: inline-block; width: 49%; vertical-align: text-top;
            }

            .vLine{
                border-left: solid #6c757d; height: 100%;
            }

            h2 {
                text-align: center;
            }

            .container {
                width: 98%;
            }

            .content {
                width: 100%;
            }

        </style>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="content">
            <div class="title m-b-md" style="width: 100%">
                {{ Auth::user()->name }}
            </div>
                @foreach ($groups as $key => $value)
                    <div class="sidebar {{ ($key != 1 ? 'vLine' : '') }}">
                        <h2 style="margin-bottom: 20px;">{{ $value }}</h2>
                        <ul class="style1">
                            @foreach ($patients as $patient)
                                @if ($patient->ventilator == $key)
                                    <li>
                                        <h3 style="float: left">{{ $patient->name }}</h3>
                                        <p>{{ $patient->inserted_on }}</p>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                @endforeach
        </div>
    </div>
</div>
@endsection
