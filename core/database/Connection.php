<?php

class Connection
{
    /**
     * Database Connection Maker
     *
     * This static function is responsible for creating a database connection using the provided configuration.
     *
     * @param array $config The configuration array containing connection details.
     * @return PDO The created PDO database connection object.
     */
    public static function make($config)
    {
        try {
            return new PDO(
                $config['connection'] . ';dbname=' . $config['name'],
                $config['username'],
                $config['password'],
                $config['options']
            );
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
}
