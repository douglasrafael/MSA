<?php

include_once __DIR__ . '/DAO.php';

/**
 * Contém operações do banco de dados relacionado ao objeto do tipo Documento.
 * Implementa a interface DAO.
 *
 * @package     model
 * @author      Douglas Rafael <douglasrafaelcg@gmail.com>
 * @version     v.0.2 (23/03/2017)
 * @copyright   Copyright (c) 2017
 */
class DocumentoDAO implements DAO {

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
            $statement = $this->conn->prepare("SELECT * FROM documento ORDER BY {$fieldOrder} {$order} LIMIT :limit OFFSET :offset;");
            $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
            $statement->bindParam(':offset', $start, PDO::PARAM_INT);

            $statement->execute();

            while ($documento = $statement->fetchObject(Documento::class)) {
                array_push($result, $documento);
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
            $statement = $this->conn->prepare('SELECT * FROM documento WHERE id=:id;');
            $statement->bindParam(":id", $id);
            $statement->execute();
            return $statement->fetchObject(Documento::class);
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
     * @see DAO::inserir($documento)
     */
    public function inserir($documento) {
        $this->conn = Conexao::getInstance();

        try {
            $statement = $this->conn->prepare('INSERT INTO documento (titulo, endereco, atividade_id) VALUES (:titulo, :endereco, :atividade_id);');

            $statement->bindValue(":titulo", $documento->getTitulo());
            $statement->bindValue(":endereco", $documento->getEndereco());
            $statement->bindValue(":atividade_id", $documento->getAtividade()->getId());

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
            $statement = $this->conn->prepare('DELETE FROM documento WHERE id=:id');
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
     * Remove todos os documentos que estão relacionados com a atividade
     * 
     * @param type $atividade_id
     * @return boolean
     * @throws DAOException - Lançada quando ocorrer erro de banco de dados
     */
    public function removerDocumentos($atividade_id) {
        $this->conn = Conexao::getInstance();

        try {
            $statement = $this->conn->prepare('DELETE FROM documento WHERE atividade_id=:atividade_id');
            $statement->bindParam(":atividade_id", $atividade_id);
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
     * @see DAO::atualizar($documento)
     */
    public function atualizar($documento) {
        $this->conn = Conexao::getInstance();

        try {
            $statement = $this->conn->prepare('UPDATE documento SET titulo=:titulo, endereco=:endereco WHERE id=:id');

            $statement->bindValue(":titulo", $documento->getTitulo());
            $statement->bindValue(":endereco", $documento->getEndereco());
            $statement->bindValue(":id", $documento->getId());

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
            $statement = $this->conn->prepare("SELECT * FROM documento WHERE nome LIKE :term ORDER BY {$fieldOrder} {$order} LIMIT :limit OFFSET :offset;");
            $statement->bindValue(':term', '%' . $term . '%');
            $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
            $statement->bindValue(':offset', $start, PDO::PARAM_INT);

            $statement->execute();

            while ($documento = $statement->fetchObject(Documento::class)) {
                array_push($result, $documento);
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
            $result = $this->conn->query('SELECT COUNT(*) FROM documento;');
            return (int) $result->fetchColumn(0);
        } catch (PDOException $e) {
            throw new DAOException($e->getMessage(), null, $e);
        } finally {
            $this->conn = null;
        }
    }

    /**
     * Lista todos os documentos que estão relacionadas com à ativiadde de acordo com o seu id.
     * 
     * @param int $id - Id da atividade
     * @return array - Array contendo os documentos
     * @throws DAOException - Lançada quando ocorrer erro de banco de dados
     */
    public function listarDocumentosAtividade($id) {
        $this->conn = Conexao::getInstance();
        $result = array();

        try {
            $statement = $this->conn->prepare("SELECT * FROM documento WHERE atividade_id=:atividade_id");
            $statement->bindParam(':atividade_id', $id);

            $statement->execute();

            while ($documento = $statement->fetchObject(Documento::class)) {
                array_push($result, $documento);
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
