<?php
// index.php
session_start();

// Определяем, авторизован ли ученик, чтобы передать это в JS
$isLoggedIn = isset($_SESSION['student_id']) ? 'true' : 'false';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Тренажер Таблицы Умножения</title>
    
    <!-- Шрифты -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Стили -->
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/student.css">
    <link rel="stylesheet" href="assets/css/mobile.css">
    
    <!-- Иконка -->
    <link rel="icon" type="image/svg+xml" href="assets/img/favicon.svg">
</head>
<body>

    <!-- Основной контейнер приложения -->
    <div id="app" class="student-app">
        <!-- Контент будет рендериться через JS (см. views/student_layout.php в будущем) -->
        <div class="loader-screen">
            <div class="spinner"></div>
            <p>Загрузка тренажера...</p>
        </div>
    </div>

    <!-- Глобальные переменные для JS -->
    <script>
        window.isLoggedIn = <?php echo $isLoggedIn; ?>;
    </script>

    <!-- Скрипты -->
    <script src="assets/js/icons.js"></script>
    <script src="assets/js/utils.js"></script>
    <script src="assets/js/student-app.js"></script>
</body>
</html>