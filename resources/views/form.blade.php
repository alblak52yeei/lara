@extends('layouts.app')

@section('title', 'Форма')

@section('content')
<h1 style="color: #667eea; margin-bottom: 2rem; text-align: center;">📝 Форма для отправки данных</h1>

<form method="POST" action="{{ route('form.submit') }}" style="max-width: 600px; margin: 0 auto;">
    @csrf
    
    <div class="form-group">
        <label for="name">Имя *</label>
        <input type="text" 
               id="name" 
               name="name" 
               class="form-control @error('name') error @enderror" 
               value="{{ old('name') }}" 
               required>
        @error('name')
            <div class="error-message">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="email">Email *</label>
        <input type="email" 
               id="email" 
               name="email" 
               class="form-control @error('email') error @enderror" 
               value="{{ old('email') }}" 
               required>
        @error('email')
            <div class="error-message">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="phone">Телефон *</label>
        <input type="tel" 
               id="phone" 
               name="phone" 
               class="form-control @error('phone') error @enderror" 
               value="{{ old('phone') }}" 
               placeholder="+7 (999) 123-45-67"
               required>
        @error('phone')
            <div class="error-message">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="message">Сообщение *</label>
        <textarea id="message" 
                  name="message" 
                  class="form-control @error('message') error @enderror" 
                  rows="5" 
                  placeholder="Введите ваше сообщение..."
                  required>{{ old('message') }}</textarea>
        @error('message')
            <div class="error-message">{{ $message }}</div>
        @enderror
    </div>

    <div style="text-align: center; margin-top: 2rem;">
        <button type="submit" class="btn btn-primary" style="padding: 1rem 2rem; font-size: 1.1rem;">
            📤 Отправить данные
        </button>
    </div>
</form>

<div style="margin-top: 3rem; padding: 1.5rem; background: #e9ecef; border-radius: 8px;">
    <h3 style="color: #495057; margin-bottom: 1rem;">ℹ️ Информация о форме:</h3>
    <ul style="color: #6c757d; line-height: 1.8;">
        <li>Все поля обязательны для заполнения</li>
        <li>Email должен быть в корректном формате</li>
        <li>Сообщение не должно превышать 1000 символов</li>
        <li>Данные сохраняются в JSON файл с уникальным именем</li>
        <li>После успешной отправки вы получите уведомление</li>
    </ul>
</div>
@endsection
