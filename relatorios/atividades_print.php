<?php

include_once __DIR__ . '/../controller/Fachada.php';
include_once __DIR__ . '/../uteis/MyDate.php';
include_once __DIR__ . '/MyMPDF.php';
include_once __DIR__ . '/lib/mpdf_6.1/vendor/autoload.php';

$fachada = new Fachada();
$listaDetividades = $fachada->listarAtividades();

$result = '<h4>Não há dados para exibir...</h4>';
if (!empty($listaDetividades)) {
    $result = '<h1><u>Relatório - Lista de Atividades</u></h1>'
            . '<table class="active-style"><thead><tr>'
            . '<td>T&iacute;tulo</td>'
            . '<td>Descri&ccedil;&atilde;o</td>'
            . '<td>Aluno</td>'
            . '<td>Disciplina</td>'
            . '<td>Data Entrega</td>'
            . '<td>Data Cadstro</td>'
            . '<td>Documentos</td></tr></thead><tbody>';

    foreach ($listaDetividades as $key => $atividade) {
        $result .= "<tr>
			<td>{$atividade->getTitulo()}</td>
			<td>{$atividade->getDescricao()}</td>
			<td>{$atividade->getAluno()->getNome()}</td>
			<td>{$atividade->getDisciplina()->getNome()}</td>
			<td>" . MyDate::dateToBR($atividade->getDataEntrega()) . "</td>
			<td>" . MyDate::datetimeToMasc($atividade->getDataCadastro(), 'd/m/Y \à\s H:i:s') . "</td><td>";
        // montando documentos
        $result .= '<ol>';                
        foreach ($atividade->getListaDeDocumentos() as $doc) {
            $result .= '<div class="box-links"><li><a href="../upload/docs/' . $doc->getEndereco() . '" target="_blank">' . $doc->getTitulo() . '</a></li></div>';
        }
        $result .= "</ol></td></tr>";
    }
    $result .= '</tbody></table>';
}

/**
 * Gerando o doc pdf
 */
$mpdf = new MyMPDF("MSA - Manager of School Activities", 'FS Developer', $result, file_get_contents('styles.css'), 'A4-L');
$mpdf->buildPDF();
$mpdf->exibir('relatório-atividades_' . date('d/m/Y\_\a\s\_H:i:s') . '.pdf');
