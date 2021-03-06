<?php

require_once './entidades/fileHandler.php';

class Usuario extends FileHandler
{
    public $_email;
    public $_tipo;
    public $_password;

    public function __construct($email, $tipo, $password)
    {
        $this->_email = $email;
        $this->_password = $password;
        $this->_tipo = $tipo;
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __toString()
    {
        return $this->_email.'*'.$this->_tipo.'*'.$this->_password;
    }

    public function validarEmail($array)
    {
        $seEncuentra = false;
        foreach($array as $item)
        {
            if($item->_email == $this->_email)
            {
                $seEncuentra = true;
            }
        }
        return $seEncuentra;
    }
    
    public function validarTipo()
    {
        $esCorrecto = false;

        if($this->_tipo == 'admin' || $this->_tipo == 'user')
        {
            $esCorrecto = true;
        }
        
        return $esCorrecto;
    }

    public function verificarUsuario($array)
    {
        $login = false;
        foreach($array as $item)
        {
            if($item->_email == $this->_email && $item->_password == $this->_password)
            {
                $login = true;
            }
        }
        return $login;
    }

    public static function obtenerUsuario($array, $email, $password)
    {
        $usuario;

        foreach($array as $item)
        {
            if($item->_email == $email && $item->_password == $password)
            {
                $usuario = new Usuario($item->_email, $item->_tipo, $item->_password);
            }
        }

        return $usuario;
    }

    public static function saveUsuarioJson($obj)
    {
        parent::saveJson('./archivos/usuarios.json', $obj);
    }

    public static function readUsuarioJson()
    {
        $lista = parent::readJson('./archivos/usuarios.json');
        $arrayRetorno = array();

        foreach ($lista as $item) 
        {
            $usuario = new Usuario($item->_email, $item->_tipo, $item->_password);
            array_push($arrayRetorno, $usuario);
        }

        return $arrayRetorno;
    }
}