<?php

include_once __DIR__ . '/DAO.php';
include_once __DIR__ . '/AtividadeDAO.php';

/**
 * Contém operações do banco de dados relacionado ao objeto do tipo Disciplina.
 * Implementa a interface DAO.
 *
 * @package     model
 * @author      Douglas Rafael <douglasrafaelcg@gmail.com>
 * @version     v.0.4 (24/03/2017)
 * @copyright   Copyright (c) 2017
 */
class DisciplinaDAO implements DAO {

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
            $statement = $this->conn->prepare("SELECT * FROM disciplina ORDER BY {$fieldOrder} {$order} LIMIT :limit OFFSET :offset;");
            $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
            $statement->bindParam(':offset', $start, PDO::PARAM_INT);

            $statement->execute();

            $atividade = new AtividadeDAO();
            while ($disciplina = $statement->fetchObject(Disciplina::class)) {
                $disciplina->setListaDeAtividades($atividade->listarAtividadesDisciplina($disciplina->getId()));
                array_push($result, $disciplina);
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
            $statement = $this->conn->prepare('SELECT * FROM disciplina WHERE id=:id;');
            $statement->bindParam(":id", $id);
            $statement->execute();
            return $statement->fetchObject(Disciplina::class);
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
     * @see DAO::inserir($disciplina)
     */
    public function inserir($disciplina) {
        $this->conn = Conexao::getInstance();

        try {
            $statement = $this->conn->prepare('INSERT INTO disciplina (nome, cargaHoraria) VALUES (:nome, :cargaHoraria);');

            $statement->bindValue(":nome", $disciplina->getNome());
            $statement->bindValue(":cargaHoraria", $disciplina->getCargaHoraria());

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
     * @see DAO::remover($id)
     */
    public function remover($id) {
        $this->conn = Conexao::getInstance();

        try {
            $statement = $this->conn->prepare('DELETE FROM disciplina WHERE id=:id;');
            $statement->bindParam(":id", $id);
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
     * @see DAO::atualizar($disciplina)
     */
    public function atualizar($disciplina) {
        $this->conn = Conexao::getInstance();

        try {
            $statement = $this->conn->prepare('UPDATE disciplina SET nome=:nome, cargaHoraria=:cargaHoraria WHERE id=:id');

            $statement->bindValue(":nome", $disciplina->getNome());
            $statement->bindValue(":cargaHoraria", $disciplina->getCargaHoraria());
            $statement->bindValue(":id", $disciplina->getId());

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
    public function buscar($term, $start = 0, $limit = 100, $fieldOrder = "id", $order = "DESC") {
        $this->conn = Conexao::getInstance();
        $result = [];

        try {
            $statement = $this->conn->prepare("SELECT * FROM disciplina WHERE nome LIKE :term ORDER BY {$fieldOrder} {$order} LIMIT :limit OFFSET :offset;");
            $statement->bindValue(':term', '%' . $term . '%');
            $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
            $statement->bindValue(':offset', $start, PDO::PARAM_INT);

            $statement->execute();

            $atividade = new AtividadeDAO();
            while ($disciplina = $statement->fetchObject(Disciplina::class)) {
                $disciplina->setListaDeAtividades($atividade->listarAtividadesDisciplina($disciplina->getId()));
                array_push($result, $disciplina);
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
            $result = $this->conn->query('SELECT COUNT(*) FROM disciplina;');
            return (int) $result->fetchColumn(0);
        } catch (PDOException $e) {
            throw new DAOException($e->getMessage(), null, $e);
        } finally {
            $this->conn = null;
        }
    }

}
