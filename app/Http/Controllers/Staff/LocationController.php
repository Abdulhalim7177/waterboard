<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Lga;
use App\Models\Ward;
use App\Models\Area;
use App\Models\Zone;
use App\Models\District;
use App\Models\Paypoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use App\Services\BreadcrumbService;

class LocationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:staff', 'permission:view-locations'])->only(['lgas', 'wards', 'areas', 'zones', 'districts', 'paypoints', 'filterWards', 'filterAreas', 'filterDistricts', 'manageDistrictWards']);
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
        $this->middleware(['auth:staff', 'permission:create-zone'])->only('storeZone');
        $this->middleware(['auth:staff', 'permission:edit-zone'])->only('updateZone');
        $this->middleware(['auth:staff', 'permission:delete-zone'])->only('destroyZone');
        $this->middleware(['auth:staff', 'permission:approve-zone'])->only('approveZone');
        $this->middleware(['auth:staff', 'permission:reject-zone'])->only('rejectZone');
        $this->middleware(['auth:staff', 'permission:create-district'])->only('storeDistrict');
        $this->middleware(['auth:staff', 'permission:edit-district'])->only('updateDistrict');
        $this->middleware(['auth:staff', 'permission:delete-district'])->only('destroyDistrict');
        $this->middleware(['auth:staff', 'permission:approve-district'])->only('approveDistrict');
        $this->middleware(['auth:staff', 'permission:reject-district'])->only('rejectDistrict');
        
        $this->middleware(['auth:staff', 'permission:create-paypoint'])->only('storePaypoint');
        $this->middleware(['auth:staff', 'permission:edit-paypoint'])->only('updatePaypoint');
        $this->middleware(['auth:staff', 'permission:manage-district-wards'])->only(['manageDistrictWards', 'assignWardToDistrict', 'removeWardFromDistrict']);
        $this->middleware(['auth:staff', 'permission:view-location-details'])->only(['zoneDetails', 'districtDetails', 'paypointDetails']);
    }

    public function lgas(Request $request)
    {
        // Set breadcrumbs
        $breadcrumb = app(BreadcrumbService::class);
        $breadcrumb->addHome()->add('Location Management')->add('LGA Management');

        $lgas = Lga::withCount(['staffs', 'customers'])->get();

        return view('staff.locations.lgas', compact('lgas'));
    }

    public function wards(Request $request)
    {
        // Set breadcrumbs
        $breadcrumb = app(BreadcrumbService::class);
        $breadcrumb->addHome()->add('Location Management')->add('Ward Management');

        $wards = Ward::withCount(['staffs', 'customers'])->with('lga')->get();

        return view('staff.locations.wards', compact('wards'));
    }

    public function areas(Request $request)
    {
        // Set breadcrumbs
        $breadcrumb = app(BreadcrumbService::class);
        $breadcrumb->addHome()->add('Location Management')->add('Area Management');

        $areas = Area::withCount(['staffs', 'customers'])->with('ward.lga')->get();

        return view('staff.locations.areas', compact('areas'));
    }

    public function zones(Request $request)
    {
        // Set breadcrumbs
        $breadcrumb = app(BreadcrumbService::class);
        $breadcrumb->addHome()->add('Location Management')->add('Zone Management');

        $zones = Zone::when($request->search_zone, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")->orWhere('code', 'like', "%{$search}%");
        })->paginate(10);

        return view('staff.locations.zones', compact('zones'));
    }

    public function districts(Request $request)
    {
        // Set breadcrumbs
        $breadcrumb = app(BreadcrumbService::class);
        $breadcrumb->addHome()->add('Location Management')->add('District Management');

        $districts = District::when($request->search_district, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")->orWhere('code', 'like', "%{$search}%");
        })->when($request->zone_filter, function ($query, $zone_id) {
            return $query->where('zone_id', $zone_id);
        })->with(['zone', 'wards'])->paginate(10);

        return view('staff.locations.districts', compact('districts'));
    }

    public function filterDistricts(Request $request)
    {
        $zone_id = $request->input('zone_id');
        $districts = District::where('status', 'approved')
            ->when($zone_id, function ($query, $zone_id) {
                return $query->where('zone_id', $zone_id);
            })
            ->with('zone')
            ->get()
            ->map(function ($district) {
                return [
                    'id' => $district->id,
                    'name' => $district->name,
                    'zone_name' => $district->zone->name
                ];
            });

        return response()->json(['districts' => $districts]);
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

    public function storeZone(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:zones,code'
        ]);

        Zone::create([
            'name' => $request->name,
            'code' => $request->code,
            'status' => 'pending'
        ]);

        return redirect()->route('staff.zones.index')->with('success', 'Zone creation request submitted for approval.');
    }

    public function updateZone(Request $request, Zone $zone)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:zones,code,' . $zone->id
        ]);

        $zone->update([
            'name' => $request->name,
            'code' => $request->code,
            'status' => 'pending'
        ]);

        return redirect()->route('staff.zones.index')->with('success', 'Zone update request submitted for approval.');
    }

    public function destroyZone(Zone $zone)
    {
        $zone->update(['status' => 'pending_delete']);
        $zone->logAuditEvent('delete_requested');

        return redirect()->route('staff.zones.index')->with('success', 'Zone deletion request submitted for approval.');
    }

    public function approveZone(Zone $zone)
    {
        $this->authorize('approve-zone');

        if ($zone->status === 'pending_delete') {
            $zone->logAuditEvent('deleted');
            $zone->delete();
        } else {
            $zone->update(['status' => 'approved']);
            $zone->logAuditEvent('approved');
        }

        return redirect()->route('staff.zones.index')->with('success', 'Zone request approved.');
    }

    public function rejectZone(Zone $zone)
    {
        $this->authorize('reject-zone');

        $zone->update(['status' => 'rejected']);
        $zone->logAuditEvent('rejected');

        return redirect()->route('staff.zones.index')->with('error', 'Zone request rejected.');
    }

    public function storeDistrict(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:districts,code',
            'zone_id' => 'required|exists:zones,id'
        ]);

        District::create([
            'name' => $request->name,
            'code' => $request->code,
            'zone_id' => $request->zone_id,
            'status' => 'pending'
        ]);

        return redirect()->route('staff.districts.index')->with('success', 'District creation request submitted for approval.');
    }

    public function manageDistrictWards(District $district)
    {
        // Set breadcrumbs
        $breadcrumb = app(BreadcrumbService::class);
        $breadcrumb->addHome()->add('Location Management')->add('District Management')->add('Manage Wards');

        $wards = Ward::whereNull('district_id')->get(); // All available wards
        $assignedWards = $district->wards; // Wards assigned to this district

        return view('staff.locations.manage_district_wards', compact('district', 'wards', 'assignedWards'));
    }

    public function assignWardToDistrict(Request $request, District $district)
    {
        $request->validate([
            'ward_id' => 'required|exists:wards,id',
        ]);

        $ward = Ward::findOrFail($request->ward_id);
        
        // Ensure the ward is not already assigned to another district
        if ($ward->district_id && $ward->district_id != $district->id) {
            return redirect()->back()->with('error', 'This ward is already assigned to another district.');
        }
        
        $ward->update(['district_id' => $district->id]);

        return redirect()->back()->with('success', 'Ward assigned to district successfully.');
    }

    public function removeWardFromDistrict(Ward $ward)
    {
        // Check if the ward belongs to a district
        if (!$ward->district_id) {
            return redirect()->back()->with('error', 'This ward is not assigned to any district.');
        }
        
        $ward->update(['district_id' => null]);

        return redirect()->back()->with('success', 'Ward removed from district successfully.');
    }

    public function zoneDetails(Zone $zone)
    {
        // Set breadcrumbs
        $breadcrumb = app(BreadcrumbService::class);
        $breadcrumb->addHome()->add('Location Management')->add('Zone Management')->add($zone->name);

        $staffs = $zone->staffs()->with(['lga', 'ward', 'area', 'zone', 'district', 'paypoint'])->get();
        $customers = $zone->customers()->with(['lga', 'ward', 'area', 'category', 'tariff'])->get();

        return view('staff.locations.zone_details', compact('zone', 'staffs', 'customers'));
    }

    public function districtDetails(District $district)
    {
        // Set breadcrumbs
        $breadcrumb = app(BreadcrumbService::class);
        $breadcrumb->addHome()->add('Location Management')->add('District Management')->add($district->name);

        $zone = $district->zone;
        $staffs = $zone->staffs()->with(['lga', 'ward', 'area', 'zone', 'district', 'paypoint'])->get();
        $customers = $zone->customers()->with(['lga', 'ward', 'area', 'category', 'tariff'])->get();

        return view('staff.locations.district_details', compact('district', 'staffs', 'customers'));
    }

    public function paypointDetails(Paypoint $paypoint)
    {
        // Set breadcrumbs
        $breadcrumb = app(BreadcrumbService::class);
        $breadcrumb->addHome()->add('Location Management')->add('Paypoint Management')->add($paypoint->name);

        $staffs = $paypoint->staff()->with(['lga', 'ward', 'area', 'zone', 'district', 'paypoint'])->get();
        $customers = $paypoint->customers()->with(['lga', 'ward', 'area', 'category', 'tariff'])->get();

        return view('staff.locations.paypoint_details', compact('paypoint', 'staffs', 'customers'));
    }

    public function paypoints(Request $request)
    {
        // Set breadcrumbs
        $breadcrumb = app(BreadcrumbService::class);
        $breadcrumb->addHome()->add('Location Management')->add('Paypoint Management');

        $paypoints = Paypoint::with(['zone', 'district'])->paginate(10);

        return view('staff.locations.paypoints', compact('paypoints'));
    }

    public function storePaypoint(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:paypoints,code',
            'type' => 'required|in:zone,district',
            'zone_id' => 'nullable|required_if:type,zone|exists:zones,id',
            'district_id' => 'nullable|required_if:type,district|exists:districts,id',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive'
        ]);

        Paypoint::create($request->all());

        return redirect()->route('staff.paypoints.index')->with('success', 'Paypoint created successfully.');
    }

    public function updatePaypoint(Request $request, Paypoint $paypoint)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:paypoints,code,' . $paypoint->id,
            'type' => 'required|in:zone,district',
            'zone_id' => 'nullable|required_if:type,zone|exists:zones,id',
            'district_id' => 'nullable|required_if:type,district|exists:districts,id',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive'
        ]);

        $paypoint->update($request->all());

        return redirect()->route('staff.paypoints.index')->with('success', 'Paypoint updated successfully.');
    }

    public function updateDistrict(Request $request, District $district)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:districts,code,' . $district->id,
            'zone_id' => 'required|exists:zones,id'
        ]);

        $district->update([
            'name' => $request->name,
            'code' => $request->code,
            'zone_id' => $request->zone_id,
            'status' => 'pending'
        ]);

        return redirect()->route('staff.districts.index')->with('success', 'District update request submitted for approval.');
    }

    public function destroyDistrict(District $district)
    {
        $district->update(['status' => 'pending_delete']);
        $district->logAuditEvent('delete_requested');

        return redirect()->route('staff.districts.index')->with('success', 'District deletion request submitted for approval.');
    }

    public function approveDistrict(District $district)
    {
        $this->authorize('approve-district');

        if ($district->status === 'pending_delete') {
            $district->logAuditEvent('deleted');
            $district->delete();
        } else {
            $district->update(['status' => 'approved']);
            $district->logAuditEvent('approved');
        }

        return redirect()->route('staff.districts.index')->with('success', 'District request approved.');
    }

    public function rejectDistrict(District $district)
    {
        $this->authorize('reject-district');

        $district->update(['status' => 'rejected']);
        $district->logAuditEvent('rejected');

        return redirect()->route('staff.districts.index')->with('error', 'District request rejected.');
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
            'ward_id' => 'required|exists:wards,id'
        ]);

        Area::create([
            'name' => $request->name,
            'ward_id' => $request->ward_id,
            'status' => 'pending'
        ]);

        return redirect()->route('staff.areas.index')->with('success', 'Area creation request submitted for approval.');
    }

    public function updateArea(Request $request, Area $area)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'ward_id' => 'required|exists:wards,id'
        ]);

        $area->update([
            'name' => $request->name,
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