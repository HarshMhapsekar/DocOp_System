<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $table = 'patreg';
    protected $primaryKey = 'pid';
    public $timestamps = false;
    protected $fillable = [
        'fname', 'lname', 'gender', 'email', 'contact', 'password', 'cpassword',
        'blood_group', 'allergies', 'chronic_conditions'
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'pid', 'pid');
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'pid', 'pid');
    }

    public function documents()
    {
        return $this->hasMany(PatientDocument::class, 'pid', 'pid');
    }
}

