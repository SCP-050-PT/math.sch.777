<!-- views/admin_layout.php -->

<div class="admin-wrapper">

    <!-- САЙДБАР (Навигация) -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo-area">
                <span class="icon-holder logo-icon" style="color: var(--primary-color);">
                    <!-- SVG иконка школы -->
                    <svg viewBox="0 0 24 24"><path d="M5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82zM12 3L1 9l11 6 9-4.91V17h2V9L12 3z"/></svg>
                </span>
                <span class="logo-text">Школьный Портал</span>
            </div>
            <button class="close-sidebar-btn" id="closeSidebar">✕</button>
        </div>

        <nav class="sidebar-nav">
            <a href="#" class="nav-link active" data-tab="dashboard">
                <span class="icon-holder" data-icon="dashboard"></span>
                <span>Обзор</span>
            </a>
            <a href="#" class="nav-link" data-tab="classes">
                <span class="icon-holder" data-icon="class"></span>
                <span>Классы</span>
            </a>
            <a href="#" class="nav-link" data-tab="students">
                <span class="icon-holder" data-icon="students"></span>
                <span>Ученики</span>
            </a>
            <a href="#" class="nav-link" data-tab="exams">
                <span class="icon-holder" data-icon="trophy"></span>
                <span>Экзамены</span>
            </a>
            <a href="#" class="nav-link" data-tab="admins">
                <span class="icon-holder" data-icon="user"></span>
                <span>Администраторы</span>
            </a>
            <a href="#" class="nav-link" data-tab="settings">
                <span class="icon-holder" data-icon="settings"></span>
                <span>Мой профиль</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <button class="nav-link logout-link" id="adminLogoutBtn">
                <span class="icon-holder" data-icon="logout"></span>
                <span>Выход из системы</span>
            </button>
        </div>
    </aside>

    <!-- ОСНОВНОЙ КОНТЕНТ -->
    <div class="main-content">
        
        <!-- Верхняя полоска -->
        <header class="top-bar">
            <button class="burger-btn" id="burgerBtn">
                <span class="icon-holder" data-icon="menu"></span>
            </button>
            <h2 class="page-title" id="pageTitle">Обзор</h2>
            <div class="admin-profile">
                <span class="admin-name">Учитель</span>
                <div class="admin-avatar">У</div>
            </div>
        </header>

        <!-- ОБЛАСТЬ ДИНАМИЧЕСКОГО КОНТЕНТА -->
        <div id="content-container" class="content-container">
            
            <!-- Вкладка 1: ДАШБОРД -->
            <div id="tab-dashboard" class="tab-pane active">
                <div class="stats-cards-grid">
                    <div class="stat-card">
                        <div class="stat-icon bg-blue">👨‍🎓</div>
                        <div class="stat-info">
                            <h3>Всего учеников</h3>
                            <p class="stat-number" id="dashTotalStudents">...</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon bg-green">✅</div>
                        <div class="stat-info">
                            <h3>Решено верно</h3>
                            <p class="stat-number" id="dashTotalSolved">...</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon bg-purple">⭐</div>
                        <div class="stat-info">
                            <h3>Средний балл</h3>
                            <p class="stat-number" id="dashAvgScore">...</p>
                        </div>
                    </div>
                </div>

                <div class="charts-section">
                    <div class="chart-header">
                        <h3>Активность обучения</h3>
                        <button class="btn btn-secondary btn-small" id="exportStatsBtn">
                            <span class="icon-holder" data-icon="trophy"></span> Экспорт общего отчета
                        </button>
                    </div>
                    <div class="chart-container">
                        <canvas id="mainChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Вкладка 2: КЛАССЫ -->
            <div id="tab-classes" class="tab-pane">
                <div class="toolbar">
                    <button class="btn btn-primary" id="btnAddClass">
                        <span class="icon-holder" data-icon="plus"></span> Добавить класс
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="custom-table" id="classesTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Название класса</th>
                                <th>Кол-во учеников</th>
                                <th style="text-align:right">Действия</th>
                            </tr>
                        </thead>
                        <tbody><!-- JS render --></tbody>
                    </table>
                </div>
            </div>

            <!-- Вкладка 3: УЧЕНИКИ -->
            <div id="tab-students" class="tab-pane">
                <div class="toolbar">
                    <div class="search-box">
                        <span class="icon-holder" data-icon="search"></span>
                        <input type="text" id="searchStudent" placeholder="Поиск по имени...">
                    </div>
                    <select id="filterClass" class="custom-select" style="width: auto; min-width: 200px;">
                        <option value="all">Все классы</option>
                        <!-- JS options -->
                    </select>
                    <button class="btn btn-primary" id="btnAddStudent">
                        <span class="icon-holder" data-icon="plus"></span> Добавить
                    </button>
                </div>

                <!-- Блок экспорта списков -->
                <div style="margin-bottom: 20px; display: flex; gap: 10px; flex-wrap: wrap;">
                    <button class="btn btn-secondary btn-small" id="btnExportListAll">
                        📄 Скачать список всех учеников (CSV)
                    </button>
                    <button class="btn btn-secondary btn-small" id="btnExportListClass">
                        📄 Скачать список выбранного класса (CSV)
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="custom-table" id="studentsTable">
                        <thead>
                            <tr>
                                <th>Имя и Фамилия</th>
                                <th>Класс</th>
                                <th>Код доступа</th>
                                <th>Достижения</th>
                                <th style="text-align:right">Действия</th>
                            </tr>
                        </thead>
                        <tbody><!-- JS render --></tbody>
                    </table>
                </div>
            </div>
            
            <!-- Вкладка 4: ЭКЗАМЕНЫ -->
            <div id="tab-exams" class="tab-pane">
                 <!-- Блок активных экзаменов -->
                 <div class="glass-panel" style="padding: 30px; background: white; border-radius: 20px; margin-bottom: 30px;">
                     <h3>Активные экзамены</h3>
                     <p style="color:#666; margin-bottom: 20px;">Управление текущими запущенными экзаменами.</p>
                     
                     <div class="table-responsive">
                        <table class="custom-table" id="examsTable">
                            <thead>
                                <tr>
                                    <th>Класс</th>
                                    <th>Статус</th>
                                    <th>Сложность</th>
                                    <th>Время</th>
                                    <th style="text-align:right">Действие</th>
                                </tr>
                            </thead>
                            <tbody><!-- JS render --></tbody>
                        </table>
                     </div>
                 </div>

                 <!-- Блок истории экзаменов -->
                 <div class="glass-panel" style="padding: 30px; background: white; border-radius: 20px;">
                     <h3>История экзаменов</h3>
                     <p style="color:#666; margin-bottom: 20px;">Результаты завершенных экзаменов.</p>
                     
                     <div class="table-responsive">
                        <table class="custom-table" id="historyExamsTable">
                            <thead>
                                <tr>
                                    <th>Класс</th>
                                    <th>Дата запуска</th>
                                    <th>Сложность</th>
                                    <th style="text-align:right">Действие</th>
                                </tr>
                            </thead>
                            <tbody><!-- JS render --></tbody>
                        </table>
                     </div>
                 </div>
            </div>

            <!-- Вкладка 5: АДМИНИСТРАТОРЫ (НОВОЕ) -->
            <div id="tab-admins" class="tab-pane">
                <div class="glass-panel" style="padding: 30px; background: white; border-radius: 20px; margin-bottom: 30px;">
                     <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                         <div>
                             <h3>Управление администраторами</h3>
                             <p style="color:#666;">Добавление и удаление учителей с доступом к панели.</p>
                         </div>
                         <button class="btn btn-primary" id="btnAddAdmin">
                            <span class="icon-holder" data-icon="plus"></span> Добавить учителя
                        </button>
                     </div>
                     
                     <div class="table-responsive">
                        <table class="custom-table" id="adminsTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Логин</th>
                                    <th style="text-align:right">Действие</th>
                                </tr>
                            </thead>
                            <tbody><!-- JS render --></tbody>
                        </table>
                     </div>
                 </div>
            </div>

            <!-- Вкладка 6: НАСТРОЙКИ (ПРОФИЛЬ) -->
            <div id="tab-settings" class="tab-pane">
                 <div class="glass-panel" style="padding: 30px; background: white; border-radius: 20px; max-width: 500px;">
                     <h3>Мой профиль</h3>
                     <p style="color:#666; margin-bottom: 20px;">Смена данных для входа текущего аккаунта.</p>
                     
                     <form id="formAdminProfile">
                         <div class="form-group">
                             <label>Логин</label>
                             <input type="text" name="login" required placeholder="Ваш логин">
                         </div>
                         <div class="form-group">
                             <label>Новый пароль</label>
                             <input type="password" name="password" placeholder="••••••">
                             <small style="color:#999">Оставьте пустым, чтобы не менять пароль</small>
                         </div>
                         <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                     </form>
                 </div>
            </div>

        </div>
    </div>
