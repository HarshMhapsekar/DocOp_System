<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Admin;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Pharmacist;
use App\Models\Appointment;
use App\Models\Prescription;
use App\Models\ContactMessage;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin Accounts
        Admin::updateOrCreate(
            ['username' => 'admin'],
            ['password' => 'admin123']
        );

        // 2. Doctor Accounts across specialties
        $doctors = [
            ['username' => 'ashok', 'password' => 'ashok123', 'email' => 'ashok@gmail.com', 'spec' => 'General', 'docFees' => 500],
            ['username' => 'arun', 'password' => 'arun123', 'email' => 'arun@gmail.com', 'spec' => 'Cardiologist', 'docFees' => 600],
            ['username' => 'Dinesh', 'password' => 'dinesh123', 'email' => 'dinesh@gmail.com', 'spec' => 'General', 'docFees' => 700],
            ['username' => 'Ganesh', 'password' => 'ganesh123', 'email' => 'ganesh@gmail.com', 'spec' => 'Pediatrician', 'docFees' => 550],
            ['username' => 'Kumar', 'password' => 'kumar123', 'email' => 'kumar@gmail.com', 'spec' => 'Pediatrician', 'docFees' => 800],
            ['username' => 'Amit', 'password' => 'amit123', 'email' => 'amit@gmail.com', 'spec' => 'Cardiologist', 'docFees' => 1000],
            ['username' => 'Shubham', 'password' => 'shubham123', 'email' => 'shubham@gmail.com', 'spec' => 'Neurologist', 'docFees' => 1500],
            ['username' => 'Tiwary', 'password' => 'tiwary123', 'email' => 'tiwary@gmail.com', 'spec' => 'Pediatrician', 'docFees' => 450],
            ['username' => 'ananya', 'password' => 'ananya123', 'email' => 'ananya@gmail.com', 'spec' => 'Dermatologist', 'docFees' => 650],
            ['username' => 'priyesh', 'password' => 'priyesh123', 'email' => 'priyesh@gmail.com', 'spec' => 'Orthopedic', 'docFees' => 900],
        ];

        foreach ($doctors as $doc) {
            Doctor::updateOrCreate(
                ['username' => $doc['username']],
                $doc
            );
        }

        // 3. Patient Accounts
        $patients = [
            [
                'pid' => 1,
                'fname' => 'Ram',
                'lname' => 'Kumar',
                'gender' => 'Male',
                'email' => 'ram@gmail.com',
                'contact' => '9876543210',
                'password' => 'ram123',
                'cpassword' => 'ram123'
            ],
            [
                'pid' => 2,
                'fname' => 'Kishan',
                'lname' => 'Lal',
                'gender' => 'Male',
                'email' => 'kishansmart0@gmail.com',
                'contact' => '8838489464',
                'password' => 'kishan123',
                'cpassword' => 'kishan123'
            ],
            [
                'pid' => 3,
                'fname' => 'Gautam',
                'lname' => 'Shankararam',
                'gender' => 'Male',
                'email' => 'gautam@gmail.com',
                'contact' => '9070897653',
                'password' => 'gautam123',
                'cpassword' => 'gautam123'
            ],
            [
                'pid' => 4,
                'fname' => 'Priya',
                'lname' => 'Sharma',
                'gender' => 'Female',
                'email' => 'priya@gmail.com',
                'contact' => '9820123456',
                'password' => 'priya123',
                'cpassword' => 'priya123'
            ],
            [
                'pid' => 5,
                'fname' => 'Anjali',
                'lname' => 'Verma',
                'gender' => 'Female',
                'email' => 'anjali@gmail.com',
                'contact' => '9711234567',
                'password' => 'anjali123',
                'cpassword' => 'anjali123'
            ],
            [
                'pid' => 6,
                'fname' => 'Suresh',
                'lname' => 'Raina',
                'gender' => 'Male',
                'email' => 'suresh@gmail.com',
                'contact' => '9988776655',
                'password' => 'suresh123',
                'cpassword' => 'suresh123'
            ],
        ];

        foreach ($patients as $pat) {
            Patient::updateOrCreate(
                ['email' => $pat['email']],
                $pat
            );
        }

        // 4. Appointments
        DB::table('appointmenttb')->truncate();

        $appointments = [
            [
                'ID' => 1,
                'pid' => 1,
                'fname' => 'Ram',
                'lname' => 'Kumar',
                'gender' => 'Male',
                'email' => 'ram@gmail.com',
                'contact' => '9876543210',
                'doctor' => 'ashok',
                'docFees' => 500,
                'appdate' => '2026-10-06',
                'apptime' => '10:00:00',
                'userStatus' => 1,
                'doctorStatus' => 1
            ],
            [
                'ID' => 2,
                'pid' => 1,
                'fname' => 'Ram',
                'lname' => 'Kumar',
                'gender' => 'Male',
                'email' => 'ram@gmail.com',
                'contact' => '9876543210',
                'doctor' => 'arun',
                'docFees' => 600,
                'appdate' => '2026-10-08',
                'apptime' => '14:00:00',
                'userStatus' => 1,
                'doctorStatus' => 1
            ],
            [
                'ID' => 3,
                'pid' => 2,
                'fname' => 'Kishan',
                'lname' => 'Lal',
                'gender' => 'Male',
                'email' => 'kishansmart0@gmail.com',
                'contact' => '8838489464',
                'doctor' => 'Dinesh',
                'docFees' => 700,
                'appdate' => '2026-10-05',
                'apptime' => '11:00:00',
                'userStatus' => 1,
                'doctorStatus' => 1
            ],
            [
                'ID' => 4,
                'pid' => 2,
                'fname' => 'Kishan',
                'lname' => 'Lal',
                'gender' => 'Male',
                'email' => 'kishansmart0@gmail.com',
                'contact' => '8838489464',
                'doctor' => 'Shubham',
                'docFees' => 1500,
                'appdate' => '2026-10-09',
                'apptime' => '16:00:00',
                'userStatus' => 1,
                'doctorStatus' => 1
            ],
            [
                'ID' => 5,
                'pid' => 3,
                'fname' => 'Gautam',
                'lname' => 'Shankararam',
                'gender' => 'Male',
                'email' => 'gautam@gmail.com',
                'contact' => '9070897653',
                'doctor' => 'Ganesh',
                'docFees' => 550,
                'appdate' => '2026-10-05',
                'apptime' => '09:00:00',
                'userStatus' => 1,
                'doctorStatus' => 1
            ],
            [
                'ID' => 6,
                'pid' => 4,
                'fname' => 'Priya',
                'lname' => 'Sharma',
                'gender' => 'Female',
                'email' => 'priya@gmail.com',
                'contact' => '9820123456',
                'doctor' => 'arun',
                'docFees' => 600,
                'appdate' => '2026-10-07',
                'apptime' => '11:30:00',
                'userStatus' => 1,
                'doctorStatus' => 1
            ],
            [
                'ID' => 7,
                'pid' => 4,
                'fname' => 'Priya',
                'lname' => 'Sharma',
                'gender' => 'Female',
                'email' => 'priya@gmail.com',
                'contact' => '9820123456',
                'doctor' => 'ashok',
                'docFees' => 500,
                'appdate' => '2026-10-10',
                'apptime' => '15:00:00',
                'userStatus' => 1,
                'doctorStatus' => 1
            ],
            [
                'ID' => 8,
                'pid' => 5,
                'fname' => 'Anjali',
                'lname' => 'Verma',
                'gender' => 'Female',
                'email' => 'anjali@gmail.com',
                'contact' => '9711234567',
                'doctor' => 'Kumar',
                'docFees' => 800,
                'appdate' => '2026-10-06',
                'apptime' => '12:00:00',
                'userStatus' => 1,
                'doctorStatus' => 1
            ],
            [
                'ID' => 9,
                'pid' => 1,
                'fname' => 'Ram',
                'lname' => 'Kumar',
                'gender' => 'Male',
                'email' => 'ram@gmail.com',
                'contact' => '9876543210',
                'doctor' => 'Amit',
                'docFees' => 1000,
                'appdate' => '2026-09-20',
                'apptime' => '10:00:00',
                'userStatus' => 1,
                'doctorStatus' => 1
            ],
            [
                'ID' => 10,
                'pid' => 3,
                'fname' => 'Gautam',
                'lname' => 'Shankararam',
                'gender' => 'Male',
                'email' => 'gautam@gmail.com',
                'contact' => '9070897653',
                'doctor' => 'ashok',
                'docFees' => 500,
                'appdate' => '2026-09-22',
                'apptime' => '14:00:00',
                'userStatus' => 1,
                'doctorStatus' => 0
            ],
            [
                'ID' => 11,
                'pid' => 6,
                'fname' => 'Suresh',
                'lname' => 'Raina',
                'gender' => 'Male',
                'email' => 'suresh@gmail.com',
                'contact' => '9988776655',
                'doctor' => 'priyesh',
                'docFees' => 900,
                'appdate' => '2026-10-08',
                'apptime' => '10:30:00',
                'userStatus' => 1,
                'doctorStatus' => 1
            ],
        ];

        foreach ($appointments as $appt) {
            DB::table('appointmenttb')->insert($appt);
        }

        // 5. Prescriptions
        DB::table('prestb')->truncate();

        $prescriptions = [
            [
                'doctor' => 'ashok',
                'pid' => 1,
                'ID' => 1,
                'fname' => 'Ram',
                'lname' => 'Kumar',
                'appdate' => '2026-10-06',
                'apptime' => '10:00:00',
                'disease' => 'Acute Pharyngitis & Mild Fever',
                'allergy' => 'None',
                'prescription' => 'Tab Azithromycin 500mg once daily for 3 days after meals. Gargle with warm salt water thrice daily. Drink lukewarm water.',
                'medicine' => 'Azithromycin 500mg'
            ],
            [
                'doctor' => 'Amit',
                'pid' => 1,
                'ID' => 9,
                'fname' => 'Ram',
                'lname' => 'Kumar',
                'appdate' => '2026-09-20',
                'apptime' => '10:00:00',
                'disease' => 'Stage 1 Essential Hypertension',
                'allergy' => 'Penicillin',
                'prescription' => 'Tab Amlodipine 5mg OD morning. Maintain low sodium diet. Morning walk for 30 minutes daily. Monitor blood pressure weekly.',
                'medicine' => 'Amlodipine 5mg'
            ],
            [
                'doctor' => 'Dinesh',
                'pid' => 2,
                'ID' => 3,
                'fname' => 'Kishan',
                'lname' => 'Lal',
                'appdate' => '2026-10-05',
                'apptime' => '11:00:00',
                'disease' => 'Persistent Allergic Bronchial Cough',
                'allergy' => 'Dust & Pollen',
                'prescription' => 'Syrup Benadryl DX 10ml thrice daily for 5 days. Steam inhalation twice daily before sleeping. Avoid chilled beverages.',
                'medicine' => 'Benadryl DX Syrup'
            ],
            [
                'doctor' => 'Ganesh',
                'pid' => 3,
                'ID' => 5,
                'fname' => 'Gautam',
                'lname' => 'Shankararam',
                'appdate' => '2026-10-05',
                'apptime' => '09:00:00',
                'disease' => 'Seasonal Viral Pyrexia',
                'allergy' => 'Sulfa drugs',
                'prescription' => 'Tab Paracetamol 650mg TDS SOS if temp > 100F. Electrolyte ORS solution 1 liter daily. Complete bed rest for 48 hours.',
                'medicine' => 'Paracetamol 650mg'
            ],
            [
                'doctor' => 'arun',
                'pid' => 4,
                'ID' => 6,
                'fname' => 'Priya',
                'lname' => 'Sharma',
                'appdate' => '2026-10-07',
                'apptime' => '11:30:00',
                'disease' => 'Sinus Tachycardia & Stress Checkup',
                'allergy' => 'None',
                'prescription' => 'Tab Metoprolol 25mg OD morning after food. Daily 20 mins breathing meditation. Follow up with repeat ECG after 2 weeks.',
                'medicine' => 'Metoprolol 25mg'
            ],
            [
                'doctor' => 'priyesh',
                'pid' => 6,
                'ID' => 11,
                'fname' => 'Suresh',
                'lname' => 'Raina',
                'appdate' => '2026-10-08',
                'apptime' => '10:30:00',
                'disease' => 'Right Knee Tendinitis / Strain',
                'allergy' => 'None',
                'prescription' => 'Tab Aceclofenac + Paracetamol BD for 4 days. Apply Volini gel twice daily. Knee support brace while walking.',
                'medicine' => 'Aceclofenac 100mg'
            ]
        ];

        foreach ($prescriptions as $pres) {
            DB::table('prestb')->insert($pres);
        }

        // 6. Pharmacist Console Account & Pharmacy Stock Inventory
        DB::table('phartb')->truncate();

        // Base Pharmacist login credential
        DB::table('phartb')->insert([
            'username' => 'phar',
            'password' => 'phar123',
            'medicine' => null,
            'doctor' => null,
            'bill' => null
        ]);

        // Stock Medicines inventory
        $medicines = [
            ['username' => 'phar', 'password' => '', 'medicine' => 'Paracetamol 650mg (Dolo)', 'doctor' => 'ashok', 'bill' => 45],
            ['username' => 'phar', 'password' => '', 'medicine' => 'Azithromycin 500mg (Azithral)', 'doctor' => 'ashok', 'bill' => 140],
            ['username' => 'phar', 'password' => '', 'medicine' => 'Metoprolol 25mg (Metolar)', 'doctor' => 'arun', 'bill' => 110],
            ['username' => 'phar', 'password' => '', 'medicine' => 'Benadryl DX Cough Syrup 100ml', 'doctor' => 'Dinesh', 'bill' => 95],
            ['username' => 'phar', 'password' => '', 'medicine' => 'Crocin Advance 500mg', 'doctor' => 'Ganesh', 'bill' => 35],
            ['username' => 'phar', 'password' => '', 'medicine' => 'Amlodipine 5mg (Amlokind)', 'doctor' => 'Amit', 'bill' => 60],
            ['username' => 'phar', 'password' => '', 'medicine' => 'Pantoprazole 40mg (Pan-40)', 'doctor' => 'Shubham', 'bill' => 125],
            ['username' => 'phar', 'password' => '', 'medicine' => 'Cetirizine 10mg (Cetzine)', 'doctor' => 'Kumar', 'bill' => 30],
            ['username' => 'phar', 'password' => '', 'medicine' => 'Aceclofenac 100mg (Zerodol)', 'doctor' => 'priyesh', 'bill' => 85],
            ['username' => 'phar', 'password' => '', 'medicine' => 'Augmentin 625 Duo', 'doctor' => 'ashok', 'bill' => 210],
        ];

        foreach ($medicines as $med) {
            DB::table('phartb')->insert($med);
        }

        // 7. Contact Inquiry Messages
        DB::table('contact')->truncate();

        $messages = [
            [
                'name' => 'Sunil Rao',
                'email' => 'sunil.rao@gmail.com',
                'contact' => '9845012345',
                'message' => 'Looking for pediatric cardiology consultation availability for next Tuesday morning.'
            ],
            [
                'name' => 'Meera Deshmukh',
                'email' => 'meera.d@gmail.com',
                'contact' => '9920198765',
                'message' => 'Do you accept cashless health insurance claims for general orthopedic surgery?'
            ],
            [
                'name' => 'Vikram Mehta',
                'email' => 'vikram.m@gmail.com',
                'contact' => '9811234567',
                'message' => 'Requesting appointment reschedule for Saturday 11:00 AM with Dr. Ashok.'
            ],
        ];

        foreach ($messages as $msg) {
            DB::table('contact')->insert($msg);
        }
    }
}
