<?php

include_once __DIR__ . '/MSAException.php';

/**
 * Representa exceção do tipo Validation.
 * extends MSAException.
 * 
 * @access public
 * 
 * @package     model
 * @author      Douglas Rafael <douglasrafaelcg@gmail.com>
 * @version     v.0.1 (23/03/2017)
 * @copyright   Copyright (c) 2017
 */
class ValidationException extends MSAException {

    /**
     * Contrutor
     *
     * @access public
     * 
     * @param string $message - Mensagem do erro       	
     * @param int $code - Código do erro        	
     * @param Exception $previous - Uma outra excessão          	
     */
    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

}
