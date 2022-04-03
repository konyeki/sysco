<?php
//This add the routes to the Router class in our framework.
//Let us import the router class to this file using the namespace
use Framework\Routing\Router;
return function (Router $router){
    //If someone types the domain name in url the person will be welcomed with hello world
    $router->add(
        'GET', '/',
        fn() =>"Hello world"
    );

    //What if someone access the old page w
    //We want him to be redirected to the new page
    $router->add(
        'GET', '/old-home',
        fn() => $router->redirect('/')//We are yet to implement the redirect method in our router class
    );

    //What if someone is looking for resources that are not there
    //The person is supposed to get status code error. Let us add it at this point
    $router->add(
        'GET', '/has-server-error',
        fn() => throw new Exception() //We are going to implement this exception
    );

    $router->add(
        'GET', '/has-validation-error',
        fn() => $router->dispatchNotAllowed() //We are going to implement this in Router class
    );
    //This routes file is where we create our request it could be POST, DELETE and other methods

};
