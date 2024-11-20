<?php

use App\Core\Migration;
use Illuminate\Database\Capsule\Manager as Database;

# Load the bootstrap file to initialize configurations
require __DIR__ . '/../bootstrap/bootstrap.php';

# Check for '--rollback' argument; otherwise, run migration
if (isset($argv[1]) && $argv[1] === '--rollback') {
    rollback();
} else {
    migrate();
}

/**
 * Run the migration process if needed.
 *
 * @return void
 */
function migrate(): void
{
    try {
        # Count the number of migration files in the migrations folder
        $migrationFilesCount = count(glob(BASEPATH . '/database/migrations/*.php'));

        # Count the number of executed migrations in the database
        $executedMigrationsCount = Database::table('migrations')->count();

        # Check if there are new migrations to run
        if ($executedMigrationsCount < $migrationFilesCount) {
            Migration::migrate();
        } else {
            echo "Migrations Already Exist. No New Migrations To Run.\n";
        }
    } catch (Exception $e) {
        # Handle any exceptions and display an error message
        echo "Migration Failed. Please Check Your Configuration.\n";
    }
}

/**
 * Roll back the last executed migration.
 *
 * @return void
 */
function rollback(): void
{
    try {
        # Count the number of migration files in the migrations folder
        $migrationFilesCount = count(glob(BASEPATH . '/database/migrations/*.php'));

        # Count the number of executed migrations in the database
        $executedMigrationsCount = Database::table('migrations')->count();

        # Check if there are migrations to roll back
        if ($executedMigrationsCount <= $migrationFilesCount) {
            Migration::rollback();
        } else {
            echo "No Migrations Found To Roll Back.\n";
        }
    } catch (Exception $e) {
        # Handle any exceptions and display an error message
        echo "Rollback failed. Please Check Your Configuration.\n";
    }
}
