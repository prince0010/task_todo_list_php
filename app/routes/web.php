<?php

    $router->get('/', 'AuthController@login');
    $router->get('/login', 'AuthController@login');
    $router->post('login', 'AuthController@login');
    $router->get('/register', 'AuthController@register');
    $router->post('/register', 'AuthController@register');
    $router->get('/profile', 'AuthController@profile');
    $router->post('updateProfile', 'AuthController@updateProfile');
    $router->get('/logout', 'AuthController@logout');
    $router->get('/error', function(){
        require_once '../app/views/error.php';
    });


?>