<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Prescription;
use App\Models\Patient;
use App\Models\PatientDocument;
use App\Models\Pharmacist;

class PatientController extends Controller
{
    public function index()
    {
        if (session('role') !== 'patient') {
            return redirect()->route('home')->with('error', 'Patient authentication required.');
        }

        $pid = session('pid');
        $patient = Patient::find($pid);
        $doctors = Doctor::all();
        $specializations = Doctor::select('spec')->distinct()->pluck('spec');
        $appointments = Appointment::with('prescription')->where('pid', $pid)->orderBy('appdate', 'desc')->get();
        $prescriptions = Prescription::where('pid', $pid)->orderBy('appdate', 'desc')->get();
        $documents = PatientDocument::where('pid', $pid)->orderBy('report_date', 'desc')->get();
        $pharmacyCatalog = Pharmacist::whereNotNull('medicine')->get();

        return view('patient.dashboard', compact(
            'doctors',
            'specializations',
            'appointments',
            'prescriptions',
            'patient',
            'documents',
            'pharmacyCatalog'
        ));
    }

    public function getBookedSlots(Request $request)
    {
        $doctor = $request->query('doctor');
        $appdate = $request->query('appdate');

        if (!$doctor || !$appdate) {
            return response()->json(['booked_slots' => []]);
        }

        $bookedSlots = Appointment::where('doctor', $doctor)
            ->where('appdate', $appdate)
            ->where('doctorStatus', 1)
            ->where('userStatus', 1)
            ->pluck('apptime')
            ->map(function ($time) {
                // Return formatted H:i:s or standard time
                return substr($time, 0, 8);
            })
            ->toArray();

        return response()->json(['booked_slots' => $bookedSlots]);
    }

    public function updateEHR(Request $request)
    {
        if (session('role') !== 'patient') {
            return redirect()->route('home');
        }

        $pid = session('pid');
        $patient = Patient::find($pid);

        if ($patient) {
            $patient->update([
                'blood_group' => $request->input('blood_group', $patient->blood_group),
                'allergies' => $request->input('allergies', $patient->allergies),
                'chronic_conditions' => $request->input('chronic_conditions', $patient->chronic_conditions)
            ]);
        }

        return redirect()->route('patient.dashboard', ['#list-ehr'])->with('success', 'Health Profile & Medical History updated successfully!');
    }

    public function addDocument(Request $request)
    {
        if (session('role') !== 'patient') {
            return redirect()->route('home');
        }

        $request->validate([
            'title' => 'required|string|max:150',
            'report_type' => 'required|string|max:50',
            'report_date' => 'required|date',
            'laboratory' => 'nullable|string|max:100',
            'notes' => 'nullable|string'
        ]);

        PatientDocument::create([
            'pid' => session('pid'),
            'title' => $request->input('title'),
            'report_type' => $request->input('report_type'),
            'report_date' => $request->input('report_date'),
            'laboratory' => $request->input('laboratory') ?: 'DocOp Central Pathology Lab',
            'notes' => $request->input('notes')
        ]);

        return redirect()->route('patient.dashboard', ['#list-docs'])->with('success', 'Diagnostic lab report recorded in your health chart!');
    }

    public function bookAppointment(Request $request)
    {
        if (session('role') !== 'patient') {
            return redirect()->route('home');
        }

        $request->validate([
            'doctor' => 'required|string',
            'docFees' => 'required|numeric',
            'appdate' => 'required|date',
            'apptime' => 'required'
        ]);

        $doctor = $request->input('doctor');
        $docFees = $request->input('docFees');
        $appdate = $request->input('appdate');
        $apptime = $request->input('apptime');

        // Check if date is in the future
        $selectedDateTime = strtotime("$appdate $apptime");
        if ($selectedDateTime < time()) {
            return back()->with('error', 'Please select a date and time in the future!');
        }

        // Slot availability check
        $exists = Appointment::where('doctor', $doctor)
            ->where('appdate', $appdate)
            ->where('apptime', $apptime)
            ->where('doctorStatus', 1)
            ->where('userStatus', 1)
            ->exists();

        if ($exists) {
            return back()->with('error', 'We are sorry, the doctor is already booked at this time slot. Please choose another slot!');
        }

        Appointment::create([
            'pid' => session('pid'),
            'fname' => session('fname'),
            'lname' => session('lname'),
            'gender' => session('gender'),
            'email' => session('email'),
            'contact' => session('contact'),
            'doctor' => $doctor,
            'docFees' => $docFees,
            'appdate' => $appdate,
            'apptime' => $apptime,
            'userStatus' => 1,
            'doctorStatus' => 1,
            'payment' => 'Pay later'
        ]);

        return redirect()->route('patient.dashboard', ['#list-pat'])->with('success', 'Your appointment has been successfully booked!');
    }

    public function cancelAppointment($id)
    {
        if (session('role') !== 'patient') {
            return redirect()->route('home');
        }

        Appointment::where('ID', $id)->where('pid', session('pid'))->update(['userStatus' => 0]);

        return redirect()->route('patient.dashboard')->with('success', 'Your appointment was successfully cancelled.');
    }
}
