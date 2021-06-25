<x-modal-body id="chat-group-leave-modal">

    <x-slot name="title">
        Leave Chat Group "{{$chat_group->name??''}}"
    </x-slot>

    <div class="row">
        <div class="col">
            <form id="chat_group_leave_form" method="POST" action="{{route('chat_group_leave',$chat_group->id)}}">


                <div class="mb-3">
                    <p> Leave Chat Group "{{$chat_group->name??''}}"</p>
                    <button type="submit" class="btn btn-outline-primary">Leave</button>
                    <button type="button" class="btn btn-outline-danger float-end" data-bs-dismiss="modal">Close</button>
                </div>
                <span id="chat-group-leave-error-container"></span>
                {!! csrf_field() !!}
            </form>
        </div>
    </div>

</x-modal-body>

@push('scripts')
    <script>
        $('form#chat_group_leave_form').submit(function(e){

            e.preventDefault();
            let form =  $('form#chat_group_leave_form');
            $('span#chat-group-leave-error-container').html('');

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
                        $('span#chat-group-leave-error-container').html(' <div class="alert alert-danger" role="alert">'+error+'</div>');
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
