<?php
namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function showCustomerLoginForm()
    {
        return view('auth.customer-login');
    }

    public function customerLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('customer')->attempt(['email' => $request->email, 'password' => $request->password], $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('customer.dashboard'));
        }

        return back()->withErrors(['email' => 'Invalid email or password'])->withInput($request->only('email', 'remember'));
    }

    public function showVendorLoginForm()
    {
        return view('auth.vendor-login');
    }

    public function vendorLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Check if vendor exists and is approved
        $vendor = \App\Models\Vendor::where('email', $request->email)->first();
        
        if (!$vendor) {
            return back()->withErrors(['email' => 'Invalid email or password'])->withInput($request->only('email', 'remember'));
        }
        
        if (!$vendor->approved) {
            return back()->withErrors(['email' => 'Your account is not approved yet. Please contact the administrator.'])->withInput($request->only('email', 'remember'));
        }

        if (Auth::guard('vendor')->attempt(['email' => $request->email, 'password' => $request->password], $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('vendor.dashboard'));
        }

        return back()->withErrors(['email' => 'Invalid email or password'])->withInput($request->only('email', 'remember'));
    }

    public function showStaffLoginForm()
    {
        return view('auth.staff-login');
    }

    public function staffLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('staff')->attempt(['email' => $request->email, 'password' => $request->password], $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('staff.dashboard'));
        }

        return back()->withErrors(['email' => 'Invalid email or password'])->withInput($request->only('email', 'remember'));
    }

    public function customerLogout(Request $request)
    {
        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('customer.login');
    }

    public function vendorLogout(Request $request)
    {
        Auth::guard('vendor')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('vendor.login');
    }

    public function staffLogout(Request $request)
    {
        Auth::guard('staff')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('staff.login');
    }
}