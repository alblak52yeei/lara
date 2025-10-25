@extends('layouts.app')

@section('title', 'Данные')

@section('content')
<h1 style="color: #667eea; margin-bottom: 2rem; text-align: center;">📊 Сохраненные данные</h1>

@if(count($data) > 0)
    <div style="overflow-x: auto;">
        <table class="table">
            <thead>
                <tr>
                    <th>№</th>
                    <th>Имя</th>
                    <th>Email</th>
                    <th>Телефон</th>
                    <th>Сообщение</th>
                    <th>Дата создания</th>
                    <th>Файл</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $index => $item)
                <tr>
                    <td style="font-weight: 600; color: #667eea;">{{ $index + 1 }}</td>
                    <td style="font-weight: 500;">{{ $item['name'] }}</td>
                    <td>
                        <a href="mailto:{{ $item['email'] }}" style="color: #667eea; text-decoration: none;">
                            {{ $item['email'] }}
                        </a>
                    </td>
                    <td>
                        <a href="tel:{{ $item['phone'] }}" style="color: #28a745; text-decoration: none;">
                            {{ $item['phone'] }}
                        </a>
                    </td>
                    <td style="max-width: 200px; word-wrap: break-word;">
                        {{ Str::limit($item['message'], 100) }}
                        @if(strlen($item['message']) > 100)
                            <span style="color: #6c757d; font-size: 0.9rem;">...</span>
                        @endif
                    </td>
                    <td style="color: #6c757d; font-size: 0.9rem;">
                        {{ \Carbon\Carbon::parse($item['created_at'])->format('d.m.Y H:i') }}
                    </td>
                    <td style="font-size: 0.8rem; color: #6c757d;">
                        {{ $item['filename'] }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top: 2rem; padding: 1rem; background: #d4edda; border: 1px solid #c3e6cb; border-radius: 5px; color: #155724;">
        <strong>📈 Статистика:</strong> Всего записей: {{ count($data) }}
    </div>
@else
    <div style="text-align: center; padding: 3rem; background: #f8f9fa; border-radius: 10px; border: 2px dashed #dee2e6;">
        <div style="font-size: 4rem; margin-bottom: 1rem;">📭</div>
        <h3 style="color: #6c757d; margin-bottom: 1rem;">Данных пока нет</h3>
        <p style="color: #6c757d; margin-bottom: 2rem;">
            Заполните форму, чтобы увидеть данные здесь
        </p>
        <a href="{{ route('form.show') }}" class="btn btn-primary">
            📝 Заполнить форму
        </a>
    </div>
@endif

<div style="margin-top: 2rem; text-align: center;">
    <a href="{{ route('home') }}" class="btn btn-secondary">
        🏠 На главную
    </a>
    <a href="{{ route('form.show') }}" class="btn btn-primary" style="margin-left: 1rem;">
        📝 Добавить данные
    </a>
</div>
@endsection
