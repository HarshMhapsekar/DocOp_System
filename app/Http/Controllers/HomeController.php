<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;

class HomeController extends Controller
{
    public function index()
    {
        $doctors = Doctor::all();
        $specializations = Doctor::select('spec')->distinct()->pluck('spec');
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
