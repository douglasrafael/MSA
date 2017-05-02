<?php

include_once __DIR__ . '/DAO.php';
include_once __DIR__ . '/AtividadeDAO.php';

/**
 * Contém operações do banco de dados relacionado ao objeto do tipo Aluno.
 * Implementa a interface DAO.
 *
 * @package     model
 * @author      Douglas Rafael <douglasrafaelcg@gmail.com>
 * @version     v.0.2 (23/03/2017)
 * @copyright   Copyright (c) 2017
 */
class AlunoDAO implements DAO {

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
    public function listar($start = 0, $limit = 100, $fieldOrder = "dataCadastro", $order = "DESC") {
        $this->conn = Conexao::getInstance();
        $result = array();

        try {
            $statement = $this->conn->prepare("SELECT * FROM aluno ORDER BY {$fieldOrder} {$order} LIMIT :limit OFFSET :offset;");
            $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
            $statement->bindParam(':offset', $start, PDO::PARAM_INT);

            $statement->execute();

            $atividade = new AtividadeDAO();
            while ($aluno = $statement->fetchObject(Aluno::class)) {
                $aluno->setListaDeAtividades($atividade->listarAtividadesAluno($aluno->getMatricula()));
                array_push($result, $aluno);
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
     * @see DAO::selecionar($matricula)
     */
    public function selecionar($matricula) {
        $this->conn = Conexao::getInstance();

        try {
            $statement = $this->conn->prepare('SELECT * FROM aluno WHERE matricula=:matricula;');
            $statement->bindParam(":matricula", $matricula);
            $statement->execute();
            return $statement->fetchObject(Aluno::class);
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
     * @see DAO::inserir($aluno)
     */
    public function inserir($aluno) {
        $this->conn = Conexao::getInstance();

        try {
            $statement = $this->conn->prepare('INSERT INTO aluno (matricula, nome, dataNascimento) VALUES (:matricula, :nome, :dataNascimento);');

            $statement->bindValue(":matricula", $aluno->getMatricula());
            $statement->bindValue(":nome", $aluno->getNome());
            $statement->bindValue(":dataNascimento", $aluno->getDataNascimento());

            $statement->execute();
            if ($statement->rowCount() > 0) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            // $e->errorInfo[1] << Pega o código do erro. Útil para tratar erro de coluna UNIQUE
            throw new DAOException($e->getMessage(), $e->errorInfo[1], $e);
        } finally {
            $this->conn = null;
            $statement->closeCursor();
        }
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see DAO::remover($matricula)
     */
    public function remover($matricula) {
        $this->conn = Conexao::getInstance();

        try {
            $statement = $this->conn->prepare('DELETE FROM aluno WHERE matricula=:matricula;');
            $statement->bindParam(":matricula", $matricula);
            $statement->execute();

            if ($statement->rowCount() > 0) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            // $e->errorInfo[1] << Pega o código do erro. Útil para tratar erro de coluna CHAVE ESTRANGEIRA
            throw new DAOException($e->getMessage(), $e->errorInfo[1], $e);
        } finally {
            $this->conn = null;
            $statement->closeCursor();
        }
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see DAO::atualizar($aluno)
     */
    public function atualizar($aluno) {
        $this->conn = Conexao::getInstance();

        try {
            $statement = $this->conn->prepare('UPDATE aluno SET nome=:nome, dataNascimento=:dataNascimento WHERE matricula=:matricula');

            $statement->bindValue(":nome", $aluno->getNome());
            $statement->bindValue(":dataNascimento", $aluno->getDataNascimento());
            $statement->bindValue(":matricula", $aluno->getMatricula());

            $statement->execute();
            if ($statement->rowCount() > 0) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            // $e->errorInfo[1] << Pega o código do erro. Útil para tratar erro de coluna UNIQUE
            throw new DAOException($e->getMessage(), $e->errorInfo [1], $e);
        } finally {
            $this->conn = null;
            $statement->closeCursor();
        }
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see DAO::buscar($term, $start, $limit, $fieldOrder, $order)
     */
    public function buscar($term, $start = 0, $limit = 100, $fieldOrder = "dataCadastro", $order = "DESC") {
        $this->conn = Conexao::getInstance();
        $result = [];

        try {
            $statement = $this->conn->prepare("SELECT * FROM aluno WHERE nome LIKE :term ORDER BY {$fieldOrder} {$order} LIMIT :limit OFFSET :offset;");
            $statement->bindValue(':term', '%' . $term . '%');
            $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
            $statement->bindValue(':offset', $start, PDO::PARAM_INT);

            $statement->execute();

            $atividade = new AtividadeDAO();
            while ($aluno = $statement->fetchObject(Aluno::class)) {
                $aluno->setListaDeAtividades($atividade->listarAtividadesAluno($aluno->getMatricula()));
                array_push($result, $aluno);
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
            $result = $this->conn->query('SELECT COUNT(*) FROM aluno;');
            return (int) $result->fetchColumn(0);
        } catch (PDOException $e) {
            throw new DAOException($e->getMessage(), null, $e);
        } finally {
            $this->conn = null;
        }
    }

}
