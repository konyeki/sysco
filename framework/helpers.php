<?php
use Framework\View;
//let us check whether the function view exist in class View
if(!function_exists('view')){
    //If it does not exist let us create it
    function view (string $template, array $data = []):string
    {
        //The function accepts two parameters
        static $manager; //we declare local variable and static why?

        if(!$manager){//if it does not exist lets instanciate it from the class View
            $manager = new View\Manager();

            // let's add a path for our views folder
            // so the manager knows where to look for views
            $manager->addPath(__DIR__.'/../resources/views');

            // we'll also start adding new engine classes
            // with their expected extensions to be able to pick
            // the appropriate engine for the template

            $manager->addEngine('basic.php', new View\Engine\BasicEngine());
        }
        return $manager->render($template, $data);
    }
}
