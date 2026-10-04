<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\ContactMessage;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $search_type = $request->input('search_type');
        $query_term = $request->input('contact')
            ?? $request->input('doctor_contact')
            ?? $request->input('patient_contact')
            ?? $request->input('app_contact')
            ?? $request->input('mes_contact')
            ?? $request->input('query')
            ?? $request->query('q', '');

        // Auto-detect search_type if triggered via button names
        if ($request->has('search_submit')) $search_type = 'doctor_patient';
        if ($request->has('doctor_search_submit')) $search_type = 'doctor';
        if ($request->has('patient_search_submit')) $search_type = 'patient';
        if ($request->has('app_search_submit')) $search_type = 'appointment';
        if ($request->has('mes_search_submit')) $search_type = 'message';

        $title = 'Search Results';
        $badge_text = '';
        $back_url = url('/');
        $columns = [];
        $rows = [];
        $error_msg = '';

        if (empty($query_term)) {
            $error_msg = 'Please enter a search term.';
        } else {
            switch ($search_type) {
                case 'doctor_patient':
                    $docname = session('dname');
                    $title = 'Patient Appointment Search';
                    $badge_text = 'Doctor Portal';
                    $back_url = route('doctor.dashboard');
                    $columns = ['Patient Name', 'Email', 'Contact', 'Appointment Date', 'Time Slot'];
                    $results = Appointment::where('contact', $query_term)
                        ->where('doctor', $docname)
                        ->orderBy('appdate', 'desc')
                        ->get();

                    foreach ($results as $r) {
                        $rows[] = [
                            'Patient Name' => $r->fname . ' ' . $r->lname,
                            'Email' => $r->email,
                            'Contact' => $r->contact,
                            'Appointment Date' => $r->appdate,
                            'Time Slot' => $r->apptime
                        ];
                    }
                    break;

                case 'doctor':
                    $title = 'Doctor Registry Search';
                    $badge_text = 'Admin Portal';
                    $back_url = route('admin.dashboard') . '#list-doc';
                    $columns = ['Doctor Username', 'Specialization', 'Email Address', 'Consultancy Fee'];
                    $results = Doctor::where('email', $query_term)->orWhere('username', $query_term)->get();

                    foreach ($results as $r) {
                        $rows[] = [
                            'Doctor Username' => 'Dr. ' . $r->username,
                            'Specialization' => '<span class="badge-modern badge-modern-primary">' . htmlspecialchars($r->spec) . '</span>',
                            'Email Address' => $r->email,
                            'Consultancy Fee' => '<span style="font-weight:700; color:#059669;">$' . htmlspecialchars($r->docFees) . '</span>'
                        ];
                    }
                    break;

                case 'patient':
                    $title = 'Patient Registry Search';
                    $badge_text = 'Admin Portal';
                    $back_url = route('admin.dashboard') . '#list-pat';
                    $columns = ['Patient ID', 'Full Name', 'Gender', 'Email Address', 'Phone Number'];
                    $results = Patient::where('contact', $query_term)->orWhere('email', $query_term)->get();

                    foreach ($results as $r) {
                        $rows[] = [
                            'Patient ID' => '#' . $r->pid,
                            'Full Name' => $r->fname . ' ' . $r->lname,
                            'Gender' => $r->gender,
                            'Email Address' => $r->email,
                            'Phone Number' => $r->contact
                        ];
                    }
                    break;

                case 'appointment':
                    $title = 'Appointment Booking Search';
                    $badge_text = 'Admin Portal';
                    $back_url = route('admin.dashboard') . '#list-app';
                    $columns = ['Patient Name', 'Email', 'Contact', 'Doctor', 'Fee', 'Date & Time', 'Status'];
                    $results = Appointment::where('contact', $query_term)->orWhere('email', $query_term)->orderBy('appdate', 'desc')->get();

                    foreach ($results as $r) {
                        $status = '<span class="badge-modern badge-modern-success"><i class="fa fa-check"></i> Active</span>';
                        if ($r->userStatus == 0 && $r->doctorStatus == 1) {
                            $status = '<span class="badge-modern badge-modern-danger"><i class="fa fa-times"></i> Cancelled by Patient</span>';
                        } elseif ($r->userStatus == 1 && $r->doctorStatus == 0) {
                            $status = '<span class="badge-modern badge-modern-warning"><i class="fa fa-exclamation-triangle"></i> Cancelled by Doctor</span>';
                        } elseif ($r->userStatus == 0 && $r->doctorStatus == 0) {
                            $status = '<span class="badge-modern badge-modern-danger">Cancelled</span>';
                        }

                        $rows[] = [
                            'Patient Name' => $r->fname . ' ' . $r->lname,
                            'Email' => $r->email,
                            'Contact' => $r->contact,
                            'Doctor' => 'Dr. ' . $r->doctor,
                            'Fee' => '<span style="font-weight:700; color:#059669;">$' . htmlspecialchars($r->docFees) . '</span>',
                            'Date & Time' => $r->appdate . ' ' . $r->apptime,
                            'Status' => $status
                        ];
                    }
                    break;

                case 'message':
                    $title = 'Inquiry Message Search';
                    $badge_text = 'Admin Portal';
                    $back_url = route('admin.dashboard') . '#list-mes';
                    $columns = ['Sender Name', 'Email Address', 'Phone Contact', 'Message Content'];
                    $results = ContactMessage::where('contact', $query_term)->orWhere('email', $query_term)->orderBy('id', 'desc')->get();

                    foreach ($results as $r) {
                        $rows[] = [
                            'Sender Name' => $r->name,
                            'Email Address' => $r->email,
                            'Phone Contact' => $r->contact,
                            'Message Content' => '<span style="max-width:350px; display:inline-block;">' . htmlspecialchars($r->message) . '</span>'
                        ];
                    }
                    break;

                default:
                    $error_msg = 'Unknown search category.';
            }
        }

        return view('search', compact('title', 'badge_text', 'back_url', 'columns', 'rows', 'error_msg', 'query_term', 'search_type'));
    }
}
