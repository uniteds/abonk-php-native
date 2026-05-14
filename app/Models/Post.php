<?php

namespace App\Models;

use App\Core\Model;

/**
 * Post Model
 */
class Post extends Model {

    // Find post by ID with category and author details
    public function findById($id) {
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug, u.username as author_name, u.profile_photo as author_photo, u.name as author_full_name, u.bio as author_bio 
                FROM posts p
                JOIN categories c ON p.category_id = c.id
                JOIN users u ON p.user_id = u.id
                WHERE p.id = :id LIMIT 1";
        return $this->db->fetch($sql, ['id' => $id]);
    }

    // Find post by slug
    public function findBySlug($slug) {
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug, u.username as author_name, u.profile_photo as author_photo, u.name as author_full_name, u.bio as author_bio 
                FROM posts p
                JOIN categories c ON p.category_id = c.id
                JOIN users u ON p.user_id = u.id
                WHERE p.slug = :slug AND p.status = 'published' LIMIT 1";
        return $this->db->fetch($sql, ['slug' => $slug]);
    }

    // Get all posts for admin (regardless of status, with category & user names)
    public function getAllForAdmin() {
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug, u.username as author_name, u.profile_photo as author_photo, u.name as author_full_name, u.bio as author_bio 
                FROM posts p
                JOIN categories c ON p.category_id = c.id
                JOIN users u ON p.user_id = u.id
                ORDER BY p.id DESC";
        return $this->db->fetchAll($sql);
    }

    // Get published posts with options (search, category, pagination)
    public function getPublished($limit = 6, $offset = 0, $search = '', $categoryId = null, $tag = null) {
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug, u.username as author_name, u.profile_photo as author_photo, u.name as author_full_name, u.bio as author_bio 
                FROM posts p
                JOIN categories c ON p.category_id = c.id
                JOIN users u ON p.user_id = u.id
                WHERE p.status = 'published'";
        
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (p.title LIKE :search OR p.content LIKE :search2 OR p.tags LIKE :search3)";
            $params['search'] = "%{$search}%";
            $params['search2'] = "%{$search}%";
            $params['search3'] = "%{$search}%";
        }

        if ($categoryId !== null) {
            $sql .= " AND p.category_id = :category_id";
            $params['category_id'] = $categoryId;
        }

        if (!empty($tag)) {
            $sql .= " AND p.tags LIKE :tag";
            $params['tag'] = "%{$tag}%";
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
    public function countPublished($search = '', $categoryId = null, $tag = null) {
        $sql = "SELECT COUNT(*) FROM posts WHERE status = 'published'";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (title LIKE :search OR content LIKE :search2 OR tags LIKE :search3)";
            $params['search'] = "%{$search}%";
            $params['search2'] = "%{$search}%";
            $params['search3'] = "%{$search}%";
        }

        if ($categoryId !== null) {
            $sql .= " AND category_id = :category_id";
            $params['category_id'] = $categoryId;
        }

        if (!empty($tag)) {
            $sql .= " AND tags LIKE :tag";
            $params['tag'] = "%{$tag}%";
        }

        $stmt = $this->db->query($sql, $params);
        return $stmt->fetchColumn();
    }

    // Get recent published posts
    public function getRecent($limit = 5) {
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug, u.username as author_name, u.profile_photo as author_photo, u.name as author_full_name 
                FROM posts p
                JOIN categories c ON p.category_id = c.id
                JOIN users u ON p.user_id = u.id
                WHERE p.status = 'published' ORDER BY p.created_at DESC LIMIT :limit";
        
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->bindValue('limit', (int) $limit, \PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Get featured published posts
    public function getFeatured($limit = 5) {
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug, u.username as author_name, u.profile_photo as author_photo, u.name as author_full_name 
                FROM posts p
                JOIN categories c ON p.category_id = c.id
                JOIN users u ON p.user_id = u.id
                WHERE p.status = 'published' AND p.is_featured = 1
                ORDER BY p.created_at DESC LIMIT :limit";
        
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->bindValue('limit', (int) $limit, \PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Get distinct/popular tags from published posts
    public function getPopularTags($limit = 6) {
        $sql = "SELECT tags FROM posts WHERE status = 'published' AND tags IS NOT NULL AND tags != '' ORDER BY created_at DESC";
        $rows = $this->db->fetchAll($sql);
        
        $tagCounts = [];
        foreach ($rows as $row) {
            $parts = array_map('trim', explode(',', $row['tags']));
            foreach ($parts as $p) {
                if (!empty($p)) {
                    $tagCounts[$p] = ($tagCounts[$p] ?? 0) + 1;
                }
            }
        }
        
        arsort($tagCounts);
        return array_slice(array_keys($tagCounts), 0, $limit);
    }

    // Create a new post
    public function create($data) {
        $sql = "INSERT INTO posts (user_id, category_id, title, slug, content, image, status, is_featured, tags) 
                VALUES (:user_id, :category_id, :title, :slug, :content, :image, :status, :is_featured, :tags)";
        
        $params = [
            'user_id'     => $data['user_id'],
            'category_id' => $data['category_id'],
            'title'       => $data['title'],
            'slug'        => $data['slug'],
            'content'     => $data['content'],
            'image'       => $data['image'],
            'status'      => $data['status'],
            'is_featured' => $data['is_featured'] ?? 0,
            'tags'        => $data['tags'] ?? null
        ];

        return $this->db->query($sql, $params);
    }

    // Update a post
    public function update($id, $data) {
        if (array_key_exists('image', $data)) {
            $sql = "UPDATE posts SET category_id = :category_id, title = :title, slug = :slug, content = :content, image = :image, status = :status, is_featured = :is_featured, tags = :tags WHERE id = :id";
            $params = [
                'id'          => $id,
                'category_id' => $data['category_id'],
                'title'       => $data['title'],
                'slug'        => $data['slug'],
                'content'     => $data['content'],
                'image'       => $data['image'],
                'status'      => $data['status'],
                'is_featured' => $data['is_featured'] ?? 0,
                'tags'        => $data['tags'] ?? null
            ];
        } else {
            $sql = "UPDATE posts SET category_id = :category_id, title = :title, slug = :slug, content = :content, status = :status, is_featured = :is_featured, tags = :tags WHERE id = :id";
            $params = [
                'id'          => $id,
                'category_id' => $data['category_id'],
                'title'       => $data['title'],
                'slug'        => $data['slug'],
                'content'     => $data['content'],
                'status'      => $data['status'],
                'is_featured' => $data['is_featured'] ?? 0,
                'tags'        => $data['tags'] ?? null
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
        
        // Count total static pages
        $stats['total_pages'] = $this->db->query("SELECT COUNT(*) FROM pages")->fetchColumn();
        
        // Count total users
        $stats['total_users'] = $this->db->query("SELECT COUNT(*) FROM users")->fetchColumn();

        return $stats;
    }

    // Get recent posts for Admin Dashboard preview
    public function getRecentForAdmin($limit = 5) {
        $sql = "SELECT p.*, c.name as category_name, u.username as author_name 
                FROM posts p
                JOIN categories c ON p.category_id = c.id
                JOIN users u ON p.user_id = u.id
                ORDER BY p.id DESC LIMIT :limit";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->bindValue('limit', (int) $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
