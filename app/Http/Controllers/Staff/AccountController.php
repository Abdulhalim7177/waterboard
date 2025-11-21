<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\StaffBank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}