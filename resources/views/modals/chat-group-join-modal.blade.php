<x-modal-body id="chat-group-join-modal">

    <x-slot name="title">
        Join Chat Group "{{$chat_group->name??''}}"
    </x-slot>

    <div class="row">
        <div class="col">
            <form id="chat_group_join_form" method="POST" action="{{route('chat_group_join',$chat_group->id)}}">

                @if($chat_group->isPrivate())
                    <div class="mb-3">
                        <p>Chat group is private, only users with Join code can join this group</p>
                        <hr>
                        <label for="join_token" class="form-label">Join Code</label>
                        <input type="text" name="join_token" class="form-control" id="join_token" aria-describedby="emailHelp">
                    </div>

                @endif

                <div class="mb-3">
                    <p> Join Chat Group "{{$chat_group->name??''}}"</p>
                    <button type="submit" class="btn btn-outline-primary">Join</button>
                    <button type="button" class="btn btn-outline-danger float-end" data-bs-dismiss="modal">Close</button>
                </div>
                <span id="chat-group-join-error-container"></span>
                {!! csrf_field() !!}
            </form>
        </div>
    </div>

</x-modal-body>

@push('scripts')
    <script>
        $('form#chat_group_join_form').submit(function(e){

            e.preventDefault();
            let form =  $('form#chat_group_join_form');

            $('span#chat-group-join-error-container').html('');

            //formPoster.standardFormErrorsClear(form);

            let target = $(this).attr('action');
            let form_data = $(this).serialize();

            axios.post(target,form_data)
                .then(function (response) {

                    let data = response.data;
                    let status = data.status || false;
                    let error = data.error || '';

                    //handel errors
                    if(status == false)
                    {
                        $('span#chat-group-join-error-container').html(' <div class="alert alert-danger" role="alert">'+error+'</div>');
                        return false;
                    }

                    //Reload
                    location.reload();

                })
                .catch(function (error) {
                    console.log(error);
                });

        });

    </script>
@endpush
