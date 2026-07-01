<?php

namespace App\Http\Controllers;

use App\Models\Settings;

class DocsController extends Controller
{
    public function flow()
    {
        return view('Admin.Docs.flow', [
            'settings' => Settings::current(),
        ]);
    }
}
