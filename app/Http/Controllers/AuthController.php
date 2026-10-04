<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Admin;
use App\Models\Pharmacist;

class AuthController extends Controller
{
    public function showPatientLogin()
    {
        if (session('role') === 'patient') {
            return redirect()->route('patient.dashboard');
        }
        return view('auth.patient-login');
    }

    public function showStaffLogin()
    {
        if (session('role') === 'doctor') {
            return redirect()->route('doctor.dashboard');
        }
        if (session('role') === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        if (session('role') === 'pharmacist') {
            return redirect()->route('pharmacist.dashboard');
        }
        return view('auth.staff-login');
    }

    public function registerPatient(Request $request)
    {
        $request->validate([
            'fname' => 'required|string|max:30',
            'lname' => 'required|string|max:30',
            'email' => 'required|email|max:50',
            'contact' => 'required|string|max:15',
            'password' => 'required|string|min:6',
            'cpassword' => 'required|same:password',
            'gender' => 'required|string'
        ]);

        $patient = Patient::create([
            'fname' => $request->input('fname'),
            'lname' => $request->input('lname'),
            'gender' => $request->input('gender'),
            'email' => $request->input('email'),
            'contact' => $request->input('contact'),
            'password' => $request->input('password'),
            'cpassword' => $request->input('cpassword')
        ]);

        session([
            'role' => 'patient',
            'pid' => $patient->pid,
            'username' => $patient->fname . ' ' . $patient->lname,
            'fname' => $patient->fname,
            'lname' => $patient->lname,
            'gender' => $patient->gender,
            'contact' => $patient->contact,
            'email' => $patient->email
        ]);

        return redirect()->route('patient.dashboard')->with('success', 'Registration successful! Welcome to DocOp.');
    }

    public function loginPatient(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password2') ?? $request->input('password');

        $patient = Patient::where('email', $email)->where('password', $password)->first();

        if ($patient) {
            session([
                'role' => 'patient',
                'pid' => $patient->pid,
                'username' => $patient->fname . ' ' . $patient->lname,
                'fname' => $patient->fname,
                'lname' => $patient->lname,
                'gender' => $patient->gender,
                'contact' => $patient->contact,
                'email' => $patient->email
            ]);
            return redirect()->route('patient.dashboard');
        }

        return back()->with('error', 'Invalid Patient Email or Password. Try Again!');
    }

    public function loginDoctor(Request $request)
    {
        $username = $request->input('username3') ?? $request->input('username');
        $password = $request->input('password3') ?? $request->input('password');

        $doctor = Doctor::where('username', $username)->where('password', $password)->first();

        if ($doctor) {
            session([
                'role' => 'doctor',
                'dname' => $doctor->username,
                'username' => $doctor->username,
                'email' => $doctor->email,
                'spec' => $doctor->spec
            ]);
            return redirect()->route('doctor.dashboard');
        }

        return back()->with('error', 'Invalid Doctor Username or Password. Try Again!');
    }

    public function loginAdmin(Request $request)
    {
        $username = $request->input('username1') ?? $request->input('username');
        $password = $request->input('password2') ?? $request->input('password');

        $admin = Admin::where('username', $username)->where('password', $password)->first();

        if ($admin) {
            session([
                'role' => 'admin',
                'username' => $admin->username
            ]);
            return redirect()->route('admin.dashboard');
        }

        return back()->with('error', 'Invalid Administrator Credentials. Try Again!');
    }

    public function loginPharmacist(Request $request)
    {
        $username = $request->input('username1') ?? $request->input('username');
        $password = $request->input('password2') ?? $request->input('password');

        $pharmacist = Pharmacist::where('username', $username)->where('password', $password)->first();

        if ($pharmacist) {
            session([
                'role' => 'pharmacist',
                'username' => $pharmacist->username
            ]);
            return redirect()->route('pharmacist.dashboard');
        }

        return back()->with('error', 'Invalid Pharmacist Credentials. Try Again!');
    }

    public function logout(Request $request)
    {
        $role = session('role');
        $request->session()->flush();

        if ($role === 'doctor') {
            return redirect()->route('staff.login.view', ['role' => 'doctor'])->with('success', 'You have been logged out of Doctor Console.');
        }

        if ($role === 'admin') {
            return redirect()->route('staff.login.view', ['role' => 'admin'])->with('success', 'You have been logged out of Admin Console.');
        }

        if ($role === 'pharmacist') {
            return redirect()->route('staff.login.view', ['role' => 'pharmacist'])->with('success', 'You have been logged out of Pharmacy Console.');
        }

        return redirect()->route('home')->with('success', 'You have been safely logged out.');
    }
}
