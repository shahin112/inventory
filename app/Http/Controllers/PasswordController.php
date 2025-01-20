<?php
namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Mail\OTPMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class PasswordController extends Controller
{

    public function showSendOtpForm()
    {
        return Inertia::render('Password/SendOtp');
    }

    public function sendOtp(Request $request)
    {

        $request->validate([
            'email' => 'required',
        ]);

        $otp  = rand(1000, 9999);
        $user = User::where('email', $request->email)->first();
        if ($user) {
            Mail::to($request->email)->send(new OTPMail($otp));
            $user->update(['otp' => $otp]);
            $request->session()->put('email', $request->email);

            return redirect()->route('password.verify-otp.form')->with('message', 'Request Successful');
        } else {
            return back()->withErrors(['error' => 'Request fail']);

        }
    }

    public function showVerifyOtpForm()
    {
        return Inertia::render('Password/VerifyOtp');
    }

    public function VerifyOTP(Request $request)
    {

        $request->validate([
            'otp' => 'required|numeric',
        ]);
        $email = $request->session()->get('email');
        $user  = User::where('email', $email)->where('otp', $request->otp)->where('otp','!=',0)->first();

        if ($user) {
            $user->update(['otp' => '0']);
            $request->session()->put('otp_verify', 'yes');

            return redirect()->route('password.reset.form')->with('message', 'Otp verify Successful');
        } else {

            return back()->withErrors(['error' => 'Request fail']);

        }
    }

    public function showPasswrodResetForm()
    {
        return Inertia::render('Password/ResetPassword');
    }

    public function passwordReset(Request $request)
    {

        $request->validate(['password' => 'required']);

        $email      = $request->session()->get('email');
        $otp_verify = $request->session()->get('otp_verify');

        if ($otp_verify === "yes") {
            User::where('email', $email)->update(['password' => Hash::make($request->password)]);
            $request->session()->flush();
            return redirect()->route('loginForm')->with('message', 'password reset Successfull');
        } else {
            return back()->withErrors(['error' => 'Request fail']);
        }

    }
}
