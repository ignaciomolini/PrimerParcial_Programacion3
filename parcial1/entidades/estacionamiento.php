<?php

require_once './entidades/fileHandler.php';

class Precio extends FileHandler
{
    public $_patente;
    public $_fecha_ingreso;
    public $_tipo;
    public $_email;

    public function __construct($patente, $tipo, $email)
    {
        $this->_patente = $patente;
        $this->_fecha_ingreso = date("d")."/".date("m")."/".date("Y")." ".date("G").":".date("i");
        $this->_tipo = $tipo;
        $this->_email = $email;
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