@extends('layouts.master')

@section('content')

    <div class="row mt-2 ">
        <div class="col">

            <div class="card col-lg-6">
                <div class="card-body">

                    <h2>Chat group create</h2>
                    <div class="row">
                        <div class="col">
                            <form id="chat_group_create_form" action="{{route('chat_group_create_receiver')}}" method="post">

                                <div class="row">
                                    <div class="col">
                                        <div class="form-floating mb-3">
                                            <input type="text" name="name" class="form-control" id="name" placeholder="Chat group name">
                                            <label for="name">Chat group name</label>
                                            <div class="invalid-feedback" data-input-field="#name"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-floating mb-3">
                                            <select class="form-select" id="chat_group_category" name="chat_group_category" aria-label="Chat group category">
                                                <option value="">-</option>
                                                @foreach($categories as $category)
                                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                                @endforeach
                                            </select>
                                            <label for="chat_group_category">Chat group category</label>
                                            <div class="invalid-feedback" data-input-field="#chat_group_category"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-floating mb-3">
                                            <select class="form-select" id="chat_group_type" name="chat_group_type" aria-label="Chat group type">
                                                <option value="">-</option>
                                                @foreach(config('chat-groups.chat-groups.types') as $type => $title)
                                                    <option value="{{$type}}">{{$title}}</option>
                                                @endforeach
                                            </select>
                                            <label for="chat_group_type">Chat group type</label>
                                            <div class="invalid-feedback" data-input-field="#chat_group_type"></div>
                                        </div>
                                        <p class="text-secondary">Private type will create join token which could be used for joining chat group</p>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">Create</button>
                                {!! csrf_field() !!}
                            </form>


                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $('form#chat_group_create_form').submit(function(e){

            e.preventDefault();
            let form =  $('form#chat_group_create_form');

            formPoster.standardFormErrorsClear(form);

            let target = $(this).attr('action');
            let form_data = $(this).serialize();

            axios.post(target,form_data)
                .then(function (response) {

                    console.log(response)

                    let data = response.data;
                    let status = data.status || false;
                    let message = data.messages || {};
                    let redirect_target =  data.target || false;

                    // return false;

                    if(status == false)
                    {
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
