@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Fetched Employee Data from HRM System</h4>
                </div>
                <div class="card-body">
                    @if(isset($error))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error:</strong> {{ $error }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @elseif(empty($data) || !is_array($data))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <strong>No Data:</strong> No employee data was retrieved from the HRM system.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @else
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success:</strong> Retrieved {{ count($data) }} employee records from HRM system.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Employee ID</th>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Department</th>
                                        <th>Position</th>
                                        <th>Status</th>
                                        <th>Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $employee)
                                    <tr>
                                        <td>{{ $employee['employee_id'] ?? 'N/A' }}</td>
                                        <td>
                                            {{ $employee['first_name'] ?? '' }} 
                                            {{ $employee['middle_name'] ?? '' }} 
                                            {{ $employee['surname'] ?? '' }}
                                        </td>
                                        <td>{{ $employee['email'] ?? 'N/A' }}</td>
                                        <td>{{ $employee['department']['department_name'] ?? 'N/A' }}</td>
                                        <td>{{ $employee['rank']['title'] ?? $employee['rank']['name'] ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge 
                                                @if(strtolower($employee['status'] ?? '') === 'active') 
                                                    bg-success 
                                                @elseif(strtolower($employee['status'] ?? '') === 'suspended') 
                                                    bg-warning 
                                                @else 
                                                    bg-secondary 
                                                @endif">
                                                {{ $employee['status'] ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary view-details-btn" 
                                                    data-employee='@json($employee)'>
                                                View Details
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination if needed -->
                        @if(request()->has('page'))
                            {{ $data->links() }}
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Employee Details Modal -->
<div class="modal fade" id="employeeDetailsModal" tabindex="-1" aria-labelledby="employeeDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="employeeDetailsModalLabel">Employee Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="employeeDetailsContent">
                <!-- Employee details will be loaded here via JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle view details button clicks
    document.querySelectorAll('.view-details-btn').forEach(button => {
        button.addEventListener('click', function() {
            const employee = JSON.parse(this.getAttribute('data-employee'));
            displayEmployeeDetails(employee);
            const modal = new bootstrap.Modal(document.getElementById('employeeDetailsModal'));
            modal.show();
        });
    });
    
    function displayEmployeeDetails(employee) {
        const content = document.getElementById('employeeDetailsContent');
        
        let html = `
            <div class="row">
                <div class="col-md-6">
                    <h6>Personal Information</h6>
                    <table class="table table-sm">
                        <tr><td><strong>Employee ID:</strong></td><td>${employee.employee_id || 'N/A'}</td></tr>
                        <tr><td><strong>Full Name:</strong></td><td>${employee.first_name || ''} ${employee.middle_name || ''} ${employee.surname || ''}</td></tr>
                        <tr><td><strong>Gender:</strong></td><td>${employee.gender || 'N/A'}</td></tr>
                        <tr><td><strong>Date of Birth:</strong></td><td>${employee.date_of_birth || 'N/A'}</td></tr>
                        <tr><td><strong>Age:</strong></td><td>${calculateAge(employee.date_of_birth) || 'N/A'}</td></tr>
                        <tr><td><strong>Nationality:</strong></td><td>${employee.nationality || 'N/A'}</td></tr>
                        <tr><td><strong>NIN:</strong></td><td>${employee.nin || 'N/A'}</td></tr>
                        <tr><td><strong>Email:</strong></td><td>${employee.email || 'N/A'}</td></tr>
                        <tr><td><strong>Mobile:</strong></td><td>${employee.mobile_no || 'N/A'}</td></tr>
                        <tr><td><strong>Address:</strong></td><td>${employee.address || 'N/A'}</td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6>Employment Information</h6>
                    <table class="table table-sm">
                        <tr><td><strong>Department:</strong></td><td>${employee.department?.department_name || 'N/A'}</td></tr>
                        <tr><td><strong>Cadre:</strong></td><td>${employee.cadre?.name || 'N/A'}</td></tr>
                        <tr><td><strong>Rank:</strong></td><td>${employee.rank?.title || employee.rank?.name || 'N/A'}</td></tr>
                        <tr><td><strong>Grade Level:</strong></td><td>${employee.grade_level?.name || 'N/A'}</td></tr>
                        <tr><td><strong>Step:</strong></td><td>${employee.step?.name || 'N/A'}</td></tr>
                        <tr><td><strong>Basic Salary:</strong></td><td>${employee.step?.basic_salary ? '₦' + parseFloat(employee.step.basic_salary).toLocaleString() : 'N/A'}</td></tr>
                        <tr><td><strong>Staff No:</strong></td><td>${employee.staff_no || 'N/A'}</td></tr>
                        <tr><td><strong>Status:</strong></td><td>${employee.status || 'N/A'}</td></tr>
                        <tr><td><strong>Date of Appointment:</strong></td><td>${employee.date_of_first_appointment || 'N/A'}</td></tr>
                        <tr><td><strong>Years of Service:</strong></td><td>${employee.years_of_service || 'N/A'}</td></tr>
                        <tr><td><strong>Expected Retirement:</strong></td><td>${employee.expected_retirement_date || 'N/A'}</td></tr>
                    </table>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <h6>Location Information</h6>
                    <table class="table table-sm">
                        <tr><td><strong>State:</strong></td><td>${employee.state?.name || 'N/A'}</td></tr>
                        <tr><td><strong>LGA:</strong></td><td>${employee.lga?.name || 'N/A'}</td></tr>
                        <tr><td><strong>Ward:</strong></td><td>${employee.ward?.ward_name || 'N/A'}</td></tr>
                        <tr><td><strong>Appointment Type:</strong></td><td>${employee.appointment_type_id || 'N/A'}</td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6>Banking Information</h6>
                    <table class="table table-sm">
                        <tr><td><strong>Bank Name:</strong></td><td>${employee.bank?.bank_name || 'N/A'}</td></tr>
                        <tr><td><strong>Bank Code:</strong></td><td>${employee.bank?.bank_code || 'N/A'}</td></tr>
                        <tr><td><strong>Account Name:</strong></td><td>${employee.bank?.account_name || 'N/A'}</td></tr>
                        <tr><td><strong>Account No:</strong></td><td>${employee.bank?.account_no || 'N/A'}</td></tr>
                    </table>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <h6>Next of Kin</h6>
                    <table class="table table-sm">
                        <tr><td><strong>Name:</strong></td><td>${employee.next_of_kin?.name || 'N/A'}</td></tr>
                        <tr><td><strong>Relationship:</strong></td><td>${employee.next_of_kin?.relationship || 'N/A'}</td></tr>
                        <tr><td><strong>Mobile:</strong></td><td>${employee.next_of_kin?.mobile_no || 'N/A'}</td></tr>
                        <tr><td><strong>Address:</strong></td><td>${employee.next_of_kin?.address || 'N/A'}</td></tr>
                        <tr><td><strong>Occupation:</strong></td><td>${employee.next_of_kin?.occupation || 'N/A'}</td></tr>
                        <tr><td><strong>Place of Work:</strong></td><td>${employee.next_of_kin?.place_of_work || 'N/A'}</td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6>Additional Information</h6>
                    <table class="table table-sm">
                        <tr><td><strong>Highest Qualification:</strong></td><td>${employee.highest_certificate || 'N/A'}</td></tr>
                        <tr><td><strong>Pay Point:</strong></td><td>${employee.pay_point || 'N/A'}</td></tr>
                        <tr><td><strong>Contract Start:</strong></td><td>${employee.contract_start_date || 'N/A'}</td></tr>
                        <tr><td><strong>Contract End:</strong></td><td>${employee.contract_end_date || 'N/A'}</td></tr>
                    </table>
                </div>
            </div>
        `;
        
        content.innerHTML = html;
    }
    
    function calculateAge(dateOfBirth) {
        if (!dateOfBirth) return null;
        
        const birthDate = new Date(dateOfBirth);
        const today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();
        
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        
        return age;
    }
});
</script>
@endsection