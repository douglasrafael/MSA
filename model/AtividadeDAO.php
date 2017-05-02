<?php

include_once __DIR__ . '/DAO.php';
include_once __DIR__ . '/DisciplinaDAO.php';
include_once __DIR__ . '/AlunoDAO.php';
include_once __DIR__ . '/DocumentoDAO.php';

/**
 * Contém operações do banco de dados relacionado ao objeto do tipo Atividade.
 * Implementa a interface DAO.
 *
 * @package     model
 * @author      Douglas Rafael <douglasrafaelcg@gmail.com>
 * @version     v.0.2 (23/03/2017)
 * @copyright   Copyright (c) 2017
 */
class AtividadeDAO implements DAO {

    /**
     * Instância da conexão com o banco de dados.
     * 
     * @var PDO 
     */
    private $conn;

    /**
     * Construtor
     *
     * @access public
     */
    public function __construct() {
        
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see DAO::listar($start, $limit, $fieldOrder, $order)
     */
    public function listar($start = 0, $limit = 100, $fieldOrder = "id", $order = "DESC") {
        $this->conn = Conexao::getInstance();
        $result = array();

        try {
            $statement = $this->conn->prepare("SELECT * FROM atividade ORDER BY {$fieldOrder} {$order} LIMIT :limit OFFSET :offset;");
            $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
            $statement->bindParam(':offset', $start, PDO::PARAM_INT);

            $statement->execute();

            $aluno = new AlunoDAO();
            $disciplina = new DisciplinaDAO();
            $documento = new DocumentoDAO();
            while ($atividade = $statement->fetchObject(Atividade::class)) {
                $atividade->setAluno($aluno->selecionar($atividade->aluno_matricula));
                $atividade->setDisciplina($disciplina->selecionar($atividade->disciplina_id));
                $atividade->setListaDeDocumentos($documento->listarDocumentosAtividade($atividade->getId()));

                array_push($result, $atividade);
            }
        } catch (PDOException $e) {
            throw new DAOException($e->getMessage(), null, $e);
        } finally {
            $this->conn = null;
            $statement->closeCursor();
        }
        return $result;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see DAO::selecionar($id)
     */
    public function selecionar($id) {
        $this->conn = Conexao::getInstance();

        try {
            $statement = $this->conn->prepare('SELECT * FROM atividade WHERE id=:id;');
            $statement->bindParam(":id", $id);
            $statement->execute();
            $atividade = $statement->fetchObject(Atividade::class);

            if (!empty($atividade)) {
                $aluno = new AlunoDAO();
                $disciplina = new DisciplinaDAO();
                $documento = new DocumentoDAO();
                $atividade->setAluno($aluno->selecionar($atividade->aluno_matricula));
                $atividade->setDisciplina($disciplina->selecionar($atividade->disciplina_id));
                $atividade->setListaDeDocumentos($documento->listarDocumentosAtividade($atividade->getId()));
            }
            return $atividade;
        } catch (PDOException $e) {
            throw new DAOException($e->getMessage(), null, $e);
        } finally {
            $this->conn = null;
            $statement->closeCursor();
        }
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see DAO::inserir($atividade)
     */
    public function inserir($atividade) {
        $this->conn = Conexao::getInstance();

        try {
            $this->conn->beginTransaction();

            $statementAtividade = $this->conn->prepare('INSERT INTO atividade (titulo, descricao, dataEntrega, aluno_matricula, disciplina_id) VALUES (:titulo, :descricao, :dataEntrega, :aluno_matricula, :disciplina_id);');

            $statementAtividade->bindValue(":titulo", $atividade->getTitulo());
            $statementAtividade->bindValue(":descricao", $atividade->getDescricao());
            $statementAtividade->bindValue(":dataEntrega", $atividade->getDataEntrega());
            $statementAtividade->bindValue(":aluno_matricula", $atividade->getAluno()->getMatricula());
            $statementAtividade->bindValue(":disciplina_id", $atividade->getDisciplina()->getId());

            // Insere atividade
            $statementAtividade->execute();
            $idAtividade = $this->conn->lastInsertId();


            // Insere documentos
            if (!empty($atividade->getListaDeDocumentos())) {
                foreach ($atividade->getListaDeDocumentos() as $documento) {
                    $statementDoc = $this->conn->prepare('INSERT INTO documento (titulo, endereco, atividade_id) VALUES (:titulo, :endereco, :atividade_id);');

                    $statementDoc->bindValue(":titulo", $documento->getTitulo());
                    $statementDoc->bindValue(":endereco", $documento->getEndereco());
                    $statementDoc->bindValue(":atividade_id", $idAtividade);

                    $statementDoc->execute();
                }
            }

            if ($statementAtividade->rowCount() > 0) {
                $this->conn->commit(); // Tudo ok. Salva os dados no banco

                return true;
            }
            return false;
        } catch (PDOException $e) {
            $this->conn->rollBack(); // Houve errro em algum insert
            // $e->errorInfo[1] << Pega o código do erro. Útil para tratar erro de coluna UNIQUE
            throw new DAOException($e->getMessage(), $e->errorInfo[1], $e);
        } finally {
            $this->conn = null;
            $statementAtividade->closeCursor();
            if (isset($statementDoc)) {
                $statementDoc->closeCursor();
            }
        }
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see DAO::remover($id)
     */
    public function remover($id) {
        $this->conn = Conexao::getInstance();

        try {
            $statement = $this->conn->prepare('DELETE FROM atividade WHERE id=:id;');
            $statement->bindParam(":id", $id);
            $statement->execute();

            if ($statement->rowCount() > 0) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            throw new DAOException($e->getMessage(), null, $e);
        } finally {
            $this->conn = null;
            $statement->closeCursor();
        }
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see DAO::atualizar($atividade)
     */
    public function atualizar($atividade) {
        $this->conn = Conexao::getInstance();

        try {
            $this->conn->beginTransaction();

            $statementAtividade = $this->conn->prepare('UPDATE atividade SET titulo=:titulo, descricao=:descricao, dataEntrega=:dataEntrega, aluno_matricula=:aluno_matricula, disciplina_id=:disciplina_id WHERE id=:id;');
            $statementAtividade->bindValue(":titulo", $atividade->getTitulo());
            $statementAtividade->bindValue(":descricao", $atividade->getDescricao());
            $statementAtividade->bindValue(":dataEntrega", $atividade->getDataEntrega());
            $statementAtividade->bindValue(":aluno_matricula", $atividade->getAluno()->getMatricula());
            $statementAtividade->bindValue(":disciplina_id", $atividade->getDisciplina()->getId());
            $statementAtividade->bindValue(":id", $atividade->getId());

            // Insere atividade
            $statementAtividade->execute();

            // Insere documentos
            if (!empty($atividade->getListaDeDocumentos())) {
                foreach ($atividade->getListaDeDocumentos() as $documento) {
                    $statementDoc = $this->conn->prepare('INSERT INTO documento (titulo, endereco, atividade_id) VALUES (:titulo, :endereco, :atividade_id);');

                    $statementDoc->bindValue(":titulo", $documento->getTitulo());
                    $statementDoc->bindValue(":endereco", $documento->getEndereco());
                    $statementDoc->bindValue(":atividade_id", $atividade->getId());

                    $statementDoc->execute();
                }
            }

            if ($statementAtividade->rowCount() > 0 || isset($statementDoc)) {
                $this->conn->commit(); // Tudo ok. Salva os dados no banco

                return true;
            }
            return false;
        } catch (PDOException $e) {
            $this->conn->rollBack(); // Houve errro em algum insert
            // $e->errorInfo[1] << Pega o código do erro. Útil para tratar erro de coluna UNIQUE
            throw new DAOException($e->getMessage(), $e->errorInfo [1], $e);
        } finally {
            $this->conn = null;
            $statementAtividade->closeCursor();
            if (isset($statementDoc)) {
                $statementDoc->closeCursor();
            }
        }
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see DAO::buscar($term, $start, $limit, $fieldOrder, $order)
     */
    public function buscar($term, $start = 0, $limit = 100, $fieldOrder = "id", $order = "DESC") {
        $this->conn = Conexao::getInstance();
        $result = [];

        try {
            $statement = $this->conn->prepare("SELECT atividade.id, atividade.titulo, atividade.descricao, atividade.dataEntrega, atividade.dataCadastro, 
                atividade.aluno_matricula, atividade.disciplina_id, aluno.nome AS nomeAluno, disciplina.nome AS nomeDisciplina 
                FROM msa.atividade INNER JOIN aluno ON aluno.matricula = atividade.aluno_matricula INNER JOIN disciplina ON disciplina.id = atividade.disciplina_id
                WHERE atividade.titulo LIKE :term OR aluno.nome LIKE :term OR disciplina.nome LIKE :term ORDER BY {$fieldOrder} {$order} LIMIT :limit OFFSET :offset;");

            $statement->bindValue(':term', '%' . $term . '%');
            $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
            $statement->bindValue(':offset', $start, PDO::PARAM_INT);

            $statement->execute();

            $documento = new DocumentoDAO();
            while ($atividade = $statement->fetchObject(Atividade::class)) {
                $atividade->setAluno(new Aluno($atividade->aluno_matricula, $atividade->nomeAluno));
                $atividade->setDisciplina(new Disciplina($atividade->disciplina_id, $atividade->nomeDisciplina));
                $atividade->setListaDeDocumentos($documento->listarDocumentosAtividade($atividade->getId()));

                array_push($result, $atividade);
            }
        } catch (PDOException $e) {
            throw new DAOException($e->getMessage(), null, $e);
        } finally {
            $this->conn = Conexao::getInstance();
            $statement->closeCursor();
        }
        return $result;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see DAO::total()
     */
    public function total() {
        $this->conn = Conexao::getInstance();

        try {
            $result = $this->conn->query('SELECT COUNT(*) FROM atividade;');
            return (int) $result->fetchColumn(0);
        } catch (PDOException $e) {
            throw new DAOException($e->getMessage(), null, $e);
        } finally {
            $this->conn = null;
        }
    }

    /**
     * Lista todas as atividades que estão relacionadas ao aluno de acordo com a matricula.
     * 
     * @param int $matricula - Matricula do aluno
     * @return array - Array contendo as atividades
     * @throws DAOException - Lançada quando ocorrer erro de banco de dados
     */
    public function listarAtividadesAluno($matricula) {
        $this->conn = Conexao::getInstance();
        $result = array();

        try {
            $statement = $this->conn->prepare("SELECT * FROM atividade WHERE aluno_matricula=:matricula");
            $statement->bindParam(':matricula', $matricula);

            $statement->execute();

            $disciplina = new DisciplinaDAO();
            while ($atividade = $statement->fetchObject(Atividade::class)) {
                $atividade->setDisciplina($disciplina->selecionar($atividade->disciplina_id));
                array_push($result, $atividade);
            }
        } catch (PDOException $e) {
            throw new DAOException($e->getMessage(), null, $e);
        } finally {
            $this->conn = null;
            $statement->closeCursor();
        }
        return $result;
    }

    /**
     * Lista todas as atividades que estão relacionadas a disciplina de acordo com o seu id.
     * 
     * @param int $id - Id da disciplina
     * @return array - Array contendo as atividades
     * @throws DAOException - Lançada quando ocorrer erro de banco de dados
     */
    public function listarAtividadesDisciplina($id) {
        $this->conn = Conexao::getInstance();
        $result = array();

        try {
            $statement = $this->conn->prepare("SELECT * FROM atividade WHERE disciplina_id=:disciplina_id");
            $statement->bindParam(':disciplina_id', $id);

            $statement->execute();

            $disciplina = new DisciplinaDAO();
            $aluno = new AlunoDAO();
            while ($atividade = $statement->fetchObject(Atividade::class)) {
                $atividade->setDisciplina($disciplina->selecionar($atividade->disciplina_id));
                $atividade->setAluno($aluno->selecionar($atividade->aluno_matricula));
                array_push($result, $atividade);
            }
        } catch (PDOException $e) {
            throw new DAOException($e->getMessage(), null, $e);
        } finally {
            $this->conn = null;
            $statement->closeCursor();
        }
        return $result;
    }

}
