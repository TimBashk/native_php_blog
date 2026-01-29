<?php

namespace App\DataProviders;

use App\DB\Database;
use PDO;
use PDOStatement;

class BlogSeeder
{
    private PDO $db;

    private array $categoryNames = [
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

    private array $postTitles = [
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

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function run(): void
    {
        echo "Запись данных старт...\n";
        $this->createSchema();
        $this->truncateTables();
        $this->seedData();

        echo "Запись данных окончена удачно.\n";
    }

    private function createSchema(): void
    {
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS categories (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255),
                description TEXT
            )
        ");

        $this->db->exec("
            CREATE TABLE IF NOT EXISTS posts (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(255),
                description TEXT,
                content TEXT,
                image VARCHAR(255),
                views INT DEFAULT 0,
                created_at DATETIME
            )
        ");

        $this->db->exec("
            CREATE TABLE IF NOT EXISTS post_categories (
                post_id INT,
                category_id INT,
                PRIMARY KEY (post_id, category_id)
            )
        ");
    }


    private function truncateTables(): void
    {
        $this->db->exec("SET FOREIGN_KEY_CHECKS = 0");
        $this->db->exec("TRUNCATE TABLE post_categories");
        $this->db->exec("TRUNCATE TABLE posts");
        $this->db->exec("TRUNCATE TABLE categories");
        $this->db->exec("SET FOREIGN_KEY_CHECKS = 1");
    }

    private function seedData(): void
    {
        $this->db->beginTransaction();

        $categoryStmt = $this->db->prepare(
            "INSERT INTO categories (name, description)
             VALUES (:name, :description)"
        );

        $postStmt = $this->db->prepare(
            "INSERT INTO posts (title, description, content, image, views, created_at)
             VALUES (:title, :description, :content, :image, :views, :created_at)"
        );

        $linkStmt = $this->db->prepare(
            "INSERT INTO post_categories (post_id, category_id)
             VALUES (?, ?)"
        );

        foreach ($this->categoryNames as $categoryName) {

            $categoryStmt->execute([
                ':name' => $categoryName,
                ':description' => "Статьи на тему «{$categoryName}»"
            ]);

            $categoryId = $this->db->lastInsertId();

            echo "Category: {$categoryName}\n";

            $this->seedPostsForCategory($categoryId, $postStmt, $linkStmt);
        }

        $this->db->commit();
    }

    private function seedPostsForCategory(
        int $categoryId,
        PDOStatement $postStmt,
        PDOStatement $linkStmt
    ): void {
        for ($i = 1; $i <= 10; $i++) {

            $title = $this->postTitles[array_rand($this->postTitles)] . " #{$i}";

            $postStmt->execute([
                ':title' => $title,
                ':description' => 'Краткое описание статьи для превью.',
                ':content' => str_repeat(
                    "<p>Это пример текста статьи. Он нужен для демонстрации контента.</p>",
                    random_int(5, 12)
                ),
                ':image' => 'placeholder.jpg',
                ':views' => random_int(0, 5000),
                ':created_at' => date(
                    'Y-m-d H:i:s',
                    strtotime('-' . random_int(0, 180) . ' days')
                )
            ]);

            $postId = $this->db->lastInsertId();

            $linkStmt->execute([$postId, $categoryId]);
        }
    }
}
