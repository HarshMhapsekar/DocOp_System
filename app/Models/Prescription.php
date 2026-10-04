<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    protected $table = 'prestb';
    public $timestamps = false;
    protected $fillable = [
        'doctor', 'pid', 'ID', 'fname', 'lname',
        'appdate', 'apptime', 'disease', 'allergy', 'prescription', 'medicine'
    ];
}
