<?php

require_once 'conexion/ConexionMySQL.php';
require_once 'entidad/Propiedad.php';

class PropiedadDAO
{
    private $conexion;

    public function __construct()
    {
        try{
			$conexionMySQL = new ConexionMySQL();
        	$this->conexion = $conexionMySQL->obtenerConexion();
		} catch (Throwable $t) { 
			error_log("Error o excepción capturada: " . $t->getMessage());
		}
    }

    public function obtenerPropiedad($key)
    {
        $consulta = "SELECT * FROM propiedad p WHERE p.nombre = ?";

        try{

            $stmt = $this->conexion->prepare($consulta);
            $stmt->bindParam(1, $key);
            $stmt->execute();

            while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
                
                return new Propiedad($fila['id'], $fila['nombre'], $fila['value'] );

            }
        
        } catch (PDOException $e) {
			error_log("Error en la ejecución de la consulta: " . $e->getMessage());
			return null;
		} catch (Exception $e) {
			error_log("Error general en la ejecución: " . $e->getMessage());
			return null;
		} catch (Throwable $t) { 
			error_log("Error o excepción capturada: " . $t->getMessage());
		}	

        return null;

    }
}
