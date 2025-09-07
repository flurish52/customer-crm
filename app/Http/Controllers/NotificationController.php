<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;
use Resend\Laravel\Facades\Resend;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

        public function sendJobProgressEmail(Request $request, Job $job)
    {
        $request->validate([
            'subject' => 'required|string',
            'from_name' => 'required|string',
            'message' => 'required|string',
            'sendToEmail' => 'required|email',
            'replyToEmail' => 'required|email',
        ]);

        $authUser = User::with('business')->find(auth()->id());
        Resend::emails()->send([
            'from' => $request->from_name . '<user@entroly.com.ng>',
            'reply_to' => $request->replyToEmail,
            'bcc' => [$request->replyToEmail],
            'to' => $request->sendToEmail,
            'subject' => $request->subject,
            'html' => '<p>' . $request->message. '</p>'
        ]);
        return response()->json(['status' => 'success', 'message' => 'Notification sent']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
