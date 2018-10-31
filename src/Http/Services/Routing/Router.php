<?php

namespace Http\Services\Routing;

use Helpers\TextHelper;
use Http\Services\Request\RequestInterface;

/**
 * Class Router
 */
class Router implements RouterInterface
{
    protected $request;
    protected $route;
    protected $method;
    protected $controller;
    protected $routeParams;

    /**
     * Router constructor.
     * @param RequestInterface $request
     * @throws \Exception
     */
    public function __construct(RequestInterface $request)
    {
        $this->request = $request;

        $this->setRoute();
        $this->setMethod();
        $this->setController();
        $this->setRouteParams();
    }

    /**
     * @return mixed
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @return mixed
     */
    public function getRouteParams()
    {
        return $this->routeParams;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function dispatch()
    {
        $controller = $this->controller;

        if (!class_exists($controller['class_name_full'])) {
            throw new \Exception('Controller doesn\'t exist');
        }

        if (!method_exists($controller['class_name_full'], $controller['class_method'])) {
            throw new \Exception('Controller method doesn\'t exist');
        }

        return call_user_func_array(
            [new $controller['class_name_full'], $controller['class_method']],
            $this->parseControllerMethodParams()
        );
    }

    /**
     * @throws \Exception
     */
    private function setRoute()
    {
        if (!isset($this->request->requestUri)) {
            throw new \Exception('REQUEST_URI is not set');
        }

        $requestUri = $this->request->requestUri;
        if (($position = strpos(trim($requestUri), '?')) !== false) {
            $route = substr($requestUri, 0, $position);
        } else {
            $route = $requestUri;
        }

        $routeArray = explode('/', $route);

        if (!isset($routeArray[1])) {
            throw new \Exception('Wrong URL format');
        }

        $this->route = $route;
    }

    /**
     * @throws \Exception
     */
    private function setMethod()
    {
        if (!isset($this->request->requestMethod)) {
            throw new \Exception('REQUEST_METHOD is not set');
        }

        $method = $this->request->requestMethod;

        // if request comes from browser
        if ($method === 'POST' && !empty($_POST['_method'])) {
            if (in_array($_POST['_method'], ['PUT', 'PATCH', 'DELETE'])) {
                $method = $_POST['_method'];
            }
        }

        $this->method = $method;
    }

    private function setController()
    {
        $controller = [];

        $routeArray = explode('/', $this->route);

        $param2 = $routeArray[2] ?? null;
        if (!is_numeric($param2)) {
            $action = $param2;
        } else {
            $action = $routeArray[3] ?? '';
        }

        $controller['class_name_short'] = TextHelper::toCamelCase(urldecode($routeArray[1])) . 'Controller';
        $controller['class_name_full'] = '\Http\Controllers\\' . $controller['class_name_short'];

        switch ($this->method) {
            case 'GET':
                $controllerMethod = 'index';
                break;
            case 'POST':
                $controllerMethod = 'store';
                break;
            case 'PUT':
            case 'PATCH':
                $controllerMethod = 'update';
                break;
            case 'DELETE':
                $controllerMethod = 'destroy';
                break;
            default:
                $controllerMethod = 'index';
        }

        $controller['class_method'] = empty($action) ? $controllerMethod : $action;

        $this->controller = $controller;
    }

    protected function setRouteParams()
    {
        $params = [];
        $routeArray = explode('/', $this->route);
        $param2 = $routeArray[2] ?? '';

        if (is_numeric($param2)) {
            $params['id'] = $param2;
        } else {
            for ($i = 3; $i < count($routeArray); $i++) {
                $params[$routeArray[$i]] = $routeArray[$i + 1] ?? '';
                $i++;
            }
        }

        $this->routeParams = $params;
    }

    /**
     * Detects what dependencies has Controller method and injects them to it
     * @return array
     * @throws \ReflectionException
     */
    protected function parseControllerMethodParams()
    {
        $controller = $this->controller;
        $controllerClassReflection = new \ReflectionClass($controller['class_name_full']);
        $controllerClassMethodParameters = $controllerClassReflection->getMethod($controller['class_method'])->getParameters();
        $controllerClassMethodParametersPrepared = [];
        foreach ($controllerClassMethodParameters as $parameter) {
            $parameterName = $parameter->getName();
            $parameterType = $parameter->getType();
            $parameterValue = $parameterType ? $parameterType->getName() : null;
            if (class_exists($parameterValue)) {
                $controllerClassMethodParametersPrepared[$parameterName] = new $parameterValue;
            } else {
                $controllerClassMethodParametersPrepared[$parameterName] = $this->routeParams[$parameterName] ?? '';
            }
        }

        return $controllerClassMethodParametersPrepared;
    }
}