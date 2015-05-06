<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */



/**
 * Our custom REST routes
 */
Router::parseExtensions('json','xml');
Router::mapResources(array('api'));



Router::connect('/login', array('controller' => 'users', 'action' => 'login'));
Router::connect('/', array('controller' => 'users', 'action' => 'login'));



Router::connect('/api/gencode.json', array('controller' => 'api', 'action' => 'gencode'));

/*
 * Routing del API
 */


Router::connect('/api/oauth/authorize', array('plugin'=>'OAuth','controller' => 'oauth', 'action' => 'authorize'));
Router::connect('/api/oauth/token', array('plugin'=>'OAuth','controller' => 'oauth', 'action' => 'token'));
Router::connect('/api/createOAuthClient', array('controller' => 'api', 'action' => 'createOAuthClient','ext'=>'json'));


Router::connect('/api/user', array('controller' => 'api', 'action' => 'user','ext'=>'json'));
Router::connect('/api/pregunta', array('controller' => 'api', 'action' => 'pregunta','ext'=>'json'));




//


Router::connect('/logout', array('controller' => 'users', 'action' => 'logout'));
Router::connect('/Evaluaciones/Users/logout', array('controller' => 'users', 'action' => 'logout'));
Router::connect('/Evaluaciones/Evaluaciones', array('controller' => 'Evaluaciones', 'action' => 'index'));
Router::connect('/Evaluaciones/evaluaciones', array('controller' => 'Evaluaciones', 'action' => 'index'));
Router::connect('/Evaluaciones/reanudar_evaluacion', array('controller' => 'Evaluaciones', 'action' => 'evaluacion'));
Router::connect('/reanudar_evaluacion', array('controller' => 'Evaluaciones', 'action' => 'evaluacion'));
Router::connect('/evaluacion', array('controller' => 'Evaluaciones', 'action' => 'evaluacion'));
Router::connect('/revision', array('controller' => 'Evaluaciones', 'action' => 'revision'));
Router::connect('/calificar', array('controller' => 'Evaluaciones', 'action' => 'calificar'));
Router::connect('/getDatosAlumno', array('controller' => 'Evaluaciones', 'action' => 'getDatosAlumno'));



/**
 * ...and connect the rest of 'Pages' controller's URLs.
 */
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));

/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';


