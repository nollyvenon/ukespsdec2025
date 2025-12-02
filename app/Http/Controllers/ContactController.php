<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    /**
     * Show the contact form.
     */
    public function showForm()
    {
        $contactContent = \App\Models\SiteSetting::get('contact_content');
        $contactEmail = \App\Models\SiteSetting::get('contact_email') ?: 'support@example.com';
        $contactPhone = \App\Models\SiteSetting::get('contact_phone') ?: '+1 (123) 456-7890';
        $contactAddress = \App\Models\SiteSetting::get('contact_address') ?: '123 Main St, City, Country';

        return view('contact.form', compact('contactContent', 'contactEmail', 'contactPhone', 'contactAddress'));
    }

    /**
     * Store a newly created contact message in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:500',
            'message' => 'required|string|min:10',
        ]);

        ContactMessage::create([
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('contact.form')->with('success', 'Your message has been sent successfully. We will get back to you soon.');
    }

    /**
     * Display contact messages for admin management.
     */
    public function adminIndex()
    {
        $messages = ContactMessage::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.contact-messages', compact('messages'));
    }

    /**
     * Show a specific contact message.
     */
    public function show(ContactMessage $message)
    {
        $message->update(['read_at' => now()]); // Mark as read
        return view('admin.contact-message', compact('message'));
    }

    /**
     * Update the status of a contact message.
     */
    public function updateStatus(Request $request, ContactMessage $message)
    {
        $request->validate([
            'status' => 'required|in:new,in_progress,resolved,closed',
        ]);

        $message->update(['status' => $request->status]);

        return redirect()->route('admin.contact.show', $message)->with('success', 'Message status updated successfully.');
    }

    /**
     * Mark a contact message as read.
     */
    public function markAsRead(ContactMessage $message)
    {
        $message->update(['read_at' => now()]);

        return redirect()->back();
    }
}
