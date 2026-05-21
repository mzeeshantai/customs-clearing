<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Cartage;

class CartageController extends Controller
{
    public function store(Request $request)
    {
        \Log::info('Cartage store request:', $request->all());
        $validated = $request->validate([
            'port_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        Cartage::create($validated);

        return redirect()->back()->with('success', 'Cartage entry added successfully.');
    }

    public function update(Request $request, Cartage $cartage)
    {
        $validated = $request->validate([
            'port_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        $cartage->update($validated);

        return redirect()->back()->with('success', 'Cartage entry updated successfully.');
    }

    public function destroy(Cartage $cartage)
    {
        $cartage->delete();

        return redirect()->back()->with('success', 'Cartage entry deleted successfully.');
    }
}
