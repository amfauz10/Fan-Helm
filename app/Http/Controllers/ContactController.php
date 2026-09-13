<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'firstName' => 'required|string|max:100',
            'lastName' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'mobile' => 'required|string|max:30',
            'message' => 'required|string|max:2000',
        ], [
            'firstName.required' => 'Nama depan wajib diisi.',
            'lastName.required' => 'Nama belakang wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'mobile.required' => 'Nomor telepon wajib diisi.',
            'message.required' => 'Pesan wajib diisi.',
        ]);

        ContactMessage::create([
            'first_name' => $validated['firstName'],
            'last_name' => $validated['lastName'],
            'email' => $validated['email'],
            'phone' => $validated['mobile'],
            'message' => $validated['message'],
        ]);

        return redirect()->back()->with('success', 'Terima kasih! Pesan Anda telah berhasil dikirim ke tim Fan Helmet.');
    }
}
