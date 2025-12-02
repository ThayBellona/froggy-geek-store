<?php
class Conexao {
    
    public static function getConexao() {
        try {
            // Ajuste aqui se o seu usuário/senha do MySQL for diferente
            $host = "localhost";
            $db_name = "seu bd";
            $username = "root"; 
            $password = "";     
            
$conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);            
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            return $conn;
        } catch(PDOException $e) {
            echo "Erro na conexão: " . $e->getMessage();
            return null;
        }
    }
}
?>