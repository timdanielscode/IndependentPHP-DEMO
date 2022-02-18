<?php

namespace app\controllers;

class DemoController extends Controller {

    public function index() {

        return $this->view("demo/index");
    }

}