<?php
namespace App\Models;use Illuminate\Database\Eloquent\Model;
class ContactMessage extends Model{protected $fillable=['name','email','company','budget','subject','message','is_read'];protected $casts=['is_read'=>'boolean'];}
