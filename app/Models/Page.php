<?php

namespace App\Models;

use App\Core\Model;

/**
 * Page Model for Static Pages
 */
class Page extends Model {

    // Find page by ID
    public function findById($id) {
        $sql = "SELECT * FROM pages WHERE id = :id LIMIT 1";
        return $this->db->fetch($sql, ['id' => $id]);
    }

    // Find page by slug (only published for public view, or all if admin)
    public function findBySlug($slug, $onlyPublished = false) {
        $sql = "SELECT * FROM pages WHERE slug = :slug";
        if ($onlyPublished) {
            $sql .= " AND status = 'published'";
        }
        $sql .= " LIMIT 1";
        return $this->db->fetch($sql, ['slug' => $slug]);
    }

    // Get all pages (for admin listing)
    public function getAll() {
        $sql = "SELECT * FROM pages ORDER BY created_at DESC";
        return $this->db->fetchAll($sql);
    }

    // Create new page
    public function create($data) {
        $sql = "INSERT INTO pages (title, slug, content, status) VALUES (:title, :slug, :content, :status)";
        $params = [
            'title'   => $data['title'],
            'slug'    => $data['slug'],
            'content' => $data['content'],
            'status'  => $data['status'] ?? 'draft'
        ];
        return $this->db->query($sql, $params);
    }

    // Update page
    public function update($id, $data) {
        $sql = "UPDATE pages SET title = :title, slug = :slug, content = :content, status = :status WHERE id = :id";
        $params = [
            'id'      => $id,
            'title'   => $data['title'],
            'slug'    => $data['slug'],
            'content' => $data['content'],
            'status'  => $data['status'] ?? 'draft'
        ];
        return $this->db->query($sql, $params);
    }

    // Delete page
    public function delete($id) {
        $sql = "DELETE FROM pages WHERE id = :id";
        return $this->db->query($sql, ['id' => $id]);
    }
}
