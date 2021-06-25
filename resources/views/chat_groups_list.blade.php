@extends('layouts.master')

@section('content')

    <div class="row mt-2 ">
        <div class="col">

            <div class="card">
                <div class="card-body">

                    <h2>Search Chat Groups</h2>
                    @include('includes.tables.datatables-chat-group-list')

                </div>
            </div>

        </div>
    </div>

@endsection
