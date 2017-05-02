<?php

require_once __DIR__ . '/lib/mpdf_6.1/vendor/autoload.php';

/**
 * Classse para gerar relatórios com template padrão.
 * 
 * @package     relatorios
 * @author      Douglas Rafael <douglasrafaelcg@gmail.com>
 * @version     v.0.1 (27/03/2017)
 * @copyright   Copyright (c) 2017
 */
class MyMPDF extends mPDF {

    private $pdf;
    private $titulo;
    private $footer;
    private $html;
    private $css;
    public $format;
    public $default_font_size;
    public $default_font;

    /**
     * Construtor.
     * 
     * @param string $titulo - Título
     * @param string $footer - Footer
     * @param string $html - Html conteudo
     * @param string $css - CSS
     * @param string $format - formato da página A4/A4-L
     * @param string $default_font_size - Tamanho da fonte
     * @param string $default_font - Nome da fonte
     */
    function __construct($titulo, $footer, $html, $css = NULL, $format = 'A4', $default_font_size = 9, $default_font = 'freesans') {
        $this->titulo = $titulo;
        $this->footer = $footer;
        $this->html = $html;
        $this->css = $css;
        $this->format = $format;
        $this->default_font_size = $default_font_size;
        $this->default_font = $default_font;
    }

    /**
     * Método para setar o conteúdo do arquivo CSS para o atributo css  
     * @param $file - Caminho para arquivo CSS  
     */
    public function setarCSS($file) {
        if (file_exists($file)):
            $this->css = file_get_contents($file);
        else:
            echo 'Arquivo CSS inexistente!';
        endif;
    }

    /**
     * Método para montar o Cabeçalho do relatório em PDF  
     * 
     * @return string
     */
    protected function getHeader() {
        date_default_timezone_set('America/Sao_Paulo');
        $data = date('d/m/Y \à\s H:i:s');
        $retorno = "<table class=\"tbl_header\" width=\"1000\">  
               <tr>  
                 <td align=\"left\">{$this->titulo}</td>  
                 <td align=\"right\">Gerado em: $data</td>  
               </tr>  
             </table>";
        return $retorno;
    }

    /**
     * get content html
     * 
     * @return stirng
     */
    protected function getContent() {
        return  $this->html ;
    }

    /**
     * Método para montar o Rodapé do relatório em PDF
     * 
     * @return string
     */
    protected function getFooter() {
        $retorno = "<table class=\"tbl_footer\" width=\"1000\">  
               <tr>  
                 <td align=\"left\"><a href='mailto:douglasrafaelcg@gmail.com'>{$this->footer}</a></td>  
                 <td align=\"right\">Página: {PAGENO}</td>  
               </tr>  
             </table>";
        return $retorno;
    }

    /**
     * Método para construir o arquivo PDF  
     */
    public function buildPDF() {
        $this->pdf = new mPDF('utf-8', $this->format, $this->default_font_size, $this->default_font);

        if (!empty($this->css)) {
            $this->pdf->WriteHTML($this->css, 1);
        }
        $this->pdf->SetHTMLHeader($this->getHeader());
        $this->pdf->WriteHTML($this->getContent(), 2);
        $this->pdf->SetHTMLFooter($this->getFooter());
    }

    /**
     * Método para exibir o arquivo PDF  
     * 
     * @param $name - Nome do arquivo se necessário grava-lo 
     */
    public function exibir($name = null) {
        $this->pdf->Output($name, 'I');
    }

}
