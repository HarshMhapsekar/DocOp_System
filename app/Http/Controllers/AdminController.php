<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Prescription;
use App\Models\ContactMessage;

class AdminController extends Controller
{
    public function index()
    {
        if (session('role') !== 'admin') {
            return redirect()->route('home')->with('error', 'Administrator authentication required.');
        }

        $doctors = Doctor::all();
        $patients = Patient::all();
        $appointments = Appointment::with('prescription')->orderBy('appdate', 'desc')->get();
        $prescriptions = Prescription::all();
        $messages = ContactMessage::all();

        $count_docs = $doctors->count();
        $count_pats = $patients->count();
        $count_apps = $appointments->count();
        $count_pres = $prescriptions->count();

        return view('admin.dashboard', compact(
            'doctors', 'patients', 'appointments', 'prescriptions', 'messages',
            'count_docs', 'count_pats', 'count_apps', 'count_pres'
        ));
    }

    public function addDoctor(Request $request)
    {
        if (session('role') !== 'admin') {
            return redirect()->route('home');
        }

        $request->validate([
            'doctor' => 'required|string|max:50',
            'dpassword' => 'required|string',
            'demail' => 'required|email|max:50',
            'special' => 'required|string|max:50',
            'docFees' => 'required|numeric'
        ]);

        Doctor::create([
            'username' => $request->input('doctor'),
            'password' => $request->input('dpassword'),
            'email' => $request->input('demail'),
            'spec' => $request->input('special'),
            'docFees' => $request->input('docFees'),
        ]);

        return redirect()->route('admin.dashboard', ['#list-doc'])->with('success', 'Doctor registered successfully!');
    }

    public function deleteDoctor(Request $request)
    {
        if (session('role') !== 'admin') {
            return redirect()->route('home');
        }

        $email = $request->input('demail');
        Doctor::where('email', $email)->delete();

        return redirect()->route('admin.dashboard', ['#list-doc'])->with('success', 'Doctor removed successfully!');
    }

    public function updatePayment(Request $request)
    {
        if (session('role') !== 'admin') {
            return redirect()->route('home');
        }

        $contact = $request->input('contact');
        $status = $request->input('status');

        Appointment::where('contact', $contact)->update(['payment' => $status]);

        return redirect()->route('admin.dashboard', ['#list-app'])->with('success', 'Payment status updated successfully!');
    }
}
