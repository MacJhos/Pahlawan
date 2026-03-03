<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;

class LocaleController extends Controller
{
    public function setLocale($lang)
    {
        if (in_array($lang, ['id', 'en'])) {
            Session::put('locale', $lang);
        }
        return redirect()->back();
    }
}
