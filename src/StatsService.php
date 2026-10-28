<?php
// src/StatsService.php

class StatsService {
    private $db;

    public function __construct(DataManager $db) {
        $this->db = $db;
    }

    // ==========================================
    // --- ОБЩАЯ СТАТИСТИКА ---
    // ==========================================

    // Глобальная статистика для дашборда
    public function getGlobalStats() {
        $students = $this->db->getStudents();
        $results = $this->db->getResults();

        $totalSolved = 0;
        $totalCorrect = 0;
        
        foreach ($results as $res) {
            $totalSolved += $res['total'];
            $totalCorrect += $res['correct'];
        }

        $avgScore = ($totalSolved > 0) ? round(($totalCorrect / $totalSolved) * 5, 1) : 0;

        return [
            'total_students' => count($students),
            'total_solved' => $totalSolved,
            'avg_score' => $avgScore,
            'total_tests' => count($results)
        ];
    }

    // Данные для графика (Линейный)
    public function getChartData($filter = 'week') {
        $results = $this->db->getResults();
        $data = [];
        
        $days = ($filter === 'month') ? 30 : 7;
        
        for ($i = $days - 1; $i >= 0; $i--) {
            $dateKey = date('Y-m-d', strtotime("-$i days"));
            $data[$dateKey] = 0;
        }

        foreach ($results as $res) {
            $resDate = substr($res['timestamp'], 0, 10);
            if (isset($data[$resDate])) {
                $data[$resDate] += $res['correct'];
            }
        }

        return [
            'labels' => array_keys($data),
            'values' => array_values($data)
        ];
    }

    // Статистика конкретного ученика
    public function getStudentStats($studentId) {
        $results = $this->db->getStudentResults($studentId);
        
        $totalSolved = 0;
        $totalCorrect = 0;
        
        foreach ($results as $res) {
            $totalSolved += $res['total'];
            $totalCorrect += $res['correct'];
        }
        
        $accuracy = ($totalSolved > 0) ? round(($totalCorrect / $totalSolved) * 100) : 0;

        return [
            'total_solved' => $totalSolved,
            'accuracy' => $accuracy,
            'history' => array_reverse(array_slice($results, -50)) // Отдаем последние 50 игр
        ];
    }

    // ==========================================
    // --- ПРОВЕРКА ДОСТИЖЕНИЙ (АЧИВКИ) ---
    // ==========================================
    
