<?php

/**
 * Representa objeto do tipo Disciplina.
 * A interface JsonSerializable é implementada, útil para retornar o objeto no formato json quando desejar.
 *
 * @package     model
 * @author      Douglas Rafael <douglasrafaelcg@gmail.com>
 * @version     v.0.2 (23/03/2017)
 * @copyright   Copyright (c) 2017
 */
class Disciplina implements JsonSerializable {

    private $id;
    private $nome;
    private $cargaHoraria;

    /**
     * Lista de Atividades
     * 
     * @var array 
     */
    private $listaDeAtividades;

    function __construct($id = NULL, $nome = NULL, $cargaHoraria = NULL, $listaDeAtividades = NULL) {
        $this->id = $id !== NULL ? $id : $this->id;
        $this->nome = $nome !== NULL ? $nome : $this->nome;
        $this->cargaHoraria = $cargaHoraria !== NULL ? $cargaHoraria : $this->cargaHoraria;
        $this->listaDeAtividades = $listaDeAtividades !== NULL ? $listaDeAtividades : $this->listaDeAtividades;
    }

    function getId() {
        return $this->id;
    }

    function getNome() {
        return $this->nome;
    }

    function getCargaHoraria() {
        return $this->cargaHoraria;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setCargaHoraria($cargaHoraria) {
        $this->cargaHoraria = $cargaHoraria;
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
            'id' => $this->id,
            'nome' => $this->nome,
            'cargaHoraria' => $this->cargaHoraria,
            'listaDeAtividades' => $this->listaDeAtividades
        );
    }

}
