<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Employee::with('department');

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        if ($request->has('department') && $request->get('department') != '') {
            $query->where('department_id', $request->get('department'));
        }

        if ($request->has('status') && $request->get('status') != '') {
            $query->where('status', $request->get('status'));
        }

        $employees = $query->paginate(15);
        $departments = Department::all();

        return view('admin.employees.index', compact('employees', 'departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::where('is_active', true)->get();
        return view('admin.employees.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|unique:employees',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees',
            'phone' => 'nullable|string|max:20',
            'position' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'salary' => 'nullable|numeric|min:0',
            'hire_date' => 'required|date',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string',
            'employment_type' => 'required|in:full-time,part-time,contract,intern',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'skills' => 'nullable|string',
        ]);

        Employee::create($validated);

        return redirect()->route('admin.employees.index')
            ->with('success', 'Employee created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        $employee->load(['department', 'managedProjects', 'assignedTasks', 'equipment']);
        return view('admin.employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        $departments = Department::where('is_active', true)->get();
        return view('admin.employees.edit', compact('employee', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'employee_id' => 'required|unique:employees,employee_id,' . $employee->id,
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'phone' => 'nullable|string|max:20',
            'position' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'salary' => 'nullable|numeric|min:0',
            'hire_date' => 'required|date',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string',
            'employment_type' => 'required|in:full-time,part-time,contract,intern',
            'status' => 'required|in:active,inactive,terminated',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'skills' => 'nullable|string',
        ]);

        $employee->update($validated);

        return redirect()->route('admin.employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()->route('admin.employees.index')
            ->with('success', 'Employee deleted successfully.');
    }
}
