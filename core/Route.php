<?php 
/**
 * Use for setup routes
 * 
 * @author Tim Daniëls
 * @version 1.0
 */
namespace core;

class Route extends Router {

    private static $_response, $_request, $_routeKeys;

    public function __construct(Request $request, Response $response) {

        self::$_request = $request;
        self::$_response = $response;
    } 

    /**
     * @param string $path
     * @return object Router 
     */
    public static function get($path) {

        $route = new Router(self::$_request, self::$_response);
        if(self::$_request->getMethod() === 'GET') {

           return $route->handleGetRequest($path, self::$_routeKeys);
        } else {
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

           return $route->handlePostRequest($path, self::$_routeKeys);
        } else {
            return $route;
        }
    }

    /**
     * @param string $path
     * @return object Router
     */    
    public static function view($path) {

        $route = new Router(self::$_request, self::$_response);
        if(self::$_request->getMethod() === 'GET') {

           return $route->handleView($path);
        } else {
            return $route;
        }
    }

    /**
     * @param array $keys
     * @return property 
    */
    public static function setRouteKeys($keys) {

        self::$_routeKeys = $keys;
        return self::$_routeKeys;
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