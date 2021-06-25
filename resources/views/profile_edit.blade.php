@extends('layouts.master')

@section('content')

    <div class="row justify-content-md-center mt-5">
        <div class="col-md-8 col-sm-12">
            <div class="p-2 border border-info rounded shadow ">

                <form id="profile-edit-form" action="{{route('profile-edit-receiver')}}" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col" style="">
                            <h1>Profile Edit</h1>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col">
                            <div class="form-floating mb-3">
                                <input type="text" name="username" value="{{$user->username}}" class="form-control" id="username" placeholder="Username">
                                <label for="username">Username</label>
                                <div class="invalid-feedback" data-input-field="#username"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-floating mb-3">
                                <input type="email" disabled name="email" value="{{$user->email}}" class="form-control" id="email" placeholder="name@example.com">
                                <label for="email">Email address</label>
                                <div class="invalid-feedback" data-input-field="#email"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-floating mb-3">
                                <input type="text" name="name" value="{{$user->name}}" class="form-control" id="name" placeholder="John">
                                <label for="name">Name</label>
                                <div class="invalid-feedback" data-input-field="#name"></div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-floating mb-3">
                                <input type="text" name="surname" value="{{$user->surname}}" class="form-control" id="surname" placeholder="Doe">
                                <label for="surname">Surname</label>
                                <div class="invalid-feedback" data-input-field="#surname"></div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    @include('includes.fields.image_upload',['field_name' =>'avatar', 'field_label' => 'Avatar', 'value'=> (($user->avatar_url)?auth()->user()->avatar_url->getUrl():'')])

                    <button type="submit" class="btn btn-primary">Submit</button>
                    {!! csrf_field() !!}
                </form>
            </div>
        </div>
    </div>

@endsection


@push('scripts')

    <script>

        $('form#profile-edit-form').submit(function (e) {
                console.log('edit form submited');
                e.preventDefault();
                let form = $('form#profile-edit-form');

                formPoster.standardFormErrorsClear(form);
                formPoster.setCropImageValues()

                let target = $(this).attr('action');
                let form_data = formPoster.getFormDataAsJson(form);

                axios.post(target, form_data)
                    .then(function (response) {

                        let data = response.data;
                        let status = data.status || false;
                        let message = data.messages || {};
                        let redirect_target = data.target || false;

                        if (status == false) {
                            formPoster.standardFormErrorShow(form, message);
                            return false;
                        }
                        window.location.replace(redirect_target);


                    })
                    .catch(function (error) {
                        console.log(error);
                    });
        });

    </script>
@endpush
