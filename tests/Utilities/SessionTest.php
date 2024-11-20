<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Utilities\Session;

final class SessionTest extends TestCase
{
    /**
     * Setup method executed before each test.
     * Starts the session if it has not been started yet.
     */
    protected function setUp(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            Session::run(); # Start session for testing
        }
    }

    /**
     * Tests the has method of the Session class.
     *
     * Verifies if the method correctly detects the existence of session keys.
     */
    #[Test]
    public function hasSession(): void
    {
        Session::set('test_key', 'test_value');
        $this->assertTrue(Session::has('test_key')); # Check if key exists

        $this->assertFalse(Session::has('nonexistent_key')); # Check if non-existent key is handled

        Session::clear(); # Clear session data after test
    }
    
    /**
     * Tests the run method of the Session class.
     *
     * Verifies that the session settings like gc_maxlifetime and cookie_httponly
     * are set correctly and the session is active.
     */
    #[Test]
    public function isRunSession(): void
    {
        $this->assertEquals(300, ini_get('session.gc_maxlifetime')); # Check session lifetime setting
        $this->assertEquals('1', ini_get('session.cookie_httponly')); # Check session cookie security setting
        $this->assertTrue(session_status() === PHP_SESSION_ACTIVE); # Verify session is active

        Session::clear(); # Clear session data after test
    }

    /**
     * Tests the set and get methods of the Session class.
     *
     * Checks if values can be stored and retrieved from the session,
     * including storing arrays.
     */
    #[Test]
    public function isSetAndGetSession(): void
    {
        Session::set('username', 'amirreza');
        $this->assertEquals('amirreza', Session::get('username')); # Test set and get methods

        Session::set('data', ['name' => 'Ali', 'age' => 25]);
        $this->assertEquals(['name' => 'Ali', 'age' => 25], Session::get('data')); # Test array storage

        Session::clear(); # Clear session data after test
    }

    /**
     * Tests the empty method of the Session class.
     *
     * Verifies if the method accurately checks if a session key has an empty value.
     */
    #[Test]
    public function isEmptySession(): void
    {
        Session::set('empty_key', '');
        $this->assertTrue(Session::empty('empty_key')); # Check empty method for empty value

        Session::set('not_empty_key', 'value');
        $this->assertFalse(Session::empty('not_empty_key')); # Check empty method for non-empty value

        Session::clear(); # Clear session data after test
    }

    /**
     * Tests the delete method of the Session class.
     *
     * Checks if a session key can be removed correctly.
     */
     #[Test]
     public function isDeleteSession(): void
    {
        Session::set('key_to_delete', 'value');
        $this->assertTrue(Session::has('key_to_delete')); # Verify key is set

        Session::delete('key_to_delete');
        $this->assertFalse(Session::has('key_to_delete')); # Verify key is deleted

        Session::clear(); # Clear session data after test
    }

    /**
     * Tests the clear method of the Session class.
     *
     * Ensures that all session data is removed when the session is cleared.
     */
    #[Test]
    public function isClearSession(): void
    {
        Session::set('key1', 'value1');
        Session::set('key2', 'value2');

        Session::clear(); # Clear session data 

        $this->assertFalse(Session::has('key1')); # Check if first key is removed
        $this->assertFalse(Session::has('key2')); # Check if second key is removed
    }
}