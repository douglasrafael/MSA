<?php

/**
 * Classe para estabelecer conexão com o banco de dados.
 * Para pegar a instância da conexão que é única, basta utilizar o método getInstance().
 * Exemplo: <code>$connection = Connection->getInstance();</code>
 * 
 * As seguites variavés deverão ser setadas de acordo com as configurações do banco que deseja se utilizar:
 * <code>
 * <br/>$host = '127.0.0.1';
 * <br/>$type = 'mysql';
 * <br/>$port = 3306;
 * <br/>$user = 'username';
 * <br/>$pass = 'password';
 * <br/>$name = 'dbname';
 * </code>
 * 
 * Os seguintes bancos são suportados: MySQL, PostgreSQL e SQLite.
 * 
 * @access public
 * 
 * @package     model
 * @author      Douglas Rafael <douglasrafaelcg@gmail.com>
 * @version     v.0.5 (09/02/2017)
 * @copyright   Copyright (c) 2017
 */
class Conexao {

    /**
     * SQL Error (1062): Coluna a ser inserida já existe. Violaçãoo de UNIQUE or PRIMARY KEY.
     */
    const DUPLICATE_ENTRY = 1062;

    /**
     * SQL Error (1048): Coluna não pode ser vazia.
     */
    const ENTRY_NULL = 1048;

    /**
     * SQL Error (1451): Não é possível excluir ou atualizar com restrição de chave estrangeira
     */
    const CONSTRAINT_FAILS = 1451;

    /**
     * Instância da conexão com o banco de dados.
     * 
     * @var PDO 
     */
    private static $conn = NULL;

    /**
     * Contrutor
     *
     * @access private
     */
    private function __construct() {
        
    }

    /**
     * Destrutor.
     * Chamado sempre que a instância do objeto é encerrada.
     *
     * @access public
     */
    public function __destruct() {
        $this->disconnect();
    }

    /**
     * Efetua conexao com o banco de dados.
     *
     * @access public
     *        
     * @return PDO - Instaância da conexão.
     * @expectedExceptionMessage Erro de conexão.
     */
    public static function getInstance() {
        if (empty(self::$conn)) {
            try {
                $type = 'mysql';
                $host = '127.0.0.1';
                $port = 3306;
                $user = 'root';
                $pass = '';
                $name = 'msa';

                switch ($type) {
                    case 'mysql' :
                        self::$conn = new PDO("{$type}:host={$host};port={$port};dbname={$name}", $user, $pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
                        break;
                    case 'pgsql' :
                        self::$conn = new PDO("pgsql:dbname={$name};user={$user};password={$pass};host={$host}");
                    case 'sqlite' :
                        self::$conn = new PDO("sqlite:{$name}");
                        break;
                    default :
                        break;
                }
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                // Se houver erro
                die("Erro: <code>" . $e->getMessage() . "</code>");
            }
        }
        return self::$conn;
    }

    /**
     * Exibe o SQL a ser preparado antes de tentar executar no banco
     *
     * @access protected
     *        
     * @param PDOStatement $stm - O statement        	
     * @return string - SQl
     */
    protected function debugSQL($stm) {
        return $stm->debugDumpParams();
    }

    /**
     * Desconecta do banco de dados
     *
     * @access public
     */
    public static function disconnect() {
        self::$conn = null;
    }

}
