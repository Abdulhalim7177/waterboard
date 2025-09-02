<?php
namespace App\Http\Controllers\Staff;
use App\Http\Controllers\Controller;
use App\Models\Lga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LgaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:staff');
    }

    public function index()
    {
        $this->authorize('viewAny', Lga::class);
        $lgas = Lga::all();
        return view('staff.lgas.index', compact('lgas'));
    }

    public function create()
    {
        $this->authorize('create', Lga::class);
        return view('staff.lgas.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Lga::class);
        $validated = $request->validate([
            'code' => 'required|unique:lgas',
            'name' => 'required',
        ]);

        Lga::create($validated + ['status' => 'pending']);
        return redirect()->route('staff.lgas.index')->with('success', 'LGA created, pending approval');
    }

    public function edit(Lga $lga)
    {
        $this->authorize('edit', $lga);
        return view('staff.lgas.edit', compact('lga'));
    }

    public function update(Request $request, Lga $lga)
    {
        $this->authorize('edit', $lga);
        $validated = $request->validate([
            'code' => 'required|unique:lgas,code,' . $lga->id,
            'name' => 'required',
        ]);

        $lga->update($validated + ['status' => 'pending']);
        return redirect()->route('staff.lgas.index')->with('success', 'LGA updated, pending approval');
    }

    public function destroy(Lga $lga)
    {
        $this->authorize('delete', $lga);
        $lga->update(['status' => 'pending']); // Soft delete with approval
        return redirect()->route('staff.lgas.index')->with('success', 'LGA deletion requested');
    }

    public function approve(Lga $lga)
    {
        $this->authorize('approve-lga', Lga::class);
        $lga->update(['status' => 'approved']);
        return redirect()->route('staff.lgas.index')->with('success', 'LGA approved');
    }

    public function reject(Lga $lga)
    {
        $this->authorize('reject-lga', Lga::class);
        $lga->update(['status' => 'rejected']);
        return redirect()->route('staff.lgas.index')->with('success', 'LGA rejected');
    }
}