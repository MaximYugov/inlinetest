<?php

if (!isset($token)) {
    header('HTTP/1.1 403 Forbidden');
}

require_once('config.php');
require_once('db.php');

/**
 * @param string $query поисковый запрос
 * @return array заголовок поста и строка комментария, содержащая запрос
 */
function searchPosts(string $query): array
{
    $db = connectToDb();

    $values = [
        'query' => '%'.$query.'%',
    ];
    $comments = 'SELECT a.title, b.body FROM posts a, comments b WHERE b.body LIKE :query AND b.post_id = a.id';
    $preparedStatement = $db->prepare($comments); //TODO
    $res = $preparedStatement->execute($values); //TODO

    if ($res !== false) {
        $posts = $preparedStatement->fetchAll(); //TODO
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
