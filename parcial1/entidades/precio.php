<?php

require_once './entidades/fileHandler.php';

class Precio extends FileHandler
{
    public $_precio_hora;
    public $_precio_estadia;
    public $_precio_mensual;

    public function __construct($precio_hora = 0, $precio_estadia = 0, $precio_mensual = 0)
    {
        $this->_precio_hora = $precio_hora;
        $this->_precio_estadia = $precio_estadia;
        $this->_precio_mensual = $precio_mensual;
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
        return $this->_precio_hora.'*'.$this->_precio_estadia.'*'.$this->_precio_mensual;
    }

    public static function savePreciosJson($obj)
    {
        parent::saveJson('./archivos/precios.json', $obj);
    }

    public static function readPreciosJson()
    {
        $lista = parent::readJson('./archivos/precios.json');
        $arrayRetorno = array();

        foreach ($lista as $item) 
        {
            $precio = new Precio($item->_precio_hora, $item->_precio_estadia, $item->_precio_mensual);
            array_push($arrayRetorno, $precio);
        }

        return $arrayRetorno;
    }

}