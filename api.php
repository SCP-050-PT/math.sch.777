<?php
// api.php
header('Content-Type: application/json; charset=utf-8');
session_start();

require_once 'src/DataManager.php';
require_once 'src/Auth.php';
require_once 'src/StatsService.php';
require_once 'src/Helper.php';

// Получение данных из JSON body или POST
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, true);
if (!$input && !empty($_POST)) $input = $_POST;

$action = $input['action'] ?? $_GET['action'] ?? '';
$response = ['success' => false, 'error' => 'Unknown action'];

try {
    $db = new DataManager();
    $auth = new Auth($db);
    $stats = new StatsService($db);

    switch ($action) {
        // ==========================================
        // --- АВТОРИЗАЦИЯ ---
        // ==========================================
        
        case 'admin_login':
            if ($auth->loginAdmin($input['login'] ?? '', $input['password'] ?? '')) {
                $response = ['success' => true];
            } else {
                $response = ['success' => false, 'error' => 'Ошибка входа'];
            }
            break;

        case 'student_login':
            $student = $auth->loginStudent($input['class_id'] ?? 0, $input['pin'] ?? '');
            if ($student) $response = ['success' => true, 'student' => $student];
            else $response = ['success' => false, 'error' => 'Неверный PIN'];
            break;

        case 'logout':
            $auth->logout();
            $response = ['success' => true];
            break;

        // ==========================================
        // --- ДАННЫЕ ДЛЯ СТУДЕНТА ---
        // ==========================================

        case 'get_initial_data':
            $response = ['success' => true, 'classes' => $db->getClassesSimple()];
            break;
            
        case 'get_student_profile':
            $auth->requireStudent();
            $studentId = $_SESSION['student_id'];
            $classId = $_SESSION['class_id'];
            
            // Поиск активного экзамена для класса ученика
            $exams = $db->getExams(); 
            $myExam = null;
            
            foreach ($exams as $exam) {
                $examClassId = (int)($exam['class_id'] ?? 0);
                $targetClassId = (int)$classId;
                $status = $exam['status'] ?? 'finished'; 

                if ($status === 'active' && $examClassId === $targetClassId) {
                    $myExam = $exam;
                    break; 
                }
            }

            $rarityMap = $stats->getAchievementRarity($classId);

            $response = [
                'success' => true, 
                'profile' => $db->getStudentById($studentId),
                'stats' => $stats->getStudentStats($studentId),
                'active_exam' => $myExam,
                'rarity' => $rarityMap
            ];
            break;

        case 'save_result':
            $auth->requireStudent();
            $resultData = [
                'student_id' => $_SESSION['student_id'],
                'mode' => $input['mode'], // Поддерживает новые режимы (memory, true_false и т.д.)
                'difficulty' => $input['difficulty'] ?? 'easy',
                'correct' => (int)$input['correct'],
                'total' => (int)$input['total'],
                'time_spent' => (int)$input['time_spent'],
                'mistakes' => $input['mistakes'] ?? [],
                'timestamp' => date('Y-m-d H:i:s')
            ];
            $db->saveResult($resultData);
            
            // Проверка новых достижений
            $newAchievements = $stats->checkAchievements($_SESSION['student_id']);
            $response = ['success' => true, 'achievements' => $newAchievements];
            break;

        // ==========================================
        // --- ПАНЕЛЬ АДМИНИСТРАТОРА ---
        // ==========================================

        case 'get_admin_dashboard':
            $auth->requireAdmin();
            $response = [
                'success' => true,
                'classes' => $db->getClasses(),
                'students' => $db->getStudents(),
                'global_stats' => $stats->getGlobalStats(),
                'exams' => $db->getExams()
            ];
            break;
            
        case 'manage_exam':
            $auth->requireAdmin();
            if ($input['method'] === 'start') {
                $db->startExam($input['class_id'], $input['difficulty'], $input['duration']);
            } elseif ($input['method'] === 'stop') {
                $db->stopExam($input['class_id']);
            }
            $response = ['success' => true];
            break;
            
        case 'get_exam_results':
            $auth->requireAdmin();
            $classId = $input['class_id'];
            $students = $db->getStudents();
            $classStudents = array_filter($students, function($s) use ($classId) { return $s['class_id'] == $classId; });
            
            $report = [];
            foreach ($classStudents as $s) {
                $sResults = $db->getStudentResults($s['id']);
                $sExams = array_filter($sResults, function($r) { return ($r['mode'] ?? '') === 'exam'; });
                $lastExam = end($sExams);
                if ($lastExam) {
                    $report[] = [
                        'name' => $s['name'],
                        'correct' => $lastExam['correct'],
                        'total' => $lastExam['total'],
                        'score' => ($lastExam['total'] > 0) ? round(($lastExam['correct'] / $lastExam['total']) * 5) : 0,
                        'date' => $lastExam['timestamp']
                    ];
                }
            }
            $response = ['success' => true, 'report' => $report];
            break;

        // Управление классами
        case 'manage_class':
            $auth->requireAdmin();
            if ($input['method'] === 'add') $db->addClass($input['name']);
            elseif ($input['method'] === 'delete') $db->deleteClass($input['id']);
            elseif ($input['method'] === 'edit') $db->updateClass($input['id'], $input['name']);
            $response = ['success' => true];
            break;

        // Управление учениками
        case 'manage_student':
            $auth->requireAdmin();
            if ($input['method'] === 'add') {
                $newS = $db->addStudent($input['class_id'], $input['name']);
                $response = ['success' => true, 'student' => $newS];
            } 
            elseif ($input['method'] === 'delete') {
                $db->deleteStudent($input['id']);
                $response = ['success' => true];
            }
            break;

        case 'get_chart_data':
            $auth->requireAdmin();
            $response = ['success' => true, 'data' => $stats->getChartData($input['filter'] ?? 'week')];
            break;
            
        case 'get_student_details':
            $auth->requireAdmin();
            $student = $db->getStudentById($input['id']);
            $statsData = $stats->getStudentStats($input['id']);
            $response = ['success' => true, 'student' => $student, 'stats' => $statsData];
            break;

        // ==========================================
        // --- НОВОЕ: УПРАВЛЕНИЕ АДМИНАМИ ---
        // ==========================================

        case 'manage_admin':
            $auth->requireAdmin();
            if ($input['method'] === 'get') {
                $admins = $db->getAdmins();
                // Удаляем хеши паролей перед отправкой на клиент
                foreach ($admins as &$a) unset($a['password_hash']);
                $response = ['success' => true, 'admins' => $admins];
            }
            elseif ($input['method'] === 'add') {
                $db->addAdmin($input['login'], $input['password']);
                $response = ['success' => true];
            }
            elseif ($input['method'] === 'delete') {
                $db->deleteAdmin($input['id']);
                $response = ['success' => true];
            }
            break;

        case 'admin_update_profile':
            $auth->requireAdmin();
            // Обновляем данные ТЕКУЩЕГО авторизованного админа
            $currentAdminId = $_SESSION['admin_id'];
            $db->updateAdmin($currentAdminId, $input['login'], $input['password']);
            $response = ['success' => true];
            break;

        // ==========================================
        // --- ЭКСПОРТ (CSV) ---
        // ==========================================

        case 'export_stats':
            $auth->requireAdmin();
            $csv = $stats->generateCsvExport();
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=report_full_' . date('Y-m-d') . '.csv');
            echo $csv;
            exit;
            
        case 'export_student_stats':
            $auth->requireAdmin();
            $csv = $stats->generateStudentCsvExport($_GET['id']);
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=student_' . $_GET['id'] . '.csv');
            echo $csv;
            exit;

        // НОВОЕ: Скачивание списка учеников с ПИН-кодами
        case 'export_student_list':
            $auth->requireAdmin();
            $classId = $_GET['class_id'] ?? 'all';
            $csv = $stats->generateStudentListCsv($classId);
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=students_pins_' . date('Y-m-d') . '.csv');
            echo $csv;
            exit;

        default:
            $response = ['success' => false, 'error' => 'Action not found'];
    }
} catch (Exception $e) {
    $response = ['success' => false, 'error' => $e->getMessage()];
}
echo json_encode($response);
exit;