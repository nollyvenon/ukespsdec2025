<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventsManagerController extends Controller
{
    /**
     * Display the events manager dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user->isEventHoster() && !$user->is_admin) {
            abort(403, 'Unauthorized access. Only event hosters and admins can access this page.');
        }

        // Get stats for events manager dashboard
        $totalEventsCreated = Event::where('created_by', $user->id)->count();
        $totalEventRegistrations = EventRegistration::whereIn('event_id',
            Event::where('created_by', $user->id)->pluck('id')
        )->count();

        // Get recent events
        $recentEvents = Event::where('created_by', $user->id)
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

        // Get recent registrations
        $recentRegistrations = EventRegistration::whereIn('event_id',
            Event::where('created_by', $user->id)->pluck('id')
        )
        ->with(['event', 'user'])
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

        return view('events-manager.dashboard', compact(
            'totalEventsCreated',
            'totalEventRegistrations',
            'recentEvents',
            'recentRegistrations'
        ));
    }

    /**
     * Display the events managed by the user.
     */
    public function events()
    {
        $user = Auth::user();

        if (!$user->isEventHoster() && !$user->is_admin) {
            abort(403, 'Unauthorized access. Only event hosters and admins can access this page.');
        }

        $events = Event::where('created_by', $user->id)
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        return view('events-manager.events', compact('events'));
    }

    /**
     * Display all registrations for the user's events.
     */
    public function registrations()
    {
        $user = Auth::user();

        if (!$user->isEventHoster() && !$user->is_admin) {
            abort(403, 'Unauthorized access. Only event hosters and admins can access this page.');
        }

        $registrations = EventRegistration::whereIn('event_id',
            Event::where('created_by', $user->id)->pluck('id')
        )
        ->with(['event', 'user'])
        ->latest()
        ->paginate(10);

        return view('events-manager.registrations', compact('registrations'));
    }
}
