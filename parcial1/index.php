<?php

require_once './entidades/usuario.php';
require_once './entidades/precio.php';

require __DIR__.'/vendor/autoload.php';
use \Firebase\JWT\JWT;

$method = $_SERVER['REQUEST_METHOD'];
$path_info = $_SERVER['PATH_INFO'] ?? '';

$token = $_SERVER['HTTP_TOKEN'] ?? '';
$key = 'primerparcial';
try
{
    $decoded = JWT::decode($token, $key, array('HS256'));
}
catch(Throwable $e)
{
    echo '<br>Sin loguear<br>';
}

switch($path_info)
{
    case '/registro':
        if($method == 'POST')
        {
            $email = $_POST['email'] ?? '';
            $tipo = $_POST['tipo'] ?? '';
            $password = $_POST['password'] ?? '';

            $usuario  = new Usuario($email, $tipo, $password);

            if($usuario->validarTipo())
            {
                $listaUsuarios = Usuario::readUsuarioJson();
                if(!$usuario->validarEmail($listaUsuarios) )
                {
                    array_push($listaUsuarios, $usuario);
                    Usuario::saveUsuarioJson($listaUsuarios);
                    echo '<br>Usuario registrado<br>';
                }
                else
                {
                    echo '<br>Error. El mail ya se encuentra registrado<br>';
                }  
            }
            else
            {
                echo '<br>Error. El tipo es incorrecto<br>';
            }  
        }
    break;

    case '/login':
        if($method == 'POST')
        {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $listaUsuarios = Usuario::readUsuarioJson();
            $usuario = Usuario::obtenerUsuario($listaUsuarios, $email, $password);
            if($usuario->verificarUsuario($listaUsuarios))
            {
                $payload = array(
                    "email" => "$usuario->_email",
                    "tipo" => "$usuario->_tipo",
                );  
                $token = JWT::encode($payload, $key);
                echo $token;
            }
            else
            {
                echo '<br>Error al loguearse<br>';
            }
        }
    break;

    case '/precio':
        if($decoded->tipo == 'admin')
        {
            if($method == 'POST')
            {
                $precio_hora = $_POST['precio_hora'] ?? '';
                $precio_estadia = $_POST['precio_estadia'] ?? '';
                $precio_mensual = $_POST['precio_mensual'] ?? '';

                $precio = new Precio($precio_hora, $precio_estadia, $precio_mensual);
                $listaPrecios = Precio::readPreciosJson();

                array_push($listaPrecios, $precio);
                Precio::savePreciosJson($listaPrecios);
                echo '<br>Se guardaron los precios<br>';    
            }
        }
        else
        {
            echo '<br>No tiene los permisos<br>';
        }
    break;

    case '/ingreso':
        if($decoded->tipo == 'user')
        {
            if($method == 'POST')
            {
                $patente = $_POST['patente'] ?? '';
                $tipo = $_POST['tipo'] ?? '';

                $auto = new Auto($patente, $tipo, $decoded->email);
                $listaAutos = Auto::readAutosJson();

                array_push($listaAutos, $auto);
                Auto::saveAutosJson($listaPrecios);
                echo '<br>Se guardo el auto<br>';    
            }
        }
        else
        {
            echo '<br>No tiene los permisos<br>';
        }
    break;

}