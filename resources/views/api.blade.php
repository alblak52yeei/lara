@extends('layouts.app')

@section('title', 'API Управление')

@section('content')
<div id="app">
    <div style="margin-bottom: 2rem;">
        <h1 style="color: #667eea; margin-bottom: 1rem;">🔧 Управление через API</h1>
        <div style="display: flex; gap: 1rem; margin-bottom: 1rem;">
            <button class="btn btn-primary" onclick="showSection('categories')">📁 Категории</button>
            <button class="btn btn-primary" onclick="showSection('contacts')">👤 Контакты</button>
            <button class="btn btn-primary" onclick="showSection('comments')">💬 Комментарии</button>
        </div>
    </div>

    <!-- Категории -->
    <div id="categories-section" class="section">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h2>Категории</h2>
            <button class="btn btn-primary" onclick="openModal('category', 'create')">➕ Добавить категорию</button>
        </div>
        <div style="margin-bottom: 1rem;">
            <input type="text" id="category-search" class="form-control" placeholder="Поиск категорий..." onkeyup="searchCategories(this.value)">
        </div>
        <div id="categories-table-container">
            <div class="loading">Загрузка...</div>
        </div>
    </div>

    <!-- Контакты -->
    <div id="contacts-section" class="section" style="display: none;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h2>Контакты</h2>
            <button class="btn btn-primary" onclick="openModal('contact', 'create')">➕ Добавить контакт</button>
        </div>
        <div style="margin-bottom: 1rem; display: flex; gap: 1rem; flex-wrap: wrap;">
            <input type="text" id="contact-search" class="form-control" style="flex: 1; min-width: 200px;" placeholder="Поиск контактов..." onkeyup="searchContacts(this.value)">
            <select id="contact-status-filter" class="form-control" style="width: 200px;" onchange="loadContacts()">
                <option value="">Все статусы</option>
                <option value="new">Новые</option>
                <option value="in_progress">В работе</option>
                <option value="completed">Завершено</option>
                <option value="archived">Архив</option>
            </select>
        </div>
        <div id="contacts-table-container">
            <div class="loading">Загрузка...</div>
        </div>
    </div>

    <!-- Комментарии -->
    <div id="comments-section" class="section" style="display: none;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h2>Комментарии</h2>
            <button class="btn btn-primary" onclick="openModal('comment', 'create')">➕ Добавить комментарий</button>
        </div>
        <div style="margin-bottom: 1rem;">
            <input type="text" id="comment-search" class="form-control" placeholder="Поиск комментариев..." onkeyup="searchComments(this.value)">
        </div>
        <div id="comments-table-container">
            <div class="loading">Загрузка...</div>
        </div>
    </div>
</div>

<!-- Модальное окно -->
<div id="modal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2 id="modal-title"></h2>
        <form id="modal-form" onsubmit="handleSubmit(event)">
            <div id="modal-body"></div>
            <div style="margin-top: 1.5rem; display: flex; gap: 1rem; justify-content: flex-end;">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Отмена</button>
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </div>
        </form>
    </div>
</div>

<style>
.section {
    background: white;
    padding: 2rem;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    margin-bottom: 2rem;
}

.loading {
    text-align: center;
    padding: 2rem;
    color: #666;
}

.table-container {
    overflow-x: auto;
}

.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 1rem;
}

.table th,
.table td {
    padding: 0.75rem;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.table th {
    background-color: #f8f9fa;
    font-weight: 600;
}

.table tr:hover {
    background-color: #f5f5f5;
}

.btn-danger {
    background-color: #dc3545;
    color: white;
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 0.875rem;
}

.btn-danger:hover {
    background-color: #c82333;
}

.btn-edit {
    background-color: #ffc107;
    color: #333;
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 0.875rem;
    margin-right: 0.5rem;
}

.btn-edit:hover {
    background-color: #e0a800;
}

.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.5);
}

