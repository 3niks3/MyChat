@extends('layouts.master')
@section('content')



    @include('includes.menu.profile.chat-menu')

    <div class="row mt-2">
        <div class="col-md-9 col-sm-12">
            <div class="row">
                <div class="col d-grid align-items-stretch overflow-auto"  >

                    <div class="card">
                        <div class="card-header text-white bg-success ">
                            Chat
                        </div>
                        <div class="card-body col-md-9 col-sm-12 overflow-auto w-100 p-0" style="height: 60vh;">
                            <div class="overflow-auto w-100 p-1 h-100 d-flex flex-column pb-3"  id="chat-container"  >

                                @if($user->isMemberChatRoom($chat_group->id))
                                    @foreach($init_chat_messages as $message)

                                        @if($message->user_id == auth()->user()->id)
                                            @include('includes.chat_messages.this_user_chat_message')
                                        @else
                                            @include('includes.chat_messages.other_user_chat_message')
                                        @endif
                                    @endForeach
                                @else
                                    <h5 class="card-title mt-auto mb-auto text-center">Only members of Chat group can view messages</h5>
                                @endif

                            </div>
                        </div>
                    </div>

                </div>
            </div>

            @if($user->isMemberChatRoom($chat_group->id))
                <div class="row mt-2">
                    <div class="col">
                        <form method="Post" action="{{route('chat_group_send_message',$chat_group->id)}}" id="send-message-form">
                            <div class="form-floating">
                                <textarea class="form-control" name="message_content" placeholder="Leave a comment here" id="send-message-content" style="height: 100px"></textarea>
                                <label for="message_text">Message</label>
                            </div>
                            <p class="text-start text-danger mt-2 mb-2" id="send-message-error"></p>
                            <button type="submit" class="btn btn-primary mt-2">Send</button>
                            {!! csrf_field() !!}
                        </form>
                    </div>
                </div>
            @endif

        </div>

        <div class="col-md-3 col-sm-12 mt-2 mt-md-0 d-grid align-items-stretch">
            <div class="card " >
                <div class="card-header text-white bg-success ">
                    Users
                </div>

                <div class="card-body overflow-auto p-0"  style="height: 60vh;">
                    <ul class="list-group" id="online-users">
                        @foreach($chat_group->chatUsers as $chat_user)
                            <li class="list-group-item order-2" >

                                <i class="fas fa-circle text-secondary online-icon" id="chat-member-{{$chat_user->id}}"></i>
                                {{$chat_user->username}}
                                {{--                        {{dd($chat_user)}}--}}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3 mb-5">

    </div>



@endsection