    public function checkAchievements($studentId) {
        $student = $this->db->getStudentById($studentId);
        $results = $this->db->getStudentResults($studentId);
        $currentBadges = $student['achievements'] ?? [];
        $newBadges = [];

        // --- Агрегация данных для проверки ---
        $totalCorrect = 0;
        $totalTotal = 0;
        $perfectTests = 0;       // 100% точность (мин 10 вопросов)
        $perfectHardTests = 0;   // 100% точность в сложном
        $survivalScoreMax = 0;
        $examsPassed = 0;

        // Новые счетчики для режимов
        $trueFalseMaxStreak = 0; // Макс верных в одной игре True/False
        $findXTotal = 0;         // Всего решено уравнений
        $findXHardPerfect = 0;   // Идеальные игры в Find X Hard
        $botWinsHard = 0;        // Победы над ботом (Hard)
        $botSpeedWins = 0;       // Быстрые победы
        $memoryPerfectGames = 0; // Идеальные игры в Memory
        $rainMaxDrops = 0;       // Макс капель за игру
        $rainLongestTime = 0;    // Макс время в дожде
        $builderPerfectGames = 0;// Идеальные игры в Конструкторе
        
        foreach ($results as $r) {
            $mode = $r['mode'] ?? 'free';
            $diff = $r['difficulty'] ?? 'easy';
            $correct = $r['correct'];
            $total = $r['total'];
            $time = $r['time_spent'] ?? 0;
            
            $totalCorrect += $correct;
            $totalTotal += $total;
            
            $isPerfect = ($total >= 10 && $correct == $total);

            // Стандартные метрики
            if ($isPerfect) $perfectTests++;
            if ($mode === 'survival' && $correct > $survivalScoreMax) $survivalScoreMax = $correct;
            if ($mode === 'exam') $examsPassed++;
            if ($diff === 'hard' && $isPerfect) $perfectHardTests++;

            // --- Метрики новых режимов ---
            
            // 1. True/False
            if ($mode === 'true_false') {
                if ($correct > $trueFalseMaxStreak) $trueFalseMaxStreak = $correct;
            }

            // 2. Find X
            if ($mode === 'find_x') {
                $findXTotal += $correct;
                if ($diff === 'hard' && $isPerfect) $findXHardPerfect++;
            }

            // 3. Bot Battle
            if ($mode === 'bot_battle') {
                // Считаем победой, если решили 20 примеров (стандарт длины боя)
                if ($correct >= 20) {
                    if ($diff === 'hard') $botWinsHard++;
                    // Если время очень маленькое (например < 40 сек на 20 примеров) - гонщик
                    if ($time > 0 && $time < 40) $botSpeedWins++; 
                }
            }

            // 4. Memory
            if ($mode === 'memory') {
                // В Memory total - это попытки. Correct - это пары.
                // Идеальная игра: кол-во попыток (кликов/2) == кол-во пар
                // Но проще: если mistakes пусто или correct == total (если логика сохранения такая)
                // Для упрощения считаем: если ошибок 0 и пар >= 6
                $mistakesCount = isset($r['mistakes']) ? count($r['mistakes']) : 0;
                if ($mistakesCount === 0 && $correct >= 6) $memoryPerfectGames++;
            }

            // 5. Rain
            if ($mode === 'rain') {
                if ($correct > $rainMaxDrops) $rainMaxDrops = $correct;
                if ($time > $rainLongestTime) $rainLongestTime = $time;
            }

            // 6. Builder
            if ($mode === 'builder') {
                // Идеальная игра в конструкторе
                $mistakesCount = isset($r['mistakes']) ? count($r['mistakes']) : 0;
                if ($mistakesCount === 0 && $correct >= 1) $builderPerfectGames++;
            }
        }
        
        $accuracy = ($totalTotal > 0) ? ($totalCorrect / $totalTotal) : 0;
        $lastResult = end($results);

        // --- СПИСОК УСЛОВИЙ (BADGES) ---
        $conditions = [
            // Уровень 1: Прогресс
            'first_step' => fn() => $totalCorrect >= 1,
            'beginner' =>   fn() => $totalCorrect >= 50,
            'student' =>    fn() => $totalCorrect >= 100,
            'scholar' =>    fn() => $totalCorrect >= 250,
            'master' =>     fn() => $totalCorrect >= 500,
            'grandmaster' => fn() => $totalCorrect >= 1000,
            'legend' =>     fn() => $totalCorrect >= 5000,
            'millionaire' => fn() => $totalCorrect >= 10000,

            // Уровень 2: Точность
            'careful' =>    fn() => $totalTotal >= 50 && $accuracy >= 0.9,
            'sniper' =>     fn() => $perfectTests >= 1,
            'sharpshooter'=> fn() => $perfectTests >= 5,
            'aimbot' =>     fn() => $perfectTests >= 20,
            
            // Уровень 3: Выживание
            'survivor_1' => fn() => $survivalScoreMax >= 20,
            'survivor_2' => fn() => $survivalScoreMax >= 50,
            'survivor_3' => fn() => $survivalScoreMax >= 100,
            
            // Уровень 4: Скорость (Flash)
            'flash_1' =>    function() use ($results) { foreach($results as $r) if(($r['mode']=='test') && $r['time_spent']<=60 && $r['correct']>=15) return true; return false; },
            'flash_2' =>    function() use ($results) { foreach($results as $r) if(($r['mode']=='test') && $r['time_spent']<=60 && $r['correct']>=25) return true; return false; },
            'flash_3' =>    function() use ($results) { foreach($results as $r) if(($r['mode']=='test') && $r['time_spent']<=60 && $r['correct']>=40) return true; return false; },

            // Уровень 5: Сложность
            'hard_worker' => function() use ($results) { foreach($results as $r) if(($r['difficulty']??'')=='hard' && $r['correct']>=10) return true; return false; },
            'math_god' =>    function() use ($results) { foreach($results as $r) if(($r['difficulty']??'')=='hard' && $r['correct']>=50 && $r['correct']==$r['total']) return true; return false; },

            // Уровень 6: Время суток
            'early_bird' => function() use ($lastResult) { if(!$lastResult) return false; $h=(int)date('H', strtotime($lastResult['timestamp'])); return $h>=5 && $h<8; },
            'night_owl' =>  function() use ($lastResult) { if(!$lastResult) return false; $h=(int)date('H', strtotime($lastResult['timestamp'])); return $h>=22 || $h<4; },
            'lunch_time' => function() use ($lastResult) { if(!$lastResult) return false; $h=(int)date('H', strtotime($lastResult['timestamp'])); return $h>=12 && $h<=13; },

            // Уровень 7: Экзамены
            'examiner_1' => fn() => $examsPassed >= 1,
            'examiner_5' => fn() => $examsPassed >= 5,
            
            // Уровень 8: Упорство
            'persistent' => fn() => count($results) >= 50,
            'addicted' =>   fn() => count($results) >= 200,
            'lucky_7' =>    function() use ($lastResult) { if(!$lastResult) return false; return $lastResult['correct'] == 77; },
            'cyborg' =>     fn() => $totalCorrect >= 1000 && $accuracy >= 0.98,
            'teacher_pet' => fn() => $perfectHardTests >= 10,

            // --- НОВЫЕ РЕЖИМЫ ---
            
            // True/False
            'lie_detector' => fn() => $trueFalseMaxStreak >= 20,
            'judge_dredd' =>  fn() => $trueFalseMaxStreak >= 50, // Упростили до стрика, т.к. очки = стрик примерно
            // trust_nobody - объединили с judge_dredd для упрощения

            // Find X
            'sherlock' =>         fn() => $findXTotal >= 50,
            'reverse_engineer' => fn() => $findXHardPerfect >= 10,
            
            // Bot Battle
            'machine_breaker' =>  fn() => $botWinsHard >= 1,
            'speed_racer' =>      fn() => $botSpeedWins >= 1,

            // Memory
            'elephant_memory' =>  fn() => $memoryPerfectGames >= 1,
            'combo_king' =>       fn() => $memoryPerfectGames >= 5, // Условно 5 идеальных игр

            // Rain
            'umbrella' =>     fn() => $rainLongestTime >= 180, // 3 минуты
            'storm_chaser' => fn() => $rainMaxDrops >= 50,

            // Builder
            'architect' =>     fn() => $builderPerfectGames >= 20,
            'perfectionist' => fn() => $builderPerfectGames >= 5 // 5 идеальных подряд (упрощенно до всего)
        ];

        foreach ($conditions as $badge => $condition) {
            if (!in_array($badge, $currentBadges) && $condition()) {
                $newBadges[] = $badge;
                $currentBadges[] = $badge;
            }
        }

        if (!empty($newBadges)) {
            $this->db->updateStudentData($studentId, ['achievements' => $currentBadges]);
        }
        
        return $newBadges;
    }

