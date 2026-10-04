<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;

class HomeController extends Controller
{
    public function index()
    {
        try {
            $doctors = Doctor::all();
            $specializations = Doctor::select('spec')->distinct()->pluck('spec');
        } catch (\Throwable $e) {
            $doctors = collect();
            $specializations = collect();
        }
        return view('home', compact('doctors', 'specializations'));
    }

    public function services()
    {
        return view('services');
    }

    public function about()
    {
        return view('about');
    }
}
