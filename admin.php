<?php
// admin.php
session_start();

// Проверка авторизации администратора
$isAdmin = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель управления | Умножение</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="assets/css/mobile.css">
</head>
<body class="admin-body">

    <?php if (!$isAdmin): ?>
        <!-- ЭКРАН ВХОДА (Если не авторизован) -->
        <div class="admin-login-wrapper">
            <div class="glass-panel login-panel">
                <div class="login-header">
                    <h2>Вход для учителя</h2>
                    <p>Введите данные для доступа к панели управления</p>
                </div>
                <form id="adminLoginForm" onsubmit="return false;">
                    <div class="form-group">
                        <label>Логин</label>
                        <input type="text" name="login" placeholder="admin" required autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label>Пароль</label>
                        <input type="password" name="password" placeholder="••••••" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Войти в систему</button>
                    <div id="loginError" class="error-msg" style="display:none;"></div>
                </form>
            </div>
        </div>

        <script src="assets/js/utils.js"></script>
        <script>
            // Мини-скрипт для логина, чтобы не грузить тяжелый admin-app.js
            document.getElementById('adminLoginForm').addEventListener('submit', async (e) => {
                const formData = new FormData(e.target);
                const result = await postData('api.php', {
                    action: 'admin_login',
                    ...Object.fromEntries(formData)
                });
                
                if (result.success) {
                    window.location.reload();
                } else {
                    const err = document.getElementById('loginError');
                    err.innerText = result.error || 'Ошибка входа';
                    err.style.display = 'block';
                }
            });
        </script>

    <?php else: ?>
        <!-- ИНТЕРФЕЙС CRM (Если авторизован) -->
        <div id="adminApp" class="admin-layout">
            <!-- Сайдбар и контент будут загружены через views/admin_layout.php или сгенерированы JS -->
            <!-- Для старта поставим контейнер -->
            <div class="loader-container">
                <div class="spinner"></div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Для графиков -->
        <script src="assets/js/icons.js"></script>
        <script src="assets/js/utils.js"></script>
        <script src="assets/js/admin-app.js"></script>
    <?php endif; ?>

</body>
</html>