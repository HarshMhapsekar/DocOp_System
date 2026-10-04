<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactMessage;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }

    public function store(Request $request)
    {
        $request->validate([
            'txtName' => 'required|string|max:50',
            'txtEmail' => 'required|email|max:100',
            'txtPhone' => 'required|string|max:15',
            'txtMsg' => 'required|string|max:1000',
        ]);

        ContactMessage::create([
            'name' => $request->input('txtName'),
            'email' => $request->input('txtEmail'),
            'contact' => $request->input('txtPhone'),
            'message' => $request->input('txtMsg'),
        ]);

        return redirect()->route('contact')->with('success', 'Your message has been sent successfully! Our administrative team will reach out shortly.');
    }
}
