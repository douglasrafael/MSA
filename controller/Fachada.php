<?php

include_once __DIR__ . '/../uteis/URL.php';
include_once __DIR__ . '/../uteis/MyDate.php';
include_once __DIR__ . '/../model/AlunoDAO.php';
include_once __DIR__ . '/Messenger.php';

/**
 * Fachada que fornece acesso aos métodos dos DAO's
 *
 * @access public
 * 
 * @package     controller
 * @author      Douglas Rafael <douglasrafaelcg@gmail.com>
 * @version     v.0.5 (23/03/2017)
 * @copyright   Copyright (c) 2017
 */
class Fachada {

    private $atividadeDAO;
    private $alunoDAO;
    private $disciplinaDAO;
    private $documentoDAO;

    function __construct() {
        if (empty($this->alunoDAO)) {
            $this->alunoDAO = new AlunoDAO();
        }
        if (empty($this->disciplinaDAO)) {
            $this->disciplinaDAO = new DisciplinaDAO();
        }
        if (empty($this->atividadeDAO)) {
            $this->atividadeDAO = new AtividadeDAO();
        }
        if (empty($this->documentoDAO)) {
            $this->documentoDAO = new DocumentoDAO();
        }
    }

    /**
     * Seleciona o aluno de acordo com a matricula.
     * 
     * @param int $matricula
     * @return Aluno
     */
    public function selecionarAluno($matricula) {
        return $this->alunoDAO->selecionar($matricula);
    }

    /**
     * Lista alunos de acordo com os parâmetros.
     * 
     * @param int $start
     * @param int $limit
     * @param string $fieldOrder
     * @param string $order
     * @return array
     */
    public function listarAlunos($start = 0, $limit = 100, $fieldOrder = "dataCadastro", $order = "DESC") {
        return $this->alunoDAO->listar($start, $limit, $fieldOrder, $order);
    }

    /**
     * Insere objeto Aluno no banco de dados.
     * 
     * @param Aluno $aluno
     * @return boolean
     * @throws DAOException - Lançada quando ocorre erro de banco de dados.
     */
    public function inserirAluno($aluno) {
        return $this->alunoDAO->inserir($aluno);
    }

    /**
     * Atualiza objeto Aluno no banco de dados.
     * 
     * @param Aluno $aluno
     * @return boolean
     * @throws DAOException - Lançada quando ocorre erro de banco de dados.
     */
    public function atualizarAluno($aluno) {
        return $this->alunoDAO->atualizar($aluno);
    }

    /**
     * Remove objeto Aluno do banco de dados de acordo com a matricula.
     * 
     * @param int $matricula
     * @return boolean
     * @throws DAOException - Lançada quando ocorre erro de banco de dados.
     */
    public function removerAluno($matricula) {
        return $this->alunoDAO->remover($matricula);
    }

    /**
     * Busca objeto Aluno no banco de dados de acordo coms parâmetros.
     * 
     * @param string $term
     * @param int $start
     * @param int $limit
     * @param string $fieldOrder
     * @param string $order
     * @return array
     */
    public function buscarAluno($term = "", $start = 0, $limit = 10, $fieldOrder = "nome", $order = "ASC") {
        return $this->alunoDAO->buscar($term, $start, $limit, $fieldOrder, $order);
    }

    /**
     * Seleciona discipplina de acordo com o id.
     * 
     * @param int $id
     * @return Disciplina
     */
    public function selecionarDisciplina($id) {
        return $this->disciplinaDAO->selecionar($id);
    }

    /**
     * Lista disciplinas de acordo com os parâmteros.
     * 
     * @param int $start
     * @param int $limit
     * @param string $fieldOrder
     * @param string $order
     * @return array
     */
    public function listarDisciplinas($start = 0, $limit = 100, $fieldOrder = "nome", $order = "ASC") {
        return $this->disciplinaDAO->listar($start, $limit, $fieldOrder, $order);
    }

    /**
     * Insere objeto Disciplina no banco de dados.
     * 
     * @param Disciplina $disciplina
     * @return boolean
     * @throws DAOException - Lançada quando ocorre erro de banco de dados.
     */
    public function inserirDisciplina($disciplina) {
        return $this->disciplinaDAO->inserir($disciplina);
    }

    /**
     * Atualiza objeto Disciplina no banco de dados.
     * 
     * @param Disciplina $disciplina
     * @return boolean
     * @throws DAOException - Lançada quando ocorre erro de banco de dados.
     */
    public function atualizarDisciplina($disciplina) {
        return $this->disciplinaDAO->atualizar($disciplina);
    }

