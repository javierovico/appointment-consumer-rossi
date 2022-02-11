<?php

namespace App\Models\Rossi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

abstract class RossiModel extends Model
{
    use HasFactory;
    const CONNECTION_DB = 'conexion_db_rossi';
    protected $connection = self::CONNECTION_DB;
    const tableName = 'forge';
}
