<?php

abstract class Model
{
    public function fetch($sql, $attributes = [])
    {
        $query = $this->connexion();
    }

    public function fetchAll($sql, $attributes = [])
    {
        $query = $this->connexion();
    }

    protected function connexion()
    {
        // CONFIGURER PDO
        $charset = 'utf8mb4';
        $dsn = 'mysql:host=' . MYSQL_HOST . ';dbname=' . MYSQL_DBNAME . ';charset=' . $charset;

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $pdo = new PDO($dsn, MYSQL_USER, MYSQL_PASSWORD, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }

        return $pdo;
    }
}