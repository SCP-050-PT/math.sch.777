<?php
// src/DataManager.php

class DataManager {
    private $dataDir;

    public function __construct() {
        // Путь к папке с данными
        $this->dataDir = __DIR__ . '/../data/';
        $this->ensureFilesExist();
    }

    /**
     * Проверка существования файлов и создание дефолтных данных
     */
    private function ensureFilesExist() {
        $files = ['classes.json', 'students.json', 'results.json', 'admin.json', 'exams.json'];
        
        // Создаем папку data, если нет
        if (!is_dir($this->dataDir)) {
            mkdir($this->dataDir, 0755, true);
            // Защита папки от прямого доступа из браузера
            file_put_contents($this->dataDir . '.htaccess', "Deny from all");
        }

        foreach ($files as $file) {
            $path = $this->dataDir . $file;
            if (!file_exists($path)) {
                $defaultData = [];
                
                // Инициализация администратора (ТЕПЕРЬ ЭТО МАССИВ ОБЪЕКТОВ)
                if ($file === 'admin.json') {
                    $defaultData = [
                        [
                            'id' => 1,
                            'login' => 'admin',
                            'password_hash' => password_hash('admin', PASSWORD_DEFAULT)
                        ]
                    ];
                }
                
                file_put_contents($path, json_encode($defaultData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            }
        }
    }

    /**
     * Чтение JSON файла с блокировкой
     */
    public function readJson($filename) {
        $path = $this->dataDir . $filename;
        if (!file_exists($path)) return [];

        $fp = fopen($path, 'r');
        $content = '[]';
        
        if ($fp && flock($fp, LOCK_SH)) {
            $size = filesize($path);
            if ($size > 0) {
                $content = stream_get_contents($fp);
            }
            flock($fp, LOCK_UN);
            fclose($fp);
        }
        
        $data = json_decode($content, true);
        return is_array($data) ? $data : [];
    }

    /**
     * Запись JSON файла с блокировкой
     */
    public function saveJson($filename, $data) {
        $path = $this->dataDir . $filename;
        $fp = fopen($path, 'c');
        if ($fp && flock($fp, LOCK_EX)) {
            ftruncate($fp, 0);
            fwrite($fp, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            flock($fp, LOCK_UN);
            fclose($fp);
        }
    }

    // ==========================================
    // --- КЛАССЫ (CLASSES) ---
    // ==========================================

    public function getClasses() { 
        return $this->readJson('classes.json'); 
    }
    
    public function getClassesSimple() {
        $classes = $this->getClasses();
        // Возвращаем упрощенный список для фронтенда
        return array_map(function($c) { 
            return ['id' => $c['id'], 'name' => $c['name']]; 
        }, $classes);
    }

    public function addClass($name) {
        $classes = $this->getClasses();
        $newId = Helper::generateId($classes);
        $classes[] = ['id' => $newId, 'name' => $name];
        $this->saveJson('classes.json', $classes);
        return $newId;
    }

    public function updateClass($id, $name) {
        $classes = $this->getClasses();
        foreach ($classes as &$c) {
            if ($c['id'] == $id) { 
                $c['name'] = $name; 
                break; 
            }
        }
        $this->saveJson('classes.json', $classes);
    }

    public function deleteClass($id) {
        $classes = $this->getClasses();
        $classes = array_filter($classes, function($c) use ($id) { return $c['id'] != $id; });
        $this->saveJson('classes.json', array_values($classes));
        
        // При удалении класса удаляем и учеников этого класса (опционально, но логично)
        $students = $this->getStudents();
        $students = array_filter($students, function($s) use ($id) { return $s['class_id'] != $id; });
        $this->saveJson('students.json', array_values($students));
    }

    // ==========================================
    // --- УЧЕНИКИ (STUDENTS) ---
    // ==========================================

    public function getStudents() { 
        return $this->readJson('students.json'); 
    }

    public function getStudentById($id) {
        $students = $this->getStudents();
        foreach ($students as $s) { 
            if ($s['id'] == $id) return $s; 
        }
        return null;
    }

    public function addStudent($classId, $name) {
        $students = $this->getStudents();
        $newId = Helper::generateId($students);
        $pin = Helper::generatePin($students);
        
        $newStudent = [
            'id' => $newId,
            'class_id' => $classId,
            'name' => $name,
            'pin' => $pin,
            'achievements' => []
        ];
        
        $students[] = $newStudent;
        $this->saveJson('students.json', $students);
        return $newStudent;
    }

    public function deleteStudent($id) {
        $students = $this->getStudents();
        $students = array_filter($students, function($s) use ($id) { return $s['id'] != $id; });
        $this->saveJson('students.json', array_values($students));
    }
    
    public function updateStudentData($id, $newData) {
        $students = $this->getStudents();
        foreach ($students as &$s) {
            if ($s['id'] == $id) {
                $s = array_merge($s, $newData);
                break;
            }
        }
        $this->saveJson('students.json', $students);
    }

    // ==========================================
    // --- РЕЗУЛЬТАТЫ (RESULTS) ---
    // ==========================================

    public function saveResult($resultItem) {
        $results = $this->readJson('results.json');
        $resultItem['id'] = Helper::generateId($results);
        $results[] = $resultItem;
        $this->saveJson('results.json', $results);
    }
    
    public function getResults() { 
        return $this->readJson('results.json'); 
    }
    
    public function getStudentResults($studentId) {
        $all = $this->getResults();
        return array_filter($all, function($r) use ($studentId) { 
            return $r['student_id'] == $studentId; 
        });
    }

    // ==========================================
    // --- ЭКЗАМЕНЫ (EXAMS) ---
    // ==========================================

    public function getExams() { 
        return $this->readJson('exams.json'); 
    }

    public function startExam($classId, $difficulty, $duration) {
        $exams = $this->getExams();
        // Завершаем старые активные экзамены для этого класса
        foreach ($exams as &$e) {
            if ($e['class_id'] == $classId && $e['status'] === 'active') {
                $e['status'] = 'finished';
                $e['end_time'] = date('Y-m-d H:i:s');
            }
        }
        
        // Создаем новый активный
        $exams[] = [
            'id' => Helper::generateId($exams),
            'class_id' => $classId,
            'difficulty' => $difficulty,
            'duration' => (int)$duration,
            'start_time' => date('Y-m-d H:i:s'),
            'status' => 'active'
        ];
        
        $this->saveJson('exams.json', $exams);
    }

    public function stopExam($classId) {
        $exams = $this->getExams();
        foreach ($exams as &$e) {
            if ($e['class_id'] == $classId && $e['status'] === 'active') {
                $e['status'] = 'finished';
                $e['end_time'] = date('Y-m-d H:i:s');
            }
        }
        $this->saveJson('exams.json', $exams);
    }

    // ==========================================
    // --- АДМИНИСТРАТОРЫ (ADMINS) - ОБНОВЛЕНО ---
    // ==========================================

    /**
     * Получить список всех администраторов
     * Включает миграцию со старого формата (объект) на новый (массив)
     */
    public function getAdmins() {
        $data = $this->readJson('admin.json');
        
        // МИГРАЦИЯ: Если файл содержит старый формат (ассоциативный массив с ключом login)
        // преобразуем его в массив объектов и сохраняем.
        if (isset($data['login'])) {
            $newFormat = [
                [
                    'id' => 1,
                    'login' => $data['login'],
                    'password_hash' => $data['password_hash']
                ]
            ];
            $this->saveJson('admin.json', $newFormat);
            return $newFormat;
        }

        // Если это уже массив
        return $data;
    }

    /**
     * Добавить нового администратора
     */
    public function addAdmin($login, $password) {
        $admins = $this->getAdmins();
        
        // Проверка на дубликат логина
        foreach ($admins as $admin) {
            if (mb_strtolower($admin['login']) === mb_strtolower($login)) {
                throw new Exception("Администратор с таким логином уже существует");
            }
        }

        $newId = Helper::generateId($admins);
        $newAdmin = [
            'id' => $newId,
            'login' => $login,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT)
        ];

        $admins[] = $newAdmin;
        $this->saveJson('admin.json', $admins);
        
        return $newAdmin; // Возвращаем данные без пароля для фронта (пароль хеширован внутри)
    }

    /**
     * Удалить администратора
     */
    public function deleteAdmin($id) {
        $admins = $this->getAdmins();
        
        // Защита: нельзя удалить последнего администратора
        if (count($admins) <= 1) {
            throw new Exception("Нельзя удалить последнего администратора");
        }

        $admins = array_filter($admins, function($a) use ($id) {
            return $a['id'] != $id;
        });

        $this->saveJson('admin.json', array_values($admins));
    }

    /**
     * Обновить пароль администратора
     */
    public function updateAdmin($id, $newLogin, $newPassword = null) {
        $admins = $this->getAdmins();
        $found = false;

        foreach ($admins as &$admin) {
            if ($admin['id'] == $id) {
                // Проверка уникальности логина (если меняем логин)
                if ($admin['login'] !== $newLogin) {
                    foreach ($admins as $check) {
                        if ($check['id'] != $id && mb_strtolower($check['login']) === mb_strtolower($newLogin)) {
                            throw new Exception("Логин занят другим администратором");
                        }
                    }
                    $admin['login'] = $newLogin;
                }

                if (!empty($newPassword)) {
                    $admin['password_hash'] = password_hash($newPassword, PASSWORD_DEFAULT);
                }
                $found = true;
                break;
            }
        }

        if (!$found) throw new Exception("Администратор не найден");

        $this->saveJson('admin.json', $admins);
    }
}