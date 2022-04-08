<?php
//This add the routes to the Router class in our framework.
//Let us import the router class to this file using the namespace
use Framework\Routing\Router;
return function (Router $router){
    //If someone types the domain name in url the person will be welcomed with hello world
    $router->add(
        'GET', '/',
        fn() =>"Hello world welcome to our beautiful website"
    );

    //What if someone access the old page
    //We want him to be redirected to the new page
    $router->add(
        'GET', '/old-home',//such named parameter will come when we are  creating the controller
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

    //Let us implement the named parameter in our project's url
    //it follows the controller/action/parameter one which must be provided and one which is optional
    $router->add(
        'GET', 'product/view/{product}',
        function () use ($router){//using the the function current() in a Router class
            $parameters = $router->current()->parameters();
            return "Product is {$parameters['product']}";
        },
    );

    //option parameter
    $router->add(
        'GET', '/service/view/{service?}',
        function () use ($router){
            $parameters = $router->current()->parameters();
            //Let us do some logical operation here to allow all services to be displayed in case
            //it has not been provided and return what has been provide if there is an input
            if(empty($parameters['service'])){
                return 'all services';
            }
            return "Service is {parameter['service']}";
        },
    );

    $router->add(
        'GET', '/products/{page?}',
        function () use ($router) {
            $parameters = $router->current()->parameters();
            $parameters['page'] ??= 1;
            return "products for page {$parameters['page']}";
        },
    )->name('product-list');
};

