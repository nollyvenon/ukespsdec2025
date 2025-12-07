<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventsController extends Controller
{
    /**
     * Display a listing of events.
     */
    public function index(Request $request)
    {
        $query = Event::where('event_status', 'published');

        // Advanced search criteria
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('location', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('address', 'LIKE', "%{$searchTerm}%");
            });
        }

        if ($request->filled('event_type')) {
            $query->where('event_type', $request->input('event_type'));
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('location')) {
            $query->where('location', 'LIKE', '%' . $request->input('location') . '%');
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->input('min_price'));
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('start_date', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('end_date', '<=', $request->input('date_to'));
        }

        $events = $query
            ->orderBy('is_premium', 'desc')  // Premium events first
            ->orderBy('start_date', 'asc')   // Then by start date
            ->paginate(10);

        // Get filter options for the view
        $eventTypes = ['workshop', 'seminar', 'conference', 'training', 'webinar', 'course', 'meetup', 'social'];
        $categories = ['education', 'technology', 'business', 'health', 'arts', 'sports', 'networking', 'career'];

        return view('events.index', compact('events', 'eventTypes', 'categories'));
    }

    /**
     * Show the form for creating a new event.
     */
    public function create()
    {
        $this->authorize('create', Event::class);
        return view('events.create');
    }

    /**
     * Store a newly created event in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Event::class);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date',
            'location' => 'required|string|max:255',
            'max_participants' => 'nullable|integer|min:1',
            'registration_deadline' => 'nullable|date|before_or_equal:start_date',
            'event_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'event_status' => 'required|in:draft,published,cancelled,completed',
            'is_premium' => 'boolean',
        ]);

        $data = $request->except(['created_by']);
        $data['created_by'] = Auth::id();

        // Handle premium event settings
        if ($request->is_premium) {
            if ($request->premium_fee) {
                $data['is_premium'] = true;
                $data['premium_fee'] = $request->premium_fee;
                $data['premium_expires_at'] = now()->addDays(30); // Default to 30 days
            } else {
                $data['is_premium'] = false; // Don't allow premium without specifying fee
            }
        } else {
            $data['is_premium'] = false;
            $data['premium_fee'] = null;
            $data['premium_expires_at'] = null;
        }

        if ($request->hasFile('event_image')) {
            $path = $request->file('event_image')->store('events', 'public');
            $data['event_image'] = $path;
        }

        Event::create($data);

        return redirect()->route('events.index')->with('success', 'Event created successfully.');
    }

    /**
     * Display the specified event.
     */
    public function show(Event $event)
    {
        // Only authorize if the event is not published (for unpublished events, only authorized users can view)
        if ($event->event_status !== 'published') {
            $this->authorize('view', $event);
        }

        return view('events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified event.
     */
    public function edit(Event $event)
    {
        $this->authorize('update', $event);
        return view('events.edit', compact('event'));
    }

    /**
     * Update the specified event in storage.
     */
    public function update(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date',
            'location' => 'required|string|max:255',
            'max_participants' => 'nullable|integer|min:1',
            'registration_deadline' => 'nullable|date|before_or_equal:start_date',
            'event_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'event_status' => 'required|in:draft,published,cancelled,completed',
            'is_premium' => 'boolean',
        ]);

        $data = $request->except(['created_by']); // Don't allow changing created_by

        if ($request->hasFile('event_image')) {
            $path = $request->file('event_image')->store('events', 'public');
            $data['event_image'] = $path;
        }

        $event->update($data);

        return redirect()->route('events.index')->with('success', 'Event updated successfully.');
    }

    /**
     * Remove the specified event from storage.
     */
    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);

        $event->delete();

        return redirect()->route('events.index')->with('success', 'Event deleted successfully.');
    }

    /**
     * Display events created by the authenticated user.
     */
    public function myEvents()
    {
        $events = Event::where('created_by', Auth::id())->paginate(10);
        return view('events.my-events', compact('events'));
    }

    /**
     * Register for an event.
     */
    public function register(Event $event)
    {
        // Check if user is already registered
        $existingRegistration = EventRegistration::where('event_id', $event->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingRegistration) {
            return redirect()->back()->with('error', 'You are already registered for this event.');
        }

        // Check if the event has reached max capacity
        if ($event->max_participants && $event->registrations()->count() >= $event->max_participants) {
            return redirect()->back()->with('error', 'This event has reached maximum capacity.');
        }

        // Check if registration deadline has passed
        if ($event->registration_deadline && $event->registration_deadline < now()) {
            return redirect()->back()->with('error', 'Registration deadline has passed for this event.');
        }

        EventRegistration::create([
            'event_id' => $event->id,
            'user_id' => Auth::id(),
            'attendance_status' => 'registered',
            'payment_status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Successfully registered for the event.');
    }

    /**
     * Display user's event registrations.
     */
    public function myRegistrations()
    {
        $registrations = EventRegistration::where('user_id', Auth::id())
            ->with('event')
            ->paginate(10);

        return view('events.my-registrations', compact('registrations'));
    }
}
