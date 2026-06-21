<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PageController extends Controller
{
    public function about()
    {
        return view('pages.about');
    }

    public function delivery()
    {
        return view('pages.delivery');
    }

    public function return()
    {
        return view('pages.return');
    }

    public function contacts()
    {
        return view('pages.contacts');
    }

    public function sendContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string',
            'message' => 'required|string',
        ]);

        // Отправка email (настройте под свой почтовый драйвер)
        // Mail::to('info@mirhimii.ru')->send(new ContactMail($validated));

        return back()->with('success', 'Сообщение отправлено! Мы свяжемся с вами в ближайшее время.');
    }
}
