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

    /**
     * Update or Insert Query for a single unique column
     *
     * Updates an existing row based on a unique column value or inserts a new row if it doesn't exist.
     *
     * @param string $table The name of the table to update or insert into.
     * @param array $data An associative array of column-value pairs to be updated or inserted.
     * @param string $uniqueColumn The column name that makes a record unique.
     * @return void
     */
    public function updateOrInsert($table, $data, $uniqueColumn)
    {
        $uniqueValue = $data[$uniqueColumn];
        unset($data[$uniqueColumn]);

        $selectSql = "SELECT COUNT(*) as count FROM $table WHERE $uniqueColumn = :$uniqueColumn";
        $selectStatement = $this->pdo->prepare($selectSql);
        $selectStatement->execute([$uniqueColumn => $uniqueValue]);
        $result = $selectStatement->fetch(PDO::FETCH_ASSOC);

        if ($result['count'] > 0) {
            $updateColumns = [];
            foreach ($data as $column => $value) {
                $updateColumns[] = $column . ' = :' . $column;
            }
            $updateColumns = implode(', ', $updateColumns);

            $sql = "UPDATE $table SET $updateColumns WHERE $uniqueColumn = :$uniqueColumn";

            try {
                $updateStatement = $this->pdo->prepare($sql);
                $updateStatement->execute(array_merge([$uniqueColumn => $uniqueValue], $data));
            } catch (Exception $e) {
                die('Whoops, something went wrong!');
            }
        } else {
            $columns = implode(', ', array_keys($data));
            $placeholders = ':' . implode(', :', array_keys($data));

            $sql = "INSERT INTO $table ($uniqueColumn, $columns) VALUES (:$uniqueColumn, $placeholders)";

            try {
                $insertStatement = $this->pdo->prepare($sql);
                $insertStatement->execute(array_merge([$uniqueColumn => $uniqueValue], $data));
            } catch (Exception $e) {
                die('Whoops, something went wrong!');
            }
        }
    }

    /**
     * Select Query with WHERE clause
     *
     * Retrieves rows from the specified table based on the given condition.
     *
     * @param string $table The name of the table to select from.
     * @param string $condition The WHERE condition to apply.
     * @param array $params An array of parameters to bind to the prepared statement.
     * @return array An array of objects representing the selected rows.
     */
    public function selectWhere($table, $condition, $params = array())
    {
        $query = $this->pdo->prepare("SELECT * FROM {$table} WHERE {$condition}");
        $query->execute($params);
        return $query->fetchAll(PDO::FETCH_CLASS);
    }
}
