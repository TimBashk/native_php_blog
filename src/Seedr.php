<?php
require_once 'Database.php';

$db = Database::getInstance();

echo "Seeding started...\n";

/**
 * Очистка таблиц
 */
$db->exec("SET FOREIGN_KEY_CHECKS = 0");
$db->exec("TRUNCATE TABLE post_categories");
$db->exec("TRUNCATE TABLE posts");
$db->exec("TRUNCATE TABLE categories");
$db->exec("SET FOREIGN_KEY_CHECKS = 1");

/**
 * Данные
 */
$categoryNames = [
    'Технологии',
    'Бизнес',
    'Путешествия',
    'Наука',
    'Образование',
    'Дизайн',
    'Маркетинг',
    'Стартапы',
    'Разработка',
    'Искусственный интеллект'
];

$postTitles = [
    'Как мы пришли к этому решению',
    '10 ошибок, которые совершают все',
    'Почему это работает',
    'Разбор реального кейса',
    'Что нужно знать в 2025 году',
    'Практическое руководство',
    'Будущее индустрии',
    'Лучшие инструменты для работы',
    'Как ускорить разработку',
    'Главные тренды года'
];

$db->beginTransaction();

/**
 * Вставка категорий
 */
$categoryStmt = $db->prepare(
    "INSERT INTO categories (name, description) VALUES (:name, :description)"
);

$postStmt = $db->prepare(
    "INSERT INTO posts (title, description, content, image, views, created_at)
     VALUES (:title, :description, :content, :image, :views, :created_at)"
);

$linkStmt = $db->prepare(
    "INSERT INTO post_categories (post_id, category_id) VALUES (?, ?)"
);

foreach ($categoryNames as $categoryIndex => $categoryName) {

    $categoryStmt->execute([
        ':name' => $categoryName,
        ':description' => "Статьи на тему «{$categoryName}»"
    ]);

    $categoryId = $db->lastInsertId();

    echo "Category: {$categoryName}\n";

    for ($i = 1; $i <= 10; $i++) {

        $title = $postTitles[array_rand($postTitles)] . " #{$i}";
        $views = random_int(0, 5000);

        $postStmt->execute([
            ':title' => $title,
            ':description' => 'Краткое описание статьи для превью.',
            ':content' => str_repeat(
                "<p>Это пример текста статьи. Он нужен для демонстрации контента.</p>",
                random_int(5, 12)
            ),
            ':image' => 'placeholder.jpg',
            ':views' => $views,
            ':created_at' => date(
                'Y-m-d H:i:s',
                strtotime("-" . random_int(0, 180) . " days")
            )
        ]);

        $postId = $db->lastInsertId();

        $linkStmt->execute([$postId, $categoryId]);
    }
}

$db->commit();

echo "Seeding finished successfully.\n";
