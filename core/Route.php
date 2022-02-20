<?php 
/**
 * Use for setup routes
 * 
 * @author Tim Daniëls
 * @version 1.1
 */
namespace core;

use core\RouteBinder;

class Route extends Router {

    private static $_response, $_request, $_routeKeys;

    public function __construct(Request $request, Response $response) {

        self::$_request = $request;
        self::$_request->get();
        self::$_response = $response;
    } 

    public static function setRouteKeys($keys = null) {
        if($keys) {
            self::$_routeKeys = $keys;
        }
    }


    /**
     * @param string $path
     * @return object Router 
     */
    public static function get($path) {

        $route = new Router(self::$_request, self::$_response);
        if(self::$_request->getMethod() === 'GET') {
            if(self::$_routeKeys !== null) {
               // $routeb = new RouteBinder($path, self::$_routeKeys);
            }
           return $route->getRequest($path, self::$_routeKeys);
        } 
        else {
            return $route;
        }
    }

    /**
     * @param string $path
     * @return object Router
     */
    public static function post($path) {

        $route = new Router(self::$_request, self::$_response);
        if(self::$_request->getMethod() === 'POST') {

           return $route->postRequest($path, self::$_routeKeys);
        } else {
            return $route;
        }
    }

    /**
     * @param string $path
     * @return object Router
     */    
    public static function view($path, $view) {

        $route = new Router(self::$_request, self::$_response);
        if(self::$_request->getMethod() === 'GET') {

           return $route->handleView($path, $view);
        } else {
            return $route;
        }
    }

    /**
     * @param mixed int|string $code
     * @return void
    */
    public function response($code) {
        if(empty($this->_path)) {
            $this->response->set(404);
            $controller = new ResponseController();
            $controller->pageNotFound();
        } 
    }
    
    /**
     * @return object Router
    */
    public static function setResponseCode() {
        
        $route = new Router(self::$_request, self::$_response);
        return $route; 
    }
}