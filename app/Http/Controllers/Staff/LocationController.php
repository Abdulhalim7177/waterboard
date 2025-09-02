<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Lga;
use App\Models\Ward;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

class LocationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:staff', 'permission:view-locations'])->only(['lgas', 'wards', 'areas', 'filterWards', 'filterAreas']);
        $this->middleware(['auth:staff', 'permission:create-lga'])->only('storeLga');
        $this->middleware(['auth:staff', 'permission:edit-lga'])->only('updateLga');
        $this->middleware(['auth:staff', 'permission:delete-lga'])->only('destroyLga');
        $this->middleware(['auth:staff', 'permission:approve-lga'])->only('approveLga');
        $this->middleware(['auth:staff', 'permission:reject-lga'])->only('rejectLga');
        $this->middleware(['auth:staff', 'permission:create-ward'])->only('storeWard');
        $this->middleware(['auth:staff', 'permission:edit-ward'])->only('updateWard');
        $this->middleware(['auth:staff', 'permission:delete-ward'])->only('destroyWard');
        $this->middleware(['auth:staff', 'permission:approve-ward'])->only('approveWard');
        $this->middleware(['auth:staff', 'permission:reject-ward'])->only('rejectWard');
        $this->middleware(['auth:staff', 'permission:create-area'])->only('storeArea');
        $this->middleware(['auth:staff', 'permission:edit-area'])->only('updateArea');
        $this->middleware(['auth:staff', 'permission:delete-area'])->only('destroyArea');
        $this->middleware(['auth:staff', 'permission:approve-area'])->only('approveArea');
        $this->middleware(['auth:staff', 'permission:reject-area'])->only('rejectArea');
    }

    public function lgas(Request $request)
    {
        $lgas = Lga::when($request->search_lga, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")->orWhere('code', 'like', "%{$search}%");
        })->paginate(10);

        return view('staff.locations.lgas', compact('lgas'));
    }

    public function wards(Request $request)
    {
        $wards = Ward::when($request->search_ward, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")->orWhere('code', 'like', "%{$search}%");
        })->when($request->lga_filter, function ($query, $lga_id) {
            return $query->where('lga_id', $lga_id);
        })->with('lga')->paginate(10);

        return view('staff.locations.wards', compact('wards'));
    }

    public function areas(Request $request)
    {
        $areas = Area::when($request->search_area, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")->orWhere('code', 'like', "%{$search}%");
        })->when($request->ward_filter, function ($query, $ward_id) {
            return $query->where('ward_id', $ward_id);
        })->when($request->lga_filter, function ($query, $lga_id) {
            return $query->whereHas('ward', function ($q) use ($lga_id) {
                $q->where('lga_id', $lga_id);
            });
        })->with('ward.lga')->paginate(10);

        return view('staff.locations.areas', compact('areas'));
    }

    public function filterWards(Request $request)
    {
        $lga_id = $request->input('lga_id');
        $wards = Ward::where('status', 'approved')
            ->when($lga_id, function ($query, $lga_id) {
                return $query->where('lga_id', $lga_id);
            })
            ->with('lga')
            ->get()
            ->map(function ($ward) {
                return [
                    'id' => $ward->id,
                    'name' => $ward->name,
                    'lga_name' => $ward->lga->name
                ];
            });

        return response()->json(['wards' => $wards]);
    }

    public function filterAreas(Request $request)
    {
        $ward_id = $request->input('ward_id');
        $lga_id = $request->input('lga_id');
        $areas = Area::where('status', 'approved')
            ->when($ward_id, function ($query, $ward_id) {
                return $query->where('ward_id', $ward_id);
            })
            ->when($lga_id, function ($query, $lga_id) {
                return $query->whereHas('ward', function ($q) use ($lga_id) {
                    $q->where('lga_id', $lga_id);
                });
            })
            ->with('ward.lga')
            ->get()
            ->map(function ($area) {
                return [
                    'id' => $area->id,
                    'name' => $area->name,
                    'ward_name' => $area->ward->name,
                    'lga_name' => $area->ward->lga->name
                ];
            });

        return response()->json(['areas' => $areas]);
    }

    public function storeLga(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:lgas,code',
            'state' => 'required|string|max:255'
        ]);

        Lga::create([
            'name' => $request->name,
            'code' => $request->code,
            'state' => $request->state,
            'status' => 'pending'
        ]);

        return redirect()->route('staff.lgas.index')->with('success', 'LGA creation request submitted for approval.');
    }

    public function updateLga(Request $request, Lga $lga)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:lgas,code,' . $lga->id,
            'state' => 'required|string|max:255'
        ]);

        $lga->update([
            'name' => $request->name,
            'code' => $request->code,
            'state' => $request->state,
            'status' => 'pending'
        ]);

        return redirect()->route('staff.lgas.index')->with('success', 'LGA update request submitted for approval.');
    }

    public function destroyLga(Lga $lga)
    {
        $lga->update(['status' => 'pending_delete']);
        $lga->logAuditEvent('delete_requested');

        return redirect()->route('staff.lgas.index')->with('success', 'LGA deletion request submitted for approval.');
    }

    public function approveLga(Lga $lga)
    {
        $this->authorize('approve-lga');

        if ($lga->status === 'pending_delete') {
            $lga->logAuditEvent('deleted');
            $lga->delete();
        } else {
            $lga->update(['status' => 'approved']);
            $lga->logAuditEvent('approved');
        }

        return redirect()->route('staff.lgas.index')->with('success', 'LGA request approved.');
    }

    public function rejectLga(Lga $lga)
    {
        $this->authorize('reject-lga');

        $lga->update(['status' => 'rejected']);
        $lga->logAuditEvent('rejected');

        return redirect()->route('staff.lgas.index')->with('error', 'LGA request rejected.');
    }

    public function storeWard(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:wards,code',
            'lga_id' => 'required|exists:lgas,id'
        ]);

        Ward::create([
            'name' => $request->name,
            'code' => $request->code,
            'lga_id' => $request->lga_id,
            'status' => 'pending'
        ]);

        return redirect()->route('staff.wards.index')->with('success', 'Ward creation request submitted for approval.');
    }

    public function updateWard(Request $request, Ward $ward)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:wards,code,' . $ward->id,
            'lga_id' => 'required|exists:lgas,id'
        ]);

        $ward->update([
            'name' => $request->name,
            'code' => $request->code,
            'lga_id' => $request->lga_id,
            'status' => 'pending'
        ]);

        return redirect()->route('staff.wards.index')->with('success', 'Ward update request submitted for approval.');
    }

    public function destroyWard(Ward $ward)
    {
        $ward->update(['status' => 'pending_delete']);
        $ward->logAuditEvent('delete_requested');

        return redirect()->route('staff.wards.index')->with('success', 'Ward deletion request submitted for approval.');
    }

    public function approveWard(Ward $ward)
    {
        $this->authorize('approve-ward');

        if ($ward->status === 'pending_delete') {
            $ward->logAuditEvent('deleted');
            $ward->delete();
        } else {
            $ward->update(['status' => 'approved']);
            $ward->logAuditEvent('approved');
        }

        return redirect()->route('staff.wards.index')->with('success', 'Ward request approved.');
    }

    public function rejectWard(Ward $ward)
    {
        $this->authorize('reject-ward');

        $ward->update(['status' => 'rejected']);
        $ward->logAuditEvent('rejected');

        return redirect()->route('staff.wards.index')->with('error', 'Ward request rejected.');
    }

    public function storeArea(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:areas,code',
            'ward_id' => 'required|exists:wards,id'
        ]);

        Area::create([
            'name' => $request->name,
            'code' => $request->code,
            'ward_id' => $request->ward_id,
            'status' => 'pending'
        ]);

        return redirect()->route('staff.areas.index')->with('success', 'Area creation request submitted for approval.');
    }

    public function updateArea(Request $request, Area $area)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:areas,code,' . $area->id,
            'ward_id' => 'required|exists:wards,id'
        ]);

        $area->update([
            'name' => $request->name,
            'code' => $request->code,
            'ward_id' => $request->ward_id,
            'status' => 'pending'
        ]);

        return redirect()->route('staff.areas.index')->with('success', 'Area update request submitted for approval.');
    }

    public function destroyArea(Area $area)
    {
        $area->update(['status' => 'pending_delete']);
        $area->logAuditEvent('delete_requested');

        return redirect()->route('staff.areas.index')->with('success', 'Area deletion request submitted for approval.');
    }

    public function approveArea(Area $area)
    {
        $this->authorize('approve-area');

        if ($area->status === 'pending_delete') {
            $area->logAuditEvent('deleted');
            $area->delete();
        } else {
            $area->update(['status' => 'approved']);
            $area->logAuditEvent('approved');
        }

        return redirect()->route('staff.areas.index')->with('success', 'Area request approved.');
    }

    public function rejectArea(Area $area)
    {
        $this->authorize('reject-area');

        $area->update(['status' => 'rejected']);
        $area->logAuditEvent('rejected');

        return redirect()->route('staff.areas.index')->with('error', 'Area request rejected.');
    }
}