    // Расчет редкости ачивок (для отображения % у кого есть)
    public function getAchievementRarity($classId) {
        $students = $this->db->getStudents();
        $classStudents = 0;
        $counts = [];

        foreach ($students as $s) {
            if ($s['class_id'] == $classId) {
                $classStudents++;
                if (!empty($s['achievements'])) {
                    foreach ($s['achievements'] as $badge) {
                        if (!isset($counts[$badge])) $counts[$badge] = 0;
                        $counts[$badge]++;
                    }
                }
            }
        }

        $rarity = [];
        if ($classStudents > 0) {
            foreach ($counts as $badge => $count) {
                $rarity[$badge] = round(($count / $classStudents) * 100);
            }
        }
        return $rarity;
    }

    // ==========================================
    // --- ЭКСПОРТ (CSV) ---
    // ==========================================

    // Экспорт общей таблицы результатов (FIXED DATE ISSUE)
    public function generateCsvExport() {
        $results = $this->db->getResults();
        $students = $this->db->getStudents();
        $classes = $this->db->getClasses();

        $sMap = []; foreach($students as $s) $sMap[$s['id']] = $s;
        $cMap = []; foreach($classes as $c) $cMap[$c['id']] = $c['name'];

        $output = "\xEF\xBB\xBF"; // BOM
        $output .= "ID;Дата;Класс;Ученик;Режим;Сложность;Верно;Всего;Оценка\n";

        foreach ($results as $r) {
            $student = $sMap[$r['student_id']] ?? null;
            if (!$student) continue;
            
            $className = $cMap[$student['class_id']] ?? 'Unknown';
            
            // Расчет оценки
            $scoreNum = ($r['total'] > 0) ? round(($r['correct']/$r['total'])*5, 1) : 0;
            // FIX: Заменяем точку на запятую, чтобы Excel не думал, что "4.5" это "4 мая" или "04.апр"
            $scoreStr = str_replace('.', ',', (string)$scoreNum);
            
            $line = [
                $r['id'], 
                $r['timestamp'], 
                $className, 
                $student['name'],
                $r['mode'], 
                $r['difficulty'] ?? 'easy', 
                $r['correct'], 
                $r['total'], 
                $scoreStr // Используем строку с запятой
            ];
            
            $output .= implode(';', $line) . "\n";
        }
        
        return $output;
    }