</div>

<!-- === МОДАЛЬНЫЕ ОКНА === -->

<!-- 1. МОДАЛКА: КЛАСС -->
<div id="modalClass" class="modal-overlay">
    <div class="modal-content">
        <h3 id="modalClassTitle" style="margin-bottom: 20px;">Новый класс</h3>
        <form id="formClass">
            <input type="hidden" name="id" id="classId">
            <div class="form-group">
                <label>Название класса (например, 2 "А")</label>
                <input type="text" name="name" id="className" required placeholder="Введите название">
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary close-modal">Отмена</button>
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </div>
        </form>
    </div>
</div>

<!-- 2. МОДАЛКА: УЧЕНИК -->
<div id="modalStudent" class="modal-overlay">
    <div class="modal-content">
        <h3 id="modalStudentTitle" style="margin-bottom: 20px;">Добавить ученика</h3>
        <form id="formStudent">
            <input type="hidden" name="id" id="studentId">
            <div class="form-group">
                <label>ФИО Ученика</label>
                <input type="text" name="name" id="studentName" required placeholder="Иванов Иван">
            </div>
            <div class="form-group">
                <label>Класс</label>
                <select name="class_id" id="studentClassSelect" class="custom-select" required></select>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary close-modal">Отмена</button>
                <button type="submit" class="btn btn-primary">Создать</button>
            </div>
        </form>
    </div>
