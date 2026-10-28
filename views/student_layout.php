<!-- views/student_layout.php -->

<!-- ВЕРХНЯЯ ПАНЕЛЬ -->
<header class="app-header glass-panel">
    <div class="user-profile-compact">
        <div class="avatar-circle pulse-animation" id="userAvatar">U</div>
        <div class="user-text">
            <div class="user-name" id="displayUserName">Загрузка...</div>
            <div class="user-class-badge">
                <span class="icon-holder small-icon" data-icon="class"></span>
                <span id="displayUserClass">...</span>
            </div>
        </div>
    </div>
    
    <div class="gamification-bar">
        <div class="star-counter" title="Твои достижения">
            <span class="icon-holder star-icon" data-icon="star"></span>
            <span id="userStars" class="star-value">0</span>
        </div>
        
        <button class="btn-icon-circle" id="btnProfile" title="Профиль">
            <span class="icon-holder" data-icon="user"></span>
        </button>
        <button class="btn-icon-circle logout" id="btnLogout" title="Выйти">
            <span class="icon-holder" data-icon="logout"></span>
        </button>
    </div>
</header>

<main class="app-content-wide">

    <!-- 1. ГЛАВНОЕ МЕНЮ -->
    <section id="view-menu" class="view-section active">
        <div class="hero-welcome">
            <h1>Тренировка</h1>
            <p>Выбирай режим и прокачивай навыки!</p>
        </div>
        
        <div class="game-modes-container">
            
            <!-- ЭКЗАМЕН (Скрыт по умолчанию) -->
            <div class="game-card mode-exam locked" id="examCard" data-mode="exam" style="display: none; border: 2px solid #6366f1; background: #eef2ff;">
                <div class="card-content">
                    <div class="card-icon-wrapper" style="color: #6366f1;">
                        <span class="icon-holder" data-icon="trophy"></span>
                    </div>
                    <h2>ЭКЗАМЕН</h2>
                    <p>Учитель начал проверку!</p>
                    <div class="card-badge time-badge" style="background: #6366f1; color: white;">ЖМИ СЮДА</div>
                </div>
            </div>

            <!-- 1. Свободная тренировка -->
            <div class="game-card mode-free" data-mode="free">
                <div class="card-bg-decoration"></div>
                <div class="card-content">
                    <div class="card-icon-wrapper">
                        <span class="icon-holder" data-icon="infinity"></span>
                    </div>
                    <h2>Свободный</h2>
                    <p>Решай без ограничений</p>
                    <div class="card-badge">База</div>
                </div>
            </div>

            <!-- 2. На время -->
            <div class="game-card mode-time" data-mode="test">
                <div class="card-bg-decoration"></div>
                <div class="card-content">
                    <div class="card-icon-wrapper">
                        <span class="icon-holder" data-icon="timer"></span>
                    </div>
                    <h2>На время</h2>
                    <p>Успей решить максимум</p>
                    <div class="card-badge time-badge">Таймер</div>
                </div>
            </div>

            <!-- 3. Выживание -->
            <div class="game-card mode-survival" data-mode="survival">
                <div class="card-bg-decoration"></div>
                <div class="card-content">
                    <div class="card-icon-wrapper">
                        <span class="icon-holder" data-icon="heart"></span>
                    </div>
                    <h2>Выживание</h2>
                    <p>3 жизни / Хардкор</p>
                    <div class="card-badge danger-badge">Сложно</div>
                </div>
            </div>

            <!-- 4. Верю - Не верю -->
            <div class="game-card mode-tf" data-mode="true_false">
                <div class="card-bg-decoration" style="background: linear-gradient(135deg, #a7f3d0, #34d399);"></div>
                <div class="card-content">
                    <div class="card-icon-wrapper" style="color:#059669">⚖️</div>
                    <h2>Верю / Не верю</h2>
                    <p>Найди ошибки в решении</p>
                    <div class="card-badge">Логика</div>
                </div>
            </div>

            <!-- 5. Поиск неизвестного -->
            <div class="game-card mode-findx" data-mode="find_x">
                <div class="card-bg-decoration" style="background: linear-gradient(135deg, #c4b5fd, #8b5cf6);"></div>
                <div class="card-content">
                    <div class="card-icon-wrapper" style="color:#7c3aed">🔍</div>
                    <h2>Поиск X</h2>
                    <p>Найди множитель (? * 5 = 20)</p>
                    <div class="card-badge">Алгебра</div>
                </div>
            </div>

            <!-- 6. Битва с Роботом -->
            <div class="game-card mode-bot" data-mode="bot_battle">
                <div class="card-bg-decoration" style="background: linear-gradient(135deg, #cbd5e1, #94a3b8);"></div>
                <div class="card-content">
                    <div class="card-icon-wrapper" style="color:#475569">🤖</div>
                    <h2>Битва с Ботом</h2>
                    <p>Обгони Искусственный Интеллект</p>
                    <div class="card-badge danger-badge">Гонка</div>
                </div>
            </div>

            <!-- 7. Найди Пару -->
            <div class="game-card mode-memory" data-mode="memory">
                <div class="card-bg-decoration" style="background: linear-gradient(135deg, #fcd34d, #fbbf24);"></div>
                <div class="card-content">
                    <div class="card-icon-wrapper" style="color:#d97706">🧩</div>
                    <h2>Найди Пару</h2>
                    <p>Тренировка памяти</p>
                    <div class="card-badge">Мемори</div>
                </div>
            </div>

            <!-- 8. Математический Дождь -->
            <div class="game-card mode-rain" data-mode="rain">
                <div class="card-bg-decoration" style="background: linear-gradient(135deg, #60a5fa, #2563eb);"></div>
                <div class="card-content">
                    <div class="card-icon-wrapper" style="color:#1d4ed8">🌧</div>
                    <h2>Мат. Дождь</h2>
                    <p>Успей решить пока не упало</p>
                    <div class="card-badge time-badge">Реакция</div>
                </div>
            </div>

            <!-- 9. Конструктор -->
            <div class="game-card mode-builder" data-mode="builder">
                <div class="card-bg-decoration" style="background: linear-gradient(135deg, #f9a8d4, #db2777);"></div>
                <div class="card-content">
                    <div class="card-icon-wrapper" style="color:#be185d">🏗</div>
                    <h2>Конструктор</h2>
                    <p>Собери число из множителей</p>
                    <div class="card-badge">Пазл</div>
                </div>
            </div>

        </div>
    </section>

    <!-- 2. ИГРОВОЙ ПРОЦЕСС -->
    <section id="view-game" class="view-section">
        <div class="game-top-bar">
            <button class="btn-pill" id="btnBackToMenu">
                <span class="icon-holder" data-icon="arrow-left"></span> Меню
            </button>
            
            <div class="game-status">
                <div class="status-item timer-pill" id="gameTimer" style="display:none">00:00</div>
                <div class="status-item lives-pill" id="gameLives" style="display:none">❤❤❤</div>
            </div>
        </div>

        <!-- ============================================== -->
        <!-- СПЕЦИАЛЬНЫЕ UI ЭЛЕМЕНТЫ ДЛЯ НОВЫХ РЕЖИМОВ -->
        <!-- ============================================== -->

        <!-- UI для Битвы с Роботом -->
        <div id="botBattleUI" class="mode-specific-ui glass-panel" style="display:none; margin-bottom: 20px; padding: 15px;">
             <div class="hp-bars-container">
                 <div class="hp-side">
                    <span class="hp-label">ТЫ</span>
                    <div class="hp-track"><div class="hp-fill player" id="hpPlayer" style="width:100%"></div></div>
                 </div>
                 <div class="bot-avatar-center" id="botAvatar">🤖</div>
                 <div class="hp-side">
                    <span class="hp-label">БОТ</span>
                    <div class="hp-track"><div class="hp-fill bot" id="hpBot" style="width:100%"></div></div>
                 </div>
             </div>
             <div style="text-align:center; font-size:0.8rem; color:#666; margin-top:5px;" id="botStatusText">Бот думает...</div>
        </div>

        <!-- Игровая доска (Стандартная + TrueFalse + FindX) -->
        <div class="game-board glass-panel" id="standardGameBoard">
            <div class="progress-line-container">
                <div class="progress-line-fill" id="gameProgress"></div>
            </div>

            <!-- Счетчики -->
            <div class="game-counters">
                <div class="counter-box correct">✅ <span id="gameCorrectCount">0</span></div>
                <div class="counter-box wrong">❌ <span id="gameWrongCount">0</span></div>
            </div>

            <!-- Контейнер для примера (Цифры или True/False вопрос) -->
            <div class="problem-area" id="problemContainer"></div>
            
            <!-- UI для True/False (Скрыт по умолчанию) -->
            <div id="trueFalseControls" class="mode-specific-ui" style="display:none; margin-top: 20px; gap: 20px; justify-content: center;">
                 <button class="tf-btn btn-true" id="btnTrue">ВЕРНО ✔</button>
                 <button class="tf-btn btn-false" id="btnFalse">НЕВЕРНО ✘</button>
            </div>

            <div class="feedback-text" id="feedbackMsg"></div>
        </div>

        <!-- UI для Memory (Сетка карт) -->
        <div id="memoryUI" class="mode-specific-ui glass-panel" style="display:none; padding:20px;">
            <div class="memory-header" style="text-align:center; margin-bottom:15px;">
                Найди все пары! Ходов: <span id="memoryMoves">0</span>
            </div>
            <div class="memory-grid" id="memoryGrid"></div>
        </div>

        <!-- UI для Математического Дождя (С НОВЫМ ПОЛЕМ ВВОДА) -->
        <div id="rainUI" class="mode-specific-ui glass-panel" style="display:none; padding:0; overflow:hidden; position:relative; height:400px; background: linear-gradient(to bottom, #1e293b, #334155);">
            <div id="rainCanvasArea" style="width:100%; height:100%; position:relative;">
                <!-- Падающие блоки генерируются JS -->
            </div>
            <div class="rain-ground-line"></div>
            
            <!-- НОВОЕ ПОЛЕ ОТОБРАЖЕНИЯ ВВОДА -->
            <div id="rainInputDisplay" class="rain-input-display"></div>
        </div>

        <!-- UI для Конструктора -->
        <div id="builderUI" class="mode-specific-ui glass-panel" style="display:none; padding:20px; text-align:center;">
            <div style="margin-bottom:20px;">
                <span style="font-size:1rem; color:#666; text-transform:uppercase; font-weight:700;">Собери число:</span>
                <div class="builder-target" id="builderTarget">0</div>
            </div>
            <div class="builder-grid" id="builderGrid"></div>
            <button class="btn btn-primary btn-block" id="btnBuilderCheck" style="margin-top:20px;">Готово</button>
        </div>

        <!-- Клавиатура (Скрывается для Memory, Builder, True/False) -->
        <div class="virtual-keyboard-modern" id="virtualKeyboard">
            <button class="v-key" data-key="1">1</button>
            <button class="v-key" data-key="2">2</button>
            <button class="v-key" data-key="3">3</button>
            <button class="v-key" data-key="4">4</button>
            <button class="v-key" data-key="5">5</button>
            <button class="v-key" data-key="6">6</button>
            <button class="v-key" data-key="7">7</button>
            <button class="v-key" data-key="8">8</button>
            <button class="v-key" data-key="9">9</button>
            <button class="v-key action-del" data-key="del">⌫</button>
            <button class="v-key" data-key="0">0</button>
            <button class="v-key action-go" data-key="enter">OK</button>
        </div>
    </section>

    <!-- 3. ПРОФИЛЬ -->
    <section id="view-profile" class="view-section">
        <div class="profile-layout glass-panel">
            <div class="profile-header-large">
                <button class="btn-icon-circle back-btn" id="btnBackFromProfile">
                    <span class="icon-holder" data-icon="arrow-left"></span>
                </button>
                <div class="profile-avatar-large" id="profileAvatarBig">U</div>
                <h2 id="profileName">Имя Ученика</h2>
                <div class="profile-class-badge" id="profileClassBadge">Класс</div>
            </div>

            <div class="stats-grid-modern">
                <div class="stat-box">
                    <span class="stat-label">Решено примеров</span>
                    <span class="stat-value" id="totalSolved">0</span>
                </div>
                <div class="stat-box">
                    <span class="stat-label">Точность</span>
                    <span class="stat-value" id="accuracy">0%</span>
                </div>
            </div>

            <h3 class="section-header">🏆 Твои награды</h3>
            <div class="achievements-scroll" id="achievementsList"></div>
            
            <h3 class="section-header">📜 История тренировок</h3>
            <div class="history-container" id="historyList"></div>
        </div>
    </section>

