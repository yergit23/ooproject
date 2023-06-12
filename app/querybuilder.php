<?php
namespace App;
use Aura\SqlQuery\QueryFactory;
use PDO;

class QueryBuilder
{
    private $pdo;
    private $queryFactory;

    public function __construct()
    {
        $this->pdo = new PDO("mysql:host=localhost;dbname=ooproject;charset=utf8","root","");
        $this->queryFactory = new QueryFactory('mysql');
    }

    public function getAll($table)
    {
        $select = $this->queryFactory->newSelect();

        $select->cols(['*'])
            ->from($table);

        // prepare the statement
        $sth = $this->pdo->prepare($select->getStatement());

        // bind the values and execute
        $sth->execute($select->getBindValues());

        // get the result back as an associative array
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function getUser($table, $id)
    {
        $select = $this->queryFactory->newSelect();

        $select->cols([
            'id',
            'email',
            'username',
            'job',
            'phone',
            'address',
            'img',
            'user_status',
            'vk',
            'tgm',
            'inst'
            ])
            ->from($table)
            ->where('id = :id')
            ->bindValue('id', $id);

        // prepare the statement
        $sth = $this->pdo->prepare($select->getStatement());

        // bind the values and execute
        $sth->execute($select->getBindValues());

        // get the result back as an associative array
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function getUserEmail($table, $email)
    {
        $select = $this->queryFactory->newSelect();

        $select->cols([
            'id',
            'email',
            'username',
            'job',
            'phone',
            'address',
            'img',
            'vk',
            'tgm',
            'inst'
            ])
            ->from($table)
            ->where('email = :email')
            ->bindValue('email', $email);

        // prepare the statement
        $sth = $this->pdo->prepare($select->getStatement());

        // bind the values and execute
        $sth->execute($select->getBindValues());

        // get the result back as an associative array
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function update($data, $id, $table)
    {
        $update = $this->queryFactory->newUpdate();

        $update
            ->table($table)
            ->cols($data)
            ->where('id = :id')
            ->bindValue('id', $id);

        // prepare the statement
        $sth = $this->pdo->prepare($update->getStatement());

        // execute with bound values
        $sth->execute($update->getBindValues());
    }

    public function insert($data, $table) {
		$insert = $this->queryFactory->newInsert();

		$insert
    		->into($table)                   // INTO this table
    		->cols($data);

    	$sth = $this->pdo->prepare($insert->getStatement());

    	$sth->execute($insert->getBindValues());

	}
    
}





?>