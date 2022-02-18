<?php
/**
 * Use for handling routes
 * 
 * @author Tim Daniëls
 * @version 1.0
 */
namespace core;

use core\Request;
use database\DB;
use app\controllers\http\ResponseController;

class Router {

    private $_uri, $_path, $_error, $_uriRequestVals;
    
    # route key based paths
    private $_collRouteKeys = [], $_collPostKeys = [], $_pathRouteKeyVals = [], $_uriRouteKeyVals = [], $_pathUriKeyPairedVals = [], $_collPathRouteKeyKeys = [], $_partsPath;
    public $request, $request_vals;

    /**
     * @param object $request Request
     * @param object $response Response
     */
    public function __construct($request, $response) {
    
        $this->request = $request;
        $this->request_vals = $request->get();
        $this->response = $response;
    }

    /**
     * 
     * handles get request based routes
     * 
     * @param string $path
     * @param array $routeKeys optional
     */
    public function handleGetRequest($path, $routeKeys = null) {

        if($routeKeys) {
            # if routekeys exists in path
            if(preg_match("/".$this->collectRouteKeys($routeKeys)."/", $path, $match) === 1) { 
               $this->_partsPath = explode("/", $path);
               $partsUri = explode("/", $this->uri());
               
               $this->collectRouteKeyKeys($this->_collRouteKeys);

               foreach($this->_collPathRouteKeyKeys as $PahtRouteKeyKey) {
                   
                   if (array_key_exists($PahtRouteKeyKey, $partsUri)) {
                   
                       $this->_uriRouteKeyVals[$PahtRouteKeyKey] = $partsUri[$PahtRouteKeyKey];
                       $this->_pathRouteKeyVals[$PahtRouteKeyKey] = $this->_partsPath[$PahtRouteKeyKey];
                       $this->_pathUriKeyPairedVals[$this->_pathRouteKeyVals[$PahtRouteKeyKey]] = $this->_uriRouteKeyVals[$PahtRouteKeyKey];
                   }
               }
   
               $replace = array_replace($this->_partsPath, $this->_uriRouteKeyVals);
               $replaced = implode("/", $replace);
 
               if($replaced == $this->uri()) {                
                   $path = $replaced;
                  
                   foreach($this->_pathUriKeyPairedVals as $pathRouteKey => $uriRouteKeyVal) {
                     
                       $pathRouteKey = trim($pathRouteKey, "[]");
                       $this->_uriRequestVals[$pathRouteKey] = $uriRouteKeyVal;
                   }
               }
            } 
        }
        # if request uri is equal to path
        if(strtok($this->request->getUri(), '?') == $path || strtok($this->request->getUri(), '?') . "/" == $path) {
            if($this->request->getMethod() === 'GET') {
                $this->_path = $path;
            } 
            
        } 
        return $this;
    }

