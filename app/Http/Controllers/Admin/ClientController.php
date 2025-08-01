<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Client::withCount('projects');

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->get('status') != '') {
            $query->where('status', $request->get('status'));
        }

        if ($request->has('client_type') && $request->get('client_type') != '') {
            $query->where('client_type', $request->get('client_type'));
        }

        $clients = $query->paginate(15);

        return view('admin.clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.clients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|unique:clients',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'website' => 'nullable|url',
            'industry' => 'nullable|string|max:255',
            'client_type' => 'required|in:individual,small_business,enterprise,government',
            'status' => 'required|in:active,inactive,prospect',
            'notes' => 'nullable|string',
            'total_contract_value' => 'nullable|numeric|min:0',
            'contract_start_date' => 'nullable|date',
            'contract_end_date' => 'nullable|date|after_or_equal:contract_start_date',
        ]);

        Client::create($validated);

        return redirect()->route('admin.clients.index')
            ->with('success', 'Client created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        $client->load(['projects' => function($query) {
            $query->with('projectManager')->orderBy('created_at', 'desc');
        }]);

        return view('admin.clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        return view('admin.clients.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email,' . $client->id,
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'website' => 'nullable|url',
            'industry' => 'nullable|string|max:255',
            'client_type' => 'required|in:individual,small_business,enterprise,government',
            'status' => 'required|in:active,inactive,prospect',
            'notes' => 'nullable|string',
            'total_contract_value' => 'nullable|numeric|min:0',
            'contract_start_date' => 'nullable|date',
            'contract_end_date' => 'nullable|date|after_or_equal:contract_start_date',
        ]);

        $client->update($validated);

        return redirect()->route('admin.clients.index')
            ->with('success', 'Client updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        if ($client->projects()->count() > 0) {
            return redirect()->route('admin.clients.index')
                ->with('error', 'Cannot delete client with active projects. Please complete or reassign projects first.');
        }

        $client->delete();

        return redirect()->route('admin.clients.index')
            ->with('success', 'Client deleted successfully.');
    }
}
