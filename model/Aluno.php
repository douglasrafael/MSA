<?php

/**
 * Representa objeto do tipo Aluno
 * A interface JsonSerializable é implementada, útil para retornar o objeto no formato json quando desejar.
 * 
 * @package     model
 * @author      Douglas Rafael <douglasrafaelcg@gmail.com>
 * @version     v.0.3 (23/03/2017)
 * @copyright   Copyright (c) 2017
 */
class Aluno implements JsonSerializable {

    private $matricula;
    private $nome;
    private $dataNascimento;
    private $dataCadastro;
    private $listaDeAtividades;

    function __construct($matricula = NULL, $nome = NULL, $dataNascimento = NULL, $dataCadastro = NULL, $listaDeAtividades = NULL) {
        $this->matricula = $matricula !== NULL ? $matricula : $this->matricula;
        $this->nome = $nome !== NULL ? $nome : $this->nome;
        $this->dataNascimento = $dataNascimento !== NULL ? $dataNascimento : $this->dataNascimento;
        $this->dataCadastro = $dataCadastro !== NULL ? $dataCadastro : $this->dataCadastro;
        $this->listaDeAtividades = $listaDeAtividades !== NULL ? $listaDeAtividades : $this->listaDeAtividades;
    }

    function getMatricula() {
        return $this->matricula;
    }

    function getNome() {
        return $this->nome;
    }

    function getDataNascimento() {
        return $this->dataNascimento;
    }

    function getDataCadastro() {
        return $this->dataCadastro;
    }

    function setMatricula($matricula) {
        $this->matricula = $matricula;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setDataNascimento($dataNascimento) {
        $this->dataNascimento = $dataNascimento;
    }

    function setDataCadastro($dataCadastro) {
        $this->dataCadastro = $dataCadastro;
    }

    function getListaDeAtividades() {
        return $this->listaDeAtividades;
    }

    function setListaDeAtividades($listaDeAtividades) {
        $this->listaDeAtividades = $listaDeAtividades;
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
            'matricula' => $this->matricula,
            'nome' => $this->nome,
            'dataNascimento' => $this->dataNascimento,
            'dataCadastro' => $this->dataCadastro,
            'listaDeAtividades' => $this->listaDeAtividades,
        );
    }

}
