<?php

namespace App\Http\Controllers\Admin;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatsController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        $data['pageTitle']     = 'Chat with Admin';

        $data['users'] = User::chatUsersWithMessages()->get();

        return inertia('Admin/Chat/Index', [
            'data' => $data,
        ]);
    }

    public function readMessage($userId)
    {
        $data['messages']  = Message::query()
            ->where('user_id', $userId)
            ->where('read_at', null)
            ->update([
                'read_at' => now(),
            ]);

        $data['users'] = User::chatUsersWithMessages()->get();

        return response()->json($data);
    }

    public function fetchChatUsers()
    {
        $data['users'] = User::chatUsersWithMessages()->get();

        return response()->json($data);
    }

    public function sendMessage(Request $request)
    {
        // dd('hit');
        
        $request->validate([
            'user_id' => 'required|integer',
            'message' => 'required',
        ]);

        $message = Message::create([
            'user_id' => $request->user_id,
            'admin_id' => $request->admin_id,
            'message' => $request->message,
        ]);

        // $user = User::find($request->user_id);

        // $message = $message->load(['admin' => function ($query) {
        //     $query->select('id', 'profile_photo_url');
        // }]);

        // dd( $message);

        MessageSent::dispatch($message);

        return redirect()->route('admin.chat.index');
    }
}
