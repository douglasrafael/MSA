<?php

/**
 * Classe para manipulação de mensagens usadas no sistema.
 * As mensagens são retornadas usando o padrão alert do bootstrap.
 *
 * @access public
 * 
 * @package     controller
 * @author      Douglas Rafael <douglasrafaelcg@gmail.com>
 * @version     v.1.2 (17/02/2017)
 * @copyright   Copyright (c) 2017
 */
class Messenger {

    /**
     * Exibe mensagem personalizada que pode ser de:
     * Sucesso, aviso, informação, ou perigo.
     * 
     * A mensagem é retornada seguindo o format alert do boostrap.
     * Exemplo:
     * <code>
     * <div class='alert alert-success snackbar' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'>
     *      <span aria-hidden='true'>&times;</span></button>
     *      <h4 class='alert-heading'>Título da Mensagem</h4>
     *      <p>Mensagem...</p>
     * </div>
     * </code>
     *
     * @access public
     *        
     * @param string $message - Mensagem a ser exibida ao usuário.
     * @param string $type - Tipo da mensagem: success | info | warning | danger. O valor default é success.
     * @return string - Mensagem no format alert do bootstrap. 
     */
    public static function printMessage($message, $type = "success") {
        switch ($type) {
            case 'info' :
                $type = 'alert-info';
                break;
            case 'warning' :
                $type = 'alert-warning';
                break;
            case 'danger' :
                $type = 'alert-danger';
                break;
            default :
                $type = 'alert-success';
                break;
        }
        echo "<div class='alert {$type} snackbar' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'>"
        . "<span aria-hidden='true'>&times;</span></button><h4 class='alert-heading'>" . Messenger::getNameTitle($type) . "</h4><p>$message</p></div>";
    }

    /**
     * Exibe mensagem de sucesso.
     *
     * @access public
     *        
     * @param string $message - Mensagem a ser exibida ao usuário.
     * @return string - Mensagem no format alert do bootstrap.      	
     */
    public static function printMessageSuccess($message) {
        self::printMessage($message, 'success');
    }

    /**
     * Exibe mensagem de informação.
     *
     * @access public
     *        
     * @param string $message - Mensagem a ser exibida ao usuário.
     * @return string - Mensagem no format alert do bootstrap.   	
     */
    public static function printMessageInfo($message) {
        self::printMessage($message, 'info');
    }

    /**
     * Exibe mensagem de aviso.
     *
     * @access public
     *        
     * @param string $message - Mensagem a ser exibida ao usuário.
     * @return string - Mensagem no format alert do bootstrap.      	
     */
    public static function printMessageWarning($message) {
        self::printMessage($message, 'warning');
    }

    /**
     * Exibe mensagem de perigo.
     *
     * @access public
     *        
     * @param string $message - Mensagem a ser exibida ao usuário.
     * @return string - Mensagem no format alert do bootstrap.     	
     */
    public static function printMessageDanger($message) {
        self::printMessage($message, 'danger');
    }

    /**
     * Exibe show alert com a mensagem passada como parâmetro.
     * 
     * @access public
     * 
     * @param string $message - Mensagem a ser exibida ao usuário.
     * @return string - Mensagem no format alert do javascript. 
     */
    public static function showAlert($message) {
        echo "<script language='javascript'>alert(\"$message\");</script>";
    }

    /**
     * Prepara a mensagem antes de imprimir.
     * Útil quando se quer retornar a mensagm dentro de um objeto json.
     * 
     * @param string $message - Mensagem a ser exibida ao usuário.
     * @param string $type - Tipo da mensagem: success | info | warning | danger
     * @return string - Mensagem no format alert do bootstrap. 
     */
    public static function preparedMessage($message, $type = "success") {
        switch ($type) {
            case 'info' :
                $type = 'alert-info';
                break;
            case 'warning' :
                $type = 'alert-warning';
                break;
            case 'danger' :
                $type = 'alert-danger';
                break;
            default :
                $type = 'alert-success';
                break;
        }

        return "<div class='alert {$type
                } snackbar' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'>"
                . "<span aria-hidden='true'>&times;</span></button><h4 class='alert-heading'>" . Messenger::getNameTitle($type) . "</h4><p>$message</p></div>";
    }

    /**
     * Retorna o título da mensagem de acordo com o parâmetro:
     * - alert-success (Sucesso)
     * - alert-info (Informação)
     * - alert-warning (Atenção)
     * - alert-danger (Erro)
     * 
     * @access public
     * 
     * @param string $type - Tipo da mensagem: alert-success | alert-info | alert-warning | alert-danger
     * @return string - Tipo da mensagem.
     */
    private static function getNameTitle($type) {
        switch ($type) {
            case 'alert-info' :
                $type = 'Informação';
                break;
            case 'alert-warning' :
                $type = 'Atenção';
                break;
            case 'alert-danger' :
                $type = 'Erro';
                break;
            default :
                $type = 'Sucesso';
                break;
        }
        return $type;
    }

}
