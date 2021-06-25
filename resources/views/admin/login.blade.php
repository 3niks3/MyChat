@extends('layouts.plain-layout')

@section('header')
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
{{--            <a class="navbar-brand" href="#">Home</a>--}}
            <h2 class="text-white">Admin Authorization</h2>
        </div>
    </nav>
@endsection

@section('content')

    <div class="row justify-content-md-center mt-5">
        <div class="col-md-6 col-sm-12">
            <div class="p-2 border border-info rounded shadow ">

                <form id="registration-form" action="{{route('admin_login_action')}}" method="post">

                    <div class="row">
                        <div class="col mb-2">
                            <h3>Admin Authorization</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-floating mb-3">
                                <input type="email" name="email" class="form-control" id="email" placeholder="name@example.com">
                                <label for="email">Email address</label>
                                <div class="invalid-feedback" data-input-field="#email"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-floating mb-3">
                                <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                                <label for="password">Password</label>
                                <div class="invalid-feedback" data-input-field="#password"></div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-outline-primary">Submit</button>
                    {!! csrf_field() !!}

                    @if(session()->has('errors'))
                        <div class="row mt-3">
                            <div class="col">
                                <div class="alert alert-danger text-center" role="alert">
                                    {{session()->get('errors')->first()}}
                                </div>
                            </div>
                        </div>
                    @endif

                </form>
            </div>
        </div>
    </div>

@endsection