.modal-content {
    background-color: white;
    margin: 5% auto;
    padding: 2rem;
    border-radius: 10px;
    width: 90%;
    max-width: 600px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.close:hover {
    color: #000;
}

select.form-control {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 1rem;
}

.badge {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.875rem;
    font-weight: 500;
}

.badge-new { background-color: #28a745; color: white; }
.badge-in_progress { background-color: #ffc107; color: #333; }
.badge-completed { background-color: #17a2b8; color: white; }
.badge-archived { background-color: #6c757d; color: white; }
</style>

<script>
const API_BASE = '/api/v2';

let currentSection = 'categories';
let currentEditId = null;
let categories = [];

// Показать секцию
function showSection(section) {
    document.querySelectorAll('.section').forEach(s => s.style.display = 'none');
    document.getElementById(section + '-section').style.display = 'block';
    currentSection = section;
    
    if (section === 'categories') loadCategories();
    if (section === 'contacts') loadContacts();
    if (section === 'comments') loadComments();
}

// Загрузка категорий
async function loadCategories() {
    try {
        const response = await fetch(`${API_BASE}/categories`, {
            headers: {
                'Accept': 'application/json',
            }
        });
        const result = await response.json();
        // API Resource collection возвращает данные в формате {data: [...]}
        categories = result.data || [];
        renderCategories(categories);
    } catch (error) {
        console.error('Ошибка загрузки категорий:', error);
        showError('Ошибка загрузки категорий: ' + error.message);
    }
}

// Отображение категорий
function renderCategories(data) {
    const container = document.getElementById('categories-table-container');
    if (!data || data.length === 0) {
        container.innerHTML = '<p style="text-align: center; padding: 2rem; color: #666;">Нет категорий</p>';
        return;
    }
    
    let html = '<div class="table-container"><table class="table"><thead><tr><th>ID</th><th>Название</th><th>Описание</th><th>Действия</th></tr></thead><tbody>';
    data.forEach(item => {
        html += `
            <tr>
                <td>${item.id}</td>
                <td>${item.name}</td>
                <td>${item.description || '-'}</td>
                <td>
                    <button class="btn-edit" onclick="openModal('category', 'edit', ${item.id})">✏️ Редактировать</button>
                    <button class="btn-danger" onclick="deleteItem('category', ${item.id})">🗑️ Удалить</button>
                </td>
            </tr>
        `;
    });
    html += '</tbody></table></div>';
    container.innerHTML = html;
}

// Поиск категорий
function searchCategories(query) {
    if (!query) {
        renderCategories(categories);
        return;
    }
    const filtered = categories.filter(cat => 
        cat.name.toLowerCase().includes(query.toLowerCase()) ||
        (cat.description && cat.description.toLowerCase().includes(query.toLowerCase()))
    );
    renderCategories(filtered);
}

// Загрузка контактов
async function loadContacts() {
    try {
        let url = `${API_BASE}/contacts`;
        const status = document.getElementById('contact-status-filter')?.value;
        if (status) url += `?status=${status}`;
        
        const response = await fetch(url, {
            headers: {
                'Accept': 'application/json',
            }
        });
        const result = await response.json();
        // API Resource collection возвращает данные в формате {data: [...]}
        window.contacts = result.data || [];
        renderContacts(window.contacts);
    } catch (error) {
        console.error('Ошибка загрузки контактов:', error);
        showError('Ошибка загрузки контактов: ' + error.message);
    }
}

// Отображение контактов
function renderContacts(data) {
    const container = document.getElementById('contacts-table-container');
    if (!data || data.length === 0) {
        container.innerHTML = '<p style="text-align: center; padding: 2rem; color: #666;">Нет контактов</p>';
        return;
    }
    
    let html = '<div class="table-container"><table class="table"><thead><tr><th>ID</th><th>Имя</th><th>Email</th><th>Телефон</th><th>Статус</th><th>Категория</th><th>Действия</th></tr></thead><tbody>';
    data.forEach(item => {
        const statusClass = `badge-${item.status}`;
        html += `
            <tr>
                <td>${item.id}</td>
                <td>${item.name}</td>
                <td>${item.email}</td>
                <td>${item.phone}</td>
                <td><span class="badge ${statusClass}">${getStatusText(item.status)}</span></td>
                <td>${item.category ? item.category.name : '-'}</td>
                <td>
                    <button class="btn-edit" onclick="openModal('contact', 'edit', ${item.id})">✏️ Редактировать</button>
                    <button class="btn-danger" onclick="deleteItem('contact', ${item.id})">🗑️ Удалить</button>
                </td>
            </tr>
        `;
    });
    html += '</tbody></table></div>';
    container.innerHTML = html;
}

// Поиск контактов
function searchContacts(query) {
    if (!query) {
        renderContacts(window.contacts || []);
        return;
    }
    const filtered = (window.contacts || []).filter(contact => 
        contact.name.toLowerCase().includes(query.toLowerCase()) ||
        contact.email.toLowerCase().includes(query.toLowerCase()) ||
        contact.phone.includes(query)
    );
    renderContacts(filtered);
}

// Загрузка комментариев
async function loadComments() {
    try {
        const response = await fetch(`${API_BASE}/comments`, {
            headers: {
                'Accept': 'application/json',
            }
        });
        const result = await response.json();
        // API Resource collection возвращает данные в формате {data: [...]}
        window.comments = result.data || [];
        renderComments(window.comments);
    } catch (error) {
        console.error('Ошибка загрузки комментариев:', error);
        showError('Ошибка загрузки комментариев: ' + error.message);
    }
}

// Отображение комментариев
function renderComments(data) {
    const container = document.getElementById('comments-table-container');
    if (!data || data.length === 0) {
        container.innerHTML = '<p style="text-align: center; padding: 2rem; color: #666;">Нет комментариев</p>';
        return;
    }
    
    let html = '<div class="table-container"><table class="table"><thead><tr><th>ID</th><th>Содержимое</th><th>Тип модели</th><th>ID модели</th><th>Пользователь</th><th>Действия</th></tr></thead><tbody>';
    data.forEach(item => {
        const modelType = item.commentable_type ? item.commentable_type.replace('App\\Models\\', '') : '-';
        html += `
            <tr>
                <td>${item.id}</td>
                <td>${item.content.substring(0, 50)}${item.content.length > 50 ? '...' : ''}</td>
                <td>${modelType}</td>
                <td>${item.commentable_id || '-'}</td>
                <td>${item.user ? item.user.name : '-'}</td>
                <td>
                    <button class="btn-edit" onclick="openModal('comment', 'edit', ${item.id})">✏️ Редактировать</button>
                    <button class="btn-danger" onclick="deleteItem('comment', ${item.id})">🗑️ Удалить</button>
                </td>
            </tr>
        `;
    });
    html += '</tbody></table></div>';
    container.innerHTML = html;
}

// Поиск комментариев
function searchComments(query) {
    if (!query) {
        renderComments(window.comments || []);
        return;
    }
    const filtered = (window.comments || []).filter(comment => 
        comment.content.toLowerCase().includes(query.toLowerCase())
    );
    renderComments(filtered);
}

// Открыть модальное окно
async function openModal(type, action, id = null) {
    currentEditId = id;
    const modal = document.getElementById('modal');
    const title = document.getElementById('modal-title');
    const body = document.getElementById('modal-body');
    
    title.textContent = action === 'create' ? `Создать ${getTypeName(type)}` : `Редактировать ${getTypeName(type)}`;
    
    if (action === 'edit' && id) {
        await loadItemForEdit(type, id, body);
    } else {
        renderCreateForm(type, body);
    }
    
    modal.style.display = 'block';
}

// Закрыть модальное окно
function closeModal() {
    document.getElementById('modal').style.display = 'none';
    document.getElementById('modal-form').reset();
    currentEditId = null;
}

// Загрузить данные для редактирования
async function loadItemForEdit(type, id, body) {
    try {
        const response = await fetch(`${API_BASE}/${type}s/${id}`, {
            headers: {
                'Accept': 'application/json',
            }
        });
        const result = await response.json();
        if (response.ok && result.data) {
            renderEditForm(type, result.data, body);
        } else {
            showError(result.message || 'Ошибка загрузки данных');
        }
    } catch (error) {
        console.error('Ошибка загрузки данных:', error);
        showError('Ошибка загрузки данных: ' + error.message);
    }
}

// Форма создания
function renderCreateForm(type, body) {
    if (type === 'category') {
        body.innerHTML = `
            <div class="form-group">
                <label>Название *</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Описание</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>
        `;
    } else if (type === 'contact') {
        body.innerHTML = `
            <div class="form-group">
                <label>Категория</label>
                <select name="category_id" class="form-control" id="contact-category-select">
                    <option value="">Без категории</option>
                </select>
            </div>
            <div class="form-group">
                <label>Имя *</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Email *</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Телефон *</label>
                <input type="text" name="phone" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Сообщение *</label>
                <textarea name="message" class="form-control" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label>Статус</label>
                <select name="status" class="form-control">
                    <option value="new">Новый</option>
                    <option value="in_progress">В работе</option>
                    <option value="completed">Завершено</option>
                    <option value="archived">Архив</option>
                </select>
            </div>
        `;
        loadCategoriesForSelect();
    } else if (type === 'comment') {
        body.innerHTML = `
            <div class="form-group">
                <label>Тип модели *</label>
                <select name="commentable_type" class="form-control" required>
                    <option value="App\\Models\\Contact">Contact</option>
                    <option value="App\\Models\\Category">Category</option>
                </select>
            </div>
            <div class="form-group">
                <label>ID модели *</label>
                <input type="number" name="commentable_id" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Пользователь ID</label>
                <input type="number" name="user_id" class="form-control">
            </div>
            <div class="form-group">
                <label>Содержимое *</label>
                <textarea name="content" class="form-control" rows="4" required></textarea>
            </div>
        `;
    }
}

// Форма редактирования
function renderEditForm(type, data, body) {
    if (type === 'category') {
        body.innerHTML = `
            <div class="form-group">
                <label>Название *</label>
                <input type="text" name="name" class="form-control" value="${data.name || ''}" required>
            </div>
            <div class="form-group">
                <label>Описание</label>
                <textarea name="description" class="form-control" rows="3">${data.description || ''}</textarea>
            </div>
        `;
    } else if (type === 'contact') {
        body.innerHTML = `
            <div class="form-group">
                <label>Категория</label>
                <select name="category_id" class="form-control" id="contact-category-select">
                    <option value="">Без категории</option>
                </select>
            </div>
            <div class="form-group">
                <label>Имя *</label>
                <input type="text" name="name" class="form-control" value="${data.name || ''}" required>
            </div>
            <div class="form-group">
                <label>Email *</label>
                <input type="email" name="email" class="form-control" value="${data.email || ''}" required>
            </div>
            <div class="form-group">
                <label>Телефон *</label>
                <input type="text" name="phone" class="form-control" value="${data.phone || ''}" required>
            </div>
            <div class="form-group">
                <label>Сообщение *</label>
                <textarea name="message" class="form-control" rows="4" required>${data.message || ''}</textarea>
            </div>
            <div class="form-group">
                <label>Статус</label>
                <select name="status" class="form-control">
                    <option value="new" ${data.status === 'new' ? 'selected' : ''}>Новый</option>
                    <option value="in_progress" ${data.status === 'in_progress' ? 'selected' : ''}>В работе</option>
                    <option value="completed" ${data.status === 'completed' ? 'selected' : ''}>Завершено</option>
                    <option value="archived" ${data.status === 'archived' ? 'selected' : ''}>Архив</option>
                </select>
            </div>
        `;
        loadCategoriesForSelect(data.category_id);
    } else if (type === 'comment') {
        body.innerHTML = `
            <div class="form-group">
                <label>Тип модели *</label>
                <select name="commentable_type" class="form-control" required>
                    <option value="App\\Models\\Contact" ${data.commentable_type === 'App\\Models\\Contact' ? 'selected' : ''}>Contact</option>
                    <option value="App\\Models\\Category" ${data.commentable_type === 'App\\Models\\Category' ? 'selected' : ''}>Category</option>
                </select>
            </div>
            <div class="form-group">
                <label>ID модели *</label>
                <input type="number" name="commentable_id" class="form-control" value="${data.commentable_id || ''}" required>
            </div>
            <div class="form-group">
                <label>Пользователь ID</label>
                <input type="number" name="user_id" class="form-control" value="${data.user_id || ''}">
            </div>
            <div class="form-group">
                <label>Содержимое *</label>
                <textarea name="content" class="form-control" rows="4" required>${data.content || ''}</textarea>
            </div>
        `;
    }
}

// Загрузить категории для select
async function loadCategoriesForSelect(selectedId = null) {
    const select = document.getElementById('contact-category-select');
    if (!select) return;
    
    try {
        const response = await fetch(`${API_BASE}/categories`);
        const result = await response.json();
        const cats = result.data || [];
        
        select.innerHTML = '<option value="">Без категории</option>';
        cats.forEach(cat => {
            const option = document.createElement('option');
            option.value = cat.id;
            option.textContent = cat.name;
            if (selectedId && cat.id == selectedId) option.selected = true;
            select.appendChild(option);
        });
    } catch (error) {
        console.error('Ошибка загрузки категорий:', error);
    }
}

// Обработка отправки формы
async function handleSubmit(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());
    
    // Преобразование пустых строк в null для опциональных полей
    if (data.category_id === '') data.category_id = null;
    if (data.user_id === '') data.user_id = null;
    
    const type = currentSection.slice(0, -1); // убираем 's' в конце
    const url = currentEditId 
        ? `${API_BASE}/${type}s/${currentEditId}`
        : `${API_BASE}/${type}s`;
    
    const method = currentEditId ? 'PATCH' : 'POST';
    
    try {
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (response.ok) {
            if (result.success !== false) {
                showSuccess(currentEditId ? 'Успешно обновлено!' : 'Успешно создано!');
                closeModal();
                if (type === 'category') loadCategories();
                if (type === 'contact') loadContacts();
                if (type === 'comment') loadComments();
            } else {
                showError(result.message || 'Ошибка при сохранении');
                if (result.errors) {
                    console.error('Ошибки валидации:', result.errors);
                    let errorMsg = 'Ошибки валидации:\n';
                    Object.keys(result.errors).forEach(key => {
                        errorMsg += `${key}: ${result.errors[key].join(', ')}\n`;
                    });
                    showError(errorMsg);
                }
            }
        } else {
            const errorMsg = result.message || result.error || 'Ошибка при сохранении';
            showError(errorMsg);
            if (result.errors) {
                console.error('Ошибки валидации:', result.errors);
            }
        }
    } catch (error) {
        showError('Ошибка: ' + error.message);
        console.error('Ошибка запроса:', error);
    }
}

// Удаление элемента
async function deleteItem(type, id) {
    if (!confirm(`Вы уверены, что хотите удалить этот ${getTypeName(type)}?`)) {
        return;
    }
    
    try {
        const response = await fetch(`${API_BASE}/${type}s/${id}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            }
        });
        
        const result = await response.json();
        
        if (response.ok) {
            if (result.success !== false) {
                showSuccess('Успешно удалено!');
                if (type === 'category') loadCategories();
                if (type === 'contact') loadContacts();
                if (type === 'comment') loadComments();
            } else {
                showError(result.message || 'Ошибка при удалении');
            }
        } else {
            showError(result.message || 'Ошибка при удалении');
        }
    } catch (error) {
        console.error('Ошибка удаления:', error);
        showError('Ошибка: ' + error.message);
    }
}

// Вспомогательные функции
function getTypeName(type) {
    const names = {
        'category': 'категорию',
        'contact': 'контакт',
        'comment': 'комментарий'
    };
    return names[type] || 'элемент';
}

function getStatusText(status) {
    const texts = {
        'new': 'Новый',
        'in_progress': 'В работе',
        'completed': 'Завершено',
        'archived': 'Архив'
    };
    return texts[status] || status;
}

function showSuccess(message) {
    const alert = document.createElement('div');
    alert.className = 'alert alert-success';
    alert.textContent = message;
    alert.style.position = 'fixed';
    alert.style.top = '20px';
    alert.style.right = '20px';
    alert.style.zIndex = '9999';
    alert.style.minWidth = '300px';
    document.body.appendChild(alert);
    setTimeout(() => alert.remove(), 3000);
}

function showError(message) {
    const alert = document.createElement('div');
    alert.className = 'alert alert-danger';
    alert.textContent = message;
    alert.style.position = 'fixed';
    alert.style.top = '20px';
    alert.style.right = '20px';
    alert.style.zIndex = '9999';
    alert.style.minWidth = '300px';
    document.body.appendChild(alert);
    setTimeout(() => alert.remove(), 5000);
}

// Закрытие модального окна при клике вне его
window.onclick = function(event) {
    const modal = document.getElementById('modal');
    if (event.target === modal) {
        closeModal();
    }
}

// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    loadCategories();
});
</script>
@endsection