</div>

<!-- 3. МОДАЛКА: ЗАПУСК ЭКЗАМЕНА -->
<div id="modalStartExam" class="modal-overlay">
    <div class="modal-content">
        <h3>Начать экзамен</h3>
        <p id="examClassName" style="margin-bottom:15px; color:#666;"></p>
        <form id="formStartExam">
            <input type="hidden" name="class_id" id="examClassId">
            
            <div class="form-group">
                <label>Сложность примеров</label>
                <select name="difficulty" class="custom-select">
                    <option value="easy">Легко (2 × 2)</option>
                    <option value="medium">Средне (2 × 2 + 5)</option>
                    <option value="hard">Сложно (2 × 2 + 3 × 4)</option>
                </select>
            </div>

            <div class="form-group">
                <label>Длительность (минут)</label>
                <select name="duration" class="custom-select">
                    <option value="1">1 минута</option>
                    <option value="3">3 минуты</option>
                    <option value="5">5 минут</option>
                    <option value="10">10 минут</option>
                    <option value="20">20 минут</option>
                </select>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn btn-secondary close-modal">Отмена</button>
                <button type="submit" class="btn btn-primary">Запустить</button>
            </div>
        </form>
    </div>
</div>

<!-- 4. МОДАЛКА: ДЕТАЛИ УЧЕНИКА (СТАТИСТИКА + ЭКСПОРТ) -->
<div id="modalStudentDetails" class="modal-overlay">
    <div class="modal-content" style="max-width: 600px;">
        <h3 id="detailStudentName" style="margin-bottom: 20px;">Карточка ученика</h3>
        
        <div style="margin-bottom: 20px; display:flex; gap:15px; justify-content:center">
            <div class="stat-pill" style="background:#eef2ff; padding:5px 15px; border-radius:15px">
                Решено: <b id="detailSolved">0</b>
            </div>
            <div class="stat-pill" style="background:#ecfdf5; padding:5px 15px; border-radius:15px">
                Точность: <b id="detailAccuracy">0%</b>
            </div>
        </div>
        
        <div id="detailBadges"></div>

        <div style="margin-bottom:15px;">
            <button id="btnExportStudent" class="btn btn-secondary btn-small btn-block">
                <span class="icon-holder" data-icon="dashboard"></span> Скачать полную статистику (CSV)
            </button>
        </div>

        <h4 style="text-align:left; margin-bottom:10px">Последние тренировки</h4>
        <div style="max-height: 300px; overflow-y: auto; text-align: left; background: #f9fafb; padding: 10px; border-radius: 10px;" id="detailHistory">
            <!-- JS RENDER -->
        </div>

        <div class="modal-actions" style="margin-top:20px">
            <button type="button" class="btn btn-secondary close-modal">Закрыть</button>
        </div>
    </div>
</div>

<!-- 5. МОДАЛКА: РЕЗУЛЬТАТЫ ЗАВЕРШЕННОГО ЭКЗАМЕНА -->
<div id="modalExamResults" class="modal-overlay">
    <div class="modal-content" style="max-width: 600px;">
        <h3 style="margin-bottom: 15px;">Результаты экзамена</h3>
        <p id="examResultTitle" style="color:#666; margin-bottom:20px;">Класс ...</p>
        
        <div class="table-responsive" style="max-height: 400px; overflow-y:auto;">
             <table class="custom-table" id="examResultsTable">
                 <thead>
                    <tr>
                        <th>Ученик</th>
                        <th>Верно</th>
                        <th>Оценка</th>
                    </tr>
                 </thead>
                 <tbody>
                    <!-- JS RENDER -->
                 </tbody>
             </table>
        </div>

        <div class="modal-actions" style="margin-top:20px">
            <button type="button" class="btn btn-secondary close-modal">Закрыть</button>
        </div>
    </div>
</div>

<!-- 6. МОДАЛКА: ДОБАВИТЬ АДМИНИСТРАТОРА -->
<div id="modalAddAdmin" class="modal-overlay">
    <div class="modal-content">
        <h3 style="margin-bottom: 20px;">Новый учитель</h3>
        <form id="formAddAdmin">
            <div class="form-group">
                <label>Логин</label>
                <input type="text" name="login" required placeholder="Логин">
            </div>
            <div class="form-group">
                <label>Пароль</label>
                <input type="password" name="password" required placeholder="Пароль">
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary close-modal">Отмена</button>
                <button type="submit" class="btn btn-primary">Добавить</button>
            </div>
        </form>
    </div>
</div>