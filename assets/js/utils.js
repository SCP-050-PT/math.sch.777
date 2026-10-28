/* assets/js/utils.js */

/**
 * Отправка POST запроса на API
 * @param {string} url - Адрес (обычно 'api.php')
 * @param {object} data - Объект с данными
 * @returns {Promise<object>} - Ответ от сервера
 */
async function postData(url, data = {}) {
    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        // Если сервер вернул 401 (нет авторизации) или 403, обрабатываем
        if (response.status === 401) {
            window.location.href = 'index.php'; // Редирект на логин ученика
            return { success: false, error: 'Unauthorized' };
        }
        
        if (response.status === 403) {
             alert('Доступ запрещен');
             return { success: false, error: 'Forbidden' };
        }

        return await response.json();
    } catch (error) {
        console.error('API Error:', error);
        return { success: false, error: 'Network error' };
    }
}

/**
 * Безопасное создание HTML элементов из строки (защита от XSS)
 */
function escapeHtml(text) {
    if (text === null || text === undefined) return '';
    return text
        .toString()
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

/**
 * Простая задержка (sleep)
 */
function wait(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

/**
 * Форматирование даты
 */
function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('ru-RU', {
        day: '2-digit', month: '2-digit', hour: '2-digit', minute: '2-digit'
    });
}