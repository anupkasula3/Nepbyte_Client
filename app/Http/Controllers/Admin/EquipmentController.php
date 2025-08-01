<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Equipment;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Equipment::with('assignedEmployee');

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('asset_tag', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->get('status') != '') {
            $query->where('status', $request->get('status'));
        }

        if ($request->has('category') && $request->get('category') != '') {
            $query->where('category', $request->get('category'));
        }

        $equipment = $query->paginate(15);

        return view('admin.equipment.index', compact('equipment'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::where('status', 'active')->get();

        return view('admin.equipment.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'asset_tag' => 'required|string|max:255|unique:equipment',
            'serial_number' => 'nullable|string|max:255',
            'model' => 'required|string|max:255',
            'manufacturer' => 'required|string|max:255',
            'category' => 'required|in:laptop,desktop,server,network,mobile,printer,other',
            'status' => 'required|in:available,assigned,maintenance,retired',
            'assigned_to' => 'nullable|exists:employees,id',
            'purchase_date' => 'required|date',
            'purchase_price' => 'required|numeric|min:0',
            'warranty_expiry' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'specifications' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        Equipment::create($validated);

        return redirect()->route('admin.equipment.index')
            ->with('success', 'Equipment created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Equipment $equipment)
    {
        $equipment->load('assignedEmployee');

        return view('admin.equipment.show', compact('equipment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Equipment $equipment)
    {
        $employees = Employee::where('status', 'active')->get();

        return view('admin.equipment.edit', compact('equipment', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Equipment $equipment)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'asset_tag' => 'required|string|max:255|unique:equipment,asset_tag,' . $equipment->id,
            'serial_number' => 'nullable|string|max:255',
            'model' => 'required|string|max:255',
            'manufacturer' => 'required|string|max:255',
            'category' => 'required|in:laptop,desktop,server,network,mobile,printer,other',
            'status' => 'required|in:available,assigned,maintenance,retired',
            'assigned_to' => 'nullable|exists:employees,id',
            'purchase_date' => 'required|date',
            'purchase_price' => 'required|numeric|min:0',
            'warranty_expiry' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'specifications' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $equipment->update($validated);

        return redirect()->route('admin.equipment.index')
            ->with('success', 'Equipment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Equipment $equipment)
    {
        $equipment->delete();

        return redirect()->route('admin.equipment.index')
            ->with('success', 'Equipment deleted successfully.');
    }
}