@push('scripts')
    <script>
        chatManager.test();

        $(window).on("load", function () {
            $('#chat-container').scrollTop($('#chat-container')[0].scrollHeight);
        });

        $('form#send-message-form').submit(function(e){
            e.preventDefault();
            $('form#send-message-form p#send-message-error').text('');
            let message_text = $('form#send-message-form textarea#send-message-content').val().trim();

            if(message_text.length <= 0)
            {
                $('form#send-message-form p#send-message-error').text('Message is empty');
                return false;
            }

            //send data
            let target = $(this).attr('action');
            let form_data = $(this).serialize();

            axios.post(target,form_data)
                .then(function (response) {
                    let status =  response.data.status||false;
                    let error =  response.data.error||'Unknown error';
                    let message =  response.data.message||'';

                    if(status){
                        $('#chat-container').append( message );
                        $('#chat-container').scrollTop($('#chat-container')[0].scrollHeight);
                        $('form#send-message-form textarea#send-message-content').val('');
                    }else{
                        $('form#send-message-form p#send-message-error').text(error);
                    }

                })
                .catch(function (error) {
                    console.log(error);
                });

        })

        var content_load = false;
        var counter = 1;

        $('#chat-container').on('scroll', function() {
            let div = $(this).get(0);
            let loader = ' <div class="d-flex justify-content-center mb-4 mt-2" id="content_load_loader">\n' +
                '                        <div class="spinner-border" role="status">\n' +
                '                            <span class="visually-hidden">Loading...</span>\n' +
                '                        </div>\n' +
                '                    </div>';
            if(div.scrollTop <= div.clientHeight && !content_load) {
                content_load = true;
                $('#chat-container').prepend( loader );

                let target = '{{route('chat_group_show_load_messages',$chat_group->id)}}'
                let data = {"counter": counter, 'last_message':'{{$last_message}}'};

                axios.post(target,data)
                    .then(function (response) {
                        let no_content = response.data.no_content;
                        let html = response.data.html.trim();

                        if(html.length > 0)
                        {
                            $('#chat-container').prepend( html );
                            $('#chat-container #content_load_loader').remove();
                        }

                        if(!no_content)
                        {
                            content_load = false;
                            counter = counter +1;
                        }else{
                            console.log('no nentent left');
                            $('#chat-container #content_load_loader').remove();
                        }

                    })
                    .catch(function (error) {
                        console.log(error);
                    });

                console.log('scroll and load');
            }
            //console.log([div.scrollTop, div.clientHeight, div.scrollTop - div.clientHeight, div.scrollHeight]);
        });

        //Websocket events
        var test_channel = Echo.channel(`test`)
            .listen('Test', (e) => {
                console.log(e);
            });

        //chatManager.chatMemberOnline(100, 'test', 'join');

        var chat_room_channel = Echo.join(`chat-room.`+{{$chat_group->id}})
            .here((users) => {

                $.each(users, function(id, data){
                    let user_id = data.id || null;
                    let username = data.username || null;

                    chatManager.chatMemberOnline(user_id, username, 'join');
                });
                console.log(users);
            })
            .joining((user) => {
                let user_id = user.id || null;
                let username = user.username || null;

                chatManager.chatMemberOnline(user_id, username, 'join');
            })
            .leaving((user) => {
                let user_id = user.id || null;
                let username = user.username || null;

                chatManager.chatMemberOnline(user_id, username, 'leave');
            })
            .listen('.send_message', (e) => {
                let message = e.chat_message||''
                let scroll_bottom = false;

                if(message.trim().length > 0)
                {
                    chatManager.freshActionScrollBottom(function(){
                        $('#chat-container').append( message );
                    });
                }

                console.log([e, '12312312']);
            })
            .listen('.chat_member_left', (user) => {
                let user_id = user.user_id || null;
                let username = user.username || null;

                chatManager.chatMemberOnline(user_id, username, 'left_group');
            })
            .listenForWhisper('typing', (e) => {
                let username = e.username||'';
                let scroll_bottom = false;
                let typing_loader = '<div class="m-1 order-1 '+username+'_typing_container">\n' +
                    '                                <strong>Typing...</strong>\n' +
                    '                                <div class="spinner-grow spinner-grow-sm" role="status">\n' +
                    '                                    <span class="visually-hidden">Loading...</span>\n' +
                    '                                </div>\n' +
                    '                                <div class="spinner-grow spinner-grow-sm" role="status">\n' +
                    '                                    <span class="visually-hidden">Loading...</span>\n' +
                    '                                </div>\n' +
                    '                                <div class="spinner-grow spinner-grow-sm" role="status">\n' +
                    '                                    <span class="visually-hidden">Loading...</span>\n' +
                    '                                </div>\n' +
                    '                                <strong>'+username+'</strong>\n' +
                    '                            </div>';

                chatManager.freshActionScrollBottom(function(){
                    $('#chat-container').append( typing_loader );
                });



                console.log(['typing ', e]);
            })
            .listenForWhisper('stop_typing', (e) => {
            let username = e.username||'';
            let search_class = username+'_typing_container';

            $('div.'+search_class).remove();
            console.log('stop_typing ');
        })
            .error((error) => {
                console.error(error);
            });

        var notifications_channel = Echo.private('notifications.'+{{auth()->user()->id}})
            .listen('.noty', (e) => {
                let message = e.message || '';
                let type = e.type || 'info';

                new Noty({
                    type: type,
                    text: message,
                }).show();
            });


        $('textarea#send-message-content').focus(function(){
            chat_room_channel.whisper('typing', {
                username: '{{$user->username}}'
            });
        }).focusout(function(){
            chat_room_channel.whisper('stop_typing', {
                username: '{{$user->username}}'
            });
        })
    </script>
@endpush

@push('modals')

    @if($user->isMemberChatRoom($chat_group->id))
        @include('modals.chat-group-leave-modal',['chat_group' => $chat_group])
    @else
        @include('modals.chat-group-join-modal',['chat_group' => $chat_group])
    @endif

@endpush
