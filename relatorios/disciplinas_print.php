<?php

include_once __DIR__ . '/../controller/Fachada.php';
include_once __DIR__ . '/../uteis/MyDate.php';
include_once __DIR__ . '/MyMPDF.php';
include_once __DIR__ . '/lib/mpdf_6.1/vendor/autoload.php';

$fachada = new Fachada();
$listaDeDisciplinas = $fachada->listarDisciplinas();

$result = '<h4>Não há dados para exibir...</h4>';
if (!empty($listaDeDisciplinas)) {
    $result = '<h1><u>Relatório - Lista de Disciplinas</u></h1>'
            . '<table class="active-style" style="width: 100%"><thead><tr>'
            . '<td>Id</td>'
            . '<td>Nome</td>'
            . '<td>Carga Horária</td></tr></thead><tbody>';

    foreach ($listaDeDisciplinas as $key => $disciplina) {
        $result .= "<tr>
			<td>" . str_pad($disciplina->getId(), 4, '0', STR_PAD_LEFT)  . "</td>
			<td>{$disciplina->getNome()}</td>
			<td>{$disciplina->getCargaHoraria()} horas</td>"
                . "</tr>";
    }
    $result .= '</tbody></table>';
}

/**
 * Gerando o doc pdf
 */
$mpdf = new MyMPDF("MSA - Manager of School Activities", 'FS Developer', $result, file_get_contents('styles.css'));
$mpdf->buildPDF();
$mpdf->exibir('relatório-disciplinas_' . date('d/m/Y\_\a\s\_H:i:s') . '.pdf');
