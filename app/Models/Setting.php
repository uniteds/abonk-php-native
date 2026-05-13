<?php

namespace App\Models;

use App\Core\Model;

/**
 * Setting Model
 */
class Setting extends Model {

    // Get all settings as a simple associative array: ['setting_key' => 'setting_value']
    public function getAll() {
        $sql = "SELECT setting_key, setting_value FROM settings";
        $raw = $this->db->fetchAll($sql);
        
        $settings = [];
        foreach ($raw as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        
        return $settings;
    }

    // Get a specific setting value
    public function get($key, $default = null) {
        $sql = "SELECT setting_value FROM settings WHERE setting_key = :key LIMIT 1";
        $row = $this->db->fetch($sql, ['key' => $key]);
        return $row ? $row['setting_value'] : $default;
    }

    // Save/update settings from an associative array
    public function saveAll($data) {
        $sql = "INSERT INTO settings (setting_key, setting_value) 
                VALUES (:key, :value) 
                ON DUPLICATE KEY UPDATE setting_value = :value_update";
        
        $stmt = $this->db->getConnection()->prepare($sql);
        
        foreach ($data as $key => $value) {
            $stmt->execute([
                'key'          => $key,
                'value'        => $value,
                'value_update' => $value
            ]);
        }
        return true;
    }
}
