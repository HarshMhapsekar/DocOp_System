<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $table = 'contact';
    public $timestamps = false;
    protected $fillable = ['name', 'email', 'contact', 'message'];
}
