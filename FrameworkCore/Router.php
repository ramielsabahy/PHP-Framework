<?php

namespace app\FrameworkCore;

/**
 * @author Ramy Mohamed <ramyelsabahy95@gmail.com
 * @package FrameworkCore
 */
class Router
{
    protected array $routes = [];
    public Request $request;
    public Response $response;

    /**
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }


    public function get($path, $callback){
        $this->routes['get'][$path] = $callback;
    }

    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->getMethod();
        $callback = $this->routes[$method][$path] ?? false;
        // Handle page not found
        if (!$callback) {
            $this->response->setStatusCode(404);
            $this->renderView('not_found');
            include_once Application::$ROOT_DIR."/views/not_found.php";
            die();
        }
        // Handle normal page like /home
        if (is_string($callback)){
            return $this->renderView($callback);
        }
        // Handle @toDo
        return call_user_func($callback);
    }

    /**
     * @param $view
     */
    public function renderView($view)
    {
        $layoutContent = $this->layoutContent();
        $viewContent = $this->renderOnlyView($view);
        echo str_replace('{{content}}', $viewContent, $layoutContent);
        die();
    }

    public function layoutContent(): bool|string
    {
        ob_start();
        include_once Application::$ROOT_DIR."/views/layouts/main.php";
        return ob_get_clean();
    }

    protected function renderOnlyView($view): bool|string
    {
        ob_start();
        include_once Application::$ROOT_DIR."/views/$view.php";
        return ob_get_clean();
    }
}