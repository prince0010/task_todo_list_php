<?php

    class Controller{
        
        public $view;
        public $model;


        public function __construct(){
            $this->view = new stdClass();
        }

        public function model($model)
        {
            require_once '../app/models/' . $model . '.php';
            $this->model = new $model();
        }


        public function view($view, $data = [])
        {
           extract($data);
           require_once '../app/views/' . $view . '.php';
        }
    }

?>