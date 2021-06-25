@extends('layouts.master')

@section('content')

    @include('includes.menu.profile.chat-menu')

    <div class="row mt-2 ">
        <div class="col">

            <div class="card">
                <div class="card-header text-white bg-success">
                    Chat Group Info
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th colspan="2" class="text-center">
                                        General
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><strong>Created</strong></td>
                                    <td>{{$chat_group->created_at}}</td>
                                </tr>
                                <tr>
                                    <td><strong>Members</strong></td>
                                    <td>{{$chat_group->chatUsers()->count()}}</td>
                                </tr>
                                <tr>
                                    <td><strong>Admin</strong></td>
                                    <td>{{$chat_group->chatAdmin->username??''}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="col">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th colspan="2" class="text-center">
                                        Overall
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><strong>Category</strong></td>
                                    <td>{{$chat_group->chatCategory->name}}</td>
                                </tr>
                                <tr>
                                    <td><strong>Type</strong></td>
                                    <td>{{$chat_group->type_title}}</td>
                                </tr>
                                @if($chat_group->type == 'private' && auth()->user()->isMemberChatRoom($chat_group->id))
                                    <tr>
                                        <td><strong>Join code</strong></td>
                                        <td>{{$chat_group->join_token}}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td><strong>Messages</strong></td>
                                    <td>{{$chat_group->chatMessages()->count()}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

@endsection
