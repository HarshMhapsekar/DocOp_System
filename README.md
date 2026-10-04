# DocOp Healthcare Management System

DocOp is a full-featured, modern hospital and clinical consultation management platform built on Laravel and modern CSS. It streamlines outpatient consultations, medical diagnostics, digital prescriptions, hospital tax invoicing, and pharmacy dispensary operations across Patient, Doctor, Pharmacist, and Admin roles.

---

## 🌟 Key Features

### 1. Patient Portal
- **Consultation Booking**: Real-time slot conflict prevention with dynamic booked slot disabling.
- **Appointment History**: Track scheduled, confirmed, attended, and cancelled consultations.
- **Patient Electronic Health Record (EHR)**: Record Blood Group, known allergies, and chronic medical history.
- **Diagnostic Reports Archive**: Log and review lab investigations (Pathology, Radiology, Cardiology, Biochemistry).
- **Consolidated Hospital Tax Invoices**: Itemized bills (Physician Fee, OPD Facility Fee, Formulary) with 1-click printable receipt.
- **Digital Medical Pass**: Printable check-in slip with token barcode simulation.

### 2. Doctor Portal
- **Appointments Schedule**: View scheduled appointments and attended history.
- **Clinical Prescribe Desk**:
  - Live Patient EHR Snapshot (Blood Group, Allergies alert, Chronic History).
  - Review patient lab investigation reports on file.
  - Formulary medication dropdown with prefilled allergies to prevent contraindications.
  - Automatically syncs newly reported allergies back to patient's permanent EHR.
- **Digital Rx Slips**: Official hospital-branded printable prescription slips with doctor signature.

### 3. Pharmacist Portal
- **Pending Prescriptions**: Review incoming doctor diagnoses awaiting medication assignment.
- **Dispense Medication**: Dispense formulary drugs and assign pharmacy bills.
- **Live Dispensary Inventory**:
  - Real-time stock quantity tracking per item.
  - Automatic low-stock alert banner when quantities drop to or below 10 units.
  - 1-click Quick Restock (+20 units) button.

### 4. Administrator Console
- **Hospital Metrics**: Live summary counts for Doctors, Patients, Appointments, Prescriptions, and Inquiries.
- **Doctor & Department Registry**: Add or remove medical officers and manage consultation fees.
- **Master Appointments & Prescriptions Log**: Comprehensive hospital activity tracking.
- **Patient Directory & Contact Message Inquiries**.

### 5. Universal Capabilities
- **Dual Theme**: One-click Dark / Light mode toggle with CSS token design system.
- **Modern Datepicker**: Styled Flatpickr date selector adapted to light and dark themes.
- **1-Click CSV Data Export**: Available on all data tables across all 4 portals.

---

## 🚀 Getting Started

### Prerequisites
- PHP >= 8.2
- Composer
- MySQL / MariaDB (e.g. Laragon, XAMPP, or Docker)

### Installation

1. **Clone the repository**:
   ```bash
   git clone https://github.com/HarshMhapsekar/DocOp_System.git
   cd DocOp_System
   ```

2. **Install PHP dependencies**:
   ```bash
   composer install
   ```

3. **Configure Environment**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Update `.env` with your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=myhmsdb
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Run Migrations & Seeders**:
   ```bash
   php artisan migrate
   php artisan db:seed --class=DummyDataSeeder
   ```

5. **Start Development Server**:
   ```bash
   php artisan serve
   ```
   Open `http://localhost:8000` in your browser.

---

## 🔑 Default Demo Credentials

| Role | Login Route | Email / Username | Password |
| :--- | :--- | :--- | :--- |
| **Patient** | `/patient/login` | `ram@gmail.com` | `ram123` |
| **Doctor** | `/doctor/login` | `ashok` | `ashok123` |
| **Pharmacist** | `/pharmacist/login` | `pharmacist` | `pharm123` |
| **Administrator** | `/admin/login` | `admin` | `admin123` |

---

## 📄 License
This project is licensed under the MIT License.
