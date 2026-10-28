<?php
// src/Auth.php

class Auth {
    private $db;

    public function __construct(DataManager $db) {
        $this->db = $db;
    }

    // Авторизация учителя (Обновлено для поддержки нескольких администраторов)
    public function loginAdmin($login, $password) {
        // Получаем список всех администраторов
        $admins = $this->db->getAdmins();
        
        foreach ($admins as $admin) {
            // Проверяем логин и хеш пароля
            if ($admin['login'] === $login && password_verify($password, $admin['password_hash'])) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $admin['id'];      // ID текущего админа
                $_SESSION['admin_login'] = $admin['login']; // Логин для отображения
                return true;
            }
        }
        return false;
    }

    // Авторизация ученика по PIN и ID класса
    public function loginStudent($classId, $pin) {
        $students = $this->db->getStudents();
        foreach ($students as $student) {
            // Строгое сравнение строк (PIN может начинаться с 0)
            if ($student['class_id'] == $classId && (string)$student['pin'] === (string)$pin) {
                $_SESSION['student_id'] = $student['id'];
                $_SESSION['student_name'] = $student['name'];
                $_SESSION['class_id'] = $student['class_id'];
                return $student;
            }
        }
        return null;
    }

    public function logout() {
        session_unset();
        session_destroy();
    }

    // Middleware: Проверка, что юзер - Админ
    public function requireAdmin() {
        if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
            header('HTTP/1.0 403 Forbidden');
            echo json_encode(['success' => false, 'error' => 'Доступ запрещен']);
            exit;
        }
    }

    // Middleware: Проверка, что юзер - Ученик
    public function requireStudent() {
        if (!isset($_SESSION['student_id'])) {
            header('HTTP/1.0 401 Unauthorized');
            echo json_encode(['success' => false, 'error' => 'Требуется вход']);
            exit;
        }
    }
}