    // Экспорт статистики одного ученика (FIXED DATE ISSUE)
    public function generateStudentCsvExport($studentId) {
        $results = $this->db->getStudentResults($studentId);
        $student = $this->db->getStudentById($studentId);
        
        $output = "\xEF\xBB\xBF";
        $output .= "Ученик: " . ($student['name'] ?? 'Unknown') . "\n";
        $output .= "Дата;Режим;Сложность;Верно;Всего;Оценка;Ошибки\n";

        foreach ($results as $r) {
            $scoreNum = ($r['total'] > 0) ? round(($r['correct']/$r['total'])*5, 1) : 0;
            // FIX: Замена точки на запятую
            $scoreStr = str_replace('.', ',', (string)$scoreNum);

            $mistakesStr = '';
            if (isset($r['mistakes']) && is_array($r['mistakes'])) {
                foreach ($r['mistakes'] as $m) $mistakesStr .= "{$m['q']}!={$m['user']} ";
            }

            $line = [
                $r['timestamp'], 
                $r['mode'], 
                $r['difficulty'] ?? 'easy',
                $r['correct'], 
                $r['total'], 
                $scoreStr, 
                $mistakesStr
            ];
            $output .= implode(';', $line) . "\n";
        }
        return $output;
    }

    // НОВЫЙ МЕТОД: Экспорт списка учеников с ПИН-кодами
    public function generateStudentListCsv($classId = 'all') {
        $students = $this->db->getStudents();
        $classes = $this->db->getClasses();
        $cMap = []; 
        foreach($classes as $c) $cMap[$c['id']] = $c['name'];

        $output = "\xEF\xBB\xBF";
        $output .= "Класс;ФИО Ученика;ПИН-код;ID\n";

        foreach ($students as $s) {
            // Фильтрация по классу
            if ($classId !== 'all' && $s['class_id'] != $classId) {
                continue;
            }

            $className = $cMap[$s['class_id']] ?? 'Unknown';
            // ПИН код оборачиваем в ="...", чтобы Excel не съел ведущие нули (0042 -> 42)
            // Но для совместимости просто строкой. В CSV часто ="1234" работает.
            $pinStr = '="' . $s['pin'] . '"';

            $line = [
                $className,
                $s['name'],
                $pinStr,
                $s['id']
            ];
            $output .= implode(';', $line) . "\n";
        }

        return $output;
    }
}