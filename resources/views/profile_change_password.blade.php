@extends('layouts.master')

@section('content')

    <div class="row justify-content-md-center mt-5">
        <div class="col-md-8 col-sm-12">
            <div class="p-2 border border-info rounded shadow ">

                <form id="password-change-form" action="{{route('profile-change-password-receiver')}}" method="post">
                    <div class="row">
                        <div class="col" style="">
                            <h1>Password Change</h1>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col">
                            <div class="form-floating mb-3">
                                <input type="password" name="current_password" class="form-control" id="current_password" placeholder="Current Password">
                                <label for="username">Current Password</label>
                                <div class="invalid-feedback" data-input-field="#current_password"></div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col">
                            <div class="form-floating mb-3">
                                <input type="password" name="new_password" class="form-control" id="new_password" placeholder="New Password">
                                <label for="new_password">New Password</label>
                                <div class="invalid-feedback" data-input-field="#new_password"></div>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="password" name="new_password_again" class="form-control" id="new_password_again" placeholder="New Password again">
                                <label for="new_password_again">New Password again</label>
                                <div class="invalid-feedback" data-input-field="#new_password_again"></div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                    {!! csrf_field() !!}
                </form>
            </div>
        </div>
    </div>

@endsection



@push('scripts')


    <script>

        $('form#password-change-form').submit(function (e) {
                e.preventDefault();
                let form = $('form#password-change-form');

                formPoster.standardFormErrorsClear(form);

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
