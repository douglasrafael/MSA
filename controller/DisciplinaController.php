<?php

include_once __DIR__ . '/Fachada.php';

$disciplinaController = new DisciplinaController(new Fachada());

if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST') {
    $action = filter_input(INPUT_POST, 'action');

    // Verificando se há dados vindo do form
    if (filter_has_var(INPUT_POST, 'nome')) {
        /**
         * Pegando os dados vindo do form
         */
        $nome = filter_input(INPUT_POST, 'nome');
        $cargaHoraria = filter_input(INPUT_POST, 'carga-horaria');
    }

    /**
     * Verifica a ação e chama os métodos respectivos
     */
    if ($action === 'list') {
        $disciplinaController->listar();
    } else if ($action === 'insert') {
        $disciplinaController->inserir(new Disciplina(NULL, $nome, $cargaHoraria));
    } else if ($action === 'update') {
        $disciplinaController->atualizar(new Disciplina(filter_input(INPUT_POST, 'id'), $nome, $cargaHoraria));
    } else if ($action === 'get') {
        $disciplinaController->selecionar(filter_input(INPUT_POST, 'id'));
    } else if ($action === 'delete') {
        $disciplinaController->remover(filter_input(INPUT_POST, 'id'));
    }
}

class DisciplinaController {

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
     * Insere disciplina no banco.
     * 
     * @param Disciplina $disciplina
     */
    public function inserir($disciplina) {
        try {
            if ($this->fachada->inserirDisciplina($disciplina)) {
                Messenger::printMessageSuccess("Disciplina: <b>{$disciplina->getNome()}</b> cadastrada com sucesso!");
            } else {
                Messenger::printMessageWarning('Não foi possível cadastrar a disciplina!<br/>Por favor, tente novamente...');
            }
        } catch (DAOException $e) {
            if ($e->getCode() === Conexao::DUPLICATE_ENTRY) {
                Messenger::printMessageWarning("Essa disciplina já possui cadastro!");
            } else {
                Messenger::printMessageDanger($e->getMessage());
            }
        }
    }

    /**
     * Seleciona Disciplina pela $id
     * 
     * @param Disciplina $id
     * @return json - Objeto Disciplina no formato json.
     */
    public function selecionar($id) {
        try {
            $disciplina = $this->fachada->selecionarDisciplina($id);

            if (!empty($disciplina)) {
                echo json_encode($disciplina);
            } else {
                echo json_encode(array("error" => Messenger::preparedMessage("Não há dados para serem exibidos...", "info")));
            }
        } catch (DAOException $e) {
            echo json_encode(array("error" => Messenger::preparedMessage("Ocorreu um erro de banco de dados ao tentar recuperar os dados!", "danger")));
        }
    }

    /**
     * Insere disciplina no banco.
     * 
     * @param Disciplina $disciplina
     */
    public function atualizar($disciplina) {
        try {
            if ($this->fachada->atualizarDisciplina($disciplina)) {
                Messenger::printMessageSuccess("Os dados da Disciplina: <b>{$disciplina->getNome()}</b> foram atualizados com sucesso!");
            } else {
                Messenger::printMessageWarning('Não foi possível atualizar os dados!<br/>Por favor, tente novamente...');
            }
        } catch (DAOException $e) {
            if ($e->getCode() === Conexao::DUPLICATE_ENTRY) {
                Messenger::printMessageWarning("Esse disciplina já está cadastrado!");
            } else {
                Messenger::printMessageDanger($e->getMessage());
            }
        }
    }

    /**
     * Remove disciplina do banco.
     * 
     * @param int $id
     */
    public function remover($id) {
        try {
            if ($this->fachada->removerDisciplina($id)) {
                Messenger::printMessageSuccess("Disciplina removida com sucesso!");
            } else {
                Messenger::printMessageWarning('Não remover a disciplina!<br/>Apenas disciplinas que não estão relacionadas com nenhuma atividade estão permitidas para remoção...');
            }
        } catch (DAOException $e) {
            if ($e->getCode() === Conexao::CONSTRAINT_FAILS) {
                Messenger::printMessageWarning("OPS! Não é possível remover a disciplina pois está relacionada com alguma atividade...");
            } else {
                Messenger::printMessageDanger($e->getMessage());
            }
        }
    }

    /**
     * selecionar disciplina pelo id
     * 
     * @param Disciplina $disciplina
     */
    public function listar() {
        try {
            $disciplinas = $this->fachada->listarDisciplinas();

            if (!empty($disciplinas)) {
                echo json_encode($disciplinas);
            } else {
                echo json_encode(array('message' => '<br/><p class="text-center">Não há dados a serem exibidos...</p>'));
            }
        } catch (DAOException $e) {
            Messenger::printMessageDanger($e->getMessage());
        }
    }

}
