<?php

if (!isset($token)) {
    header('HTTP/1.1 403 Forbidden');
}

require_once('config.php');

$query = isset($_POST['query']) ? $_POST['query'] : null;

/**
 * @param string $query поисковый запрос
 * @return array заголовок поста и строка комментария, содержащая запрос
 */
function searchPosts(string $query): array
{
    /*
    |-----------------------------------------------------------------------
    | Подключаемся к базе данных
    |-----------------------------------------------------------------------
    */

    try {
        $db = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
    } catch (PDOException $e) {
        print "Ошибка подключения к базе данных: " . $e->getMessage() . PHP_EOL;
        die();
    }

    /*--------------------------------------------------------------------*/

    $values = [
        'query' => '%'.$query.'%',
    ];
    $comments = 'SELECT a.title, b.body FROM posts a, comments b WHERE b.body LIKE :query AND b.post_id = a.id';
    $preparedStatement = $db->prepare($comments);
    $res = $preparedStatement->execute($values);

    if ($res !== false) {
        $posts = $preparedStatement->fetchAll();
        foreach ($posts as &$post) {
            $lines = explode("\n", $post['body']);
            $newLines = [];
            foreach ($lines as $line) {
                if (str_contains($line, $query)) {
                    $newLines[] = $line;
                }
            }
            $post['body'] = implode("\n", $newLines);
        }

        return $posts;
    }

    return [];
}