<?php

require_once('find.php');

$token = 'my_secret_key';

$query = isset($_POST['query']) ? $_POST['query'] : null;
$posts = [];
$search = false;

if (empty($query)) {
    $msg = 'Введите поисковый запрос.';
} else {
    if (strlen($query) < 3) {
        $msg = 'Поисковый запрос должен содержать минимум 3 символа';
    } else {
        $posts = searchPosts($query);
        $search = true;
        if (empty($posts)) {
            $msg = 'По вашему запросу ничего не найдено.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Поиск</title>
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <div class="wrapper">
        <form action="search.php" method="POST">
            <div class="search">
                <input type="text" name="query" value="<?=$query;?>">
                <button>Найти</button>
            </div>
        </form>
        <div class="results">
            <div class="title">
                <h2>Результаты поиска <?= $search ? 'по запросу: <b>'.$query.'</b>' : '' ?></h2>
            </div>
            <?php
                if ($posts) {
                    foreach ($posts as $post) {
            ?>
            <div class="search-result">
                
                <h3><?=$post['title']?></h3>
                <p><?php echo nl2br(str_replace($query, '<b>'.$query.'</b>', $post['body'])); ?></p>
            </div>
            <?php
                    }
                } else {
            ?>
            <div class="empty">
                <p><?=$msg?></p>
            </div>
            <?php
                }
            ?>
        </div>
    </div>
</body>
</html>