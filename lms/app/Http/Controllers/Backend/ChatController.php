<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ChatMessage;
use App\Events\MessageSent;
use App\Services\GeminiService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    protected $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    public function SendMessage(Request $request)
    {

        $request->validate([
            'msg' => 'required',
            'receiver_id' => 'required|exists:users,id'
        ]);

        // Prevent sending messages to oneself
        if ($request->receiver_id == Auth::user()->id) {
            return response()->json([
                'message' => 'You cannot send messages to yourself',
                'error' => true
            ], 400);
        }

        // Save the user's message
        $message = ChatMessage::create([
            'sender_id' => Auth::user()->id,
            'receiver_id' => $request->receiver_id,
            'msg' => $request->msg,
            'created_at' => Carbon::now(),
        ]);

        // Broadcast the message for real-time updates
        broadcast(new MessageSent($message))->toOthers();

        // Load sender relationship for consistent data structure
        $message->load('sender');
        $message->user = $message->sender;

        // Check if this is a @gpt message
        if ($this->geminiService->isAiMessage($request->msg)) {
            // Extract the prompt (remove @gpt prefix)
            $prompt = $this->geminiService->extractPrompt($request->msg);

            // Generate AI response
            $aiResponse = $this->geminiService->generateResponse($prompt);

            // Save the AI response as a message from the receiver
            $aiMessage = ChatMessage::create([
                'sender_id' => $request->receiver_id,
                'receiver_id' => Auth::user()->id,
                'msg' => "🤖 Gemini AI: " . $aiResponse,
                'created_at' => Carbon::now(),
            ]);

            // Broadcast the AI response
            broadcast(new MessageSent($aiMessage))->toOthers();

            // Load sender relationship for the AI message
            $aiMessage->load('sender');
            $aiMessage->user = $aiMessage->sender;

            // Return both messages
            return response()->json([
                'message' => 'Message Sent Successfully with AI Response',
                'data' => $message,
                'ai_response' => $aiMessage
            ]);
        }

        return response()->json([
            'message' => 'Message Send Successfully',
            'data' => $message
        ]);

    } // End Method 

    public function GetAllUsers()
    {

        $chats = ChatMessage::where(function ($q) {
            $q->where('sender_id', Auth::id())
                ->orWhere('receiver_id', Auth::id());
        })
            ->orderBy('id', 'DESC')
            ->get();

        $users = $chats->flatMap(function ($chat) {
            if ($chat->sender_id === Auth::id()) {
                return [$chat->sender, $chat->receiver];
            }
            return [$chat->receiver, $chat->sender];
        })->unique()->filter(function ($user) {
            // Remove self from the user list
            return $user && $user->id !== Auth::id();
        })->values();

        return $users;
    }// End Method

    public function UserMsgById($userId)
    {

        $user = User::find($userId);

        if ($user) {
            $messages = ChatMessage::where(function ($q) use ($userId) {
                $q->where('sender_id', Auth::id());
                $q->where('receiver_id', $userId);
            })->orWhere(function ($q) use ($userId) {
                $q->where('sender_id', $userId);
                $q->where('receiver_id', Auth::id());
            })->with(['sender', 'receiver'])->orderBy('created_at', 'ASC')->get();

            // Transform messages to include user info based on sender
            $messages = $messages->map(function ($message) {
                $message->user = $message->sender;
                return $message;
            });

            return response()->json([
                'user' => $user,
                'messages' => $messages,
            ]);
        } else {
            abort(404);
        }

    }// End Method 

    public function LiveChat()
    {
        return view('instructor.chat.live_chat');
    }



}
