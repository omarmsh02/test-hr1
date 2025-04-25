<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Chat;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * Display the chat dashboard
     */
    public function index()
    {
        $user = auth()->user();
        $recentChats = Chat::getRecentConversations($user->id);
        
        // Get user details for each chat
        $chatUsers = User::whereIn('id', $recentChats->pluck('user_id'))->get()
            ->keyBy('id');
            
        return view('chats.index', compact('recentChats', 'chatUsers'));
    }

    /**
     * Show conversation with a specific user
     */
    public function show(User $user)
    {
        $currentUser = auth()->user();
        
        // Ensure the user is not trying to chat with themselves
        if ($user->id === $currentUser->id) {
            return redirect()->route('chats.index')
                ->with('error', 'You cannot chat with yourself.');
        }
        
        $messages = Chat::getConversation($currentUser->id, $user->id);
        $recentChats = Chat::getRecentConversations($currentUser->id);
        $chatUsers = User::whereIn('id', $recentChats->pluck('user_id'))->get()
            ->keyBy('id');
            
        return view('chats.show', compact('messages', 'user', 'recentChats', 'chatUsers'));
    }

    /**
     * Store a new message
     */
    public function store(Request $request, User $user)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
        ]);
        
        $currentUser = auth()->user();
        
        // Ensure the user is not trying to chat with themselves
        if ($user->id === $currentUser->id) {
            return redirect()->route('chats.index')
                ->with('error', 'You cannot chat with yourself.');
        }
        
        Chat::create([
            'sender_id' => $currentUser->id,
            'receiver_id' => $user->id,
            'message' => $validated['message'],
        ]);
        
        return redirect()->route('chats.show', $user->id)
            ->with('success', 'Message sent successfully.');
    }

    /**
     * Get chat users for the sidebar (AJAX)
     */
    public function getUsers()
    {
        $user = auth()->user();
        $role = $user->role;
        
        // Admin can chat with all users
        if ($role === 'admin') {
            $users = User::where('id', '!=', $user->id)->get();
        } 
        // Manager can chat with their department and admins
        elseif ($role === 'manager') {
            $users = User::where(function ($query) use ($user) {
                $query->where('department', $user->department)
                    ->orWhere('role', 'admin');
            })->where('id', '!=', $user->id)->get();
        } 
        // Employees can chat with their department managers and admins
        else {
            $users = User::where(function ($query) use ($user) {
                $query->where('role', 'admin')
                    ->orWhere(function ($q) use ($user) {
                        $q->where('role', 'manager')
                          ->where('department', $user->department);
                    });
            })->where('id', '!=', $user->id)->get();
        }
        
        return response()->json(['users' => $users]);
    }
}