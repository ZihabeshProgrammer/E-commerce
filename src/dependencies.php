<?php
// DIC configuration
use Respect\Validation\Validator as v;

session_start();


$container = $app->getContainer();


$capsule  = new \Illuminate\Database\Capsule\Manager;

$capsule ->addConnection($container['settings']['db']);

$capsule->setAsGlobal();
$capsule->bootEloquent();


$container['db'] = function($container) use ($capsule){
    return $capsule;
};


// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

$container['view'] = function ($c) {
	$conf = ['cache' => '../cache'];
	$view = new \Slim\Views\Twig(__DIR__ . '/../resource/templates', $conf);
	$view->addExtension(new \Slim\Views\TwigExtension($c->get('router'), $c->get('request')->getUri()));
	return $view;
};


 //monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

$container['auth']=function ($container){
    
        return new \App\Auth\Auth;
    
    };
    

    $container['auth']=function ($container){
        
            return new \App\Auth\Auth;
        
        };
    
    $container['flash']=function ($container){
        
            return new \Slim\Flash\Messages;
        
        };
    
    
    $container['view'] = function ($container) {
        $view = new \Slim\Views\Twig(__DIR__.'/../resource/templates', [ 
            'cache'  => false
        ]);
     
    
    $view -> addExtension(new \Slim\Views\TwigExtension(
        $container['router'],
        $container['request']->getUri()
        ));
    
        $view->getEnvironment()->addGlobal('auth', [
            'check' => $container->auth->check(),
            'user' => $container->auth->user(),
        ]);
    
        $view->getEnvironment()->addGlobal('flash', $container->flash);
        return $view;
    };
    
    $container['validator']=function($container){
        return new App\validation\validator;
    };
    
    $container['H_control'] = function($container){
            return new \App\Controllers\H_control($container);
    }; 
    
    $container['AuthController'] = function($container){
        return new \App\Controllers\Auth\AuthController($container);
    }; 
    
    $container['PassController'] = function($container){
        return new \App\Controllers\Auth\PassController($container);
    }; 
    
   /* $container['csrf']=function ($container){
    
        return new \Slim\Csrf\Guard;
    
    };
    */
    $app->add(new App\Middleware\v_error_middleware($container));
    
    $app->add(new App\Middleware\existinput($container));
    
    //$app->add(new App\Middleware\csrfviewMiddleware($container));
    
   // $app->add($container->csrf);
    
    v::with('App\\validation\\Rules');
    


   // require __DIR__. '/../src/routes.php';