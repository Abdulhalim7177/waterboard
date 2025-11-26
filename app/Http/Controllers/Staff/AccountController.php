<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\StaffBank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:staff');
    }

    public function overview()
    {
        $staff = Auth::guard('staff')->user()->load([
            'department', 'rank', 'cadre', 'gradeLevel', 'step', 'appointmentType',
            'lga', 'ward', 'area', 'zone', 'district', 'paypoint'
        ]);

        // Get related information
        $bankInfo = StaffBank::where('staff_id', $staff->id)->first();

        return view('staff.account.overview', compact('staff', 'bankInfo'));
    }

    public function changePassword(Request $request)
    {
        $staff = Auth::guard('staff')->user();

        $request->validate([
            'current_password' => [
                'required',
                function ($attribute, $value, $fail) use ($staff) {
                    if (!Hash::check($value, $staff->password)) {
                        $fail('The provided password does not match your current password.');
                    }
                },
            ],
            'new_password' => [
                'required',
                'confirmed',
                Password::min(8)->mixedCase()->numbers(),
            ],
        ]);

        $staff->password = Hash::make($request->new_password);
        $staff->save();

        return redirect()->route('staff.account.overview')->with('success', 'Your password has been changed successfully.');
    }
}