    /**
     * 
     * handles post request based routes
     * 
     * @param string $path
     * @param array $routeKeys optional
     */
    public function handlePostRequest($path, $routeKeys = null) {

        if($routeKeys) {
           # if routekeys exists in path
           if(preg_match("/".$this->collectRouteKeys($routeKeys)."/", $path, $match) === 1) { 
               $this->_partsPath = explode("/", $path);
               $partsUri = explode("/", $this->uri());
               
               $this->collectRouteKeyKeys($this->_collRouteKeys);
                # important so you can update or edit values on a page that is also used in the uri
                # therefore post keys should be in brackets
                $postKeys = array_keys($_POST);
                foreach($postKeys as $postKey) {
                    $postKey = "[".$postKey."]";
                    array_push($this->_collPostKeys, $postKey);
                }
    
                foreach($this->_collPathRouteKeyKeys as $PahtRouteKeyKey) {
                    
                    if (array_key_exists($PahtRouteKeyKey, $partsUri)) {
                        
                        $this->_uriRouteKeyVals[$PahtRouteKeyKey] = $partsUri[$PahtRouteKeyKey];
                        $this->_pathRouteKeyVals[$PahtRouteKeyKey] = $this->_partsPath[$PahtRouteKeyKey];
                        $this->_pathUriKeyPairedVals[$this->_pathRouteKeyVals[$PahtRouteKeyKey]] = $this->_uriRouteKeyVals[$PahtRouteKeyKey];
                    }
                }
                # returns containing values of path routekey values commpared to postkeys
                $postRouteKeyVals = array_intersect($this->_collPostKeys, $this->_pathRouteKeyVals);
                $postRouteKeyVals = preg_replace("/[][]/", "", $postRouteKeyVals );
    
                foreach($postRouteKeyVals as $postRouteVal) {
                    if($_POST[$postRouteVal] !== null) {
                        $this->_pathUriKeyPairedVals["[$postRouteVal]"] = $_POST[$postRouteVal];
                    }
                }
    
                $replace = array_replace($this->_partsPath, $this->_uriRouteKeyVals);
                $replaced = implode("/", $replace);
    
                if($replaced == $this->uri()) {                
                    $path = $replaced;
                    foreach($this->_pathUriKeyPairedVals as $pathRouteKey => $uriRouteKeyVal) {
                      
                        $pathRouteKey = trim($pathRouteKey, "[]");
                        $this->_uriRequestVals[$pathRouteKey] = $uriRouteKeyVal;
                    }
                }
            } 
        }

        if($this->uri() == $path || $this->uri() . "/" == $path) {

            if($this->request->getMethod() === 'POST') {
                $this->_path = $path;
            } 
        } 
        return $this;
    }

    /**
     * 
     * adds controller and method 
     * 
     * @param string $class like controller
     * @param string $method optional 'action' 
     */    
    public function add($class, $method = null) {  

        if($this->uri() == $this->_path || $this->uri() . "/" == $this->_path) {
            
            $namespaceClass = $this->namespace($class);
            if(class_exists($namespaceClass)) { 

                $instance = new $namespaceClass;
                if($method) {
                    if(method_exists($namespaceClass, $method)) {
                        
                        if(!empty($this->_uriRequestVals)) {
                            $this->request_vals = array_merge($this->request_vals, $this->_uriRequestVals);
                        }
                        return $instance->$method($this->request_vals) . exit();
                        
                    } else {
                        echo $this->error("Method " . $method . " not found!");
                        exit();
                    }
                } else {
                    return $instance . exit(); 
                }
            } else {
                echo $this->error("Class " . $class . " not found!");
                exit();
            }
        } 
    }

    /**
     * 
     * handles view based on path
     * 
     * @param string $path 
     */ 
    public function handleView($path = null, $view) {
     
        if(strtok($this->request->getUri(), '?') == $path || strtok($this->request->getUri(), '?') . "/" == $path) {
            if($this->request->getMethod() === 'GET') {
                $this->_path = $path;
                $controller = $this->namespace("Controller");
                $instance = new $controller;
                
                return $instance->view($view) . exit();
            } 
        } 
    }

    /**
     * get uri
     * @return property uri 
     */   
    private function uri() {
        
        $this->_uri = $this->request->getUri();
        $this->_uri = strtok($this->_uri, '?');
            
        return $this->_uri; 
    }


    public function collectRouteKeys($routeKeys) {

        foreach($routeKeys as $key) {
            $this->_collRouteKeys[] = "[" . $key . "]";
       }
       $keysColl = implode("|", $this->_collRouteKeys);
       return $keysColl;
    }

    public function collectRouteKeyKeys($collRouteKeys) {

        foreach($collRouteKeys as $routeKey) {
            $pathRouteKeyKeys = array_search($routeKey, $this->_partsPath);
            array_push($this->_collPathRouteKeyKeys, $pathRouteKeyKeys);
        }
    }
    
    /**
     * add namespace to classes
     * @param string $class like controller 
     * @return string default namespace src\controllers\
     */      
    private function namespace($class) {

        $namespace = 'app\controllers\\' . $class;       
        return $namespace;
    }    

    /**
     * add status code
     * @param string $code 
     * @return void
     */        
    public function code($code) {

        if(empty($this->_path)) {
            $this->response->set($code);
            $controller = new ResponseController();
            $controller->pageNotFound();
        } 
    }

    private function error($error) {

        $this->_error = $error;
        return $this->_error;
    }
}
