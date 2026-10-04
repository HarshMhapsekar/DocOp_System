<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $table = 'appointmenttb';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'pid', 'fname', 'lname', 'gender', 'email', 'contact',
        'doctor', 'docFees', 'appdate', 'apptime', 'userStatus', 'doctorStatus', 'payment'
    ];

    public function prescription()
    {
        return $this->hasOne(Prescription::class, 'ID', 'ID');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'pid', 'pid');
    }

    public function isCompleted()
    {
        return $this->prescription()->exists();
    }
}

