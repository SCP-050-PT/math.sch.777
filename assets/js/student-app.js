/* assets/js/student-app.js */

// --- КОНФИГУРАЦИЯ ДОСТИЖЕНИЙ (30+ ШТУК) ---
const achievementsConfig = {
    // Уровень 1: Прогресс
    'first_step': { name: 'Первый шаг', icon: '🦶', desc: 'Реши свой первый пример' },
    'beginner': { name: 'Новичок', icon: '🐣', desc: 'Реши 50 примеров верно' },
    'student': { name: 'Ученик', icon: '🎒', desc: 'Реши 100 примеров верно' },
    'scholar': { name: 'Ученый', icon: '📘', desc: 'Реши 250 примеров верно' },
    'master': { name: 'Мастер', icon: '🥋', desc: 'Реши 500 примеров верно' },
    'grandmaster': { name: 'Грандмастер', icon: '🧘', desc: 'Реши 1000 примеров верно' },
    'legend': { name: 'Легенда', icon: '👑', desc: 'Реши 5000 примеров верно' },
    'millionaire': { name: 'Миллионер', icon: '💎', desc: 'Реши 10000 примеров верно' },

    // Уровень 2: Точность
    'careful': { name: 'Аккуратный', icon: '🧐', desc: 'Держи точность 90% (мин. 50 решений)' },
    'sniper': { name: 'Снайпер', icon: '🎯', desc: '100% точность в тесте (>10 вопросов)' },
    'sharpshooter': { name: 'Стрелок', icon: '🏹', desc: '5 идеальных тестов подряд' },
    'aimbot': { name: 'Аимбот', icon: '🤖', desc: '20 идеальных тестов' },

    // Уровень 3: Выживание
    'survivor_1': { name: 'Выживший', icon: '🛡', desc: 'Набери 20 очков в режиме Выживания' },
    'survivor_2': { name: 'Воин', icon: '⚔️', desc: 'Набери 50 очков в режиме Выживания' },
    'survivor_3': { name: 'Бессмертный', icon: '🧛', desc: 'Набери 100 очков в режиме Выживания' },

    // Уровень 4: Скорость
    'flash_1': { name: 'Бегун', icon: '👟', desc: '15 верных ответов за 1 минуту' },
    'flash_2': { name: 'Флэш', icon: '⚡', desc: '25 верных ответов за 1 минуту' },
    'flash_3': { name: 'Скорость света', icon: '🚀', desc: '40 верных ответов за 1 минуту' },

    // Уровень 5: Сложность
    'hard_worker': { name: 'Трудяга', icon: '🔨', desc: '10 верных в Сложном режиме' },
    'math_god': { name: 'Бог Математики', icon: '⚛️', desc: '50 верных в Сложном режиме без ошибок' },

    // Уровень 6: Время
    'early_bird': { name: 'Жаворонок', icon: '☀️', desc: 'Зайди в тренажер с 5:00 до 8:00 утра' },
    'night_owl': { name: 'Сова', icon: '🦉', desc: 'Зайди в тренажер после 22:00 или до 4:00' },
    'lunch_time': { name: 'Обед', icon: '🍔', desc: 'Решай примеры в обеденное время (12:00-13:00)' },

    // Уровень 7: Экзамены
    'examiner_1': { name: 'Студент', icon: '📜', desc: 'Сдай 1 экзамен' },
    'examiner_5': { name: 'Профессор', icon: '🎓', desc: 'Сдай 5 экзаменов' },

    // Уровень 8: Специальные
    'persistent': { name: 'Упорный', icon: '✊', desc: 'Запусти тренировку 50 раз' },
    'addicted': { name: 'Фанатик', icon: '🤪', desc: 'Запусти тренировку 200 раз' },
    'lucky_7': { name: 'Счастливчик', icon: '🍀', desc: 'Заверши тест с результатом ровно 77' },
    'cyborg': { name: 'Киборг', icon: '🦾', desc: '1000 очков и точность 98%' },
    'teacher_pet': { name: 'Любимчик', icon: '🍎', desc: '10 сложных тестов на отлично' },

    // НОВЫЕ АЧИВКИ (ДЛЯ НОВЫХ РЕЖИМОВ)
    'lie_detector': { name: 'Детектив', icon: '🕵️', desc: '20 верных ответов подряд в "Верю-Не верю"' },
    'judge_dredd': { name: 'Судья', icon: '⚖️', desc: 'Набери 50 очков в "Верю-Не верю"' },
    'sherlock': { name: 'Шерлок', icon: '🔎', desc: 'Реши 50 уравнений в "Поиск X"' },
    'reverse_engineer': { name: 'Инженер', icon: '📐', desc: '10 уравнений сложном режиме без ошибок' },
    'machine_breaker': { name: 'Восстание машин', icon: '🦾', desc: 'Победить бота на сложности Hard' },
    'speed_racer': { name: 'Гонщик', icon: '🏎', desc: 'Обогнать бота очень быстро' },
    'elephant_memory': { name: 'Слоновья память', icon: '🐘', desc: 'Пройти Memory без ошибок' },
    'combo_king': { name: 'Король комбо', icon: '👑', desc: '5 идеальных игр в Memory' },
    'umbrella': { name: 'Зонтик', icon: '☂️', desc: 'Продержаться 3 минуты под Дождем' },
    'storm_chaser': { name: 'Шторм', icon: '🌪', desc: '50 капель за одну игру' },
    'architect': { name: 'Архитектор', icon: '🏛', desc: '20 успешных сборок в Конструкторе' },
    'perfectionist': { name: 'Перфекционист', icon: '✨', desc: '5 идеальных сборок подряд' }
};

