<?php

require_once './entidades/fileHandler.php';

class Auto extends FileHandler
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
        return $this->_patente.'*'.$this->_fecha_ingreso.'*'.$this->_tipo.'*'.$this->_email;
    }

    public static function saveAutosJson($obj)
    {
        parent::saveJson('./archivos/autos.json', $obj);
    }

    public static function readAutosJson()
    {
        $lista = parent::readJson('./archivos/autos.json');
        $arrayRetorno = array();

        foreach ($lista as $item) 
        {
            $auto = new Autos($item->_patente, $item->_fecha_ingreso, $item->_tipo, $item->_email);
            array_push($arrayRetorno, $auto);
        }

        return $arrayRetorno;
    }

}