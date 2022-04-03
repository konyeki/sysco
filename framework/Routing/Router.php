<?php


namespace Framework\Routing;


class Router
{
    protected array $routes = [];
    protected array $errorHandler = [];

    public function add(
        string $method,
        string $path,
        callable $handler
    ): Route
    {
        $route = $this->routes[] = new Route(
            $method, $path, $handler
        );
        return $route;
    }
    //Let us allow dispatch to take the person to the resources they are looking for if it exist
    //Here we have one method "GET" method and as such we use matching method which will be implemented
    //in the Route class under the get path method
    //The paths and request are in global function $_SERVER
    public function dispatch(){
        $paths = $this->paths();//We will create the method paths here
        //In which case if user open our website the default method is GET and the path is our domain name
        //Going by our routes.php the dispatch will take one to greeting or our home page which we will
        //customize in our controller
        $requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $requestPath = $_SERVER['REQUEST_URI']  ?? '/';
        //We need to validate paths, request and the path her so
        $matching = $this->match($requestMethod, $requestPath);
        //Logical condition allowing the dispatch to take the user to the resources
        if ($matching){
            try{
                // this action could throw and exception
                // so we catch it and display the global error
                // page that we will define in the routes file
                return $matching->dispatch();

            }catch(\Throwable $e){//Here the Throwable exist in the Global Namespace and we need to preceed it
                //with a back slash
                return $this->dispatchError();
            }
        }
        // if the path is defined for a different method
        // we can show a unique error page for it
        if (in_array($requestPath, $paths)){//Checks whether there exist the requested path in array of paths
            //an returns it
            return $this->dispatchNotAllowed();

        }
        return $this->dispatchNotFound();
    }

    //That was for dispatch but where is the method for the paths that we get from the Route class
    private function paths(): array
    {
        //we don't want it to be accessed outside the class
        //that can accidentally be changed
        $paths = []; //create an empty array that accepts arrays
        foreach ($this->routes as $route){//In the routes that we receive from the Route class
            $paths[] = $route->path();//subset and find the index and return the value of the path
            //from the getPath method. Here getPath/path is the same
        }
        return $paths;
    }

    private function match(string $method, string $path): ?Route
    {
        //We are accessing the Route class without going through injection
        foreach($this->routes as $route){
            if($route->matches($method, $path)){//Let us create the matches method in the Route class
                return $route;
            }
        }
        return null;
    }

    public function errorHandler(int $code, callable $handler)
    {
        $this->errorHandler[$code] = $handler;
    }

    public function dispatchNotAllowed()
    {
        $this->errorHandler[400] ??= fn() => "not allowed";
        return $this->errorHandler[400]();
    }

    public function dispatchNotFound()
    {
        $this->errorHandler[404] ??= fn() => "not found";
        return $this->errorHandler[404]();
    }

    public function dispatchError()
    {
        $this->errorHandler[500] ??= fn() => "server error";
        return $this->errorHandler[500]();
    }

    public function redirect($path)
    {
        header(
            "Location: {$path}", $replace = true, $code = 301
        );
        exit;
    }



}


