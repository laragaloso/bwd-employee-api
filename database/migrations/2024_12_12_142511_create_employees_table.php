<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    public function up()
    {
        // Check if the employees table already exists
        if (!Schema::hasTable('employees')) {
            Schema::create('employees', function (Blueprint $table) {
                $table->id();
                $table->string('firstname');
                $table->string('lastname');
                $table->string('middlename')->nullable();  // Middlename is optional
                $table->string('name_extension')->nullable();  // Name extension is optional
                $table->string('email')->unique(); // Ensures that the email is unique
                $table->string('division');
                $table->string('position');
                $table->string('section');
                $table->timestamps();  // Automatically adds created_at and updated_at
            });
        }
    }

    public function down()
    {
        // Drops the employees table if it exists
        Schema::dropIfExists('employees');
    }
}
