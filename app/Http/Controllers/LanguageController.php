<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switch(Request $request)
    {
        $language = $request->get('language');
        
        // Validate the language
        $availableLanguages = ['en', 'de', 'tr'];
        
        if (!in_array($language, $availableLanguages)) {
            $language = 'de'; // Default to German
        }
        
        // Store in session
        Session::put('locale', $language);
        
        // Set app locale
        App::setLocale($language);
        
        return redirect()->back();
    }
}
