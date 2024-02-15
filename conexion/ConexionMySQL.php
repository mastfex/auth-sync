<?php

/*
    Hecho por: Felipe Rubin
    Modificado para usar PDO
*/

require_once "config/Config.php";

class ConexionMySQL
{
    private $host = MYSQL_HOST;
    private $usuario = MYSQL_USER;
    private $clave = MYSQL_PASS;
    private $baseDeDatos = MYSQL_BD;
    private $conexion;

    public function __construct()
    {
        try {
            $dsn = "mysql:host=$this->host;dbname=$this->baseDeDatos;charset=utf8";
            $this->conexion = new PDO($dsn, $this->usuario, $this->clave);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            error_log("Error de conexión PDO: " . $e->getMessage());
            $this->conexion = null;
        }
    }

    public function obtenerConexion()
    {
        return $this->conexion;
    }

    public function ejecutarConsulta($consulta, $parametros = [])
    {
        if ($this->conexion === null) {
            return false;
        }

        try {
            $stmt = $this->conexion->prepare($consulta);
            $stmt->execute($parametros);
            return $stmt;
        } catch (PDOException $e) {
            error_log("Error en la consulta PDO: " . $e->getMessage());
            return false;
        }
    }

    public function cerrarConexion()
    {
        $this->conexion = null;
    }
    
}
