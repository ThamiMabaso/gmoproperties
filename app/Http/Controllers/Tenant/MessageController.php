<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MessageController extends Controller
{
    /**
     * Display a listing of messages.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $user = Auth::user();

        $messages = Message::where('recipient_id', $user->id)
            ->orWhere('sender_id', $user->id)
            ->with(['sender', 'recipient'])
            ->latest()
            ->paginate(20);

        $unreadCount = $user->unreadMessagesCount();

        return view('tenant.messages.index', compact('messages', 'unreadCount'));
    }

    /**
     * Show the form for creating a new message.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        $user = Auth::user();

        // Get company admins and property managers as recipients
        $recipients = User::where('company_id', $user->company_id)
            ->whereIn('type', ['company_admin', 'property_manager'])
            ->where('id', '!=', $user->id)
            ->orderBy('name')
            ->get();

        return view('tenant.messages.create', compact('recipients'));
    }

    /**
     * Store a newly created message.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'subject' => 'nullable|string|max:255',
            'body' => 'required|string',
            'related_entity_type' => 'nullable|string',
            'related_entity_id' => 'nullable|integer',
        ]);

        // Ensure recipient belongs to the same company
        $recipient = User::findOrFail($validated['recipient_id']);
        if ($recipient->company_id !== $user->company_id) {
            return redirect()
                ->back()
                ->with('error', 'Invalid recipient.');
        }

        Message::create([
            'company_id' => $user->company_id,
            'sender_id' => $user->id,
            'recipient_id' => $validated['recipient_id'],
            'subject' => $validated['subject'],
            'body' => $validated['body'],
            'type' => 'message',
            'related_entity_type' => $validated['related_entity_type'] ?? null,
            'related_entity_id' => $validated['related_entity_id'] ?? null,
        ]);

        return redirect()
            ->route('tenant.messages.index')
            ->with('success', 'Message sent successfully.');
    }

    /**
     * Display the specified message.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\View\View
     */
    public function show(Message $message): View
    {
        $user = Auth::user();

        // Ensure user has access to this message
        if ($message->sender_id !== $user->id && $message->recipient_id !== $user->id) {
            abort(403, 'Unauthorized access to this message.');
        }

        // Mark as read if recipient
        if ($message->recipient_id === $user->id && !$message->is_read) {
            $message->markAsRead();
        }

        $message->load(['sender', 'recipient', 'relatedEntity']);

        return view('tenant.messages.show', compact('message'));
    }

    /**
     * Mark message as read.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsRead(Message $message): RedirectResponse
    {
        if ($message->recipient_id === Auth::id()) {
            $message->markAsRead();
        }

        return redirect()->back();
    }

    /**
     * Remove the specified message.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Message $message): RedirectResponse
    {
        $user = Auth::user();

        // Only sender or recipient can delete
        if ($message->sender_id !== $user->id && $message->recipient_id !== $user->id) {
            abort(403, 'Unauthorized.');
        }

        $message->delete();

        return redirect()
            ->route('tenant.messages.index')
            ->with('success', 'Message deleted successfully.');
    }
}
