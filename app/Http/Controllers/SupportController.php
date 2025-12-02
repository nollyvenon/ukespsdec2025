<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Models\SupportCategory;
use App\Models\SupportReply;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SupportController extends Controller
{
    /**
     * Display a listing of the support tickets.
     */
    public function index()
    {
        $tickets = SupportTicket::where('user_id', auth()->id())
            ->with('category', 'latestReply')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $categories = SupportCategory::where('is_active', true)->get();

        return view('support.index', compact('tickets', 'categories'));
    }

    /**
     * Show the form for creating a new ticket.
     */
    public function create()
    {
        $categories = SupportCategory::where('is_active', true)->get();
        return view('support.create', compact('categories'));
    }

    /**
     * Store a newly created ticket in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'support_category_id' => 'required|exists:support_categories,id',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        $ticket = SupportTicket::create([
            'user_id' => auth()->id(),
            'support_category_id' => $request->support_category_id,
            'subject' => $request->subject,
            'description' => $request->description,
            'priority' => $request->priority,
        ]);

        return redirect()->route('support.show', $ticket->id)->with('success', 'Ticket created successfully.');
    }

    /**
     * Display the specified ticket.
     */
    public function show($id)
    {
        $ticket = SupportTicket::where('id', $id)
            ->where('user_id', auth()->id())
            ->with('user', 'category', 'replies.user')
            ->firstOrFail();

        // Increment the view count
        $ticket->increment('view_count');

        return view('support.show', compact('ticket'));
    }

    /**
     * Display all tickets for admin.
     */
    public function adminIndex()
    {
        $this->authorize('admin', User::class);

        $tickets = SupportTicket::with('user', 'category', 'latestReply')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $categories = SupportCategory::where('is_active', true)->get();
        $priorities = ['low', 'medium', 'high', 'urgent'];
        $statuses = ['open', 'in_progress', 'on_hold', 'resolved', 'closed'];

        return view('support.admin.index', compact('tickets', 'categories', 'priorities', 'statuses'));
    }

    /**
     * Show the specified ticket for admin.
     */
    public function adminShow($id)
    {
        $this->authorize('admin', User::class);

        $ticket = SupportTicket::with('user', 'category', 'replies.user', 'assignedUser')
            ->findOrFail($id);

        // Increment the view count
        $ticket->increment('view_count');

        $categories = SupportCategory::where('is_active', true)->get();
        $priorities = ['low', 'medium', 'high', 'urgent'];
        $statuses = ['open', 'in_progress', 'on_hold', 'resolved', 'closed'];
        $users = User::all();

        return view('support.admin.show', compact('ticket', 'categories', 'priorities', 'statuses', 'users'));
    }

    /**
     * Update ticket status for admin.
     */
    public function updateStatus(Request $request, $id)
    {
        $this->authorize('admin', User::class);

        $request->validate([
            'status' => 'required|in:open,in_progress,on_hold,resolved,closed',
        ]);

        $ticket = SupportTicket::findOrFail($id);
        $ticket->update([
            'status' => $request->status,
        ]);

        if ($request->status == 'resolved') {
            $ticket->update(['resolved_at' => now()]);
        }

        return redirect()->back()->with('success', 'Ticket status updated successfully.');
    }

    /**
     * Assign ticket to a user for admin.
     */
    public function assignTicket(Request $request, $id)
    {
        $this->authorize('admin', User::class);

        $request->validate([
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $ticket = SupportTicket::findOrFail($id);
        $ticket->update([
            'assigned_to' => $request->assigned_to,
        ]);

        return redirect()->back()->with('success', 'Ticket assigned successfully.');
    }

    /**
     * Add a reply to the ticket.
     */
    public function addReply(Request $request, $id)
    {
        $ticket = SupportTicket::where('id', $id)
            ->where(function ($query) {
                $query->where('user_id', auth()->id())
                      ->orWhere('assigned_to', auth()->id());
            })
            ->firstOrFail();

        $request->validate([
            'content' => 'required|string',
        ]);

        SupportReply::create([
            'support_ticket_id' => $id,
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);

        // Update ticket status to 'in_progress' when replied
        if ($ticket->status === 'open') {
            $ticket->update(['status' => 'in_progress']);
        }

        return redirect()->back()->with('success', 'Reply added successfully.');
    }
}
