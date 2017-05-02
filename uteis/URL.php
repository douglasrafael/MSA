<?php

/**
 * Fornece métodos úteis para manipulação de url.
 *
 * @package     uteis
 * @author      Douglas Rafael <douglasrafaelcg@gmail.com>
 * @version     v.0.1 (23/03/2017)
 * @copyright   Copyright (c) 2017
 */
class URL {

    /**
     * Retorna o nome da página de acordo com a URL.
     * Ex. URL: /msa/home.php irá retornar a string "home"
     * 
     * @return string ou FALSE - Retorna a página ou FALSE caso não seja possível obter.
     */
    public static function getPagina() {
        $_arr = explode("/", $_SERVER['REQUEST_URI']);
        return substr(end($_arr), 0, -4);
    }

    /**
     * Retona página através da qual o agente do usuário acessou a página atual. Ou seja, de onde o usuário veio.
     * 
     * @param boolean $ext - Se o nome da página deve ou nõa ser retornado com a extenção
     * @return string ou FALSE - Retorna a página ou FALSE caso não seja possível obter.
     */
    public static function getUltimaPagina($ext = FALSE) {
        $_arr = explode("/", filter_input(INPUT_SERVER, 'HTTP_REFERER'));
        return $ext ? end($_arr) : substr(end($_arr), 0, -4);
    }

    /**
     * Retorna a url base da aplicação.
     * 
     * @return string
     */
    public static function getBasePath() {
        $base_url = "http://" . filter_input(INPUT_SERVER, 'HTTP_HOST');
        $base_url .= str_replace(basename(filter_input(INPUT_SERVER, 'SCRIPT_NAME')), "", filter_input(INPUT_SERVER, 'SCRIPT_NAME'));
        return $base_url;
    }

    /**
     * 
     * @return type
     */
    public static function getPathBase() {
        return filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/msa/';
    }

}
