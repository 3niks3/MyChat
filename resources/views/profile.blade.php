@extends('layouts.master')

@section('content')


    <div class="row mt-2">
        <div class="col" style="border-bottom: solid #cccccc thin">
            <h1>Profile</h1>
        </div>
    </div>

    <div class="row mt-2 ">
        <div class="col-md-12 col-lg-6">

            <div class="card">
                <div class="card-body">

                    <h2>My groups</h2>
                    @include('includes.tables.datatables-user-chat-group-list')

                </div>
            </div>

        </div>

        <div class="col-md-12 mt-2 mt-lg-0 col-lg-6 d-grid align-items-stretch">
            <div class="card">
                <div class="card-body">
                    <h1>Profile data</h1>
                </div>
            </div>
        </div>

    </div>



@endsection

