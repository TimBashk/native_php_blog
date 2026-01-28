<?php
namespace App\Models;

use App\DB\Database;
use PDO;

class Post {
    public static function getByCategory(int $categoryId, int $limit = 3) {
        $db = Database::getInstance();
        $limit = (int)$limit;
        $stmt = $db->prepare("
            SELECT p.* 
            FROM posts p
            JOIN post_categories pc ON p.id = pc.post_id
            WHERE pc.category_id = ?
            ORDER BY p.created_at DESC
            LIMIT $limit
        ");
        $stmt->execute([$categoryId]);
        return $stmt->fetchAll();
    }

    public static function getAllByCategory(int $categoryId, string $sort = 'date'): array
    {
        $db = Database::getInstance();

        // Определяем поле сортировки
        $orderBy = match($sort) {
            'views' => 'p.views DESC',
            default => 'p.created_at DESC'
        };

        $sql = "
        SELECT p.*
        FROM posts p
        JOIN post_categories pc ON pc.post_id = p.id
        WHERE pc.category_id = :category_id
        ORDER BY $orderBy
    ";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getByCategoryWithPagination(int $categoryId, string $sort = 'date', int $limit = 5, int $offset = 0): array
    {
        $db = Database::getInstance();

        $orderBy = match($sort) {
            'views' => 'p.views DESC',
            default => 'p.created_at DESC'
        };

        $sql = "
        SELECT p.*
        FROM posts p
        JOIN post_categories pc ON pc.post_id = p.id
        WHERE pc.category_id = :category_id
        ORDER BY $orderBy
        LIMIT :limit OFFSET :offset
    ";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function countByCategory(int $categoryId): int
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("
        SELECT COUNT(*) 
        FROM posts p
        JOIN post_categories pc ON pc.post_id = p.id
        WHERE pc.category_id = :category_id
    ");
        $stmt->execute([':category_id' => $categoryId]);
        return (int)$stmt->fetchColumn();
    }

    // Находим пост по ID
    public static function find(int $id): ?array
    {
        $db = Database::getInstance();

        $stmt = $db->prepare("SELECT * FROM posts WHERE id = :id");
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // Увеличиваем просмотры
    public static function incrementViews(int $id): void
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE posts SET views = views + 1 WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }

    public static function getRelated(int $postId, int $limit = 3): array
    {
        $db = Database::getInstance();

        $sql = "
            SELECT DISTINCT p.*
            FROM posts p
            JOIN post_categories pc ON pc.post_id = p.id
            WHERE pc.category_id IN (
                SELECT category_id FROM post_categories WHERE post_id = :post_id
            ) AND p.id != :post_id
            ORDER BY p.created_at DESC
            LIMIT $limit
        ";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':post_id', $postId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}