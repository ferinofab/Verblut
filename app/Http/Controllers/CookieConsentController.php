<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class CookieConsentController extends Controller
{
    public function consent(Request $request)
    {
        Cookie::queue('cookie_consent', 'accepted', 60 * 24 * 365);
        return response()->json(['success' => true]);
    }
}
