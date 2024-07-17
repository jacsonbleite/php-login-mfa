<?php

namespace App\Http;

use \Closure;
use \Exception;
use \ReflectionFunction;

class Router
{
    private $url = '';
    private $prefixUrl = '';
    private $routes = [];
    private $request;

    public function __construct($url)
    {
        $this->request = new Request($this);
        $this->url = $url;
        $this->setPrefixUrl();
    }

    private function setPrefixUrl()
    {
        $parseUrl = parse_url($this->url);
        $this->prefixUrl = $parseUrl['path'] ?? '';
    }

    private function addRoute($method, $route, $params = [])
    {
        foreach ($params as $key => $value) {
            if ($value instanceof Closure) {
                $params['controller'] = $value;
                unset($params[$key]);
                continue;
            }
        }

        $params['variables'] = [];
        $patternVariable = '/{(.*?)}/';

        if (preg_match_all($patternVariable, $route, $matches)) {
            $route = preg_replace($patternVariable, '(.*?)', $route);
            $params['variables'] = $matches[1];
        }

        $patternRoute = '/^' . str_replace('/', '\/', $route) . '$/';
        $this->routes[$patternRoute][$method] = $params;
    }

    private function getUri()
    {
        $uri = $this->request->getUri();
        $explodeUri = strlen($this->prefixUrl) ? explode($this->prefixUrl, $uri) : [$uri];
        return end($explodeUri);
    }

    private function getRoute()
    {
        $uri = $this->getUri();
        $method = $this->request->getMethod();

        foreach ($this->routes as $key => $value) {
            if (preg_match($key, $uri, $matches)) {
                if (isset($value[$method])) {
                    unset($matches[0]);
                    $keys = $value[$method]['variables'];
                    $value[$method]['variables'] = array_combine($keys, $matches);
                    $value[$method]['variables']['request'] = $this->request;
                    return $value[$method];
                }
                throw new Exception("Método não permitido", 405);
            }
        }

        throw new Exception("URL não encontrada", 404);
    }

    public function run()
    {
        try {
            $route = $this->getRoute();

            if (!isset($route['controller'])) {
                throw new Exception("Não foi possível processar a URL", 500);
            }

            $args = [];
            $reflection = new ReflectionFunction($route['controller']);

            foreach($reflection->getParameters() as $value)
            {
                $name = $value->getName();
                $args[$name] = $route['variables'][$name] ?? '';                
            }

            return call_user_func_array($route['controller'], $args);
        } catch (Exception $e) {
            return new Response($e->getCode(), $e->getMessage());
        }
    }

    public function get($route, $params = [])
    {
        return $this->addRoute('GET', $route, $params);
    }

    public function post($route, $params = [])
    {
        return $this->addRoute('POST', $route, $params);
    }

    public function put($route, $params = [])
    {
        return $this->addRoute('PUT', $route, $params);
    }

    public function delete($route, $params = [])
    {
        return $this->addRoute('DELETE', $route, $params);
    }
}
