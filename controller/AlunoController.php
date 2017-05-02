<?php

include_once __DIR__ . '/Fachada.php';

$alunoController = new AlunoController(new Fachada());

if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST') {
    $action = filter_input(INPUT_POST, 'action');

    // Verificando se há dados vindo do form
    if (filter_has_var(INPUT_POST, 'nome')) {
        /**
         * Pegando os dados vindo do form
         */
        $matricula = filter_input(INPUT_POST, 'matricula');
        $nome = filter_input(INPUT_POST, 'nome');
        // Converte padrão pt-br para formato do banco
        $dataNascimento = MyDate::dateToDB(filter_input(INPUT_POST, 'data-nascimento'));
    }

    /**
     * Verifica a ação e chama os métodos respectivos
     */
    if ($action === 'list') {
        $alunoController->listar();
    } else if ($action === 'insert') {
        $alunoController->inserir(new Aluno($matricula, $nome, $dataNascimento));
    } else if ($action === 'update') {
        $matricula = filter_input(INPUT_POST, 'mat');
        $alunoController->atualizar(new Aluno($matricula, $nome, $dataNascimento));
    } else if ($action === 'get') {
        $alunoController->selecionar(filter_input(INPUT_POST, 'matricula'));
    } else if ($action === 'delete') {
        $alunoController->remover(filter_input(INPUT_POST, 'matricula'));
    }
}

class AlunoController {

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
     * Insere aluno no banco.
     * 
     * @param Aluno $aluno
     */
    public function inserir($aluno) {
        try {
            if ($this->fachada->inserirAluno($aluno)) {
                Messenger::printMessageSuccess("Aluno(a): <b>{$aluno->getNome()}</b> cadastrado com sucesso!");
            } else {
                Messenger::printMessageWarning('Não foi possível cadastrar o aluno(a)!<br/>Por favor, tente novamente...');
            }
        } catch (DAOException $e) {
            if ($e->getCode() === Conexao::DUPLICATE_ENTRY) {
                Messenger::printMessageWarning("Esse aluno já está cadastrado!");
            } else {
                Messenger::printMessageDanger($e->getMessage());
            }
        }
    }

    /**
     * Seleciona Aluno pela $matricula
     * 
     * @param Aluno $matricula
     * @return json - Objeto Aluno no formato json.
     */
    public function selecionar($matricula) {
        try {
            $aluno = $this->fachada->selecionarAluno($matricula);

            if (!empty($aluno)) {
                echo json_encode($aluno);
            } else {
                echo json_encode(array("error" => Messenger::preparedMessage("Não há dados para serem exibidos...", "info")));
            }
        } catch (DAOException $e) {
            echo json_encode(array("error" => Messenger::preparedMessage("Ocorreu um erro de banco de dados ao tentar recuperar os dados!", "danger")));
        }
    }

    /**
     * Insere aluno no banco.
     * 
     * @param Aluno $aluno
     */
    public function atualizar($aluno) {
        try {
            if ($this->fachada->atualizarAluno($aluno)) {
                Messenger::printMessageSuccess("Os dados do Aluno(a): <b>{$aluno->getNome()}</b> foram atualizados com sucesso!");
            } else {
                Messenger::printMessageWarning('Não foi possível atualizar os dados!<br/>Por favor, tente novamente...');
            }
        } catch (DAOException $e) {
            if ($e->getCode() === Conexao::DUPLICATE_ENTRY) {
                Messenger::printMessageWarning("Esse aluno já está cadastrado!");
            } else {
                Messenger::printMessageDanger($e->getMessage());
            }
        }
    }

    /**
     * Remove aluno do banco.
     * 
     * @param int $matricula
     */
    public function remover($matricula) {
        try {
            if ($this->fachada->removerAluno($matricula)) {
                Messenger::printMessageSuccess("Aluno removido com sucesso!");
            } else {
                Messenger::printMessageWarning('Não remover o aluno!<br/>Apenas alunos que não estão relacionados com nenhuma atividade estão permitidos para remoção...');
            }
        } catch (DAOException $e) {
            if ($e->getCode() === Conexao::CONSTRAINT_FAILS) {
                Messenger::printMessageWarning("OPS! Não é possível remover o aluno pois está relacionado com alguma atividade...");
            } else {
                Messenger::printMessageDanger($e->getMessage());
            }
        }
    }

    /**
     * selecionar aluno pelo id
     * 
     * @param Aluno $aluno
     */
    public function listar() {
        try {
            $alunos = $this->fachada->listarAlunos();

            if (!empty($alunos)) {
                echo json_encode($alunos);
            } else {
                echo json_encode(array('message' => '<br/><p class="text-center">Não há dados a serem exibidos...</p>'));
            }
        } catch (DAOException $e) {
            Messenger::printMessageDanger($e->getMessage());
        }
    }

}
