@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3>Управление изображениями: {{ $product->name }}</h3>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-sm">Назад к товарам</a>
            </div>

            <div class="card-body">
                {{-- Форма массовой загрузки --}}
                <div class="mb-4">
                    <label class="form-label fw-bold">Загрузить новые изображения</label>
                    <form id="uploadForm" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <input type="file" name="images[]" id="images" multiple accept="image/*" class="form-control">
                            <small class="text-muted">Можно выбрать до 10 изображений (JPEG, PNG, GIF, WebP)</small>
                        </div>
                        <button type="submit" class="btn btn-primary" id="uploadBtn">
                            <i class="fas fa-upload"></i> Загрузить
                        </button>
                    </form>
                    <div id="uploadProgress" class="mt-2" style="display: none;">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 100%">
                                Загрузка...
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                {{-- Список изображений --}}
                <div class="row" id="imagesList">
                    @forelse($images as $image)
                        <div class="col-md-3 mb-3" data-image-id="{{ $image->id }}">
                            <div class="card h-100">
                                <img src="{{ $image->image_path }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="Image">
                                <div class="card-body text-center">
                                    @if($image->is_main)
                                        <span class="badge bg-success mb-2">⭐ Главное</span>
                                    @endif
                                    <div class="btn-group w-100" role="group">
                                        @if(!$image->is_main)
                                            <button class="btn btn-sm btn-outline-primary set-main-btn" data-image-id="{{ $image->id }}">
                                                <i class="fas fa-star"></i> Сделать главным
                                            </button>
                                        @endif
                                        <button class="btn btn-sm btn-outline-danger delete-btn" data-image-id="{{ $image->id }}">
                                            <i class="fas fa-trash"></i> Удалить
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info">Нет загруженных изображений</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script>
        // Массовая загрузка
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            e.preventDefault();

            let formData = new FormData(this);
            let uploadBtn = document.getElementById('uploadBtn');
            let progressDiv = document.getElementById('uploadProgress');

            uploadBtn.disabled = true;
            progressDiv.style.display = 'block';

            fetch('{{ route("admin.products.images.store", $product) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload(); // Перезагружаем страницу для показа новых фото
                    } else {
                        alert('Ошибка: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Ошибка загрузки: ' + error);
                })
                .finally(() => {
                    uploadBtn.disabled = false;
                    progressDiv.style.display = 'none';
                });
        });

        // Установка главного изображения
        document.querySelectorAll('.set-main-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                let imageId = this.dataset.imageId;

                fetch('{{ route("admin.products.images.set-main", ["product" => $product, "image" => ":imageId"]) }}'.replace(':imageId', imageId), {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        }
                    });
            });
        });

        // Удаление изображения
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                if (!confirm('Удалить изображение?')) return;

                let imageId = this.dataset.imageId;
                let imageDiv = document.querySelector(`[data-image-id="${imageId}"]`);

                fetch('{{ route("admin.products.images.destroy", ["product" => $product, "image" => ":imageId"]) }}'.replace(':imageId', imageId), {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            imageDiv.remove();
                            if (document.querySelectorAll('[data-image-id]').length === 0) {
                                location.reload();
                            }
                        }
                    });
            });
        });
    </script>
@endsection
