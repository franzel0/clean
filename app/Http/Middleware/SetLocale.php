<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $availableLanguages = ['en', 'de', 'tr'];
        $defaultLanguage = 'de';
        
        // Get language from session, or use browser default, or fallback to default
        $locale = Session::get('locale');
        
        if (!$locale || !in_array($locale, $availableLanguages)) {
            // Try to get from Accept-Language header
            $acceptLanguage = $request->header('Accept-Language');
            if ($acceptLanguage) {
                $locale = substr($acceptLanguage, 0, 2);
            }
            
            // Validate and fallback
            if (!in_array($locale, $availableLanguages)) {
                $locale = $defaultLanguage;
            }
            
            Session::put('locale', $locale);
        }
        
        App::setLocale($locale);
        
        return $next($request);
    }
}
