@extends('layouts.master')

@section('content')

    <div class="row justify-content-md-center mt-5">
        <div class="col-md-8 col-sm-12">
            <div class="p-2 border border-info rounded shadow ">

                <form id="forget-password-form" action="{{route('forget_password_receiver')}}" method="post">
                    <div class="row">
                        <div class="col" style="">
                            <h1>Forget password</h1>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col">
                            <div class="form-floating mb-3">
                                <input type="email" name="email" class="form-control" id="email" placeholder="name@example.com">
                                <label for="email">Email address</label>
                                <div class="invalid-feedback" data-input-field="#email"></div>
                            </div>
                        </div>
                    </div>
                    <hr>

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
                                <div class="invalid-feedback" data-input-field="new_password_again"></div>
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
        $('form#forget-password-form').submit(function (e) {

            e.preventDefault();
            let form = $('form#forget-password-form');

            formPoster.standardFormErrorsClear(form);

            let target = $(this).attr('action');
            let form_data = formPoster.getFormData(form);

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
