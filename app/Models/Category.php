<?php

namespace App\Models;

use App\Core\Model;

/**
 * Category Model
 */
class Category extends Model {

    // Find category by ID
    public function findById($id) {
        $sql = "SELECT * FROM categories WHERE id = :id LIMIT 1";
        return $this->db->fetch($sql, ['id' => $id]);
    }

    // Find category by slug
    public function findBySlug($slug) {
        $sql = "SELECT * FROM categories WHERE slug = :slug LIMIT 1";
        return $this->db->fetch($sql, ['slug' => $slug]);
    }

    // Get all categories
    public function getAll() {
        $sql = "SELECT * FROM categories ORDER BY name ASC";
        return $this->db->fetchAll($sql);
    }

    // Get categories with post count
    public function getAllWithCount() {
        $sql = "SELECT c.*, COUNT(p.id) as post_count 
                FROM categories c 
                LEFT JOIN posts p ON c.id = p.category_id AND p.status = 'published'
                GROUP BY c.id 
                ORDER BY c.name ASC";
        return $this->db->fetchAll($sql);
    }

    // Create new category
    public function create($data) {
        $sql = "INSERT INTO categories (name, slug, description) VALUES (:name, :slug, :description)";
        $params = [
            'name'        => $data['name'],
            'slug'        => $data['slug'],
            'description' => $data['description']
        ];
        return $this->db->query($sql, $params);
    }

    // Update category
    public function update($id, $data) {
        $sql = "UPDATE categories SET name = :name, slug = :slug, description = :description WHERE id = :id";
        $params = [
            'id'          => $id,
            'name'        => $data['name'],
            'slug'        => $data['slug'],
            'description' => $data['description']
        ];
        return $this->db->query($sql, $params);
    }

    // Delete category
    public function delete($id) {
        $sql = "DELETE FROM categories WHERE id = :id";
        return $this->db->query($sql, ['id' => $id]);
    }
}
