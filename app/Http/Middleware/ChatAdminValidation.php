<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ChatAdminValidation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $route_chat_group_param = $request->route('chat_group');
        $user = $request->user();

        switch(true)
        {
            case(empty($route_chat_group_param) || empty($user)):
                \Alert::error('Chat group not found')->flash();
                return redirect()->back();
                break;

            case(empty($route_chat_group_param->chatAdmin) || $user->id != $route_chat_group_param->chatAdmin->id):
                \Alert::error('Only admin can edit chat group information.')->flash();
                return redirect()->back();
                break;
        }

        return $next($request);
    }
}
