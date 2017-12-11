<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;

$app->get('/', 'H_control:index')->setName('aimeos_shop_list');

$app->group('', function() {
	
	$this->get('/auth/signup', 'AuthController:getSignUp')->setName('auth.signup');
	$this->post('/auth/signup', 'AuthController:postSignUp');
	
	
	$this->get('/auth/signin', 'AuthController:getSignIn')->setName('auth.signin');
	$this->post('/auth/signin', 'AuthController:postSignIn');
	})->add(new GuestMiddleware($container));
	
	
	$app->group('', function() {
	
		$this->get('/auth/signout', 'AuthController:getSignOut')->setName('auth.signout');
		
		$this->get('/auth/password/change', 'PassController:getChangePass')->setName('auth.password.change');
		$this->post('/auth/password/change', 'PassController:postChengePass');
		
	
	})->add(new AuthMiddleware($container));


/*

$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    // Sample log message
  $this->logger->info("Slim-Skeleton '/this' route");

    // Render index view
    return $this->renderer->render($response, 'index.php', $args);
});
*/