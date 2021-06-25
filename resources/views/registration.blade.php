@extends('layouts.master')

@section('content')

    <div class="row justify-content-md-center mt-5">
        <div class="col-md-8 col-sm-12">
            <div class="p-2 border border-info rounded shadow ">

                <form id="registration-form" action="{{route('registration_receiver')}}" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col" style="">
                            <h1>Registration</h1>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col">
                            <div class="form-floating mb-3">
                                <input type="text" name="username" class="form-control" id="username" placeholder="Username">
                                <label for="username">Username</label>
                                <div class="invalid-feedback" data-input-field="#username"></div>
                            </div>
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
                                <input type="text" name="name" class="form-control" id="name" placeholder="John">
                                <label for="name">Name</label>
                                <div class="invalid-feedback" data-input-field="#name"></div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-floating mb-3">
                                <input type="text" name="surname" class="form-control" id="surname" placeholder="Doe">
                                <label for="surname">Surname</label>
                                <div class="invalid-feedback" data-input-field="#surname"></div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col">
                            <div class="form-floating mb-3">
                                <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                                <label for="password">Password</label>
                                <div class="invalid-feedback" data-input-field="#password"></div>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="password" name="password_again" class="form-control" id="password_again" placeholder="Password again">
                                <label for="password_again-input">Password again</label>
                                <div class="invalid-feedback" data-input-field="password_again"></div>
                            </div>
                        </div>
                    </div>

                    @include('includes.fields.image_upload',['field_name' =>'avatar', 'field_label' => 'Avatar'])


                    <button type="submit" class="btn btn-primary">Submit</button>
                    {!! csrf_field() !!}
                </form>
            </div>
        </div>
    </div>

@endsection

@push('modals')
    @include('modals.login-modal')
@endpush


@push('scripts')

    <script src="/js/imageUpload.js"></script>

    <script>

        $('form#registration-form').submit(function (e) {
                console.log('first 2. submit');
                e.preventDefault();
                let form = $('form#registration-form');

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
