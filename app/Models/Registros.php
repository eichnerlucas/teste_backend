<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registros extends Model
{
    protected $table = 'registros';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = array('type', 'message', 'is_identified', 'whistleblower_name', 'whistleblower_birth','created_at', 'deleted');
}
