@if($show)
<div id="cookie-notification" class="fixed bottom-4 left-4 right-4 md:left-auto md:right-4 md:bottom-4 md:max-w-md bg-white rounded-lg shadow-xl border border-gray-200 z-50 animate-slide-up">
    <div class="p-5">
        <div class="flex items-start gap-3 mb-3">
            <span class="text-2xl">🍪</span>
            <h3 class="font-semibold text-gray-900">Мы используем cookies</h3>
        </div>

        <p class="text-sm text-gray-600 mb-4 leading-relaxed">
            Этот сайт использует файлы cookie для улучшения вашего опыта.
            Продолжая использовать сайт, вы соглашаетесь с нашей
            <a href="{{ route('privacy.policy') }}" class="text-indigo-600 hover:text-indigo-800 underline">
                Политикой конфиденциальности
            </a>.
        </p>

        <div class="flex gap-3">
            <button
                onclick="acceptCookies()"
                class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200"
            >
                Принять
            </button>
            <button
                onclick="declineCookies()"
                class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg transition duration-200"
            >
                Отказаться
            </button>
        </div>
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
            .then(response => response.json())
            .then(() => {
                document.getElementById('cookie-notification').remove();
            })
            .catch(error => console.error('Error:', error));
    }

    function declineCookies() {
        fetch('{{ route("cookie.consent") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ accept: false })
        })
            .then(response => response.json())
            .then(() => {
                document.getElementById('cookie-notification').remove();
            })
            .catch(error => console.error('Error:', error));
    }
</script>

<style>
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

    .animate-slide-up {
        animation: slideUp 0.3s ease-out;
    }
</style>
@endif
