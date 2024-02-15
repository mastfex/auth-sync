<?php

require_once 'conexion/ConexionMySQL.php';
require_once 'entidad/Usuario.php';

class UsuarioDAO{

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

    public function buscarPorUsername($username) {
        
        $consulta = "SELECT * FROM users WHERE username = ?";

        try{

            $stmt = $this->conexion->prepare($consulta);
            $stmt->bindParam(1, $username);
            $stmt->execute();

            while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
                
                return new Usuario($fila['id'], $fila['usuario'], $fila['password'] );

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