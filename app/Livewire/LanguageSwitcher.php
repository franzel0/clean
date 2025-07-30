<?php

namespace App\Livewire;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class LanguageSwitcher extends Component
{
    public $currentLocale;
    
    public function mount()
    {
        $this->currentLocale = App::getLocale();
    }
    
    public function switchLanguage($language)
    {
        $availableLanguages = ['en', 'de', 'tr'];
        
        if (in_array($language, $availableLanguages)) {
            Session::put('locale', $language);
            App::setLocale($language);
            $this->currentLocale = $language;
            
            // Refresh the page to update all translations
            return redirect()->to(request()->header('Referer') ?: '/dashboard');
        }
    }
    
    public function render()
    {
        $languages = [
            'de' => ['name' => __('messages.german'), 'flag' => '🇩🇪'],
            'en' => ['name' => __('messages.english'), 'flag' => '🇬🇧'],
            'tr' => ['name' => __('messages.turkish'), 'flag' => '🇹🇷'],
        ];
        
        return view('livewire.language-switcher', compact('languages'));
    }
}
