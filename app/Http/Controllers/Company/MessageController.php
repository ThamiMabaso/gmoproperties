<?php

declare(strict_types=1);

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Company\BaseCompanyController;
use App\Models\Company;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MessageController extends BaseCompanyController
{
    /**
     * Display a listing of messages.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\View\View
     */
    public function index(Company $company): View
    {
        $this->ensureCompanyAccess($company);

        $user = auth()->user();

        $messages = Message::where('company_id', $company->id)
            ->where(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                    ->orWhere('recipient_id', $user->id);
            })
            ->with(['sender', 'recipient'])
            ->latest()
            ->paginate(20);

        $unreadCount = $user->unreadMessagesCount();

        return view('company.messages.index', compact('company', 'messages', 'unreadCount'));
    }

    /**
     * Show the form for creating a new message.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\View\View
     */
    public function create(Company $company): View
    {
        $this->ensureCompanyAccess($company);

        // Get recipients (tenants and other company users)
        $recipients = $company->users()
            ->where('id', '!=', auth()->id())
            ->orderBy('name')
            ->get();

        return view('company.messages.create', compact('company', 'recipients'));
    }

    /**
     * Store a newly created message.
     *
     * @param  \App\Models\Company  $company
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Company $company, Request $request): RedirectResponse
    {
        $this->ensureCompanyAccess($company);

        $validated = $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'subject' => 'nullable|string|max:255',
            'body' => 'required|string',
            'related_entity_type' => 'nullable|string',
            'related_entity_id' => 'nullable|integer',
        ]);

        // Ensure recipient belongs to the same company
        $recipient = User::findOrFail($validated['recipient_id']);
        if ($recipient->company_id !== $company->id) {
            return redirect()
                ->back()
                ->with('error', 'Invalid recipient.');
        }

        Message::create([
            'company_id' => $company->id,
            'sender_id' => auth()->id(),
            'recipient_id' => $validated['recipient_id'],
            'subject' => $validated['subject'],
            'body' => $validated['body'],
            'type' => 'message',
            'related_entity_type' => $validated['related_entity_type'] ?? null,
            'related_entity_id' => $validated['related_entity_id'] ?? null,
        ]);

        return redirect()
            ->route('company.messages.index', $company)
            ->with('success', 'Message sent successfully.');
    }

    /**
     * Display the specified message.
     *
     * @param  \App\Models\Company  $company
     * @param  \App\Models\Message  $message
     * @return \Illuminate\View\View
     */
    public function show(Company $company, Message $message): View
    {
        $this->ensureCompanyAccess($company);

        $user = auth()->user();

        // Ensure user has access to this message
        if ($message->sender_id !== $user->id && $message->recipient_id !== $user->id) {
            abort(403, 'Unauthorized access to this message.');
        }

        // Mark as read if recipient
        if ($message->recipient_id === $user->id && !$message->is_read) {
            $message->markAsRead();
        }

        $message->load(['sender', 'recipient', 'relatedEntity']);

        return view('company.messages.show', compact('company', 'message'));
    }

    /**
     * Mark message as read.
     *
     * @param  \App\Models\Company  $company
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsRead(Company $company, Message $message): RedirectResponse
    {
        $this->ensureCompanyAccess($company);

        if ($message->recipient_id === auth()->id()) {
            $message->markAsRead();
        }

        return redirect()->back();
    }

    /**
     * Remove the specified message.
     *
     * @param  \App\Models\Company  $company
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Company $company, Message $message): RedirectResponse
    {
        $this->ensureCompanyAccess($company);

        $user = auth()->user();

        // Only sender or recipient can delete
        if ($message->sender_id !== $user->id && $message->recipient_id !== $user->id) {
            abort(403, 'Unauthorized.');
        }

        $message->delete();

        return redirect()
            ->route('company.messages.index', $company)
            ->with('success', 'Message deleted successfully.');
    }
}
