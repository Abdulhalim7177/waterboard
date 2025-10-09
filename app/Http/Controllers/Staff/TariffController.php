<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Tariff;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Services\BreadcrumbService;

class TariffController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:staff', 'permission:view-tariffs'])->only('index');
        $this->middleware(['auth:staff', 'permission:create-tariff'])->only('store');
        $this->middleware(['auth:staff', 'permission:edit-tariff'])->only('update');
        $this->middleware(['auth:staff', 'permission:delete-tariff'])->only('destroy');
        $this->middleware(['auth:staff', 'permission:approve-tariff'])->only('approve');
        $this->middleware(['auth:staff', 'permission:reject-tariff'])->only('reject');
    }

    public function index(Request $request)
    {
        // Set breadcrumbs
        $breadcrumb = app(BreadcrumbService::class);
        $breadcrumb->addHome()->add('Tariff Management');

        $tariffs = Tariff::when($request->search_tariff, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                        ->orWhere('catcode', 'like', "%{$search}%");
        })->with('category')->paginate(10);

        $categories = Category::where('status', 'approved')->get();

        // Analytics for Insights section
        $total_tariffs = Tariff::count();
        $status_counts = Tariff::select('status')
            ->groupBy('status')
            ->pluck('status')
            ->mapWithKeys(function ($status) {
                return [$status => Tariff::where('status', $status)->count()];
            })->toArray();
        $tariffs_per_category = Category::where('status', 'approved')
            ->withCount('tariffs')
            ->get()
            ->pluck('tariffs_count', 'name')
            ->toArray();

        return view('staff.tariffs.index', compact('tariffs', 'categories', 'total_tariffs', 'status_counts', 'tariffs_per_category'));
    }

    public function store(Request $request)
    {
        Log::info('Tariff store request:', $request->all());

        $request->validate([
            'name' => 'required|string|max:255',
            'suffix' => 'required|digits:2|numeric',
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string'
        ]);

        $category = Category::findOrFail($request->category_id);
        $catcode = $category->id . $request->suffix;
        Log::info('Generated catcode: ' . $catcode);

        // Validate catcode directly
        $validator = validator(['catcode' => $catcode], [
            'catcode' => ['max:5', Rule::unique('tariffs', 'catcode')]
        ], [
            'catcode.max' => 'The catcode must not exceed 5 characters.',
            'catcode.unique' => 'The catcode ' . $catcode . ' is already in use.'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Tariff::create([
            'name' => $request->name,
            'catcode' => $catcode,
            'category_id' => $request->category_id,
            'amount' => $request->amount,
            'description' => $request->description,
            'status' => Auth::guard('staff')->user()->hasRole('super-admin') ? 'approved' : 'pending'
        ]);

        return redirect()->route('staff.tariffs.index')->with('success', 'Tariff creation request ' . (Auth::guard('staff')->user()->hasRole('super-admin') ? 'approved.' : 'submitted for approval.'));
    }

    public function update(Request $request, Tariff $tariff)
    {
        Log::info('Tariff update request:', $request->all());

        $request->validate([
            'name' => 'required|string|max:255',
            'suffix' => 'required|digits:2|numeric',
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string'
        ]);

        $category = Category::findOrFail($request->category_id);
        $catcode = $category->id . $request->suffix;
        Log::info('Generated catcode: ' . $catcode);

        $validator = validator(['catcode' => $catcode], [
            'catcode' => ['max:5', Rule::unique('tariffs', 'catcode')->ignore($tariff->id)]
        ], [
            'catcode.max' => 'The catcode must not exceed 5 characters.',
            'catcode.unique' => 'The catcode ' . $catcode . ' is already in use.'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $tariff->update([
            'name' => $request->name,
            'catcode' => $catcode,
            'category_id' => $request->category_id,
            'amount' => $request->amount,
            'description' => $request->description,
            'status' => Auth::guard('staff')->user()->hasRole('super-admin') ? 'approved' : 'pending'
        ]);

        return redirect()->route('staff.tariffs.index')->with('success', 'Tariff update request ' . (Auth::guard('staff')->user()->hasRole('super-admin') ? 'approved.' : 'submitted for approval.'));
    }

    public function destroy(Tariff $tariff)
    {
        $tariff->update(['status' => 'pending_delete']);
        $tariff->logAuditEvent('delete_requested');

        return redirect()->route('staff.tariffs.index')->with('success', 'Tariff deletion request submitted for approval.');
    }

    public function approve(Tariff $tariff)
    {
        $this->authorize('approve-tariff');

        if ($tariff->status === 'pending_delete') {
            $tariff->logAuditEvent('deleted');
            $tariff->delete();
        } else {
            $tariff->update(['status' => 'approved']);
            $tariff->logAuditEvent('approved');
        }

        return redirect()->route('staff.tariffs.index')->with('success', 'Tariff request approved.');
    }

    public function reject(Tariff $tariff)
    {
        $this->authorize('reject-tariff');

        $tariff->update(['status' => 'rejected']);
        $tariff->logAuditEvent('rejected');

        return redirect()->route('staff.tariffs.index')->with('error', 'Tariff request rejected.');
    }
}