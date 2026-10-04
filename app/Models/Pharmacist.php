<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pharmacist extends Model
{
    protected $table = 'phartb';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = ['username', 'password', 'medicine', 'doctor', 'bill', 'stock_qty'];
}

