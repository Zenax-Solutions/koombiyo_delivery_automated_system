<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Reseller;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{


    public function registerReseller(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string',
            'email' => ['required', 'email', Rule::unique('resellers', 'email')],
            'password' => 'required|string',
            'confpassword' => 'required|string|same:password',
        ]);


        $reseller = Reseller::create([

            'reseller_code' => '',
            'password' => Hash::make($request->password),
            'fullname' => $request->fullname,
            'email' => $request->email,
            'status' => 'pending',

        ]);

        if ($reseller) {

            toastr()->success('Registration Successfully!', 'Congrats');

            $request->session()->put('auth', $request->email);

            return redirect()->route('reseller.dashboard');
        }


        return redirect('/');
    }


    public function loginReseller(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $reseller = Reseller::where('email', $request->email)->first();

        if (!$reseller || !Hash::check($request->password, $reseller->password)) {
            return back()->withErrors(['email' => 'Invalid credentials']);
        }

        $request->session()->put('auth', $request->email);

        return redirect()->route('reseller.dashboard');
    }
}
