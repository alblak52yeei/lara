@extends('layouts.app')

@section('title', 'Главная')

@section('content')
<div style="text-align: center; padding: 3rem 0;">
    <h1 style="color: #667eea; margin-bottom: 1rem; font-size: 2.5rem;">Добро пожаловать!</h1>
    <p style="font-size: 1.2rem; color: #666; margin-bottom: 2rem;">
        Это веб-приложение на Laravel с формой для сбора данных
    </p>
    
    <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
        <a href="{{ route('form.show') }}" class="btn btn-primary">
            📝 Заполнить форму
        </a>
        <a href="{{ route('data.show') }}" class="btn btn-secondary">
            📊 Посмотреть данные
        </a>
    </div>
</div>

<div style="margin-top: 3rem;">
    <h2 style="color: #333; margin-bottom: 1rem;">Возможности приложения:</h2>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
        <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; border-left: 4px solid #667eea;">
            <h3 style="color: #667eea; margin-bottom: 0.5rem;">📝 Форма</h3>
            <p>Заполните форму с валидацией полей и отправьте данные</p>
        </div>
        <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; border-left: 4px solid #28a745;">
            <h3 style="color: #28a745; margin-bottom: 0.5rem;">💾 Сохранение</h3>
            <p>Данные сохраняются в JSON файлы с уникальными именами</p>
        </div>
        <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; border-left: 4px solid #ffc107;">
            <h3 style="color: #ffc107; margin-bottom: 0.5rem;">📊 Просмотр</h3>
            <p>Просматривайте все сохраненные данные в удобной таблице</p>
        </div>
    </div>
</div>
@endsection
