<footer class="bg-light py-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-3">
                <h5><i class="bi bi-droplet-fill text-primary"></i> Мир Химии</h5>
                <p class="text-muted">Интернет-магазин автомобильной химии с доставкой по всей России.</p>
            </div>
            <div class="col-md-2 mb-3">
                <h6>Информация</h6>
                <ul class="list-unstyled">
                    <li><a href="{{ route('about') }}" class="text-decoration-none text-muted">О компании</a></li>
                    <li><a href="{{ route('delivery') }}" class="text-decoration-none text-muted">Доставка</a></li>
                    <li><a href="{{ route('contacts') }}" class="text-decoration-none text-muted">Контакты</a></li>
                </ul>
            </div>
            <div class="col-md-3 mb-3">
                <h6>Контакты</h6>
                <ul class="list-unstyled text-muted">
                    <li><i class="bi bi-telephone"></i> 8 (999) 315-99-02</li>
                    <li><i class="bi bi-envelope"></i> MirHimii@gmail.com</li>
                </ul>
            </div>
        </div>
        <hr>
        <div class="text-center text-muted">
            <small>&copy; {{ date('Y') }} Мир Химии. Все права защищены.</small>
        </div>
    </div>
</footer>
