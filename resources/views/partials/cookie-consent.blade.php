@if(!Cookie::get('cookie_consent'))
    <div id="cookie-notification" style="position: fixed; bottom: 0; left: 0; right: 0; background: #1f2937; color: white; padding: 15px 20px; z-index: 9999;">
        <div class="container d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                🍪 Мы используем cookies для улучшения работы сайта.
                <a href="{{ route('privacy.policy') }}" style="color: #60a5fa;">Политика конфиденциальности</a>
            </div>
            <button onclick="acceptCookies()" class="btn btn-primary btn-sm px-4">Принять</button>
        </div>
    </div>

    <script>
        function acceptCookies() {
            fetch('{{ route("cookie.consent") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ accept: true })
            })
                .then(() => {
                    document.getElementById('cookie-notification').remove();
                });
        }
    </script>
@endif
