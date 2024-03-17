<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserChatsController extends Controller
{
    //Add the below functions
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data['pageTitle']     = 'Live Chat';

        $data['messages'] = Message::where('user_id', auth()->id())->with('admin')->get();

        return inertia('User/Chat/Index', [
            'data' => $data,
        ]);
    }

    public function fetchMessages()
    {
        $data['messages']  = Message::get();

        return inertia('User/Chat/Index', [
            'data' => $data,
        ]);
    }

    public function sendMessage(Request $request)
    {
       

        $request->validate([
            'user_id' => 'required|integer',
            'message' => 'required',
        ]);

        $message = Message::create([
            'user_id' => $request->user_id,
            'message' => $request->message,
        ]);

        // $message = $message->load(['admin' => function ($query) {
        //     $query->select('id', 'profile_photo_url');
        // }]);

        MessageSent::dispatch($message);

        return redirect()->route('user.chat.index');
    }
}
