<?php

/**
 * Fornece métodos úteis para manipulação de data e hora.
 *
 * @package     uteis
 * @author      Douglas Rafael <douglasrafaelcg@gmail.com>
 * @version     v.0.1 (24/03/2017)
 * @copyright   Copyright (c) 2017
 */
class MyDate {

    /**
     * Recebe uma data no formato YYYY-mm-dd e retorna no formato dd/mm/YYYY.
     * Útil para converter data vinda do banco e exibir na view.
     * 
     * @param string $date
     * @return string
     */
    public static function dateToBR($date) {
        return date('d/m/Y', strtotime($date));
    }

    /**
     * Recebe uma data no formato dd/mm/YYYY e retorna no formato YYYY-mm-dd.
     * Útil para converter data vinda da view e salvar no banco de dados.
     * 
     * @param string $date
     * @return string
     */
    public static function dateToDB($date) {
        return date("Y-m-d", strtotime(str_replace('/', '-', $date)));
    }

    /**
     * Recebe uma datatime no formato YYYY-mm-dd H:i:s e retorna no formato de acordo com a $mascara.
     * Útil para converter data e hora vinda do banco e exibir na view.
     * 
     * @param string $datetime
     * @param string $mascara
     * @return string
     */
    public static function datetimeToMasc($datetime, $mascara) {
        return date($mascara, strtotime($datetime));
    }

}
