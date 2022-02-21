<?php

namespace App\Models\RossiInterno;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

abstract class RossiInternoModel extends Model
{
    use HasFactory;
    const CONNECTION_DB = 'conexion_db_rossi_interno';
    protected $connection = self::CONNECTION_DB;
    const tableName = 'forge';
}
