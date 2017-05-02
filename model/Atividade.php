<?php

/**
 * Representa objeto do tipo Atividade.
 * A interface JsonSerializable é implementada, útil para retornar o objeto no formato json quando desejar.
 *
 * @package     model
 * @author      Douglas Rafael <douglasrafaelcg@gmail.com>
 * @version     v.0.4 (25/03/2017)
 * @copyright   Copyright (c) 2017
 */
class Atividade implements JsonSerializable {

    private $id;
    private $titulo;
    private $descricao;
    private $dataEntrega;
    private $dataCadastro;

    /**
     * Objeto do tipo Aluno.
     * @var Aluno 
     */
    private $aluno;

    /**
     * Objeto do tipo Disciplina.
     * @var Disciplina 
     */
    private $disciplina;

    /**
     * Array de objetos do tipo Documento.
     * 
     * @var array
     */
    private $listaDeDocumentos;

    /**
     * Construtor.
     * 
     * @param string $titulo
     * @param string $descricao
     * @param string $dataEntrega
     * @param string $dataCadastro
     * @param Aluno $aluno
     * @param Disciplina $disciplina
     * @param array $listaDeDocumentos
     */
    function __construct($titulo = NULL, $descricao = NULL, $dataEntrega = NULL, $dataCadastro = NULL, $aluno = NULL, $disciplina = NULL, $listaDeDocumentos = NULL) {
        $this->titulo = $titulo !== NULL ? $titulo : $this->titulo;
        $this->descricao = $descricao !== NULL ? $descricao : $this->descricao;
        $this->dataEntrega = $dataEntrega !== NULL ? $dataEntrega : $this->dataEntrega;
        $this->dataCadastro = $dataCadastro !== NULL ? $dataCadastro : $this->dataCadastro;
        $this->aluno = $aluno !== NULL ? $aluno : $this->aluno;
        $this->disciplina = $disciplina !== NULL ? $disciplina : $this->disciplina;
        $this->listaDeDocumentos = $listaDeDocumentos !== NULL ? $listaDeDocumentos : $this->listaDeDocumentos;
    }

    function getId() {
        return $this->id;
    }

    function getTitulo() {
        return $this->titulo;
    }

    function getDescricao() {
        return $this->descricao;
    }

    function getDataEntrega() {
        return $this->dataEntrega;
    }

    function getDataCadastro() {
        return $this->dataCadastro;
    }

    function getAluno() {
        return $this->aluno;
    }

    function getDisciplina() {
        return $this->disciplina;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    function setDataEntrega($dataEntrega) {
        $this->dataEntrega = $dataEntrega;
    }

    function setDataCadastro($dataCadastro) {
        $this->dataCadastro = $dataCadastro;
    }

    function setAluno($aluno) {
        $this->aluno = $aluno;
    }

    function setDisciplina($disciplina) {
        $this->disciplina = $disciplina;
    }
    
    function getListaDeDocumentos() {
        return $this->listaDeDocumentos;
    }

    function setListaDeDocumentos($listaDeDocumentos) {
        $this->listaDeDocumentos = $listaDeDocumentos;
    }

    /**
     * Implementação do método da interface JsonSerializable.
     * Quando o objeto for convertido em um json esse método será utilizado pela interface.
     *  
     * @access public
     * 
     * @return array - Array contendo os atributos do objeto.
     */
    public function jsonSerialize() {
        return array(
            'id' => $this->id,
            'titulo' => $this->titulo,
            'descricao' => $this->descricao,
            'dataEntrega' => $this->dataEntrega,
            'dataCadastro' => $this->dataCadastro,
            'aluno' => $this->aluno,
            'disciplina' => $this->disciplina,
            'listaDeDocumentos' => $this->listaDeDocumentos
        );
    }
}