<?php

include_once __DIR__ . '/../controller/Fachada.php';
include_once __DIR__ . '/../uteis/MyDate.php';
include_once __DIR__ . '/MyMPDF.php';
include_once __DIR__ . '/lib/mpdf_6.1/vendor/autoload.php';

$fachada = new Fachada();
$alunos = $fachada->listarAtividades();

$result = '<h4>Não há dados para exibir...</h4>';
$listaDeAlunos = $fachada->listarAlunos();

if (!empty($listaDeAlunos)) {
    $result = '<h1><u>Relatório - Lista de Alunos</u></h1>'
            . '<table class="active-style" style="width: 100%"><thead><tr>'
            . '<td>Matrícula</td>'
            . '<td>Nome</td>'
            . '<td>Data Nascimento</td>'
            . '<td>Data Cadastro</td></tr></thead><tbody>';

    foreach ($listaDeAlunos as $key => $aluno) {
        $result .= "<tr>
			<td>{$aluno->getmatricula()}</td>
			<td>{$aluno->getNome()}</td>
			<td>" . MyDate::dateToBR($aluno->getDataNascimento()) . "</td>
			<td>" . MyDate::datetimeToMasc($aluno->getDataCadastro(), 'd/m/Y \à\s H:i:s') . "</td>"
                . "</tr>";
    }
    $result .= '</tbody></table>';
}

/**
 * Gerando o doc pdf
 */
$mpdf = new MyMPDF("MSA - Manager of School Activities", 'FS Developer', $result, file_get_contents('styles.css'));
$mpdf->buildPDF();
$mpdf->exibir('relatório-alunos_' . date('d/m/Y\_\a\s\_H:i:s') . '.pdf');