    /**
     * Remove objeto Disciplina do banco de dados de acordo com o id.
     * 
     * @param int $id
     * @return boolean
     * @throws DAOException - Lançada quando ocorre erro de banco de dados.
     */
    public function removerDisciplina($id) {
        return $this->disciplinaDAO->remover($id);
    }

    /**
     * Busca objeto Disciplina no banco de dados de acordo coms parâmetros.
     * 
     * @param string $term
     * @param int $start
     * @param int $limit
     * @param string $fieldOrder
     * @param string $order
     * @return array
     */
    public function buscarDisciplina($term = "", $start = 0, $limit = 10, $fieldOrder = "id", $order = "DESC") {
        return $this->disciplinaDAO->buscar($term, $start, $limit, $fieldOrder, $order);
    }

    /**
     * Seleciona atividade de acordo com o id.
     * 
     * @param int $id
     * @return Disciplina
     */
    public function selecionarAtividade($id) {
        return $this->atividadeDAO->selecionar($id);
    }

    /**
     * Lista atividades de acordo com os parâmteros.
     * 
     * @param int $start
     * @param int $limit
     * @param string $fieldOrder
     * @param string $order
     * @return array
     */
    public function listarAtividades($start = 0, $limit = 100, $fieldOrder = "id", $order = "DESC") {
        return $this->atividadeDAO->listar($start, $limit, $fieldOrder, $order);
    }

    /**
     * Insere objeto Atividade no banco de dados.
     * 
     * @param Disciplina $atividade
     * @return boolean
     * @throws DAOException - Lançada quando ocorre erro de banco de dados.
     */
    public function inserirAtividade($atividade) {
        return $this->atividadeDAO->inserir($atividade);
    }

    /**
     * Atualiza objeto Atividade no banco de dados.
     * 
     * @param Disciplina $atividade
     * @return boolean
     * @throws DAOException - Lançada quando ocorre erro de banco de dados.
     */
    public function atualizarAtividade($atividade) {
        return $this->atividadeDAO->atualizar($atividade);
    }

    /**
     * Remove objeto Atividade do banco de dados de acordo com o id.
     * 
     * @param int $id
     * @return boolean
     * @throws DAOException - Lançada quando ocorre erro de banco de dados.
     */
    public function removerAtividade($id) {
        return $this->atividadeDAO->remover($id);
    }

    /**
     * Busca objeto Atividade no banco de dados de acordo coms parâmetros.
     * 
     * @param string $term
     * @param int $start
     * @param int $limit
     * @param string $fieldOrder
     * @param string $order
     * @return array
     */
    public function buscarAtividade($term = "", $start = 0, $limit = 10, $fieldOrder = "id", $order = "DESC") {
        return $this->atividadeDAO->buscar($term, $start, $limit, $fieldOrder, $order);
    }

    /**
     * Lista todos os documentos que estão relacionadas com à ativiadde de acordo com o seu id.
     * 
     * @param int $id - Id da atividade
     * @return array - Array contendo os documentos
     * @throws DAOException - Lançada quando ocorrer erro de banco de dados
     */
    public function listarDocumentosAtividade($id) {
        return $this->documentoDAO->listarDocumentosAtividade($id);
    }

    /**
     * Seleciona documento de acordo com o id.
     * 
     * @param int $id
     * @return Documento
     */
    public function selecionarDocumento($id) {
        return $this->documentoDAO->selecionar($id);
    }

    /**
     * Atualiza objeto Documento no banco de dados.
     * 
     * @param Documento $documento
     * @return boolean
     * @throws DAOException - Lançada quando ocorre erro de banco de dados.
     */
    public function atualizarDocumento($documento) {
        return $this->documentoDAO->atualizar($documento);
    }

    /**
     * Remove objeto Documento do banco de dados de acordo com o id.
     * 
     * @param int $id
     * @return boolean
     * @throws DAOException - Lançada quando ocorre erro de banco de dados.
     */
    public function removerDocumento($id) {
        return $this->documentoDAO->remover($id);
    }
    
    /**
     * Remove todos os documentos que estão relacionados com a atividade.
     * 
     * @param int $id - Id da atividade
     * @return boolean
     * @throws DAOException - Lançada quando ocorre erro de banco de dados.
     */
    public function removerDocumentosAtividade($id) {
        return $this->documentoDAO->removerDocumentos($id);
    }

}
