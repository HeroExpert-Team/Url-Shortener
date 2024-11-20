<?php

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use App\Core\Request;

final class RequestTest extends TestCase
{
    /**
     * Set up the test environment by mocking global superglobals
     * such as $_SERVER and $_REQUEST to simulate an HTTP request.
     */

    #[Test]
    protected function setUp(): void
    {
        # Mocking the $_SERVER superglobal to simulate the HTTP request environment.
        $_SERVER = [
            'REQUEST_METHOD' => 'GET',  # Simulate a GET request method
            'HTTP_USER_AGENT' => 'Mozilla/5.0',  # Simulate a browser user agent string
            'REQUEST_URI' => '/test?param=value',  # Simulate a URI with a query string
            'REMOTE_ADDR' => '127.0.0.1'  # Simulate the client IP address
        ];

        # Mocking the $_REQUEST superglobal to simulate incoming request parameters.
        $_REQUEST = [
            'param' => 'value',  # Simulate a request parameter 'param' with value 'value'
            'anotherParam' => 'test'  # Simulate another request parameter 'anotherParam' with value 'test'
        ];
    }

    /**
     * Test that the constructor initializes the properties of the Request object correctly.
     */

    #[Test]
    public function constructorInitializesProperties()
    {
        # Instantiate the Request class
        $request = new Request();

        # Assert that the HTTP method is correctly initialized as 'get'
        $this->assertEquals('get', $request->method());

        # Assert that the user agent string is correctly initialized as 'Mozilla/5.0'
        $this->assertEquals('Mozilla/5.0', $request->agent());

        # Assert that the URI is correctly initialized without the query string, expected '/test'
        $this->assertEquals('/test', $request->uri());

        # Assert that the IP address is correctly initialized as '127.0.0.1'
        $this->assertEquals('127.0.0.1', $request->ip());
    }

    /**
     * Test the addParam method to ensure it adds parameters to the request.
     */

    #[Test]
    public function addParamAddsParameter()
    {
        # Instantiate the Request class
        $request = new Request();

        # Add a new parameter 'newParam' with value 'newValue' to the request
        $request->addParam('newParam', 'newValue');

        # Assert that the new parameter exists in the request
        $this->assertTrue($request->has('newParam'));

        # Assert that the value of the new parameter is correctly set to 'newValue'
        $this->assertEquals('newValue', $request->param('newParam'));
    }

    /**
     * Test that the has method correctly returns true for existing parameters.
     */

    #[Test]
    public function hasReturnsTrueForExistingParameter()
    {
        # Instantiate the Request class
        $request = new Request();

        # Assert that the 'param' parameter exists in the request
        $this->assertTrue($request->has('param'));
    }

    /**
     * Test that the has method correctly returns false for non-existent parameters.
     */

    #[Test]
    public function hasReturnsFalseForNonExistentParameter()
    {
        # Instantiate the Request class
        $request = new Request();

        # Assert that a non-existent parameter 'nonExistentParam' does not exist in the request
        $this->assertFalse($request->has('nonExistentParam'));
    }

    /**
     * Test that the param method throws an exception when accessing a non-existent parameter.
     */

    #[Test]
    public function paramThrowsExceptionIfParameterDoesNotExist()
    {
        # Expect an InvalidArgumentException to be thrown
        $this->expectException(InvalidArgumentException::class);

        # Instantiate the Request class and attempt to access a non-existent parameter
        $request = new Request();
        $request->param('nonExistentParam'); // This should throw an exception
    }

    /**
     * Test the params method to ensure it returns all parameters correctly.
     */
    
     #[Test]
    public function paramsReturnsAllParameters()
    {
        # Instantiate the Request class
        $request = new Request();

        # Get all parameters from the request
        $params = $request->params();

        # Assert that the 'param' exists in the returned array of parameters
        $this->assertArrayHasKey('param', $params);

        # Assert that the value of 'param' is correctly set to 'value'
        $this->assertEquals('value', $params['param']);
    }
}
