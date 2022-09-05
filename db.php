<?php

require_once('config.php');

/**
 * @return PDO подключение к БД
 */
function connectToDb(): PDO
{
    try {
        $db = new PDO(DB_DRIVER.':host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
    } catch (PDOException $e) {
        print "Ошибка подключения к базе данных: " . $e->getMessage();
        die();
    }

    return $db;
}

function execute(PDO $db, string $sql, array $values): bool
{
    $preparedStatement = $db->prepare($sql);
    return $preparedStatement->execute($values);
}
