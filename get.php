<?php

if (!isset($token)) {
    die('Nothing happend...');
}

require_once('config.php');

/**
 * @param string $url адрес, который вернет json
 * @return array ассоциативный массив
 */
function getJson(string $url): array
{
    $json = file_get_contents($url);
    $json = json_decode($json, true);
    return $json;
}

/**
 * @param PDO $db подключение к БД
 * @param array $data данные поста, которые нужно сохранить
 * @return bool удалось ли выполнить запрос к БД
 */
function insertPost(PDO $db, array $data): bool
{
    $values = [
        'id'        => $data['id'],
        'user_id'   => $data['userId'],
        'title'     => $data['title'],
        'body'      => $data['body'],
    ];
    $posts = 'INSERT INTO posts (id, user_id, title, body) VALUES (:id, :user_id, :title, :body)';
    $preparedStatement = $db->prepare($posts);
    $res = $preparedStatement->execute($values);

    return $res;
}

/**
 * @param PDO $db подключение к БД
 * @param array $data данные комментария, которые нужно сохранить
 * @return bool удалилось ли выполнить запрос к БД
 */
function insertComment(PDO $db, array $data): bool
{
    $values = [
        'id'        => $data['id'],
        'post_id'   => $data['postId'],
        'name'      => $data['name'],
        'email'     => $data['email'],
        'body'      => $data['body'],
    ];
    $comments = 'INSERT INTO comments (id, post_id, name, email, body) VALUES (:id, :post_id, :name, :email, :body)';
    $preparedStatement = $db->prepare($comments);
    $res = $preparedStatement->execute($values);

    return $res;
}

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

/*
|-----------------------------------------------------------------------
| Добавляем посты
|-----------------------------------------------------------------------
*/

$posts = getJson(POSTS_URL);
$counterPosts = 0;
foreach ($posts as $post) {
    if (insertPost($db, $post)) {
        $counterPosts++;
    };
}

/*
|-----------------------------------------------------------------------
| Добавляем комментарии
|-----------------------------------------------------------------------
*/

$comments = getJson(COMMENTS_URL);
$counterComments = 0;
foreach ($comments as $comment) {
    if (insertComment($db, $comment)) {
        $counterComments++;
    };
}

/*
|-----------------------------------------------------------------------
| Сообщение о результате
|-----------------------------------------------------------------------
*/

echo "Загружено {$counterPosts} записей и {$counterComments} комментариев";
