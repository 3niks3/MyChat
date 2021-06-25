<div class="row mt-2 ">
    <div class="col">
        <h2>Chat Group "{{$chat_group->name}}"</h2>
    </div>
    <div class="col text-end">

        @if(auth()->user()->isMemberChatRoom($chat_group->id))

            <button class="btn btn-danger" type="button" data-bs-toggle="modal" data-bs-target="#chat-group-leave-modal">Leave</button>
        @else
            <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#chat-group-join-modal">Join</button>
        @endif

    </div>
</div>

<div class="row mt-1">
    <div class="col d-md-flex">
        <div class="flex-fill">
            <ul class="nav  nav-tabs ">
                <li class="nav-item h-100">
                    <a class="nav-link {{(Route::currentRouteName() == 'chat_group_show')?'active':''}}" aria-current="page" href="{{route('chat_group_show',$chat_group->id)}}">Chat</a>
                </li>
                <li class="nav-item h-100">
                    <a class="nav-link {{(Route::currentRouteName() == 'chat_group_users')?'active':''}}" href="{{route('chat_group_users',$chat_group->id)}}">Users</a>
                </li>
                <li class="nav-item h-100">
                    <a class="nav-link {{(Route::currentRouteName() == 'chat_group_info')?'active':''}}" href="{{route('chat_group_info',$chat_group->id)}}">Info</a>
                </li>
                @if ($chat_group->chatAdmin && auth()->user()->id == $chat_group->chatAdmin->id)
                    <li class="nav-item h-100">
                        <a class="nav-link {{(Route::currentRouteName() == 'chat_group_edit')?'active':''}}" href="{{route('chat_group_edit',$chat_group->id)}}">Edit</a>
                    </li>
                @endif
            </ul>
        </div>
        <div class="mt-3 mt-md-0">
            <table class="table table-borderless">
                <tr>
                    <td class="p-0">
                        <span class="input-group-text rounded-0 rounded-start"><strong>Access:</strong></span>
                    </td>
                    <td class="p-0">
                        <span class="form-control bg-white rounded-0 rounded-end ">{{$chat_group->type_title}}</span>
                    </td>
                </tr>

                @if($chat_group->type == 'private' && auth()->user()->isMemberChatRoom($chat_group->id))
                    <tr>
                        <td class="p-0">
                            <span class="input-group-text rounded-0 rounded-start mt-1"><strong>Join Code:</strong></span>
                        </td>
                        <td class="p-0">
                            <span class="form-control bg-white user-select-all rounded-0 rounded-end mt-1">{{$chat_group->join_token}}</span>
                        </td>
                    </tr>
                @endif
            </table>
        </div>
    </div>
</div>
