<?php

namespace App\Models\Rossi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turno extends RossiModel
{
    use HasFactory;
    const tableName = 'EGES_TEST.TURNO';
    protected $table = self::tableName;
}
