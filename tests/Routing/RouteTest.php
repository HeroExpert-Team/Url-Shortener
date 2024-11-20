<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Core\Routing\Route;

final class RouteTest extends TestCase
{
    /**
     * This setup method is executed before each test case.
     * It resets the 'routes' property of the Route class to an empty array.
     * This ensures that each test starts with a fresh state, avoiding interference
     * from previously added routes.
     */
    protected function setUp(): void
    {
        # Using reflection to access the protected 'routes' property of the Route class.
        $reflection = new \ReflectionClass(Route::class);
        $routesProperty = $reflection->getProperty('routes');
        $routesProperty->setAccessible(true);
        # Setting the 'routes' property to an empty array to clear any existing routes.
        $routesProperty->setValue([]);
    }

    /**
     * Test to verify adding a GET route to the router.
     * It checks if the route is correctly registered with the specified method, URI, and action.
     */

    #[Test]
    public function addGetRoute()
    {
        # Adding a GET route with the URI '/home' and the action 'HomeController@index'.
        Route::get('/home', 'HomeController@index');

        # Retrieving all registered routes.
        $routes = Route::routers();

        # Asserting that there is exactly one route registered.
        $this->assertCount(1, $routes);

        # Asserting that the registered route is a GET method.
        $this->assertEquals('get', $routes[0]['method']);

        # Asserting that the URI of the route is '/home'.
        $this->assertEquals('/home', $routes[0]['uri']);

        # Asserting that the action of the route is 'HomeController@index'.
        $this->assertEquals('HomeController@index', $routes[0]['action']);
    }

    /**
     * Test to verify adding a POST route to the router.
     */

    #[Test]
    public function addPostRoute()
    {
        Route::post('/login', 'AuthController@login');
        $routes = Route::routers();

        $this->assertCount(1, $routes);
        $this->assertEquals('post', $routes[0]['method']);
        $this->assertEquals('/login', $routes[0]['uri']);
        $this->assertEquals('AuthController@login', $routes[0]['action']);
    }

    /**
     * Test to verify adding a PUT route to the router.
     */

    #[Test]
    public function addPutRoute()
    {
        Route::put('/user/update', 'UserController@update');
        $routes = Route::routers();

        $this->assertCount(1, $routes);
        $this->assertEquals('put', $routes[0]['method']);
        $this->assertEquals('/user/update', $routes[0]['uri']);
        $this->assertEquals('UserController@update', $routes[0]['action']);
    }

    /**
     * Test to verify adding a DELETE route to the router.
     */

    #[Test]
    public function addDeleteRoute()
    {
        Route::delete('/user/delete', 'UserController@delete');
        $routes = Route::routers();

        $this->assertCount(1, $routes);
        $this->assertEquals('delete', $routes[0]['method']);
        $this->assertEquals('/user/delete', $routes[0]['uri']);
        $this->assertEquals('UserController@delete', $routes[0]['action']);
    }

    /**
     * Test to verify adding a PATCH route to the router.
     */

    #[Test]
    public function addPatchRoute()
    {
        Route::patch('/post/update', 'PostController@update');
        $routes = Route::routers();

        $this->assertCount(1, $routes);
        $this->assertEquals('patch', $routes[0]['method']);
        $this->assertEquals('/post/update', $routes[0]['uri']);
        $this->assertEquals('PostController@update', $routes[0]['action']);
    }

    /**
     * Test to verify adding an OPTIONS route to the router.
     */

    #[Test]
    public function addOptionsRoute()
    {
        Route::options('/options', 'OptionsController@handle');
        $routes = Route::routers();
        $this->assertCount(1, $routes);
        $this->assertEquals('options', $routes[0]['method']);
        $this->assertEquals('/options', $routes[0]['uri']);
        $this->assertEquals('OptionsController@handle', $routes[0]['action']);
    }

    /**
     * Test to verify adding multiple routes of different methods (GET, POST, PUT).
     * It checks that all routes are registered correctly and in the correct order.
     */

    #[Test]
    public function MultipleRoutes()
    {
        # Adding multiple routes with different HTTP methods.
        Route::get('/home', 'HomeController@index');
        Route::post('/login', 'AuthController@login');
        Route::put('/user/update', 'UserController@update');

        $routes = Route::routers();

        # Asserting that there are exactly three routes registered.
        $this->assertCount(3, $routes);

        # Verifying the details of the first route (GET /home).
        $this->assertEquals('get', $routes[0]['method']);
        $this->assertEquals('/home', $routes[0]['uri']);

        # Verifying the details of the second route (POST /login).
        $this->assertEquals('post', $routes[1]['method']);
        $this->assertEquals('/login', $routes[1]['uri']);

        # Verifying the details of the third route (PUT /user/update).
        $this->assertEquals('put', $routes[2]['method']);
        $this->assertEquals('/user/update', $routes[2]['uri']);
    }
}
