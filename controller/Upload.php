<?php

/**
 * Classe para fazer upload de arquivos
 * 
 * @access public
 * 
 * @package     model
 * @author      Douglas Rafael <douglasrafaelcg@gmail.com>
 * @version     v.1.2 (17/02/2017)
 * @copyright   Copyright (c) 2017
 */
class Upload {

    const TAM_LIMIT = 2097152; // 2MB
    const DEFAULT_PATH = '../upload/';

    private $file;
    private $dir_upload;
    private $name;

    /**
     * Construtor que recebe o arquivo a ser enviado e o diretório.
     * Caso o diretório passado como parâmetro não exista o mesmo é criado.
     * 
     * @access public
     * 
     * @param array $file - Arquivo a ser enviado.
     * @param string $dir_upload - Diretório para o upload, se for null será feito para diretório default.
     * @param string $name - Nome do arquivo, se for null será gerado um aleatorio.
     */
    public function __construct($file, $dir_upload = null, $name = null) {
        $this->file = $file;
        $this->dir_upload = $dir_upload;
        $this->name = !empty($name) ? $this->editName($name) : $this->generateName();

        // Verifica se o diretorio foi passado como parâmetro.
        // Verifica se o diretório passado existe.
        // Cria o diretório caso não exista
        if (!empty($dir_upload) && !file_exists($this->dir_upload)) {
            mkdir($this->dir_upload);
        }
    }

    /**
     * Faz o upload do arquivo para a pasta definida ou para a pastra default.
     *
     * @access public
     * 
     * @return string | NULL
     */
    public function send() {
        $file_dir = self::DEFAULT_PATH;

        if (empty($this->dir_upload)) {
            $file_dir .= ($this->isFile() ? 'files/' : 'images/');
        } else {
            $file_dir = $this->dir_upload;
        }
        $file_dir .= $this->name;

        $result = false;
        if (is_uploaded_file($this->file ["tmp_name"])) {
            $result = move_uploaded_file($this->file ["tmp_name"], $file_dir);
        }

        if ($result) {
            return $this->name;
        }
        return NULL;
    }

    /**
     * Retorna extensao do arquivo.
     * 
     * @access public
     *
     * @return string - Extensão do arquivo.
     */
    private function getExtensao() {
        return pathinfo($this->file['name'], PATHINFO_EXTENSION);
    }

    /**
     * Verifica se o arquivo possui extenssão do tipo imagem válida.
     * Os seguintes tipos de imagens são permitidos: JPG, JPEG e PNG.
     * 
     * @access public
     *
     * @return boolean - Se é imagem retorna true, caso contrário false.
     */
    private function isImage() {
        return in_array($this->getExtensao(), $this->EXT_IMAGES);
    }

    /**
     * Verifica se o arquivo possui extenssão válida. Ou seja se é do tipo file permitido.
     * Os seguintes tipos de arquivos são permitidos: PDF, DOC e DOCX.
     * 
     * @access public
     *
     * @return boolean - Se é arquivo de texto retorna true, caso contrário false.
     */
    private function isFile() {
        return in_array($this->getExtensao(), $this->EXT_FILES);
    }

    /**
     * Retorna um nome aleatório com padrão.
     * Exemplo de retorno:<code>NF[0-99999]_99-99-9999.jpg</code>
     * 
     * @access public
     * 
     * @return string - Nome do arquivo gerado aleatoriamente.
     */
    private function generateName() {
        date_default_timezone_set("America/Sao_Paulo");
        $prefix = (!empty($this->prefix_name)) ? $this->prefix_name : '';
        return $prefix . rand(0, 99999) . '_' . date('d-m-Y') . '.' . $this->getExtensao();
    }

    /**
     * Edita o nome do arquivo removendo caracteres especiais e espaços em branco.
     * No final é acrescido a extensão e convertido para minusculo.
     * 
     * @param string $name Nome do arquivo
     * @return string
     */
    private function editName($name) {
        return rand(0, 99999) . '_' . strtolower((str_replace(" ", "_", preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities(trim($name))))) . '.' . $this->getExtensao());
    }

}
