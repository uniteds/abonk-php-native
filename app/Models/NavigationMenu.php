<?php

namespace App\Models;

use App\Core\Model;

/**
 * NavigationMenu Model
 */
class NavigationMenu extends Model {

    // Find menu by ID
    public function findById($id) {
        $sql = "SELECT * FROM navigation_menus WHERE id = :id LIMIT 1";
        return $this->db->fetch($sql, ['id' => $id]);
    }

    // Get all navigation menus ordered by order_num
    public function getAll() {
        $sql = "SELECT * FROM navigation_menus ORDER BY order_num ASC, id ASC";
        return $this->db->fetchAll($sql);
    }

    // Create new navigation menu
    public function create($data) {
        $sql = "INSERT INTO navigation_menus (label, url, order_num) VALUES (:label, :url, :order_num)";
        $params = [
            'label'     => $data['label'],
            'url'       => $data['url'],
            'order_num' => (int)($data['order_num'] ?? 0)
        ];
        return $this->db->query($sql, $params);
    }

    // Update navigation menu
    public function update($id, $data) {
        $sql = "UPDATE navigation_menus SET label = :label, url = :url, order_num = :order_num WHERE id = :id";
        $params = [
            'id'        => $id,
            'label'     => $data['label'],
            'url'       => $data['url'],
            'order_num' => (int)($data['order_num'] ?? 0)
        ];
        return $this->db->query($sql, $params);
    }

    // Delete navigation menu
    public function delete($id) {
        $sql = "DELETE FROM navigation_menus WHERE id = :id";
        return $this->db->query($sql, ['id' => $id]);
    }
}
