<?php 

require_once __DIR__ . '/../includes/app.php';

use Controllers\ClientesController;
use MVC\Router;
use Controllers\CursosController;


$router = new Router();


//Clientes

$router->post('/registro',[ClientesController::class,'registro']);
$router->get('/registro',[ClientesController::class,'registro']);


//Para los cursos
$router->get('/cursos',[CursosController::class,'index']); //Mostrar las tareas de un proyecto
$router->post('/cursos/crear',[CursosController::class,'crear']);
$router->get('/cursos/actualizar',[CursosController::class,'mostrar']);
$router->put('/cursos/actualizar',[CursosController::class,'actualizar']);
$router->delete('/cursos/delete',[CursosController::class,'delete']);


$router->comprobarRutas();