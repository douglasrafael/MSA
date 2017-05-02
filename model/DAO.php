<?php

include_once __DIR__ . '/Aluno.php';
include_once __DIR__ . '/Atividade.php';
include_once __DIR__ . '/Disciplina.php';
include_once __DIR__ . '/Documento.php';
include_once __DIR__ . '/Conexao.php';
include_once __DIR__ . '/../exception/DAOException.php';
include_once __DIR__ . '/../exception/ValidationException.php';

/**
 * Interface para operações básicas de banco de dados (CRUD).
 *
 * @package     model
 * @author      Douglas Rafael <douglasrafaelcg@gmail.com>
 * @version     v.0.1 (23/03/2017)
 * @copyright   Copyright (c) 2017
 */
interface DAO {

    /**
     * Lista objetos de acordo com os parâmetros.
     *
     * @access public
     *        
     * @param int $start - Sinaliza onde o cursor da busca deve inicar.
     * @param int $limit - Limita o total de objetos que deverão ser retornados.
     * @param string $fieldOrder - Campo que será usado na ordenação.
     * @param string $order - O tipo de ordenação: ASC | DESC.   
     * @throws DAOException - Lançada quando ocorre erro de banco de dados.
     */
    public function listar($start = 0, $limit = 100, $fieldOrder = "id", $order = "DESC");

    /**
     * Seleciona objeto de acordo com o id passado como parâmetro.
     *
     * @access public
     *        
     * @param int $id - Id do objeto que deseja selecionar.        	
     *
     * @return object
     * @throws DAOException - Lançada quando ocorre erro de banco de dados.
     */
    public function selecionar($id);

    /**
     * Insere objeto no banco.
     *
     * @access public
     *        
     * @param object $obj - Objeto que deseja inserir.       	
     *
     * @return boolean
     * @throws DAOException - Lançada quando ocorre erro de banco de dados.
     */
    public function inserir($obj);

    /**
     * Atualiza dados do objeto no banco.
     *
     * @access public
     *        
     * @param object $obj - Objeto que deseja atualizar.
     *
     * @return boolean
     * @throws DAOException - Lançada quando ocorre erro de banco de dados.
     */
    public function atualizar($obj);

    /**
     * Remove objeto do banco de acordo com o id passado como parâmetro.
     *
     * @access public
     *        
     * @param int $id - Id do objeto que deseja remover.       	
     *
     * @return boolean
     * @throws DAOException - Lançada quando ocorre erro de banco de dados.
     */
    public function remover($id);

    /**
     * Busca por objetos no banco e retorna um array com os objetos encontrados de acordo com o termo buscado e parâmetros.
     *
     * @access public
     *        
     * @param string $term - Termo para busca do objeto.        	
     * @param int $start - Sinaliza onde o cursor da busca deve inicar.
     * @param int $limit - Limita o total de objetos que deverão ser retornados. 	
     * @param string $fieldOrder - Campo que será usado na ordenação.
     * @param string $order - O tipo de ordenação: ASC | DESC.        	
     * @throws DAOException - Lançada quando ocorre erro de banco de dados.
     */
    public function buscar($term, $start = 0, $limit = 100, $fieldOrder = "id", $order = "DESC");

    /**
     * Retorna o total de registros contidos no banco.
     *
     * @access public
     *        
     * @return int - Total de registros.
     * @throws DAOException - Lançada quando ocorre erro de banco de dados.
     */
    public function total();
}
