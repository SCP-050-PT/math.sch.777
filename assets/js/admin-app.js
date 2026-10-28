/* assets/js/admin-app.js */

const Admin = {
    data: {
        classes: [],
        students: [],
        exams: [],
        admins: [] // Данные для вкладки администраторов
    },
    chartInstance: null,
    currentStudentId: null,

    init: async () => {
        const container = document.getElementById('adminApp');
        try {
            // Загружаем HTML структуру
            const response = await fetch('views/admin_layout.php');
            container.innerHTML = await response.text();
            
            Admin.bindEvents();
            Admin.loadDashboard();
            renderIcons();
        } catch (e) {
            console.error('Layout load failed', e);
        }
    },

    bindEvents: () => {
        // Табы навигации
        document.querySelectorAll('.nav-link[data-tab]').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                Admin.switchTab(link.dataset.tab);
                document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
                link.classList.add('active');
                if (window.innerWidth < 768) document.getElementById('sidebar').style.transform = 'translateX(-100%)';
            });
        });

        // Логаут
        document.getElementById('adminLogoutBtn').addEventListener('click', async () => {
            await postData('api.php', { action: 'logout' });
            window.location.reload();
        });

        // Сайдбар (мобилка)
        const sidebar = document.getElementById('sidebar');
        document.getElementById('burgerBtn').addEventListener('click', () => sidebar.style.transform = 'translateX(0)');
        document.getElementById('closeSidebar').addEventListener('click', () => sidebar.style.transform = 'translateX(-100%)');

        // --- Управление Классами ---
        document.getElementById('btnAddClass').addEventListener('click', () => Admin.openClassModal());
        document.getElementById('formClass').addEventListener('submit', Admin.handleClassSubmit);

        // --- Управление Учениками ---
        document.getElementById('btnAddStudent').addEventListener('click', () => Admin.openStudentModal());
        document.getElementById('formStudent').addEventListener('submit', Admin.handleStudentSubmit);
        document.getElementById('searchStudent').addEventListener('input', Admin.filterStudents);
        document.getElementById('filterClass').addEventListener('change', Admin.filterStudents);
        
        // Экспорт статистики одного ученика
        document.getElementById('btnExportStudent').addEventListener('click', () => {
            if(Admin.currentStudentId) window.location.href = `api.php?action=export_student_stats&id=${Admin.currentStudentId}`;
        });

        // Экспорт СПИСКА учеников (CSV с ПИН-кодами)
        document.getElementById('btnExportListAll').addEventListener('click', () => {
            window.location.href = 'api.php?action=export_student_list&class_id=all';
        });
        document.getElementById('btnExportListClass').addEventListener('click', () => {
            const classId = document.getElementById('filterClass').value;
            if (classId === 'all') {
                alert('Пожалуйста, выберите конкретный класс в фильтре для скачивания списка класса.');
                return;
            }
            window.location.href = `api.php?action=export_student_list&class_id=${classId}`;
        });

        // --- Экзамены ---
        document.getElementById('formStartExam').addEventListener('submit', Admin.handleStartExam);

        // --- Администраторы (НОВОЕ) ---
        document.getElementById('btnAddAdmin').addEventListener('click', () => {
            document.getElementById('modalAddAdmin').style.display = 'flex';
        });
        document.getElementById('formAddAdmin').addEventListener('submit', Admin.handleAddAdminSubmit);

        // --- Профиль ---
        document.getElementById('formAdminProfile').addEventListener('submit', async (e) => {
            e.preventDefault();
            const res = await postData('api.php', { action: 'admin_update_profile', ...Object.fromEntries(new FormData(e.target)) });
            if (res.success) alert('Профиль обновлен!');
            else alert('Ошибка: ' + (res.error || 'Unknown'));
        });

        // Экспорт глобальной статистики
        document.getElementById('exportStatsBtn').addEventListener('click', () => window.location.href = 'api.php?action=export_stats');

        // Закрытие модалок
        document.querySelectorAll('.close-modal').forEach(btn => {
            btn.addEventListener('click', () => document.querySelectorAll('.modal-overlay').forEach(m => m.style.display = 'none'));
        });
    },

    switchTab: (tabId) => {
        document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
        document.getElementById('tab-' + tabId).classList.add('active');
        
        const titles = { 
            'dashboard':'Обзор', 
            'classes':'Управление классами', 
            'students':'Список учеников', 
            'exams':'Экзамены', 
            'admins': 'Администраторы',
            'settings':'Настройки' 
        };
        document.getElementById('pageTitle').innerText = titles[tabId] || 'Раздел';

        if (tabId === 'classes') Admin.loadClasses();
        if (tabId === 'students') Admin.loadStudents();
        if (tabId === 'exams') Admin.loadExams();
        if (tabId === 'admins') Admin.loadAdmins();
    },

    // --- DASHBOARD ---
    loadDashboard: async () => {
        const res = await postData('api.php', { action: 'get_admin_dashboard' });
        if (!res.success) return;
        const s = res.global_stats;
        document.getElementById('dashTotalStudents').innerText = s.total_students;
        document.getElementById('dashTotalSolved').innerText = s.total_solved;
        document.getElementById('dashAvgScore').innerText = s.avg_score;
        const chartRes = await postData('api.php', { action: 'get_chart_data' });
        if (chartRes.success) Admin.renderChart(chartRes.data);
    },

    renderChart: (data) => {
        const ctx = document.getElementById('mainChart');
        if (!ctx) return;
        if (Admin.chartInstance) Admin.chartInstance.destroy();
        Admin.chartInstance = new Chart(ctx, {
            type: 'line',
            data: { labels: data.labels, datasets: [{ label: 'Решено', data: data.values, borderColor: '#6366f1', backgroundColor: 'rgba(99, 102, 241, 0.1)', tension: 0.4, fill: true }] },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
        });
    },

    // --- CLASSES ---
    loadClasses: async () => {
        const res = await postData('api.php', { action: 'get_admin_dashboard' });
        if (res.success) {
            Admin.data.classes = res.classes;
            const counts = {};
            res.students.forEach(s => counts[s.class_id] = (counts[s.class_id] || 0) + 1);
            const tbody = document.querySelector('#classesTable tbody');
            tbody.innerHTML = '';
            res.classes.forEach(c => {
                tbody.innerHTML += `<tr><td>${c.id}</td><td><strong>${escapeHtml(c.name)}</strong></td><td><span class="badge badge-success">${counts[c.id] || 0} уч.</span></td>
                <td><div class="action-btn-group"><div class="btn-action" onclick="Admin.openClassModal(${c.id}, '${escapeHtml(c.name)}')"><span class="icon-holder" data-icon="edit"></span></div><div class="btn-action delete" onclick="Admin.deleteClass(${c.id})"><span class="icon-holder" data-icon="delete"></span></div></div></td></tr>`;
            });
            renderIcons(tbody);
        }
    },
    openClassModal: (id = null, name = '') => {
        document.getElementById('classId').value = id || '';
        document.getElementById('className').value = name;
        document.getElementById('modalClassTitle').innerText = id ? 'Редактировать' : 'Новый класс';
        document.getElementById('modalClass').style.display = 'flex';
    },
    handleClassSubmit: async (e) => {
        e.preventDefault();
        const id = document.getElementById('classId').value;
        const res = await postData('api.php', { action: 'manage_class', method: id ? 'edit' : 'add', id, name: document.getElementById('className').value });
        if (res.success) { document.getElementById('modalClass').style.display = 'none'; Admin.loadClasses(); }
    },
    deleteClass: async (id) => { if (confirm('Удалить класс и всех его учеников?')) { await postData('api.php', { action: 'manage_class', method: 'delete', id }); Admin.loadClasses(); } },

    // --- STUDENTS ---
    loadStudents: async () => {
        const res = await postData('api.php', { action: 'get_admin_dashboard' });
        if (res.success) {
            Admin.data.students = res.students;
            Admin.data.classes = res.classes;
            Admin.renderStudentsTable(res.students);
            
            // Заполняем фильтр классов, сохраняя текущий выбор
            const fs = document.getElementById('filterClass');
            const currentVal = fs.value;
            fs.innerHTML = '<option value="all">Все классы</option>';
            res.classes.forEach(c => fs.innerHTML += `<option value="${c.id}">${c.name}</option>`);
            if (currentVal) fs.value = currentVal; 
        }
    },
    renderStudentsTable: (students) => {
        const tbody = document.querySelector('#studentsTable tbody');
        tbody.innerHTML = '';
        const cMap = {}; Admin.data.classes.forEach(c => cMap[c.id] = c.name);
        students.forEach(s => {
            const bc = s.achievements ? s.achievements.length : 0;
            tbody.innerHTML += `<tr><td style="cursor:pointer" onclick="Admin.openStudentDetails(${s.id})"><div style="font-weight:600; color:#4f46e5">${escapeHtml(s.name)}</div></td>
            <td><span class="badge badge-warning">${escapeHtml(cMap[s.class_id] || '???')}</span></td>
            <td><code style="background:#eee; padding:2px 5px; border-radius:4px; font-weight:700">${s.pin}</code></td>
            <td>${bc > 0 ? `<span style="color:#f59e0b">★ ${bc}</span>` : '<span style="color:#ccc">-</span>'}</td>
            <td><div class="action-btn-group"><div class="btn-action delete" onclick="Admin.deleteStudent(${s.id})"><span class="icon-holder" data-icon="delete"></span></div></div></td></tr>`;
        });
        renderIcons(tbody);
    },
    filterStudents: () => {
        const s = document.getElementById('searchStudent').value.toLowerCase();
        const c = document.getElementById('filterClass').value;
        const f = Admin.data.students.filter(st => st.name.toLowerCase().includes(s) && (c === 'all' || st.class_id == c));
        Admin.renderStudentsTable(f);
    },
    openStudentModal: () => {
        const s = document.getElementById('studentClassSelect'); s.innerHTML = '';
        Admin.data.classes.forEach(c => s.innerHTML += `<option value="${c.id}">${c.name}</option>`);
        document.getElementById('studentName').value = ''; document.getElementById('studentId').value = '';
        document.getElementById('modalStudent').style.display = 'flex';
    },
    handleStudentSubmit: async (e) => {
        e.preventDefault();
        const res = await postData('api.php', { action: 'manage_student', method: 'add', name: document.getElementById('studentName').value, class_id: document.getElementById('studentClassSelect').value });
        if (res.success) { document.getElementById('modalStudent').style.display = 'none'; alert(`Ученик создан.\nPIN-код: ${res.student.pin}`); Admin.loadStudents(); }
    },
    deleteStudent: async (id) => { if (confirm('Удалить ученика?')) { await postData('api.php', { action: 'manage_student', method: 'delete', id }); Admin.loadStudents(); } },
    
    // --- DETAILS ---
    openStudentDetails: async (id) => {
        Admin.currentStudentId = id;
        const res = await postData('api.php', { action: 'get_student_details', id });
        if (!res.success) return;
        const s = res.student; const st = res.stats;
        
        document.getElementById('detailStudentName').innerText = s.name;
        document.getElementById('detailSolved').innerText = st.total_solved;
        document.getElementById('detailAccuracy').innerText = st.accuracy + '%';
        
        // История
        const hc = document.getElementById('detailHistory'); 
        hc.innerHTML = '';

        // Ачивки (Кратко)
        const badgesContainer = document.getElementById('detailBadges');
        if (s.achievements && s.achievements.length > 0) {
            badgesContainer.innerHTML = `<div style="margin-bottom:15px; font-size:0.9em; color:#f59e0b;"><b>Наград:</b> ${s.achievements.length} шт.</div>`;
        } else {
            badgesContainer.innerHTML = '';
        }

        const modeMap = { 
            'free': 'Свободный', 'test': 'На время', 'survival': 'Выживание', 'exam': 'ЭКЗАМЕН',
            'true_false': 'Верю/Не верю', 'find_x': 'Поиск X', 'bot_battle': 'Битва с ботом',
            'memory': 'Пары', 'rain': 'Дождь', 'builder': 'Конструктор'
        };

        if (st.history.length === 0) hc.innerHTML = '<p style="color:#999">История пуста</p>';
        else st.history.forEach(h => {
            const modeRu = modeMap[h.mode] || h.mode;
            let mHtml = '';
            // Вывод ошибок если есть
            if (h.mistakes && h.mistakes.length) {
                // Если mistakes - массив объектов
                if (typeof h.mistakes[0] === 'object') {
                    mHtml = `<div style="margin-top:5px; font-size:0.85em; color:#ef4444;"><b>Ошибки:</b> ${h.mistakes.map(m => `${m.q}≠${m.user}`).join(', ')}</div>`;
                }
            }
            
            hc.innerHTML += `<div style="border-bottom:1px solid #eee; padding:8px 0;">
                <div style="display:flex; justify-content:space-between">
                    <span>${modeRu}</span>
                    <b>${h.correct}/${h.total}</b>
                </div>
                <div style="font-size:0.8em; color:#999">${h.timestamp}</div>
                ${mHtml}
            </div>`;
        });
        document.getElementById('modalStudentDetails').style.display = 'flex';
    },

    // --- EXAMS ---
    loadExams: async () => {
        const res = await postData('api.php', { action: 'get_admin_dashboard' });
        if (res.success) {
            Admin.data.classes = res.classes;
            Admin.data.exams = res.exams || []; 
            Admin.renderExamsTables();
        }
    },
    renderExamsTables: () => {
        const diffMap = { 'easy': 'Легко', 'medium': 'Средне', 'hard': 'Сложно' };

        // Активные
        const activeTbody = document.querySelector('#examsTable tbody');
        activeTbody.innerHTML = '';
        Admin.data.classes.forEach(c => {
            const activeExam = Admin.data.exams.find(e => e.class_id == c.id && e.status === 'active');
            if (activeExam) {
                activeTbody.innerHTML += `<tr><td><strong>${escapeHtml(c.name)}</strong></td><td><span class="badge badge-success">АКТИВЕН</span></td><td>${diffMap[activeExam.difficulty]}</td><td>${activeExam.duration} мин.</td><td><button class="btn btn-secondary btn-small" style="color:red; border-color:red" onclick="Admin.stopExam(${c.id})">Завершить</button></td></tr>`;
            } else {
                activeTbody.innerHTML += `<tr><td><strong>${escapeHtml(c.name)}</strong></td><td><span class="badge badge-gray">Не запущен</span></td><td>-</td><td>-</td><td><button class="btn btn-primary btn-small" onclick="Admin.openStartExamModal(${c.id}, '${escapeHtml(c.name)}')">Начать экзамен</button></td></tr>`;
            }
        });

        // История
        const historyTbody = document.querySelector('#historyExamsTable tbody');
        historyTbody.innerHTML = '';
        const history = Admin.data.exams.filter(e => e.status === 'finished').reverse();
        const cMap = {}; Admin.data.classes.forEach(c => cMap[c.id] = c.name);
        
        history.forEach(h => {
            historyTbody.innerHTML += `<tr><td>${escapeHtml(cMap[h.class_id] || '???')}</td><td>${h.start_time.split(' ')[0]}</td><td>${diffMap[h.difficulty]}</td><td><button class="btn btn-secondary btn-small" onclick="Admin.openExamResults(${h.class_id}, '${escapeHtml(cMap[h.class_id])}')">Результаты</button></td></tr>`;
        });
    },
    
    openStartExamModal: (cid, cname) => {
        document.getElementById('examClassId').value = cid;
        document.getElementById('examClassName').innerText = `Для класса: ${cname}`;
        document.getElementById('modalStartExam').style.display = 'flex';
    },
    handleStartExam: async (e) => {
        e.preventDefault();
        const res = await postData('api.php', { action: 'manage_exam', method: 'start', ...Object.fromEntries(new FormData(e.target)) });
        if (res.success) { document.getElementById('modalStartExam').style.display = 'none'; Admin.loadExams(); }
    },
    stopExam: async (cid) => {
        if (confirm('Завершить?')) { await postData('api.php', { action: 'manage_exam', method: 'stop', class_id: cid }); Admin.loadExams(); }
    },
    
    openExamResults: async (classId, className) => {
        const res = await postData('api.php', { action: 'get_exam_results', class_id: classId });
        if (!res.success) return;
        
        document.getElementById('examResultTitle').innerText = `Класс: ${className}`;
        const tbody = document.querySelector('#examResultsTable tbody');
        tbody.innerHTML = '';
        
        if (res.report.length === 0) {
            tbody.innerHTML = '<tr><td colspan="3" style="text-align:center">Нет результатов</td></tr>';
        } else {
            res.report.forEach(r => {
                let badgeClass = 'badge-gray';
                if(r.score == 5) badgeClass = 'badge-success';
                if(r.score == 4) badgeClass = 'badge-success'; 
                if(r.score == 3) badgeClass = 'badge-warning';
                if(r.score == 2) badgeClass = 'badge-danger';
                
                tbody.innerHTML += `<tr><td>${escapeHtml(r.name)}</td><td><b>${r.correct}/${r.total}</b></td><td><span class="badge ${badgeClass}">${r.score}</span></td></tr>`;
            });
        }
        document.getElementById('modalExamResults').style.display = 'flex';
    },

    // --- ADMINS (НОВЫЙ ФУНКЦИОНАЛ) ---
    loadAdmins: async () => {
        const res = await postData('api.php', { action: 'manage_admin', method: 'get' });
        if (res.success) {
            Admin.data.admins = res.admins;
            const tbody = document.querySelector('#adminsTable tbody');
            tbody.innerHTML = '';
            res.admins.forEach(a => {
                // Если админов > 1, показываем кнопку удаления
                const delBtn = (Admin.data.admins.length > 1) 
                    ? `<div class="btn-action delete" onclick="Admin.deleteAdmin(${a.id})"><span class="icon-holder" data-icon="delete"></span></div>` 
                    : '<span style="color:#ccc; font-size:0.8em">Основной</span>';

                tbody.innerHTML += `<tr>
                    <td>${a.id}</td>
                    <td><b>${escapeHtml(a.login)}</b></td>
                    <td><div class="action-btn-group" style="justify-content:flex-end">${delBtn}</div></td>
                </tr>`;
            });
            renderIcons(tbody);
        }
    },
    handleAddAdminSubmit: async (e) => {
        e.preventDefault();
        const fd = new FormData(e.target);
        const res = await postData('api.php', { action: 'manage_admin', method: 'add', ...Object.fromEntries(fd) });
        if (res.success) {
            document.getElementById('modalAddAdmin').style.display = 'none';
            e.target.reset();
            Admin.loadAdmins();
        } else {
            alert('Ошибка: ' + res.error);
        }
    },
    deleteAdmin: async (id) => {
        if (confirm('Удалить этого администратора? Он больше не сможет войти.')) {
            const res = await postData('api.php', { action: 'manage_admin', method: 'delete', id: id });
            if (res.success) Admin.loadAdmins();
            else alert('Ошибка: ' + res.error);
        }
    }
};

window.Admin = Admin;
document.addEventListener('DOMContentLoaded', Admin.init);