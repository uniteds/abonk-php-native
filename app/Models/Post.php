<?php

namespace App\Models;

use App\Core\Model;

/**
 * Post Model
 */
class Post extends Model {

    // Find post by ID with category and author details
    public function findById($id) {
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug, u.username as author_name 
                FROM posts p
                JOIN categories c ON p.category_id = c.id
                JOIN users u ON p.user_id = u.id
                WHERE p.id = :id LIMIT 1";
        return $this->db->fetch($sql, ['id' => $id]);
    }

    // Find post by slug
    public function findBySlug($slug) {
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug, u.username as author_name 
                FROM posts p
                JOIN categories c ON p.category_id = c.id
                JOIN users u ON p.user_id = u.id
                WHERE p.slug = :slug AND p.status = 'published' LIMIT 1";
        return $this->db->fetch($sql, ['slug' => $slug]);
    }

    // Get all posts for admin (regardless of status, with category & user names)
    public function getAllForAdmin() {
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug, u.username as author_name 
                FROM posts p
                JOIN categories c ON p.category_id = c.id
                JOIN users u ON p.user_id = u.id
                ORDER BY p.id DESC";
        return $this->db->fetchAll($sql);
    }

    // Get published posts with options (search, category, pagination)
    public function getPublished($limit = 6, $offset = 0, $search = '', $categoryId = null) {
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug, u.username as author_name 
                FROM posts p
                JOIN categories c ON p.category_id = c.id
                JOIN users u ON p.user_id = u.id
                WHERE p.status = 'published'";
        
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (p.title LIKE :search OR p.content LIKE :search2)";
            $params['search'] = "%{$search}%";
            $params['search2'] = "%{$search}%";
        }

        if ($categoryId !== null) {
            $sql .= " AND p.category_id = :category_id";
            $params['category_id'] = $categoryId;
        }

        $sql .= " ORDER BY p.created_at DESC LIMIT :limit OFFSET :offset";
        
        // Use connection object directly because bindValue is cleaner for LIMIT/OFFSET integers
        $stmt = $this->db->getConnection()->prepare($sql);
        
        // Bind dynamic parameters safely
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->bindValue('limit', (int) $limit, \PDO::PARAM_INT);
        $stmt->bindValue('offset', (int) $offset, \PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Count published posts for pagination
    public function countPublished($search = '', $categoryId = null) {
        $sql = "SELECT COUNT(*) FROM posts WHERE status = 'published'";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (title LIKE :search OR content LIKE :search2)";
            $params['search'] = "%{$search}%";
            $params['search2'] = "%{$search}%";
        }

        if ($categoryId !== null) {
            $sql .= " AND category_id = :category_id";
            $params['category_id'] = $categoryId;
        }

        $stmt = $this->db->query($sql, $params);
        return $stmt->fetchColumn();
    }

    // Get recent published posts
    public function getRecent($limit = 5) {
        $sql = "SELECT id, title, slug, created_at FROM posts WHERE status = 'published' ORDER BY created_at DESC LIMIT :limit";
        
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->bindValue('limit', (int) $limit, \PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Create a new post
    public function create($data) {
        $sql = "INSERT INTO posts (user_id, category_id, title, slug, content, image, status) 
                VALUES (:user_id, :category_id, :title, :slug, :content, :image, :status)";
        
        $params = [
            'user_id'     => $data['user_id'],
            'category_id' => $data['category_id'],
            'title'       => $data['title'],
            'slug'        => $data['slug'],
            'content'     => $data['content'],
            'image'       => $data['image'],
            'status'      => $data['status']
        ];

        return $this->db->query($sql, $params);
    }

    // Update a post
    public function update($id, $data) {
        if (array_key_exists('image', $data)) {
            $sql = "UPDATE posts SET category_id = :category_id, title = :title, slug = :slug, content = :content, image = :image, status = :status WHERE id = :id";
            $params = [
                'id'          => $id,
                'category_id' => $data['category_id'],
                'title'       => $data['title'],
                'slug'        => $data['slug'],
                'content'     => $data['content'],
                'image'       => $data['image'],
                'status'      => $data['status']
            ];
        } else {
            $sql = "UPDATE posts SET category_id = :category_id, title = :title, slug = :slug, content = :content, status = :status WHERE id = :id";
            $params = [
                'id'          => $id,
                'category_id' => $data['category_id'],
                'title'       => $data['title'],
                'slug'        => $data['slug'],
                'content'     => $data['content'],
                'status'      => $data['status']
            ];
        }

        return $this->db->query($sql, $params);
    }

    // Delete a post
    public function delete($id) {
        $sql = "DELETE FROM posts WHERE id = :id";
        return $this->db->query($sql, ['id' => $id]);
    }

    // Get statistics for Admin Dashboard
    public function getDashboardStats() {
        $stats = [];
        
        // Count total posts
        $stats['total_posts'] = $this->db->query("SELECT COUNT(*) FROM posts")->fetchColumn();
        
        // Count published posts
        $stats['published_posts'] = $this->db->query("SELECT COUNT(*) FROM posts WHERE status = 'published'")->fetchColumn();
        
        // Count draft posts
        $stats['draft_posts'] = $this->db->query("SELECT COUNT(*) FROM posts WHERE status = 'draft'")->fetchColumn();
        
        // Count total categories
        $stats['total_categories'] = $this->db->query("SELECT COUNT(*) FROM categories")->fetchColumn();
        
        // Count total users
        $stats['total_users'] = $this->db->query("SELECT COUNT(*) FROM users")->fetchColumn();

        return $stats;
    }
}
