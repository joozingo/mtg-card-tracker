<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    /**
     * Handle currency selection and store in session.
     */
    public function set(Request $request)
    {
        $currency = $request->get('currency');
        if (!in_array($currency, ['USD', 'EUR', 'GBP'])) {
            return redirect()->back()->with('error', 'Invalid currency selected.');
        }
        session(['currency' => $currency]);
        return redirect()->back();
    }
}
