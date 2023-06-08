<?php

class QueryBuilder
{
    /**
     * @var PDO $pdo
     */
    protected $pdo;

    /**
     * QueryBuilder constructor.
     *
     * @param PDO $pdo The PDO database connection object.
     */
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Select All Query
     *
     * Retrieves all rows from the specified table.
     *
     * @param string $table The name of the table to select from.
     * @return array An array of objects representing the selected rows.
     */
    public function selectAll($table)
    {
        $query = $this->pdo->prepare("SELECT * FROM {$table}");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_CLASS);
    }

    /**
     * Insert Query
     *
     * Inserts a new row into the specified table.
     *
     * @param string $table The name of the table to insert into.
     * @param array $parameters An associative array of column-value pairs to be inserted.
     * @return void
     */
    public function insert($table, $parameters)
    {
        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $table,
            implode(', ', array_keys($parameters)),
            ':' . implode(', :', array_keys($parameters)),
        );

        try {
            $statement = $this->pdo->prepare($sql);
            $statement->execute($parameters);
        } catch (Exception $e) {
            die('Whoops, something wents wrong!');
        }
    }

}
