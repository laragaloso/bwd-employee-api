<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    // Define the table name if it's not the plural form of the model name
    protected $table = 'employees';

 
    protected $fillable = [
        'firstname',
        'lastname',
        'middlename',
        'name_extension',
        'email',
        'division',
        'position',
        'section',
    ];
}
