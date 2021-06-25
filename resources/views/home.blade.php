@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col" style="border: solid black thin">
            <h1>My first page</h1>
        </div>
    </div>

@endsection

@push('modals')
    @include('modals.login-modal')
@endpush