const App = {
    state: {
        // Общие настройки
        mode: null,
        difficulty: 'easy',
        timeLimit: 1,
        activeExamData: null,
        classesMap: {}, 
        rarityMap: {},
        
        // Игровое состояние
        timer: null,
        timeLeft: 0,
        score: { correct: 0, wrong: 0, total: 0 },
        isGameActive: false,
        lives: 3,

        // Логика текущего вопроса
        currentAnswerStr: '',
        currentCorrectValue: 0, 
        currentProblemData: null,
        
        // --- Состояния спец. режимов ---
        // True/False
        tfCorrectBool: false, 
        
        // Bot
        botTimer: null,
        botHp: 100,
        playerHp: 100,
        
        // Memory
        memoryCards: [],
        memoryFlipped: [],
        memoryLocked: false,
        memoryPairsFound: 0,
        
        // Rain
        rainLoopId: null,
        rainDrops: [],
        rainSpeed: 1,
        rainLastSpawn: 0,
        
        // Builder
        builderSelected: [],
        builderCorrectOptions: []
    },

    init: async () => {
        const appContainer = document.getElementById('app');
        
        const res = await postData('api.php', { action: 'get_initial_data' });
        if (res.success && res.classes) {
            res.classes.forEach(c => App.state.classesMap[c.id] = c.name);
        }

        if (!window.isLoggedIn) {
            App.renderLogin(appContainer, res.classes);
        } else {
            await App.loadMainInterface(appContainer);
        }
    },

    renderLogin: async (container, classes) => {
        let classOptions = '<option value="" disabled selected>Выберите свой класс</option>';
        if (classes) {
            classes.forEach(c => classOptions += `<option value="${c.id}">${c.name}</option>`);
        }
        
        container.innerHTML = `
            <div class="login-wrapper fade-in">
                <div class="login-card">
                    <div class="login-icon">🎓</div>
                    <h2 style="margin-bottom:20px;">Вход</h2>
                    <form id="loginForm">
                        <div class="form-group"><select name="class_id" class="custom-select" required>${classOptions}</select></div>
                        <div class="form-group"><input type="tel" name="pin" placeholder="PIN" class="pin-input" maxlength="4" required></div>
                        <button type="submit" class="btn btn-primary btn-block">Поехали!</button>
                        <div id="loginError" class="text-red" style="margin-top:15px;"></div>
                    </form>
                </div>
            </div>`;
            
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const result = await postData('api.php', { action: 'student_login', ...Object.fromEntries(new FormData(e.target)) });
            if (result.success) window.location.reload();
            else document.getElementById('loginError').innerText = result.error;
        });
    },

    loadMainInterface: async (container) => {
        try {
            const response = await fetch('views/student_layout.php');
            container.innerHTML = await response.text();
            
            const profileRes = await postData('api.php', { action: 'get_student_profile' });
            if (profileRes.success) {
                App.updateHeader(profileRes.profile);
                App.state.rarityMap = profileRes.rarity || {};
                
                if (profileRes.active_exam) {
                    App.state.activeExamData = profileRes.active_exam;
                    App.unlockExamMode(); 
                }
            }
            App.bindEvents();
            renderIcons();
        } catch (e) { console.error(e); }
    },

    updateHeader: (profile) => {
        document.getElementById('displayUserName').innerText = profile.name;
        document.getElementById('userAvatar').innerText = profile.name.charAt(0).toUpperCase();
        const className = App.state.classesMap[profile.class_id] || ('Класс ' + profile.class_id);
        document.getElementById('displayUserClass').innerText = className;
        document.getElementById('userStars').innerText = profile.achievements ? profile.achievements.length : 0;
    },
    
    unlockExamMode: () => {
        const card = document.getElementById('examCard');
        if (!card) return;
        card.style.display = 'flex';
        card.classList.remove('locked');
        card.style.cursor = 'pointer';
        card.querySelector('p').innerText = 'ЭКЗАМЕН ЗАПУЩЕН!';
        
        card.style.animation = 'pulse 2s infinite';
        const style = document.createElement('style');
        style.innerHTML = `@keyframes pulse { 0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(99, 102, 241, 0.4); } 70% { transform: scale(1.02); box-shadow: 0 0 0 10px rgba(99, 102, 241, 0); } 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(99, 102, 241, 0); } }`;
        document.head.appendChild(style);
    },

    bindEvents: () => {
        // Карточки режимов
        document.querySelectorAll('.game-card').forEach(card => {
            card.addEventListener('click', () => {
                const mode = card.dataset.mode;
                if(card.classList.contains('locked') && mode !== 'exam') return;
                
                if (mode === 'exam') {
                    if(card.classList.contains('locked')) alert('Экзамен еще не начался');
                    else App.startExamMode();
                } else {
                    App.openSetupModal(mode);
                }
            });
        });

        // Селекторы сложности/времени
        document.querySelectorAll('.difficulty-selector .sel-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.difficulty-selector .sel-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                App.state.difficulty = btn.dataset.val;
            });
        });

        document.querySelectorAll('.time-selector .sel-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.time-selector .sel-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                App.state.timeLimit = parseInt(btn.dataset.val);
            });
        });

        // Кнопки управления
        document.getElementById('btnStartGameAction').addEventListener('click', () => {
            document.getElementById('gameSetupModal').style.display = 'none';
            App.launchGame();
        });
        document.getElementById('btnBackToMenu').addEventListener('click', App.stopGame);
        document.getElementById('btnProfile').addEventListener('click', App.openProfile);
        document.getElementById('btnBackFromProfile').addEventListener('click', () => App.switchView('view-menu'));
        document.getElementById('btnLogout').addEventListener('click', async () => {
            await postData('api.php', { action: 'logout' });
            window.location.reload();
        });

        // Виртуальная клавиатура
        document.querySelectorAll('.v-key').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault(); 
                App.handleInput(btn.dataset.key);
            });
        });

        // Рестарт
        document.getElementById('btnRestart').addEventListener('click', () => {
            document.getElementById('resultModal').style.display = 'none';
            if (App.state.mode === 'exam') App.switchView('view-menu');
            else App.launchGame(); 
        });
        document.getElementById('btnToMenu').addEventListener('click', () => {
            document.getElementById('resultModal').style.display = 'none';
            App.switchView('view-menu');
        });

        // True/False кнопки
        document.getElementById('btnTrue').addEventListener('click', () => App.checkTrueFalse(true));
        document.getElementById('btnFalse').addEventListener('click', () => App.checkTrueFalse(false));
        
        // Builder кнопка
        document.getElementById('btnBuilderCheck').addEventListener('click', () => App.checkBuilder());

        // ФИЗИЧЕСКАЯ КЛАВИАТУРА
        document.addEventListener('keydown', (e) => {
            if (!App.state.isGameActive) return;

            if (e.key >= '0' && e.key <= '9') App.handleInput(e.key);
            if (e.key === 'Backspace') App.handleInput('del');
            if (e.key === 'Enter') {
                e.preventDefault();
                if (App.state.mode === 'builder') App.checkBuilder();
                else App.handleInput('enter');
            }
            
            if (App.state.mode === 'true_false') {
                if (e.key === 'ArrowLeft') App.checkTrueFalse(true);
                if (e.key === 'ArrowRight') App.checkTrueFalse(false);
            }
        });
    },

    switchView: (viewId) => {
        document.querySelectorAll('.view-section').forEach(el => el.classList.remove('active'));
        document.getElementById(viewId).classList.add('active');
    },

    openSetupModal: (mode) => {
        App.state.mode = mode;
        App.state.difficulty = 'easy';
        App.state.timeLimit = 1;
        
        document.querySelectorAll('.sel-btn').forEach(b => b.classList.remove('active'));
        document.querySelector('.difficulty-selector .sel-btn[data-val="easy"]').classList.add('active');
        document.querySelector('.time-selector .sel-btn[data-val="1"]').classList.add('active');

        const timeSection = document.getElementById('timeSelectorSection');
        const diffSection = document.getElementById('diffSelectorSection');
        
        const timedModes = ['test', 'rain', 'true_false', 'builder', 'memory'];
        if (timedModes.includes(mode)) timeSection.style.display = 'block';
        else timeSection.style.display = 'none';
        
        diffSection.style.display = 'block';

        document.getElementById('gameSetupModal').style.display = 'flex';
    },

    startExamMode: () => {
        const exam = App.state.activeExamData;
        if (!exam) return;
        if (!confirm('Начать экзамен? Время ограничено: ' + exam.duration + ' мин.')) return;
        App.state.mode = 'exam';
        App.state.difficulty = exam.difficulty;
        App.state.timeLimit = parseInt(exam.duration);
        App.launchGame();
    },

    // --- ЗАПУСК ИГРЫ (ОБЩИЙ) ---
    launchGame: () => {
        App.state.score = { correct: 0, wrong: 0, total: 0 };
        App.state.mistakesLog = [];
        App.state.isGameActive = true;
        App.state.lives = 3;
        
        if (App.state.timer) clearInterval(App.state.timer);
        if (App.state.botTimer) clearInterval(App.state.botTimer);
        if (App.state.rainLoopId) cancelAnimationFrame(App.state.rainLoopId);

        document.getElementById('gameProgress').style.width = '0%';
        document.getElementById('gameCorrectCount').innerText = '0';
        document.getElementById('gameWrongCount').innerText = '0';
        
        // Очистка поля ввода дождя
        const rainDisplay = document.getElementById('rainInputDisplay');
        if(rainDisplay) rainDisplay.innerText = '';
        
        App.setupGameUI(App.state.mode);

        const mode = App.state.mode;
        const timerDisplay = document.getElementById('gameTimer');
        const livesDisplay = document.getElementById('gameLives');
        
        timerDisplay.style.display = 'none';
        livesDisplay.style.display = 'none';

        if (['test', 'exam', 'rain', 'true_false', 'builder', 'memory'].includes(mode)) {
            App.state.timeLeft = App.state.timeLimit * 60;
            timerDisplay.style.display = 'block';
            App.startTimer();
        } 
        
        if (mode === 'survival') {
            livesDisplay.style.display = 'block';
            App.updateLivesUI();
        }

        if (mode === 'bot_battle') {
            App.state.botHp = 100;
            App.state.playerHp = 100;
            App.updateBotUI();
            App.startBotLogic();
        }

        // Дождь: Замедленный старт
        if (mode === 'rain') {
            App.state.rainDrops = [];
            // Скорость сильно снижена: 0.3 - 0.7
            const baseSpeed = (App.state.difficulty === 'easy' ? 0.3 : (App.state.difficulty === 'medium' ? 0.5 : 0.7));
            App.state.rainSpeed = baseSpeed;
            App.startRainLoop();
        }
        
        if (mode === 'memory') {
            App.setupMemory();
        }

        App.switchView('view-game');
        
        if (mode !== 'memory' && mode !== 'rain') {
            App.nextQuestion();
        }
    },

    stopGame: () => {
        if (App.state.mode === 'exam') {
            if(!confirm('В режиме экзамена выход невозможен без сохранения результата. Сдаться?')) return;
            App.finishGame();
            return;
        }
        App.state.isGameActive = false;
        if (App.state.timer) clearInterval(App.state.timer);
        if (App.state.botTimer) clearInterval(App.state.botTimer);
        if (App.state.rainLoopId) cancelAnimationFrame(App.state.rainLoopId);
        
        App.switchView('view-menu');
    },

    setupGameUI: (mode) => {
        document.querySelectorAll('.mode-specific-ui').forEach(el => el.style.display = 'none');
        
        const board = document.getElementById('standardGameBoard');
        const keyboard = document.getElementById('virtualKeyboard');
        board.style.display = 'block';
        keyboard.style.display = 'grid'; 

        if (mode === 'bot_battle') {
            document.getElementById('botBattleUI').style.display = 'flex';
        }
        else if (mode === 'true_false') {
            document.getElementById('trueFalseControls').style.display = 'flex';
            keyboard.style.display = 'none'; 
        }
        else if (mode === 'memory') {
            board.style.display = 'none'; 
            keyboard.style.display = 'none';
            document.getElementById('memoryUI').style.display = 'block';
        }
        else if (mode === 'rain') {
            board.style.display = 'none'; 
            document.getElementById('rainUI').style.display = 'block';
        }
        else if (mode === 'builder') {
            board.style.display = 'none';
            keyboard.style.display = 'none';
            document.getElementById('builderUI').style.display = 'block';
            App.setupBuilder();
        }
    },

    startTimer: () => {
        const display = document.getElementById('gameTimer');
        const updateDisplay = () => {
            const m = Math.floor(App.state.timeLeft / 60).toString().padStart(2, '0');
            const s = (App.state.timeLeft % 60).toString().padStart(2, '0');
            display.innerText = `${m}:${s}`;
        };
        updateDisplay();

        App.state.timer = setInterval(() => {
            App.state.timeLeft--;
            updateDisplay();
            if (App.state.timeLeft <= 0) App.finishGame();
        }, 1000);
    },

    nextQuestion: () => {
        App.state.currentAnswerStr = ''; 
        
        const mode = App.state.mode;
        const diff = App.state.difficulty;
        const rand = (min, max) => Math.floor(Math.random() * (max - min + 1)) + min;

        let a, b, c, d, result, str, html;
        
        if (diff === 'easy') {
            a = rand(2, 9); b = rand(2, 9);
            result = a * b;
            str = `${a} × ${b}`;
            html = `<div class="digit-box">${a}</div> <div class="operator-box">×</div> <div class="digit-box">${b}</div>`;
        } else if (diff === 'medium') {
            a = rand(2, 9); b = rand(2, 9); c = rand(1, 20);
            if (Math.random() > 0.5) { result = a * b + c; str = `${a} × ${b} + ${c}`; html = `${a} × ${b} + ${c}`; }
            else { result = a * b - c; str = `${a} × ${b} - ${c}`; html = `${a} × ${b} - ${c}`; }
            html = `<div class="digit-box" style="font-size:0.8em">${str}</div>`;
        } else { 
            a = rand(2, 9); b = rand(2, 9); c = rand(2, 9); d = rand(2, 9);
            result = a * b + c * d;
            str = `${a} × ${b} + ${c} × ${d}`;
            html = `<div class="digit-box" style="font-size:0.65em">${str}</div>`;
        }

        App.state.currentCorrectValue = result;

        if (mode === 'true_false') {
            const isCorrect = Math.random() > 0.5;
            let displayResult = result;
            if (!isCorrect) {
                let offset = rand(1, 5) * (Math.random() > 0.5 ? 1 : -1);
                if (result + offset < 0) offset = 1;
                displayResult = result + offset;
                if (Math.random() > 0.5 && a > 2) displayResult = (a - 1) * b;
            }
            App.state.tfCorrectBool = isCorrect;
            
            const container = document.getElementById('problemContainer');
            container.innerHTML = `<div style="display:flex; flex-direction:column; align-items:center;">
                <div style="font-size:0.8em; color:#666; margin-bottom:5px;">Верно ли?</div>
                <div>${str} = <span style="color:var(--primary-color)">${displayResult}</span></div>
            </div>`;
            return;
        }

        if (mode === 'find_x') {
            if (diff === 'easy') {
                const hideA = Math.random() > 0.5;
                if (hideA) {
                    App.state.currentCorrectValue = a;
                    html = `<div class="answer-placeholder empty" id="answer-box">?</div> <div class="operator-box">×</div> <div class="digit-box">${b}</div> <div class="equals-box">=</div> <div class="digit-box">${result}</div>`;
                } else {
                    App.state.currentCorrectValue = b;
                    html = `<div class="digit-box">${a}</div> <div class="operator-box">×</div> <div class="answer-placeholder empty" id="answer-box">?</div> <div class="equals-box">=</div> <div class="digit-box">${result}</div>`;
                }
            } else {
                a = rand(2, 9); b = rand(2, 9); result = a*b;
                App.state.currentCorrectValue = a;
                html = `<div class="answer-placeholder empty" id="answer-box">?</div> <div class="operator-box">×</div> <div class="digit-box">${b}</div> <div class="equals-box">=</div> <div class="digit-box">${result}</div>`;
            }
        }
        else {
            html += ` <div class="equals-box">=</div> <div class="answer-placeholder empty" id="answer-box">?</div>`;
        }

        document.getElementById('problemContainer').innerHTML = html;
        App.state.currentProblemStr = str;
    },

    handleInput: (key) => {
        // --- RAIN LOGIC ---
        if (App.state.mode === 'rain') {
            const display = document.getElementById('rainInputDisplay');
            if (key === 'del') {
                App.state.currentAnswerStr = App.state.currentAnswerStr.slice(0, -1);
            }
            else if (key === 'enter') return; 
            else if (App.state.currentAnswerStr.length < 3) {
                App.state.currentAnswerStr += key;
            }
            
            // ОБНОВЛЕНИЕ ЭКРАНА ВВОДА В РЕЖИМЕ ДОЖДЯ
            if (display) display.innerText = App.state.currentAnswerStr;
            
            App.checkRainInput();
            return;
        }
        
        // --- STANDARD LOGIC ---
        const box = document.getElementById('answer-box');
        if (!box) return; 

        if (key === 'del') {
            App.state.currentAnswerStr = App.state.currentAnswerStr.slice(0, -1);
        } else if (key === 'enter') {
            if (App.state.currentAnswerStr !== '') App.checkAnswer();
            return;
        } else {
            if (App.state.currentAnswerStr.length < 4) {
                App.state.currentAnswerStr += key;
            }
        }
        
        box.innerText = App.state.currentAnswerStr || '?';
        if (App.state.currentAnswerStr === '') box.classList.add('empty');
        else box.classList.remove('empty');
    },

    checkAnswer: async () => {
        const userAnswer = parseInt(App.state.currentAnswerStr);
        const correctAnswer = App.state.currentCorrectValue;
        const box = document.getElementById('answer-box');
        
        App.state.score.total++;

        if (userAnswer === correctAnswer) {
            App.state.score.correct++;
            document.getElementById('gameCorrectCount').innerText = App.state.score.correct;
            box.classList.add('success');
            
            const progress = (App.state.score.correct % 10) * 10;
            document.getElementById('gameProgress').style.width = progress + '%';
            
            if (App.state.mode === 'bot_battle') {
                App.damageBot();
            }

            await wait(400); 
            App.nextQuestion();
        } else {
            App.state.score.wrong++;
            App.state.mistakesLog.push({
                q: App.state.currentProblemStr,
                user: userAnswer,
                correct: correctAnswer
            });
            document.getElementById('gameWrongCount').innerText = App.state.score.wrong;
            box.classList.add('error');
            await wait(500);
            box.classList.remove('error');
            
            if (App.state.mode === 'survival') {
                App.state.lives--;
                App.updateLivesUI();
                if (App.state.lives <= 0) App.finishGame();
                else App.nextQuestion(); 
            } else {
                App.nextQuestion();
            }
        }
    },

    checkTrueFalse: async (userBool) => {
        const isCorrect = (userBool === App.state.tfCorrectBool);
        const container = document.getElementById('problemContainer');
        App.state.score.total++;

        if (isCorrect) {
            App.state.score.correct++;
            document.getElementById('gameCorrectCount').innerText = App.state.score.correct;
            container.style.background = '#d1fae5'; 
        } else {
            App.state.score.wrong++;
            document.getElementById('gameWrongCount').innerText = App.state.score.wrong;
            container.style.background = '#fee2e2'; 
            App.state.mistakesLog.push({ q: 'TF: ' + App.state.currentProblemStr, user: userBool ? 'T' : 'F', correct: !userBool ? 'T' : 'F' });
        }
        
        setTimeout(() => {
            container.style.background = 'transparent';
            App.nextQuestion();
        }, 300);
    },

    // --- LOGIC: BOT BATTLE ---
    startBotLogic: () => {
        let interval = 4000; 
        if (App.state.difficulty === 'medium') interval = 3000;
        if (App.state.difficulty === 'hard') interval = 2000;

        App.state.botTimer = setInterval(() => {
            if (!App.state.isGameActive) return;
            App.state.playerHp -= 10;
            App.updateBotUI();
            
            const status = document.getElementById('botStatusText');
            status.innerText = "Бот атакует! 💥";
            status.style.color = "red";
            setTimeout(() => { status.innerText = "Бот думает..."; status.style.color = "#666"; }, 800);

            if (App.state.playerHp <= 0) App.finishGame();
        }, interval);
    },
    damageBot: () => {
        App.state.botHp -= 10; 
        App.updateBotUI();
        if (App.state.botHp <= 0) App.finishGame();
    },
    updateBotUI: () => {
        document.getElementById('hpPlayer').style.width = Math.max(0, App.state.playerHp) + '%';
        document.getElementById('hpBot').style.width = Math.max(0, App.state.botHp) + '%';
    },

    // --- LOGIC: MEMORY ---
    setupMemory: () => {
        const grid = document.getElementById('memoryGrid');
        grid.innerHTML = '';
        App.state.memoryPairsFound = 0;
        App.state.score.total = 0; // Сброс ходов
        document.getElementById('memoryMoves').innerText = '0';
        
        App.state.memoryFlipped = [];
        App.state.memoryLocked = false;
        
        const count = 8; 
        
        const pairs = [];
        const rand = (min, max) => Math.floor(Math.random() * (max - min + 1)) + min;
        
        for(let i=0; i<count; i++) {
            const a = rand(2, 9), b = rand(2, 9);
            const val = a * b;
            const id = i;
            pairs.push({ id: id, type: 'prob', content: `${a}×${b}`, val: val });
            pairs.push({ id: id, type: 'ans', content: `${val}`, val: val });
        }
        
        pairs.sort(() => Math.random() - 0.5);
        
        pairs.forEach((card, idx) => {
            const el = document.createElement('div');
            el.className = 'memory-card';
            el.dataset.idx = idx;
            el.innerHTML = `
                <div class="memory-card-inner">
                    <div class="memory-front">?</div>
                    <div class="memory-back">${card.content}</div>
                </div>
            `;
            el.addEventListener('click', () => App.handleMemoryClick(el, card));
            grid.appendChild(el);
        });
        App.state.memoryCards = pairs;
    },
    handleMemoryClick: (el, card) => {
        if (App.state.memoryLocked) return;
        if (el.classList.contains('flipped') || el.classList.contains('matched')) return;
        
        el.classList.add('flipped');
        App.state.memoryFlipped.push({ el, card });
        
        if (App.state.memoryFlipped.length === 2) {
            App.state.memoryLocked = true;
            App.state.score.total++; 
            // ОБНОВЛЕНИЕ СЧЕТЧИКА ХОДОВ НА ЭКРАНЕ
            document.getElementById('memoryMoves').innerText = App.state.score.total;
            
            const [c1, c2] = App.state.memoryFlipped;
            const isMatch = (c1.card.id === c2.card.id);
            
            if (isMatch) {
                setTimeout(() => {
                    c1.el.classList.add('matched');
                    c2.el.classList.add('matched');
                    App.state.memoryFlipped = [];
                    App.state.memoryLocked = false;
                    App.state.score.correct++;
                    App.state.memoryPairsFound++;
                    document.getElementById('gameCorrectCount').innerText = App.state.memoryPairsFound;
                    
                    if (App.state.memoryPairsFound === 8) App.finishGame();
                }, 500);
            } else {
                setTimeout(() => {
                    c1.el.classList.remove('flipped');
                    c2.el.classList.remove('flipped');
                    App.state.memoryFlipped = [];
                    App.state.memoryLocked = false;
                }, 1000);
            }
        }
    },

    // --- LOGIC: RAIN (С ANIMATION & SCORE FIX) ---
    startRainLoop: () => {
        const container = document.getElementById('rainCanvasArea');
        container.innerHTML = '';
        const now = Date.now();
        App.state.rainLastSpawn = now;
        
        const loop = () => {
            if (!App.state.isGameActive) return;
            App.updateRain();
            App.state.rainLoopId = requestAnimationFrame(loop);
        };
        App.state.rainLoopId = requestAnimationFrame(loop);
    },
    updateRain: () => {
        const now = Date.now();
        const container = document.getElementById('rainCanvasArea');
        const height = container.clientHeight;
        const width = container.clientWidth;

        // Спавн реже (раз в 2-3 секунды с учетом скорости)
        if (now - App.state.rainLastSpawn > 2500 / App.state.rainSpeed) {
            const a = Math.floor(Math.random()*8)+2;
            const b = Math.floor(Math.random()*8)+2;
            const drop = document.createElement('div');
            drop.className = 'rain-drop';
            drop.innerText = `${a}×${b}`;
            drop.dataset.val = a*b;
            drop.style.left = Math.floor(Math.random() * (width - 60)) + 'px';
            drop.style.top = '0px';
            container.appendChild(drop);
            
            App.state.rainDrops.push({ el: drop, val: a*b, y: 0 });
            App.state.rainLastSpawn = now;
        }

        // Движение
        for (let i = App.state.rainDrops.length - 1; i >= 0; i--) {
            const d = App.state.rainDrops[i];
            d.y += 1 * App.state.rainSpeed; // Скорость падения
            d.el.style.top = d.y + 'px';

            // Если коснулся земли
            if (d.y > height - 40) {
                // Ошибка + удаление
                d.el.remove();
                App.state.rainDrops.splice(i, 1);
                
                App.state.score.wrong++;
                document.getElementById('gameWrongCount').innerText = App.state.score.wrong;
                
                // Визуальный эффект ошибки (тряска экрана?)
                container.style.boxShadow = "inset 0 -10px 20px rgba(239, 68, 68, 0.5)";
                setTimeout(() => container.style.boxShadow = "none", 300);
            }
        }
    },
    checkRainInput: () => {
        const val = parseInt(App.state.currentAnswerStr);
        const idx = App.state.rainDrops.findIndex(d => d.val === val);
        
        if (idx !== -1) {
            const d = App.state.rainDrops[idx];
            const dropEl = d.el;
            const container = document.getElementById('rainCanvasArea');
            
            // 1. Очищаем ввод сразу
            App.state.currentAnswerStr = '';
            document.getElementById('rainInputDisplay').innerText = '';

            // 2. Создаем снаряд
            const proj = document.createElement('div');
            proj.className = 'rain-projectile';
            // Старт снизу по центру
            const startX = container.clientWidth / 2;
            const startY = container.clientHeight - 40;
            proj.style.left = startX + 'px';
            proj.style.top = startY + 'px';
            container.appendChild(proj);

            // 3. Анимация полета (reflow)
            proj.getBoundingClientRect();
            proj.style.left = (dropEl.offsetLeft + dropEl.clientWidth/2) + 'px';
            proj.style.top = (dropEl.offsetTop + dropEl.clientHeight/2) + 'px';

            // 4. Попадание
            setTimeout(() => {
                proj.remove(); // Удаляем снаряд
                
                // Эффект взрыва на примере
                dropEl.classList.add('rain-explosion');
                dropEl.innerText = '💥'; 
                
                // Удаляем из логики игры
                App.state.rainDrops.splice(idx, 1);
                App.state.score.correct++;
                document.getElementById('gameCorrectCount').innerText = App.state.score.correct;
                
                // Удаляем DOM элемент после анимации взрыва
                setTimeout(() => dropEl.remove(), 400);
                
            }, 300); // 300ms длительность полета (совпадает с CSS transition)
        }
    },

    // --- LOGIC: BUILDER ---
    setupBuilder: () => {
        const grid = document.getElementById('builderGrid');
        grid.innerHTML = '';
        App.state.builderSelected = [];
        
        const targets = [12, 16, 18, 20, 24, 28, 30, 32, 36, 40, 42, 48];
        const target = targets[Math.floor(Math.random() * targets.length)];
        document.getElementById('builderTarget').innerText = target;
        
        const correctPairs = [];
        for(let i=2; i<=9; i++) {
            if (target % i === 0) {
                const j = target / i;
                if (j >= 2 && j <= 12) correctPairs.push(`${i}×${j}`);
            }
        }
        
        const wrongPairs = [];
        while(wrongPairs.length < 5) {
            const a = Math.floor(Math.random()*8)+2;
            const b = Math.floor(Math.random()*8)+2;
            if (a*b !== target) wrongPairs.push(`${a}×${b}`);
        }
        
        let options = [...correctPairs];
        while(options.length < 6) {
            options.push(wrongPairs.pop());
        }
        options.sort(() => Math.random() - 0.5);
        options = options.slice(0, 6); 
        
        App.state.builderCorrectOptions = correctPairs;

        options.forEach(opt => {
            const btn = document.createElement('div');
            btn.className = 'builder-option';
            btn.innerText = opt;
            btn.addEventListener('click', () => {
                btn.classList.toggle('selected');
                const txt = btn.innerText;
                if (App.state.builderSelected.includes(txt)) {
                    App.state.builderSelected = App.state.builderSelected.filter(t => t !== txt);
                } else {
                    App.state.builderSelected.push(txt);
                }
            });
            grid.appendChild(btn);
        });
    },
    checkBuilder: async () => {
        const grid = document.getElementById('builderGrid');
        const options = Array.from(grid.children).map(el => el.innerText);
        
        const correctsOnScreen = options.filter(opt => {
             const [a, b] = opt.split('×').map(Number);
             const target = parseInt(document.getElementById('builderTarget').innerText);
             return a*b === target;
        });

        const selected = App.state.builderSelected;
        
        const isWin = (selected.length === correctsOnScreen.length) && 
                      selected.every(s => correctsOnScreen.includes(s));
        
        App.state.score.total++;
        
        if (isWin) {
            App.state.score.correct++;
            document.getElementById('gameCorrectCount').innerText = App.state.score.correct;
            grid.style.background = '#d1fae5';
            await wait(500);
            grid.style.background = 'transparent';
            App.setupBuilder();
        } else {
            App.state.score.wrong++;
            document.getElementById('gameWrongCount').innerText = App.state.score.wrong;
            grid.style.background = '#fee2e2';
            await wait(500);
            grid.style.background = 'transparent';
            App.setupBuilder();
        }
    },


    // --- UPDATE LIVES UI (Survival) ---
    updateLivesUI: () => {
        let hearts = '';
        for(let i=0; i<App.state.lives; i++) hearts += '❤';
        document.getElementById('gameLives').innerText = hearts;
    },

    // --- ЗАВЕРШЕНИЕ ИГРЫ ---
    finishGame: async () => {
        App.state.isGameActive = false;
        if (App.state.timer) clearInterval(App.state.timer);
        if (App.state.botTimer) clearInterval(App.state.botTimer);
        if (App.state.rainLoopId) cancelAnimationFrame(App.state.rainLoopId);

        const payload = {
            action: 'save_result',
            mode: App.state.mode,
            difficulty: App.state.difficulty,
            correct: App.state.score.correct,
            total: App.state.score.total, 
            mistakes: App.state.mistakesLog,
            time_spent: (['test','exam','rain'].includes(App.state.mode)) ? (App.state.timeLimit * 60 - App.state.timeLeft) : 0
        };

        const res = await postData('api.php', payload);

        const modal = document.getElementById('resultModal');
        document.getElementById('resCorrect').innerText = App.state.score.correct;
        document.getElementById('resWrong').innerText = App.state.score.wrong;
        
        let title = "Тренировка завершена";
        let icon = "🏁";
        
        if (App.state.mode === 'exam') { title = "Экзамен сдан!"; icon="📝"; }
        else if (App.state.mode === 'bot_battle') {
            if (App.state.botHp <= 0) { title = "Победа над Ботом!"; icon="🏆"; }
            else { title = "Бот победил..."; icon="🤖"; }
        }
        else if (App.state.score.correct > 15 && App.state.score.wrong === 0) { title = "Великолепно!"; icon="🔥"; }
        
        document.getElementById('resultTitle').innerText = title;
        document.getElementById('resultIcon').innerText = icon;

        const extraRow = document.getElementById('resExtraRow');
        extraRow.style.display = 'none';

        if (res.success && res.achievements && res.achievements.length > 0) {
            setTimeout(() => alert("🎖 Получено новое достижение!"), 500);
        }
        modal.style.display = 'flex';
    },

    openProfile: async () => {
        App.switchView('view-profile');
        const res = await postData('api.php', { action: 'get_student_profile' });
        if (!res.success) return;
        const p = res.profile;
        const s = res.stats;
        
        document.getElementById('profileName').innerText = p.name;
        document.getElementById('profileAvatarBig').innerText = p.name.charAt(0).toUpperCase();
        
        const className = App.state.classesMap[p.class_id] || ('Класс ' + p.class_id);
        document.getElementById('profileClassBadge').innerText = className;
        
        document.getElementById('totalSolved').innerText = s.total_solved;
        document.getElementById('accuracy').innerText = s.accuracy + '%';

        const list = document.getElementById('achievementsList');
        list.innerHTML = '';
        const userBadges = p.achievements || [];
        const rarity = App.state.rarityMap || {};

        for (const [key, val] of Object.entries(achievementsConfig)) {
            const isUnlocked = userBadges.includes(key);
            const statusClass = isUnlocked ? 'unlocked' : 'locked';
            const tooltip = isUnlocked ? 'Получено!' : `Условие: ${val.desc}`;
            
            const pct = rarity[key] || 0;
            let rarityHtml = '';
            if (pct > 0 && pct < 100) rarityHtml = `<div class="rarity-badge">💎 ${pct}%</div>`;
            else if (pct === 100) rarityHtml = `<div class="rarity-badge" style="background:#eef2ff; color:#6366f1">100%</div>`;
            
            list.innerHTML += `
                <div class="badge-item ${statusClass}" title="${tooltip}">
                    ${rarityHtml}
                    <div class="badge-icon">${val.icon}</div>
                    <div class="badge-name">${val.name}</div>
                </div>`;
        }

        const hist = document.getElementById('historyList');
        hist.innerHTML = '';
        const modeMap = {
            'free': 'Свободный', 'test': 'На время', 'survival': 'Выживание', 'exam': 'ЭКЗАМЕН',
            'true_false': 'Верю/Не верю', 'find_x': 'Поиск X', 'bot_battle': 'Битва с ботом',
            'memory': 'Пары', 'rain': 'Дождь', 'builder': 'Конструктор'
        };
        
        s.history.forEach(h => {
            const modeName = modeMap[h.mode] || h.mode;
            hist.innerHTML += `
                <div class="history-item">
                    <div>
                        <strong>${modeName}</strong>
                    </div>
                    <div style="font-weight:700; ${h.total === h.correct ? 'color:green' : ''}">${h.correct}/${h.total}</div>
                </div>`;
        });
    }
};

document.addEventListener('DOMContentLoaded', App.init);

function wait(ms) { return new Promise(r => setTimeout(r, ms)); }