<?php

namespace HumanityChallenge\Http\Services\Routing;

use Http\Services\Routing\Router;
use Http\Services\Request\Request;
use PHPUnit\Framework\TestCase;

/**
 * Class RouterTest
 */
class RouterTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testReturnsExceptionIfRequestIsEmpty()
    {
        $this->expectException(\Exception::class);
        new Router(new Request);
    }

    /**
     * @throws \Exception
     */
    public function testDetectsControllerClassAndMethodCorrectly()
    {
        $request = new Request;

        $request->requestUri = '/users/5';
        $request->requestMethod = 'PATCH';

        $router = new Router($request);
        $controller = $router->getController();
        $this->assertEquals('UsersController', $controller['class_name_short']);
        $this->assertEquals('update', $controller['class_method']);

        $request->requestUri = '/users/5/delete';
        $request->requestMethod = 'DELETE';

        $router = new Router($request);
        $controller = $router->getController();
        $this->assertEquals('UsersController', $controller['class_name_short']);
        $this->assertEquals('delete', $controller['class_method']);

        $request->requestUri = '/users/export';
        $request->requestMethod = 'GET';

        $router = new Router($request);
        $controller = $router->getController();
        $this->assertEquals('UsersController', $controller['class_name_short']);
        $this->assertEquals('export', $controller['class_method']);

        $request->requestUri = '/users/export/type/csv';
        $request->requestMethod = 'GET';

        $router = new Router($request);
        $controller = $router->getController();
        $this->assertEquals('UsersController', $controller['class_name_short']);
        $this->assertEquals('export', $controller['class_method']);
        $this->assertEquals('csv', $router->getRouteParams()['type']);
    }
}
