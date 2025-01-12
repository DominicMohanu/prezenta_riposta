<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('tag_id')->nullable();         // Adding tag_id field
            $table->date('date_of_birth')->nullable();    // Adding date_of_birth field
            $table->string('phone_number')->nullable();   // Adding phone_number field
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['tag_id', 'date_of_birth', 'phone_number']);
        });
    }
}
