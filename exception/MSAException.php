<?php

/**
 * Representa exceção geral do sistema MSA.
 * Outros tipos de exceção contidas no sistema deverão a estender.
 * 
 * extends Exception.
 * 
 * @access public
 * 
 * @package     model
 * @author      Douglas Rafael <douglasrafaelcg@gmail.com>
 * @version     v.0.1 (23/03/2017)
 * @copyright   Copyright (c) 2017
 */
class MSAException extends Exception {

    /**
     * Contrutor
     *
     * @access public
     *        
     * @param string $message - Mensagem do erro       	
     * @param int $code - Código do erro        	
     * @param Exception $previous - Uma outra excessão         	
     */
    public function __construct($message, $code = null, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see Exception::__toString()
     */
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
