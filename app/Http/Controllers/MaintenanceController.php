<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index()
    {
        $maintenances = Maintenance::orderBy('created_at', 'desc')->get();

        return view('maintenance.index', compact('maintenances'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tenant_name' => 'required|string|max:255',
            'room_number' => 'nullable|string|max:100',
            'category' => 'required|string|max:100',
            'description' => 'required|string|max:2000',
        ]);

        Maintenance::create([
            'tenant_name' => $validated['tenant_name'],
            'room_number' => $validated['room_number'],
            'category' => $validated['category'],
            'description' => $validated['description'],
            'status' => Maintenance::STATUS_NEW,
        ]);

        return redirect()->route('maintenance.index')->with('success', 'Komplain maintenance berhasil dikirim. Tim akan menindaklanjuti segera.');
    }

    public function manageIndex()
    {
        $maintenances = Maintenance::orderBy('created_at', 'desc')->get();

        return view('maintenance.manage', compact('maintenances'));
    }

    public function updateStatus(Request $request, Maintenance $maintenance)
    {
        $validated = $request->validate([
            'action' => 'required|in:' . implode(',', [
                Maintenance::STATUS_IN_PROGRESS,
                Maintenance::STATUS_RESOLVED,
                Maintenance::STATUS_REJECTED,
            ]),
            'owner_notes' => 'nullable|string|max:2000',
        ]);

        $maintenance->status = $validated['action'];
        $maintenance->owner_notes = $validated['owner_notes'];
        $maintenance->save();

        return redirect()->route('pemilik.maintenance')->with('success', 'Status komplain berhasil diperbarui.');
    }
}
