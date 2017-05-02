<?php

include_once __DIR__ . '/Fachada.php';
include_once __DIR__ . '/Upload.php';
include_once __DIR__ . '/../model/Documento.php';

$atividadeController = new AtividadeController(new Fachada());

if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST') {
    $action = filter_input(INPUT_POST, 'action');

    // Verificando se há dados vindo do form
    if (filter_has_var(INPUT_POST, 'titulo')) {
        /**
         * Pegando os dados vindo do form
         */
        $titulo = filter_input(INPUT_POST, 'titulo');
        $descricao = filter_input(INPUT_POST, 'descricao');
        $matriculaAluno = filter_input(INPUT_POST, 'matricula-aluno');
        $idDisciplina = filter_input(INPUT_POST, 'id-disciplina');
        $dataEntrega = MyDate::dateToDB(filter_input(INPUT_POST, 'data-entrega'));

        $atividade = new Atividade($titulo, $descricao, $dataEntrega);
        $atividade->setAluno(new Aluno($matriculaAluno));
        $atividade->setDisciplina(new Disciplina($idDisciplina));
    }

    /**
     * Verifica a ação e chama os métodos respectivos
     */
    if ($action === 'list') {
        $atividadeController->listar();
    } else if ($action === 'insert') {
        $atividadeController->inserir($atividade);
    } else if ($action === 'update') {
        $atividade->setId(filter_input(INPUT_POST, 'id'));
        $atividadeController->atualizar($atividade);
    } else if ($action === 'get') {
        $atividadeController->selecionar(filter_input(INPUT_POST, 'id'));
    } else if ($action === 'delete') {
        $atividadeController->remover(filter_input(INPUT_POST, 'id'));
    } else if ($action === 'search') {
        $atividadeController->buscar(filter_input(INPUT_POST, 'term'));
    }
} else if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'GET') {
    $action = filter_input(INPUT_GET, 'action');

    if ($action === 'auto-complete-aluno') {
        $atividadeController->autoComplete(filter_input(INPUT_GET, 'term'));
    } else if ($action === 'auto-complete-disciplina') {
        $atividadeController->autoComplete(filter_input(INPUT_GET, 'term'), FALSE);
    }
}

class AtividadeController {

    /**
     * Instância da fachada.
     * 
     * @var Fachada 
     */
    private $fachada;

    /**
     * Construtor.
     * 
     * @param Fachada $fachada - Instância da fachada
     */
    public function __construct($fachada) {
        if (empty($fachada)) {
            $fachada = new Fachada();
        }
        $this->fachada = $fachada;
    }

    /**
     * Insere atividade no banco.
     * 
     * @param Atividade $atividade
     */
    public function inserir($atividade) {
        try {
            /**
             * Realizando fluxo do upload dos arquivos
             */
            if (isset($_POST['nome-documentos'])) {
                $nomeDocumentos = $_POST['nome-documentos'];
                $listaDeDocumentos = array();

                // Monta o array com os arquivos
                $documentos = $this->preparaDocumentos();

                /**
                 * Realiza o upload
                 */
                foreach ($documentos as $key => $doc) {
                    if ($doc['error'] === UPLOAD_ERR_OK && !empty($doc['tmp_name'])) {
                        $upload = new Upload($doc, '../upload/docs/', $nomeDocumentos[$key]);

                        // Verifica se fez o upload
                        if (!empty(($endereco = $upload->send()))) {
                            array_push($listaDeDocumentos, new Documento($nomeDocumentos[$key], $endereco));
                        }
                    }
                }

                // Seta no obeto a lista de documentos
                $atividade->setListaDeDocumentos($listaDeDocumentos);
            }
            if ($this->fachada->inserirAtividade($atividade)) {
                Messenger::printMessageSuccess("Atividade: <b>{$atividade->getTitulo()}</b> cadastrada com sucesso!");
            } else {
                Messenger::printMessageWarning('Não foi possível cadastrar a atividade!<br/>Por favor, tente novamente...');
            }
        } catch (DAOException $e) {
            if ($e->getCode() === Conexao::DUPLICATE_ENTRY) {
                Messenger::printMessageWarning("Essa atividade já possui cadastro!");
            } else {
                Messenger::printMessageDanger($e->getMessage());
            }
        }
    }

    /**
     * Monta array com os documentos vindos do form.
     * 
     * @return array
     */
    private function preparaDocumentos() {
        $documentos = array();
        foreach ($_FILES['documentos'] as $k => $l) {
            foreach ($l as $i => $v) {
                if (!array_key_exists($i, $documentos)) {
                    $documentos[$i] = array();
                }
                $documentos[$i][$k] = $v;
            }
        }
        return $documentos;
    }

    /**
     * Seleciona Atividade pela $id
     * 
     * @param Atividade $id
     * @return json - Objeto Atividade no formato json.
     */
    public function selecionar($id) {
        try {
            $atividade = $this->fachada->selecionarAtividade($id);
            if (!empty($atividade)) {
                echo json_encode($atividade);
            } else {
                echo json_encode(array("error" => Messenger::preparedMessage("Não há dados para serem exibidos...", "info")));
            }
        } catch (DAOException $e) {
            echo json_encode(array("error" => Messenger::preparedMessage("Ocorreu um erro de banco de dados ao tentar recuperar os dados!", "danger")));
        }
    }

