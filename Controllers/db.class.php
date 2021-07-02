<?php

/*
  CLASE PARA LA CONEXION Y LA GESTION DE LA BASE DE DATOS Y LA PAGINA WEB
 */

class Database {

    private $conexion;
    private $conn;

    public function connect() {
        if (!isset($this->conexion)) {
            $conn = new mysqli("localhost", "root", "", "db"); /* host , usuario , contraseña, bd  */
            $this->conexion = $conn;
        }
    }

    public function execute($sql) {
        $resultado = $this->conexion->query($sql);
        if (!$resultado) {
            // echo 'MySQL Error: ' . $this->conexion->error;
            $error = "$sql<br> code error = {$this->conexion->error} <br>IP = {$_SERVER['REMOTE_ADDR']} <br>";
            file_put_contents("error.txt", $error);            
            return $this->conexion->error;
            exit;
        }
        return $resultado;
    }

    function num_rows($result) {
        //if(!is_resource($result)) return false;
        return $result->num_rows;
    }

    function to_array($result) {
        //if(!is_resource($result)) return false;
        return $result->fetch_assoc();
    }

    public function disconnect() {
        $this->conexion->close();
        $this->conexion = null;
    }

    public function affected_rows() {
        return $this->conexion->affected_rows > 0;
    }

    public function get_insert_id() {
        return $this->conexion->insert_id;
    }

    public function get_error() {
        return $this->conexion->error;
    }

    public function implode($arr_key_value, $operator) {
        $return = FALSE;
        if (is_array($arr_key_value)) {
            foreach ($arr_key_value as $key => $value) {
                if ($value === '') {
                    $value = 'NULL';
                }
                if (!is_array($value))
                    $return .= !$this->is_a_function($value) ? "{$key}='{$value}' {$operator} " : "{$key}={$value} {$operator} ";
                elseif (array_key_exists("<", $value) || array_key_exists(">", $value) || array_key_exists("<=", $value) || array_key_exists(">=", $value)) {
                    $comparation = "";
                    foreach ($value as $sub_key => $sub_value) {
                        $comparation .= !$this->is_a_function($sub_value) ? "{$key} {$sub_key} '{$sub_value}' {$operator} " : "{$key} {$sub_key} {$sub_value} {$operator} ";
                    }
                    $comparation = trim($comparation, " {$operator} ");
                    $return .= "({$comparation}) {$operator} ";
                } else
                    $return .= "({$key} IN ('" . implode("','", $value) . "')) {$operator} ";
            }
        }
        $return = $return ? trim($return, " {$operator} ") : FALSE;
        return $return;
    }

    private function is_a_function($value) {
        $return = FALSE;
        if ($value === 'NULL' || (strpos($value, '(') && strpos($value, ')') ))
            $return = TRUE;
        return $return;
    }
    




}
?>