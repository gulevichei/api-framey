<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model {

    protected $table = 'employee';

    protected $fillable = [
        'id',
        'name',
        'position',
        'start_date',
        'end_date',
        'superior',
    ];

    public function superior()
    {
        return $this->belongsTo(Employee::class, 'superior', 'id');
    }

    public function subordinates()
    {
        return $this->belongsTo(Employee::class, 'id', 'superior');
    }
}
