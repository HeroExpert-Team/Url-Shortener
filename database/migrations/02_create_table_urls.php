<?php

use Illuminate\Database\Capsule\Manager as DataBase;

class CreateTableUrls
{
    /**
     * Run the migrations to create the 'urls' table.
     *
     * @return void
     */
    public function up(): void
    {
        DataBase::schema()->create('urls', function ($table) {
            # Define columns for the 'urls' table
            $table->id()->autoIncrement(); # Primary key with auto-increment

            # Foreign key to reference 'users' table
            $table->unsignedBigInteger('created_by'); 
            $table->foreign('created_by')              # Foreign key relation to 'users' table
                ->references('id')
                ->on('users')
                ->onDelete('cascade');                 # Delete URLs if user is deleted

            $table->string('url');                     # Original URL

            $table->string('shortUrl')->unique();       # Shortened URL (must be unique)

            $table->string('qrCode')->unique();         # QR code for the URL (must be unique)

            $table->unsignedBigInteger('views')->default(0); # View counter with default value of 0

            $table->timestamp('created_at')->useCurrent(); # Timestamp for record creation
        });
    }

    /**
     * Reverse the migrations by dropping the 'urls' table.
     *
     * @return void
     */
    public function down(): void
    {
        # Drop the 'urls' table if it exists
        DataBase::schema()->dropIfExists('urls');
    }
}