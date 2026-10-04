<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Prescription;
use App\Models\Patient;
use App\Models\PatientDocument;

class DoctorController extends Controller
{
    public function index()
    {
        if (session('role') !== 'doctor') {
            return redirect()->route('home')->with('error', 'Doctor authentication required.');
        }

        $doctor = session('dname');
        $appointments = Appointment::with('prescription')->where('doctor', $doctor)->orderBy('appdate', 'desc')->get();
        $prescriptions = Prescription::where('doctor', $doctor)->orderBy('appdate', 'desc')->get();

        return view('doctor.dashboard', compact('doctor', 'appointments', 'prescriptions'));
    }

    public function cancelAppointment($id)
    {
        if (session('role') !== 'doctor') {
            return redirect()->route('home');
        }

        $doctor = session('dname');
        Appointment::where('ID', $id)->where('doctor', $doctor)->update(['doctorStatus' => 0]);

        return redirect()->route('doctor.dashboard')->with('success', 'Appointment successfully cancelled.');
    }

    public function prescribe(Request $request)
    {
        if (session('role') !== 'doctor') {
            return redirect()->route('home');
        }

        $pid = $request->query('pid');
        $patient = Patient::find($pid);
        $documents = PatientDocument::where('pid', $pid)->orderBy('report_date', 'desc')->get();

        $data = [
            'pid' => $pid,
            'ID' => $request->query('ID'),
            'fname' => $request->query('fname'),
            'lname' => $request->query('lname'),
            'appdate' => $request->query('appdate'),
            'apptime' => $request->query('apptime'),
            'doctor' => session('dname'),
            'patient' => $patient,
            'documents' => $documents
        ];

        return view('doctor.prescribe', $data);
    }

    public function storePrescription(Request $request)
    {
        if (session('role') !== 'doctor') {
            return redirect()->route('home');
        }

        $request->validate([
            'disease' => 'required|string',
            'allergy' => 'required|string',
            'prescription' => 'required|string'
        ]);

        $pid = $request->input('pid');

        Prescription::create([
            'doctor' => session('dname'),
            'pid' => $pid,
            'ID' => $request->input('ID'),
            'fname' => $request->input('fname'),
            'lname' => $request->input('lname'),
            'appdate' => $request->input('appdate'),
            'apptime' => $request->input('apptime'),
            'disease' => $request->input('disease'),
            'allergy' => $request->input('allergy'),
            'prescription' => $request->input('prescription'),
            'medicine' => $request->input('medicine') ?? ''
        ]);

        // If patient has no allergies recorded or updated, sync to EHR
        $patient = Patient::find($pid);
        if ($patient && ($patient->allergies === 'None' || empty($patient->allergies)) && !empty($request->input('allergy'))) {
            $patient->update(['allergies' => $request->input('allergy')]);
        }

        return redirect()->route('doctor.dashboard', ['#list-pres'])->with('success', 'Prescription issued successfully! Consultation marked as Completed.');
    }
}
