<?php

namespace App\Http\Controllers;

use App\Classes\Alert;
use App\Events\Test;
use App\Events\Websockets\NotyAllert;
use App\Events\Websockets\SendChatMessage;
use App\Events\Websockets\UserLeftChatGroup;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ChatGroup;
use App\Models\ChatMessage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ChatGroupsController extends Controller
{
    public function list()
    {
        //dd('list');
        return view('chat_groups_list');
    }

    public function show(ChatGroup $chat_group)
    {
        $user = auth('web')->user();
        $init_chat_messages = $chat_group->chatMessages()
            ->with('user')
            ->orderBy('created_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->take(20)
            ->get()
            ->reverse();

        $last_message = $init_chat_messages->max('id');

        //dd($init_chat_messages->pluck('id'), $latest_message);


        return view('chat_group_show', [
            'chat_group' => $chat_group,
            'init_chat_messages' => $init_chat_messages,
            'user' => $user,
            'last_message' => $last_message
        ]);
    }

    public function users(ChatGroup $chat_group)
    {
        $user = auth('web')->user();

        return view('chat_groups_users', [
            'chat_group' => $chat_group,
            'user' => $user,
        ]);
    }

    public function info(ChatGroup $chat_group)
    {
        return view('chat_groups_info',['chat_group' => $chat_group]);
    }

    public function createChatGroup()
    {
        return view('chat_group_create', ['categories' => Category::all()]);
    }

    public function edit(ChatGroup $chat_group)
    {
        return view('chat_group_edit',['chat_group' => $chat_group, 'categories' => Category::all()]);
    }

    public function createChatGroupReceiver()
    {
        $validator = \Validator::make(request()->all(), [
            'name' => 'required|min:3|max:240',
            'chat_group_category' => 'required|exists:categories,id',
            'chat_group_type' => ['required', Rule::in(array_keys(config('chat-groups.chat-groups.types') ))],
        ]);

        if($validator->fails())
        {
            $message = $validator->messages()->getMessages();
            return response()->json(['status' => false, 'messages' => $message]);
        }

        $join_token = (request()->chat_group_type == 'private')?Str::random('15'):null;

        $chat_group = ChatGroup::create([
            'name' => request()->name,
            'category' =>request()->chat_group_category,
            'type' =>request()->chat_group_type,
            'join_token' => $join_token,
        ]);

        $user = auth('web')->user();
        $chat_group->chatUsers()->attach($user->id, ['role' => 'admin']);

        \Alert::success('Chat group has been created')->flash();
        return response()->json(['status' => true, 'messages' => [], 'target' => route('chat_group_show',$chat_group->id)]);
    }

    public function chatGroupEditReceiver(ChatGroup $chat_group)
    {
        $validator = \Validator::make(request()->all(), [
            'name' => 'required|min:3|max:240',
            'chat_group_category' => 'required|exists:categories,id',
            'chat_group_type' => ['required', Rule::in(array_keys(config('chat-groups.chat-groups.types') ))],
        ]);

        if($validator->fails())
        {
            $message = $validator->messages()->getMessages();
            return response()->json(['status' => false, 'messages' => $message]);
        }

        $chat_group->name = trim(request()->name);
        $chat_group->category = trim(request()->chat_group_category);
        $chat_group->type = trim(request()->chat_group_type);

        switch(true)
        {
            case($chat_group->type == 'private' && $chat_group->getOriginal('type') != $chat_group->type):
                $join_token = Str::random('15');
                $chat_group->join_token = $join_token;
                break;
            case($chat_group->type == 'public' && $chat_group->getOriginal('type') != $chat_group->type):
                $chat_group->join_token = null;
                break;
        }

        $chat_group->save();

        \Alert::success('Chat group information has been updated')->flash();
        return response()->json(['status' => true, 'messages' => [], 'target' =>  route('chat_group_show',$chat_group->id)]);
    }

    public function sendMessage(ChatGroup $chat_group)
    {
        $user = auth()->user();
        $error = null;
        $error_message = null;
        $chat_message_content = trim(request()->message_content);



        switch(true)
        {
            case(!$user->isMemberChatRoom($chat_group->id)):
                $error = true;
                $error_message = 'You are not member of chat room';
                break;
            case(empty(trim(request()->message_content))):
                $error = true;
                $error_message = 'Message is empty';
                break;
        }

        if($error)
        {
            $response = ['status' => false, 'error' => $error_message];
            return response()->json( $response );
        }

        //save message
        $chat_message = ChatMessage::create([
            'user_id' => $user->id,
            'chat_group_id' => $chat_group->id,
            'content' => $chat_message_content,
            'type' => 'message',
        ]);
        //generate html
        $html = $chat_message->createMessageHtml('this');
        //respond

        $response = ['status' => true, 'message' => $html];

        broadcast(new SendChatMessage($chat_message))->toOthers();
        return response()->json( $response );
    }

    public function loadMessages(ChatGroup $chat_group)
    {
        $counter = request()->counter;
        $last_message = request()->last_message;

        $messages_in_page = 20;
        $offset = $messages_in_page * $counter;
        $html = '';
        $no_content = false;

        $chat_messages = $chat_group->chatMessages()
            ->where('id','<=',$last_message)
            ->orderBy('created_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->offset($offset)
            ->take($messages_in_page)
            ->get()
            ->reverse();

        foreach($chat_messages as $message)
        {
            if($message->user_id == auth()->user()->id){
                $html .= view('includes.chat_messages.this_user_chat_message', ['message' => $message]);
            }else{
                $html .= view('includes.chat_messages.other_user_chat_message', ['message' => $message]);
            }
        }

        if($chat_messages->count() == 0)
        {
            $no_content = true;
        }

        return response()->json( array('no_content' => $no_content, 'html'=>$html) );

    }

    public function leaveGroup(ChatGroup $chat_group)
    {
        $user = auth()->user();

        switch(true)
        {
            case($chat_group->chatAdmin && $chat_group->chatAdmin->id == $user->id):
                $response = ['status' => false, 'error' => 'Admin cannot leave his administrated group'];
                return response()->json( $response );
                break;

        }

        $user->chatGroups()->detach($chat_group->id);
        \Alert::success('You have left this Chat Group')->flash();
        broadcast(new UserLeftChatGroup($chat_group, $user))->toOthers();
        return response()->json( ['status' => true] );
    }

    public function joinGroup(ChatGroup $chat_group)
    {
        $join_token = request()->join_token??null;
        $user = auth()->user();

        switch(true)
        {
            case($chat_group->isPrivate() && $join_token != $chat_group->join_token):
                $response = ['status' => false, 'error' => 'Incorrect Join Code'];
                return response()->json( $response );
                break;
            case($user->isMemberChatRoom($chat_group->id)):
                \Alert::info('You are already member of this chat group')->flash();
                return response()->json( ['status' => true] );
                break;

        }

        $user->chatGroups()->save($chat_group, ['role' => 'member']);
        \Alert::success('You have join this Chat Group')->flash();
        return response()->json( ['status' => true] );
    }

    /*
     * Datatables actions
     */

    public function dataTablesUserChatGroupSearch()
    {
        $user = auth()->user();
        $data = $user->chatGroups()
            ->leftJoin('categories', 'chat_group.category', '=', 'categories.id')
            ->selectRaw('chat_group.*, user_has_chat_group.role, user_has_chat_group.created_at as join_date, categories.name as cat_name');

        //default order
        if(!request()->has('order'))
        {
            $data->orderBy('chat_group.created_at', 'desc')->orderBy('chat_group.id', 'desc');
        }

        $datatable_filter_role = request()->datatable_filter_role??'';
        $datatables_filter_type = request()->datatables_filter_type??'';

        if(request()->datatable_filter_role??false)
        {
            $data->wherePivot('role', request()->datatable_filter_role);
        }

        if(request()->datatables_filter_type??false)
        {
            $data->where('type', request()->datatables_filter_type);
        }

        //dd($data->count(), $datatables_filter_type, $datatable_filter_role, request()->all(), request()->datatable_filter_role);

        $dataTable = datatables()->eloquent($data)->only(['id','name','category','type','role', 'created_at', 'updated_at', 'joined'])
            ->addColumn('name', function ($object) {
                return '<a href="'.route('chat_group_show',$object->id).'" class="link-success">'.$object->name.'</a>';
            })
            ->addColumn('created_at', function ($object) {
                return $object->created_at->diffForHumans();
            })
            ->addColumn('joined', function ($object) {
                return \Carbon\Carbon::createFromTimeStamp(strtotime($object->join_date))->diffForHumans();
            })
            ->addColumn('category', function ($object) {
                return $object->cat_name;
            })
            ->addColumn('role', function ($object) {

                switch(true)
                {
                    case ($object->role == 'member'):
                        return '<span class="badge bg-secondary">Member</span>';
                        break;
                    case ($object->role == 'mod'):
                        return '<span class="badge bg-primary">Moderator</span>';
                        break;
                    case ($object->role == 'admin'):
                        return '<span class="badge bg-success">Admin</span>';
                        break;
                    default:
                        return '<span class="badge bg-danger">Guest</span>';
                        break;
                }
            })
            ->filterColumn('name', function($query, $keyword) {
                $query->where('name','like', "%{$keyword}%");
            })
            ->orderColumn('name', function ($query, $order) {
                $query->orderBy('name', $order);
            })
            ->orderColumn('category', function ($query, $order) {
                $query->orderBy('categories.name', $order);
            })
            ->rawColumns(['ids', 'name','role'])
            ->make();

        return $dataTable;
    }

    public function dataTablesChatGroupSearch()
    {
        $user = auth()->user();
        $data = ChatGroup::with('chatUsers')
            ->leftJoin('categories', 'chat_group.category', '=', 'categories.id')
            ->selectRaw('chat_group.*, categories.name as cat_name')
            ->withCount('chatUsers');

        //default order
        if(!request()->has('order'))
        {
            $data->orderBy('created_at', 'desc')->orderBy('id', 'desc');
        }

        //filters
        if(request()->datatables_filter_groups??false)
        {
            switch(true)
            {
                case(request()->datatables_filter_groups == 'myGroups'):
                    $data->whereHas('chatUsers', function ($query) use($user) {
                        $query->where('user_id', $user->id);
                    }, '>=', 1);
                    break;

                case(request()->datatables_filter_groups == 'newGroups'):
                    $data->whereHas('chatUsers', function ($query) use($user) {
                        $query->where('user_id', $user->id);
                    }, '=', 0);
                    break;

            }

        }

        if(request()->datatables_filter_type??false)
        {
            $data->where('type', request()->datatables_filter_type);
        }

        $dataTable = datatables()->eloquent($data)->only(['id','name','category','type','role', 'created_at', 'updated_at', 'chat_members'])
            ->addColumn('name', function ($object) {
                return '<a href="'.route('chat_group_show',$object->id).'" class="link-success">'.$object->name.'</a>';
            })
            ->addColumn('category', function ($object) {
                return $object->cat_name;
            })
            ->addColumn('chat_members', function ($object) {
                return '<span class="badge bg-dark">'.$object->chat_users_count.'</span>';
            })
            ->addColumn('created_at', function ($object) {
                return $object->created_at->diffForHumans();
            })
            ->addColumn('role', function ($object) use($user) {
                $user_role = $object->chatUsers->where('id', $user->id)->first()->pivot->role??null;

                switch(true)
                {

                    case ($user_role == 'member'):
                        return '<span class="badge bg-secondary">Member</span>';
                        break;
                    case ($user_role == 'mod'):
                        return '<span class="badge bg-primary">Moderator</span>';
                        break;
                    case ($user_role == 'admin'):
                        return '<span class="badge bg-success">Admin</span>';
                        break;
                }
            })
            ->filterColumn('name', function($query, $keyword) {
                $query->where('name','like', "%{$keyword}%");
            })
            ->orderColumn('name', function ($query, $order) {
                $query->orderBy('name', $order);
            })
            ->orderColumn('chat_members', function ($query, $order) {
                $query->orderBy('chat_users_count', $order);
            })
            ->orderColumn('category', function ($query, $order) {
                $query->orderBy('categories.name', $order);
            })
            ->rawColumns(['name','role','chat_members'])
            ->make();

        return $dataTable;
    }

    public function dataTablesChatGroupMembersSearch(ChatGroup $chat_group)
    {
        $data = $chat_group->chatUsers()
            ->selectRaw('users.*, user_has_chat_group.role as role,user_has_chat_group.created_at as join_date');

        //default order
        if(!request()->has('order')){
            $data->orderByRaw('FIELD(role,"admin","mod","member")');
        }


        $dataTable = datatables()->eloquent($data)->only(['username','role','joined'])
            ->addColumn('username', function ($object) {
                return '<a href="#" class="link-success">'.$object->username.'</a>';
            })
            ->addColumn('joined', function ($object) {
                return \Carbon\Carbon::createFromTimeStamp(strtotime($object->join_date))->diffForHumans();
            })
            ->addColumn('role', function ($object) {
                return $object->getChatUserRoleBadgeAttribute($object->role);
            })
            ->filterColumn('username', function($query, $keyword) {
                $query->where('username','like', "%{$keyword}%");
            })
            ->orderColumn('joined', function ($query, $order) {
                $query->orderBy('join_date', $order);
            })
            ->orderColumn('role', function ($query, $order) {
                $query->orderByRaw('FIELD(role,"admin","mod","member") '.$order);
            })
            ->rawColumns(['username', 'role'])
            ->make();

        return $dataTable;

//         if(!request()->has('order'))
//         {
//             $data->orderBy('chat_group.created_at', 'desc')->orderBy('chat_group.id', 'desc');
//         }
        dd('dataTablesChatGroupMembersSearch');
    }
}
