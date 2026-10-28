<?php
// src/Helper.php

class Helper {
    
    // Генерация ID (max + 1)
    public static function generateId($array) {
        if (empty($array)) return 1;
        // Извлекаем столбец 'id' и находим максимум
        $ids = array_column($array, 'id');
        return max($ids) + 1;
    }

    // Генерация уникального 4-значного PIN-кода
    public static function generatePin($existingStudents) {
        $existingPins = array_column($existingStudents, 'pin');
        
        do {
            $pin = str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
        } while (in_array($pin, $existingPins)); // Гарантия уникальности
        
        return $pin;
    }

    // Форматирование времени (секунды -> мм:сс)
    public static function formatTime($seconds) {
        $m = floor($seconds / 60);
        $s = $seconds % 60;
        return sprintf("%02d:%02d", $m, $s);
    }
}