<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientDocument extends Model
{
    protected $table = 'patient_documents';

    protected $fillable = [
        'pid',
        'title',
        'report_type',
        'report_date',
        'laboratory',
        'notes'
    ];
}
