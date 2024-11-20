<?php

use Illuminate\Database\Capsule\Manager as DataBase;

class CreateTableUsers
{
    /**
     * Run the migrations to create the 'users' table.
     *
     * @return void
     */
    public function up(): void
    {
        DataBase::schema()->create('users', function ($table) {
            # Define columns for the 'users' table
            $table->id()->autoIncrement();        # Primary key with auto-increment
            $table->string('name');               # User's name
            $table->string('email')->unique();    # User's email (must be unique)
            $table->string('otpCode');            # OTP code for user authentication
            $table->string('otpExpired');         # OTP expiration time
            $table->timestamp('created_at')->useCurrent(); # Timestamp for record creation
        });
    }

    /**
     * Reverse the migrations by dropping the 'users' table.
     *
     * @return void
     */
    public function down(): void
    {
        # Drop the 'users' table if it exists
        DataBase::schema()->dropIfExists('users');
    }
}