<?php

namespace App\Http\Controllers;

use App\Models\CustomerComplaint as ComplaintService;
use App\Services\GLPIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerComplaintController extends Controller
{
    protected $glpiService;

    public function __construct(GLPIService $glpiService)
    {
        $this->glpiService = $glpiService;
    }

    /**
     * Show the form for creating a new complaint
     */
    public function create()
    {
        return view('customer.complaints.create');
    }

    /**
     * Store a newly created complaint in storage
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'nullable|string|max:100',
            'priority' => 'nullable|in:low,medium,high,urgent',
        ]);

        // Get the authenticated customer
        $customer = Auth::guard('customer')->user();

        // Create complaint in GLPI
        $glpiResponse = ComplaintService::createInGLPI([
            'subject' => $request->subject,
            'description' => $request->description,
            'category' => $request->category,
            'priority' => $request->priority ?? 'medium',
            'customer_id' => $customer->id,
        ], $this->glpiService);

        if ($glpiResponse) {
            return redirect()->route('customer.complaints.index')
                ->with('success', 'Your complaint has been submitted successfully. Ticket ID: ' . ($glpiResponse['id'] ?? 'N/A'));
        } else {
            \Log::error('Failed to create GLPI ticket for customer: ' . $customer->id);
            
            return redirect()->route('customer.complaints.index')
                ->with('error', 'There was an issue submitting your complaint. Please try again later.');
        }
    }

    /**
     * Display a listing of the customer's complaints
     */
    public function index()
    {
        $customer = Auth::guard('customer')->user();
        
        // Get complaints directly from GLPI
        $glpiResponse = ComplaintService::getAllForCustomer($customer->id, $this->glpiService);

        $complaints = [];
        if ($glpiResponse && isset($glpiResponse['data'])) {
            $complaints = $glpiResponse['data'];
        }

        // Create a paginator manually since we're using GLPI data
        $perPage = 10;
        $currentPage = request()->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        
        $currentItems = array_slice($complaints, $offset, $perPage);
        $totalItems = count($complaints);
        
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $totalItems,
            $perPage,
            $currentPage,
            [
                'path' => request()->url(),
                'pageName' => 'page',
            ]
        );

        return view('customer.complaints.index', ['complaints' => $paginator]);
    }

    /**
     * Display the specified complaint
     */
    public function show($id)
    {
        // Get complaint directly from GLPI using the ticket ID
        $complaint = ComplaintService::findInGLPI($id, $this->glpiService);

        if (!$complaint) {
            abort(404, 'Complaint not found');
        }

        // Additional check to ensure this belongs to the authenticated customer
        $customer = Auth::guard('customer')->user();
        
        // In GLPI, we need to verify that the ticket belongs to the customer
        // This is a simplified check - in real implementation you might need to verify the requester ID
        if (isset($complaint['users_id_requester']) && $complaint['users_id_requester'] != $customer->id) {
            abort(403, 'Unauthorized to access this complaint');
        }
        
        return view('customer.complaints.show', compact('complaint'));
    }
}