</main>

<!-- МОДАЛКА: НАСТРОЙКИ ИГРЫ -->
<div id="gameSetupModal" class="modal-overlay">
    <div class="modal-content glass-panel bounce-in setup-modal">
        <h2>Настройки тренировки</h2>
        
        <div class="setup-section" id="diffSelectorSection">
            <label>Сложность:</label>
            <div class="selector-group difficulty-selector">
                <button class="sel-btn active" data-val="easy">Легко<span>2 × 2</span></button>
                <button class="sel-btn" data-val="medium">Средне<span>2 × 2 + 5</span></button>
                <button class="sel-btn" data-val="hard">Сложно<span>2 × 2 + 3 × 4</span></button>
            </div>
        </div>

        <div class="setup-section" id="timeSelectorSection">
            <label>Время:</label>
            <div class="selector-group time-selector">
                <button class="sel-btn active" data-val="1">1 мин</button>
                <button class="sel-btn" data-val="3">3 мин</button>
                <button class="sel-btn" data-val="5">5 мин</button>
            </div>
        </div>

        <button class="btn btn-primary btn-block btn-lg" id="btnStartGameAction">Начать!</button>
        <button class="btn btn-secondary btn-block" onclick="document.getElementById('gameSetupModal').style.display='none'">Отмена</button>
    </div>
</div>

<!-- МОДАЛКА: РЕЗУЛЬТАТ -->
<div id="resultModal" class="modal-overlay">
    <div class="modal-content glass-panel bounce-in game-result-modal">
        <div class="result-emoji" id="resultIcon">🎉</div>
        <h2 id="resultTitle">Победа!</h2>
        <div class="result-details">
            <div class="res-row">
                <span>Верно:</span> <b class="text-green" id="resCorrect">0</b>
            </div>
            <div class="res-row">
                <span>Ошибок:</span> <b class="text-red" id="resWrong">0</b>
            </div>
            <div class="res-row" id="resExtraRow" style="display:none">
                <span id="resExtraLabel">Инфо:</span> <b id="resExtraValue">...</b>
            </div>
        </div>
        <div class="modal-buttons">
            <button class="btn btn-primary btn-block" id="btnRestart">Играть снова</button>
            <button class="btn btn-secondary btn-block" id="btnToMenu">В меню</button>
        </div>
    </div>
</div>