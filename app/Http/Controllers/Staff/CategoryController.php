<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Services\BreadcrumbService;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:staff', 'permission:view-categories'])->only('index');
        $this->middleware(['auth:staff', 'permission:create-category'])->only('store');
        $this->middleware(['auth:staff', 'permission:edit-category'])->only('update');
        $this->middleware(['auth:staff', 'permission:delete-category'])->only('destroy');
        $this->middleware(['auth:staff', 'permission:approve-category'])->only('approve');
        $this->middleware(['auth:staff', 'permission:reject-category'])->only('reject');
    }

    public function index(Request $request)
    {
        // Set breadcrumbs
        $breadcrumb = app(BreadcrumbService::class);
        $breadcrumb->addHome()->add('Category Management');

        $categories = Category::when($request->search_category, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
        })->paginate(10);

        // Analytics for Insights section
        $total_categories = Category::count();
        $status_counts = Category::select('status')
            ->groupBy('status')
            ->pluck('status')
            ->mapWithKeys(function ($status) {
                return [$status => Category::where('status', $status)->count()];
            })->toArray();
        $tariffs_per_category = Category::withCount('tariffs')
            ->get()
            ->pluck('tariffs_count', 'name')
            ->toArray();

        return view('staff.categories.index', compact('categories', 'total_categories', 'status_counts', 'tariffs_per_category'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:categories,code',
            'description' => 'nullable|string'
        ]);

        Category::create([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'status' => Auth::guard('staff')->user()->hasRole('super-admin') ? 'approved' : 'pending'
        ]);

        return redirect()->route('staff.categories.index')->with('success', 'Category creation request ' . (Auth::guard('staff')->user()->hasRole('super-admin') ? 'approved.' : 'submitted for approval.'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => ['required', 'string', Rule::unique('categories')->ignore($category->id)],
            'description' => 'nullable|string'
        ]);

        $category->update([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'status' => Auth::guard('staff')->user()->hasRole('super-admin') ? 'approved' : 'pending'
        ]);

        return redirect()->route('staff.categories.index')->with('success', 'Category update request ' . (Auth::guard('staff')->user()->hasRole('super-admin') ? 'approved.' : 'submitted for approval.'));
    }

    public function destroy(Category $category)
    {
        $category->update(['status' => 'pending_delete']);
        $category->logAuditEvent('delete_requested');

        return redirect()->route('staff.categories.index')->with('success', 'Category deletion request submitted for approval.');
    }

    public function approve(Category $category)
    {
        $this->authorize('approve-category');

        if ($category->status === 'pending_delete') {
            $category->logAuditEvent('deleted');
            $category->delete();
        } else {
            $category->update(['status' => 'approved']);
            $category->logAuditEvent('approved');
        }

        return redirect()->route('staff.categories.index')->with('success', 'Category request approved.');
    }

    public function reject(Category $category)
    {
        $this->authorize('reject-category');

        $category->update(['status' => 'rejected']);
        $category->logAuditEvent('rejected');

        return redirect()->route('staff.categories.index')->with('error', 'Category request rejected.');
    }
}