<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\HrmService;

class EmployeeController extends Controller
{
    protected $hrmService;

    public function __construct(HrmService $hrmService)
    {
        $this->hrmService = $hrmService;
    }

    /**
     * Fetch employee data from external HRM system
     */
    public function fetchFromHrm(Request $request)
    {
        try {
            $filters = $request->only(['department', 'status', 'search']);
            $hrmData = $this->hrmService->getEmployees($filters);

            if ($hrmData) {
                return response()->json([
                    'success' => true,
                    'data' => $hrmData['data'] ?? [],
                    'message' => 'Staff data fetched successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch staff data from HRM system'
                ], 500);
            }
        } catch (\Exception $e) {
            \Log::error('Error fetching staff data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching staff data: ' . $e->getMessage()
            ], 500);
        }
    }
}