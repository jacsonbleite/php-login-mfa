<?php
use \App\Http\Response;

$routerObj->get('/', [
    function(){
        return new Response(200, ['message' => 'Estou na index'], 'application/json');
    }
]);

$routerObj->get('/categoria', [
    function(){
        return new Response(200, ['message' => 'Estou na categoria'], 'application/json');
    }
]);


$routerObj->get('/categoria/{id}/{action}', [
    function($id, $action){
        return new Response(200, ['message' => 'categoria ' . $id, 'action' => $action], 'application/json');
    }
]);

$routerObj->get('/categoria/{id}', [
    function($id){
        return new Response(200, ['message' => 'Estou na categoria ' . $id], 'application/json');
    }
]);