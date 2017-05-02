<?php

/**
 * Representa objeto do tipo Documento.
 * A interface JsonSerializable é implementada, útil para retornar o objeto no formato json quando desejar.
 * 
 * @package     model
 * @author      Douglas Rafael <douglasrafaelcg@gmail.com>
 * @version     v.0.3 (23/03/2017)
 * @copyright   Copyright (c) 2017
 */
class Documento implements JsonSerializable {

    private $id;
    private $titulo;
    private $endereco;

    /**
     * Objeto do tipo Atividade
     * 
     * @var Atividade  
     */
    private $atividade;

    function __construct($titulo = NULL, $endereco = NULL, $atividade = NULL) {
        $this->titulo = $titulo !== NULL ? $titulo : $this->titulo;
        $this->endereco = $endereco !== NULL ? $endereco : $this->endereco;
        $this->atividade = $atividade !== NULL ? $atividade : $this->atividade;
    }

    function getId() {
        return $this->id;
    }

    function getTitulo() {
        return $this->titulo;
    }

    function getEndereco() {
        return $this->endereco;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    function setEndereco($endereco) {
        $this->endereco = $endereco;
    }
    
    function getAtividade() {
        return $this->atividade;
    }

    function setAtividade(Atividade $atividade) {
        $this->atividade = $atividade;
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
            'endereco' => $this->endereco,
            'atividade' => $this->atividade
        );
    }

}
