<?php

namespace App\Models;

use App\DB\Database;
use PDO;

class Category {
    public static function getWithPosts(int $limit = 3) {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT * FROM categories");
        $categories = $stmt->fetchAll();

        foreach ($categories as &$cat) {
            $cat['posts'] = Post::getByCategory($cat['id'], $limit);
        }
        return $categories;
    }

    public static function find(int $id): ?array
    {
        $db = Database::getInstance();

        $stmt = $db->prepare(
            "SELECT * FROM categories WHERE id = :id"
        );
        $stmt->execute(['id' => $id]);

        return $stmt->fetch() ?: null;
    }

    public static function getByPost(int $postId): array
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("
        SELECT c.*
        FROM categories c
        JOIN post_categories pc ON pc.category_id = c.id
        WHERE pc.post_id = :post_id
    ");
        $stmt->execute([':post_id' => $postId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}