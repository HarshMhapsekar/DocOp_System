<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prescription;
use App\Models\Pharmacist;
use App\Models\Doctor;

class PharmacistController extends Controller
{
    public function index()
    {
        if (session('role') !== 'pharmacist') {
            return redirect()->route('home')->with('error', 'Pharmacist authentication required.');
        }

        $prescriptions = Prescription::orderBy('appdate', 'desc')->get();
        $medicines = Pharmacist::whereNotNull('medicine')->orderBy('id', 'desc')->get();
        $doctors = Doctor::all();

        // Calculate low stock metrics
        $lowStockCount = $medicines->where('stock_qty', '<=', 10)->count();

        return view('pharmacist.dashboard', compact('prescriptions', 'medicines', 'doctors', 'lowStockCount'));
    }

    public function addMedicine(Request $request)
    {
        if (session('role') !== 'pharmacist') {
            return redirect()->route('home');
        }

        $request->validate([
            'special' => 'required|string|max:150',
            'doctor' => 'required|string|max:50',
            'bill' => 'required|numeric'
        ]);

        $medicineName = $request->input('special');
        $stockQty = $request->input('stock_qty') ? intval($request->input('stock_qty')) : 50;

        // Check if an existing inventory entry exists for this formula to decrement or update
        $existing = Pharmacist::where('medicine', $medicineName)->first();
        if ($existing) {
            $existing->decrement('stock_qty', 1);
            $stockQty = max(0, $existing->stock_qty);
        }

        Pharmacist::create([
            'medicine' => $medicineName,
            'doctor' => $request->input('doctor'),
            'bill' => $request->input('bill'),
            'stock_qty' => $stockQty,
            'username' => session('username'),
            'password' => ''
        ]);

        return redirect()->route('pharmacist.dashboard', ['#list-stock'])->with('success', 'Medicine dispensed and inventory updated successfully!');
    }

    public function restockMedicine(Request $request)
    {
        if (session('role') !== 'pharmacist') {
            return redirect()->route('home');
        }

        $request->validate([
            'id' => 'required|integer',
            'add_qty' => 'required|integer|min:1'
        ]);

        $item = Pharmacist::find($request->input('id'));
        if ($item) {
            $item->increment('stock_qty', $request->input('add_qty'));
            return redirect()->route('pharmacist.dashboard', ['#list-stock'])->with('success', 'Stock replenished for ' . $item->medicine . '! Added ' . $request->input('add_qty') . ' units.');
        }

        return redirect()->route('pharmacist.dashboard')->with('error', 'Medicine item not found.');
    }

    public function deleteMedicine($id)
    {
        if (session('role') !== 'pharmacist') {
            return redirect()->route('home');
        }

        Pharmacist::where('id', $id)->whereNotNull('medicine')->delete();

        return redirect()->route('pharmacist.dashboard', ['#list-stock'])->with('success', 'Medicine item removed from stock inventory.');
    }
}
