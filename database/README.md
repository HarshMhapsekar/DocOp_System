# DocOp Database Schema

This directory contains the database dump and schema definitions for the DocOp Healthcare Management System.

## File Information
- **`myhmsdb.sql`**: Full MySQL database dump containing tables:
  - `admintb`: Admin accounts
  - `appointmenttb`: Patient appointments, doctor assignments, and payment statuses
  - `contact`: Contact inquiries and messages from the public site
  - `doctb`: Doctor credentials, specializations, and consultancy fees
  - `patreg`: Patient registration records and medical profiles
  - `prestb`: Doctor prescriptions, medicine names, and dosages
  - `phartb`: Pharmacist accounts and credentials

## Import Instructions (Laragon / MySQL CLI)
```bash
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS myhmsdb;"
mysql -u root -p myhmsdb < database/myhmsdb.sql
```
Or via phpMyAdmin:
1. Open `http://localhost/phpmyadmin`
2. Create database `myhmsdb`
3. Click **Import** and choose `database/myhmsdb.sql`
