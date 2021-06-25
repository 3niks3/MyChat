@extends('layouts.master')

@section('content')

    @include('includes.menu.profile.chat-menu')

    <div class="row mt-2 ">
        <div class="col">

                    @include('includes.tables.datatables-chat_group_members')

        </div>
    </div>

@endsection
