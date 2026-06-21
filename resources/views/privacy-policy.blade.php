@extends('layouts.app')

@section('title', 'Политика конфиденциальности')

@section('content')
    <div style="max-width: 900px; margin: 50px auto; padding: 0 20px;">
        <h1 style="font-size: 32px; font-weight: bold; margin-bottom: 30px;">Политика конфиденциальности</h1>

        <div style="line-height: 1.6; color: #374151;">
            <section style="margin-bottom: 30px;">
                <h2 style="font-size: 24px; font-weight: 600; margin-bottom: 15px;">1. Использование cookies</h2>
                <p>Наш сайт использует файлы cookie для обеспечения корректной работы и улучшения пользовательского опыта. Cookies — это небольшие текстовые файлы, которые сохраняются на вашем устройстве.</p>
            </section>

            <section style="margin-bottom: 30px;">
                <h2 style="font-size: 24px; font-weight: 600; margin-bottom: 15px;">2. Какие cookies мы используем</h2>
                <ul style="list-style: disc; padding-left: 20px;">
                    <li><strong>Необходимые cookies</strong> — обеспечивают работу сайта (навигация, доступ к защищённым разделам)</li>
                    <li><strong>Функциональные cookies</strong> — запоминают ваши настройки и предпочтения</li>
                    <li><strong>Аналитические cookies</strong> — помогают нам понять, как используется сайт</li>
                </ul>
            </section>

            <section style="margin-bottom: 30px;">
                <h2 style="font-size: 24px; font-weight: 600; margin-bottom: 15px;">3. Управление cookies</h2>
                <p>Вы можете контролировать и/или удалять cookies по своему усмотрению. Для получения дополнительной информации посетите <a href="https://www.aboutcookies.org" style="color: #3b82f6;" target="_blank">aboutcookies.org</a>. Вы можете удалить все cookies, которые уже находятся на вашем компьютере, а также настроить большинство браузеров таким образом, чтобы они блокировали их размещение.</p>
            </section>

            <section style="margin-bottom: 30px;">
                <h2 style="font-size: 24px; font-weight: 600; margin-bottom: 15px;">4. Контактная информация</h2>
                <p>Если у вас есть вопросы об использовании cookies или политике конфиденциальности, свяжитесь с нами:</p>
                <p>Email: privacy@example.com</p>
            </section>

            <p style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #e5e7eb; font-size: 14px; color: #6b7280;">
                Дата последнего обновления: {{ now()->format('d.m.Y') }}
            </p>
        </div>
    </div>
@endsection
