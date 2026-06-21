@if(isset($showCookieConsent) && $showCookieConsent)
    <div id="cookie-notification" class="cookie-notification">
        <div class="cookie-content">
            <div class="cookie-text">
                <strong>🍪 Мы используем cookies</strong>
                <p>Этот сайт использует файлы cookie для улучшения вашего опыта.
                    Продолжая использовать сайт, вы соглашаетесь с нашей
                    <a href="{{ route('privacy.policy') }}" target="_blank">Политикой конфиденциальности</a>.
                </p>
            </div>
            <div class="cookie-buttons">
                <button id="accept-cookies" class="btn-accept">Принять</button>
                <button id="decline-cookies" class="btn-decline">Отказаться</button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const notification = document.getElementById('cookie-notification');
                const acceptBtn = document.getElementById('accept-cookies');
                const declineBtn = document.getElementById('decline-cookies');

                if (acceptBtn) {
                    acceptBtn.addEventListener('click', function() {
                        fetch('{{ route("cookie.consent") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ action: 'accept' })
                        })
                            .then(response => response.json())
                            .then(() => {
                                notification.style.display = 'none';
                                // Перезагружаем страницу чтобы применить изменения
                                location.reload();
                            });
                    });
                }

                if (declineBtn) {
                    declineBtn.addEventListener('click', function() {
                        fetch('{{ route("cookie.decline") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ action: 'decline' })
                        })
                            .then(response => response.json())
                            .then(() => {
                                notification.style.display = 'none';
                                // Отключаем аналитику и другие куки
                                disableCookies();
                            });
                    });
                }
            });

            function disableCookies() {
                // Здесь код для отключения сторонних сервисов
                console.log('Cookie declined');
            }
        </script>
    @endpush

    @push('styles')
        <style>
            .cookie-notification {
                position: fixed;
                bottom: 20px;
                left: 20px;
                right: 20px;
                background: white;
                border-radius: 12px;
                box-shadow: 0 4px 20px rgba(0,0,0,0.15);
                z-index: 9999;
                animation: slideUp 0.3s ease-out;
                border: 1px solid #e0e0e0;
            }

            @keyframes slideUp {
                from {
                    transform: translateY(100%);
                    opacity: 0;
                }
                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }

            .cookie-content {
                max-width: 1200px;
                margin: 0 auto;
                padding: 16px 24px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 20px;
                flex-wrap: wrap;
            }

            .cookie-text {
                flex: 1;
            }

            .cookie-text strong {
                display: block;
                margin-bottom: 5px;
                color: #333;
            }

            .cookie-text p {
                margin: 0;
                color: #666;
                font-size: 14px;
            }

            .cookie-text a {
                color: #4F46E5;
                text-decoration: none;
            }

            .cookie-text a:hover {
                text-decoration: underline;
            }

            .cookie-buttons {
                display: flex;
                gap: 12px;
            }

            .btn-accept, .btn-decline {
                padding: 8px 20px;
                border-radius: 6px;
                font-size: 14px;
                cursor: pointer;
                transition: all 0.2s;
                border: none;
                font-weight: 500;
            }

            .btn-accept {
                background: #4F46E5;
                color: white;
            }

            .btn-accept:hover {
                background: #4338CA;
            }

            .btn-decline {
                background: #f3f4f6;
                color: #374151;
                border: 1px solid #d1d5db;
            }

            .btn-decline:hover {
                background: #e5e7eb;
            }

            @media (max-width: 768px) {
                .cookie-content {
                    flex-direction: column;
                    text-align: center;
                }

                .cookie-buttons {
                    width: 100%;
                    justify-content: center;
                }
            }
        </style>
    @endpush
@endif
