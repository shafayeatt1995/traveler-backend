<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function index()
    {
        $this->authorize('admin');
        $messages = ContactMessage::latest()->paginate(20);
        return response()->json(compact('messages'));
    }

    public function submitMessage(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required|numeric',
            'email' => 'email',
            'subject' => 'required',
            'message' => 'required|max:500',
        ]);

        $message = new ContactMessage();
        $message->name = $request->name;
        $message->phone = $request->phone;
        $message->email = $request->email;
        $message->subject = $request->subject;
        $message->message = $request->message;
        $message->save();
    }

    public function updateMessage(ContactMessage $contactMessage)
    {
        $this->authorize('admin');
        $contactMessage->solve = !$contactMessage->solve;
        $contactMessage->save();
    }

    public function deleteMessage(ContactMessage $contactMessage)
    {
        $this->authorize('admin');
        $contactMessage->delete();
    }
}
