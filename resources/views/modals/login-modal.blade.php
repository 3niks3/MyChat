<x-modal-body id="login-modal">

    <x-slot name="title">
        Sign In
    </x-slot>

    <div class="row">
        <div class="col">
            <form id="login-form" method="POST" action="{{route('login')}}">
                <div class="mb-3">
                    <label for="email-input" class="form-label">Email address</label>
                    <input type="email" name="email" class="form-control" id="email-input" aria-describedby="emailHelp">
                </div>
                <div class="mb-3">
                    <label for="password-input" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="password-input">
                </div>
                <div class="mb-3">
                    <a href="{{route('forget_password')}}" class="btn btn-outline-secondary">Forget password</a>
                    <a href="{{route('registration')}}" class="btn btn-outline-primary">Register</a>
                    <button type="submit" class="btn btn-outline-success float-end">Sign In</button>
                </div>
                <span id="login-error-container"></span>
                {!! csrf_field() !!}



            </form>
        </div>
    </div>

</x-modal-body>

@push('scripts')
    <script>
        $('form#login-form').submit(function(e){

            e.preventDefault();
            let form =  $('form#login-form');

            $('span#login-error-container').html('');

            //formPoster.standardFormErrorsClear(form);

            let target = $(this).attr('action');
            let form_data = $(this).serialize();

            axios.post(target,form_data)
                .then(function (response) {

                    let data = response.data;
                    let status = data.status || false;
                    let message = data.messages || '';
                    let redirect_target =  data.target || false;

                    //handel errors
                    if(status == false)
                    {
                        $('span#login-error-container').html(' <div class="alert alert-danger" role="alert">'+message+'</div>');
                        return false;
                    }

                    //redirect
                    window.location.replace(redirect_target);

                })
                .catch(function (error) {
                    console.log(error);
                });

        });

    </script>
@endpush
