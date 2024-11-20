<?php

use Illuminate\Database\Capsule\Manager as DataBase;

class CreateTableViews
{
    /**
     * Run the migrations to create the 'views' table.
     *
     * @return void
     */
    public function up(): void
    {
        DataBase::schema()->create('views', function ($table) {
            # Define columns for the 'views' table
            $table->id()->autoIncrement(); # Primary key with auto-increment

            # Foreign key to reference the 'urls' table
            $table->unsignedBigInteger('url_id'); 
            $table->foreign('url_id')                # Foreign key relation to 'urls' table
                ->references('id')
                ->on('urls')
                ->onDelete('cascade');               # Delete views when the associated URL is deleted

            $table->string('user_agent');             # Store the user agent string (e.g., browser info)

            $table->string('ip_address');             # Store the IP address of the visitor

            $table->timestamp('created_at')->useCurrent(); # Timestamp for when the view was created
        });
    }

    /**
     * Reverse the migrations by dropping the 'views' table.
     *
     * @return void
     */
    public function down(): void
    {
        # Drop the 'views' table if it exists
        DataBase::schema()->dropIfExists('views');
    }
}