<?php

use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Database;
use Illuminate\Support\Facades\Facade;

# Initialize and configure the application container and facades
$app = new Container();
Facade::setFacadeApplication($app);

# Initialize Eloquent's database manager
$database = new Database;

# Add MySQL connection settings using environment variables
$database->addConnection([
    'driver'    => 'mysql',             # Database driver (MySQL)
    'host'      => $_ENV['DB_HOST'],    # Database host (from environment variable)
    'database'  => $_ENV['DB_NAME'],    # Database name (from environment variable)
    'username'  => $_ENV['DB_USER'],    # Database username (from environment variable)
    'password'  => $_ENV['DB_PASS'],    # Database password (from environment variable)
    'charset'   => 'utf8',              # Character encoding for the connection
    'collation' => 'utf8_unicode_ci',   # Collation for string comparison
    'prefix'    => ''                   # Table prefix (optional)
]);

# Make the database connection globally accessible
$database->setAsGlobal();   # Enable global access to the database instance
$database->bootEloquent();  # Boot Eloquent ORM for using models and query builder

# Register the database manager instance in the application container
$app->instance('db', $database->getDatabaseManager()); 

# Check if the 'migrations' table exists in the database
$hasMigrations = $database::schema()->hasTable('migrations');

# Create the 'migrations' table if it does not exist
if (!$hasMigrations) {
    $database::schema()->create('migrations', function ($table) {
        $table->increments('id');       # Primary key auto-increment column
        $table->string('migration');    # Name of the migration file
        $table->timestamp('created_at')->useCurrent(); # Timestamp of creation with default current time
    });
}