<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Utilities\ExceptionHandler;
use App\Utilities\Session;

final class ExeptionTest extends TestCase
{
    /**
     * Set up a mock session environment before each test.
     * Ensures no actual redirection or session conflicts occur.
     */
    protected function setUp(): void
    {
        # Define a constant BASEPATH if not already defined to avoid path errors
        if (!defined('BASEPATH')) {
            define('BASEPATH', __DIR__); # Set base path to the current directory for testing purposes
        }

        # Start a session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            Session::run();
        }
    }

    /**
     * Test method to verify setting an error message in the session.
     * Confirms that the session contains the correct error message.
     */
  
    #[Test]
    public function setError(): void
    {
        # Set an error message in the session
        ExceptionHandler::setError('Error occurred');

        # Assert that the error message is correctly stored in the session
        $this->assertEquals('Error occurred', Session::get('error'));

        // Clear the session for cleanup
        Session::clear();
    }

    /**
     * Test method to verify setting a success message in the session.
     * Ensures that the session contains the correct success message.
     */

    #[Test]
    public function setMessage(): void
    {
        # Set a success message in the session
        ExceptionHandler::setMessage('Operation successful');

        # Assert that the success message is correctly stored in the session
        $this->assertEquals('Operation successful', Session::get('message'));

        # Clear the session for cleanup
        Session::clear();
    }
}
