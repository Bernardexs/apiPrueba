<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class personadas extends Model
{
    protected $table="personadas";
    public $timestamps=false;
    public $fillable=['nombre','cedula','direccion','fechaNacimiento'];

}