    /**
     * Insere atividade no banco.
     * 
     * @param Atividade $atividade
     */
    public function atualizar($atividade) {
        try {
            $result_temp = FALSE;
            $listaAntDocumentos = $this->fachada->listarDocumentosAtividade($atividade->getId());
            
            /**
             * Realizando fluxo do upload dos arquivos
             */
            if (isset($_POST['nome-documentos'])) {
                $nomeDocumentos = $_POST['nome-documentos'];

                $listaDeDocumentos = array();

                // Monta o array com os arquivos
                $documentos = $this->preparaDocumentos();

                /**
                 * Realiza o upload
                 */
                foreach ($documentos as $key => $doc) {
                    if ($doc['error'] === UPLOAD_ERR_OK && !empty($doc['tmp_name'])) {
                        $upload = new Upload($doc, '../upload/docs/', $nomeDocumentos[$key]);

                        // Verifica se fez o upload
                        if (!empty(($endereco = $upload->send()))) {
                            array_push($listaDeDocumentos, new Documento($nomeDocumentos[$key], $endereco));
                        }
                    }
                }

                /**
                 * Verifica se é para remover documentos
                 */
                if (isset($_POST['id-documentos'])) {
                    foreach ($listaAntDocumentos as $key => $doc) {
                        if (!in_array($doc->getId(), $_POST['id-documentos'])) { // Remove o documento
                            $result_temp = $this->fachada->removerDocumento($doc->getId());
                        } else if (isset($nomeDocumentos[$key]) && $doc->getTitulo() !== $nomeDocumentos[$key]) { // Atualiza o nome do documento
                            $doc->setTitulo($nomeDocumentos[$key]);
                            $result_temp = $this->fachada->atualizarDocumento($doc);
                        }
                    }
                }

                // Seta no obeto a lista de documentos
                $atividade->setListaDeDocumentos($listaDeDocumentos);
            } else if(!empty($listaAntDocumentos)) {
                // Remove todos documentos relacionados a atividade
                $result_temp = $this->fachada->removerDocumentosAtividade($atividade->getId());
            }
            
            if ($this->fachada->atualizarAtividade($atividade) || $result_temp) {
                Messenger::printMessageSuccess("Os dados da Atividade: <b>{$atividade->getTitulo()}</b> foram atualizados com sucesso!");
            } else {
                Messenger::printMessageWarning('Não foi possível atualizar os dados!<br/>Por favor, tente novamente...');
            }
        } catch (DAOException $e) {
            if ($e->getCode() === Conexao::DUPLICATE_ENTRY) {
                Messenger::printMessageWarning("Esse atividade já está cadastrado!");
            } else {
                Messenger::printMessageDanger($e->getMessage());
            }
        }
    }

    /**
     * Remove atividade do banco.
     * 
     * @param int $id
     */
    public function remover($id) {
        try {
            if ($this->fachada->removerAtividade($id)) {
                Messenger::printMessageSuccess("Atividade removida com sucesso!");
            } else {
                Messenger::printMessageWarning('Não foi possível remover. Por favor, tente novamente...');
            }
        } catch (DAOException $e) {
            Messenger::printMessageDanger($e->getMessage());
        }
    }

    /**
     * selecionar atividade pelo id
     * 
     * @param Atividade $atividade
     */
    public function listar() {
        try {
            $atividades = $this->fachada->listarAtividades();

            if (!empty($atividades)) {
                echo json_encode($atividades);
            } else {
                echo json_encode(array('message' => '<br/><p class="text-center">Não há dados a serem exibidos...</p>'));
            }
        } catch (DAOException $e) {
            Messenger::printMessageDanger($e->getMessage());
        }
    }

    /**
     * Buscar atividade pelo termo
     * 
     * @param string $term
     */
    public function buscar($term) {
        try {
            $atividades = $this->fachada->buscarAtividade($term);

            if (!empty($atividades)) {
                echo json_encode($atividades);
            } else {
                echo json_encode(array("message" => Messenger::preparedMessage("Não há dados para serem exibidos...", "info")));
            }
        } catch (DAOException $e) {
            Messenger::printMessageDanger($e->getMessage());
        }
    }

    /**
     * Busca alunos/disciplina de acordo com o parâmtros para exibir no auto-complete
     * 
     * @param String $term - Termo da busca
     * @param boolean $isAluno - Se a bsuca é por aluno, caso contrpario será por disciplina
     */
    public function autoComplete($term, $isAluno = TRUE) {
        try {
            echo $isAluno ? json_encode($this->fachada->buscarAluno($term)) : json_encode($this->fachada->buscarDisciplina($term));
        } catch (DAOException $e) {
            Messenger::printMessageDanger($e->getMessage());
        }
    }

}
