{{-- resources/views/admin/products/edit.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3>Редактирование товара: {{ $product->name }}</h3>
                <a href="{{ url('/catalog') }}" class="btn btn-secondary">← Назад к каталогу</a>
            </div>

            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('admin.products.update', $product->id) }}">
                    @csrf
                    @method('PUT')

                    {{-- Название --}}
                    <div class="mb-3">
                        <label class="form-label">Название</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $product->name) }}" required disabled>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Категория --}}
                    <div class="mb-3">
                        <label class="form-label">Категория</label>
                        <select name="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                            <option value="">Выберите категорию</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Цена --}}
                    <div class="mb-3">
                        <label class="form-label">Цена (₽)</label>
                        <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                               value="{{ old('price', $product->price) }}" step="0.01" required disabled>
                        @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Описание --}}
                    <div class="mb-3">
                        <label class="form-label">Описание</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                  rows="5">{{ old('description', $product->description) }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Кнопки --}}
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                    <a href="{{ url('/catalog') }}" class="btn btn-secondary">Отмена</a>
                </form>
            </div>

            {{-- Блок с изображениями --}}
            <div class="card-footer">
                <h4>Изображения товара</h4>
                <div class="row mb-3">
                    @foreach($product->images as $image)
                        <div class="col-md-2 text-center">
                            <img src="{{ $image->image_path }}" style="width: 100px; height: 100px; object-fit: cover;" class="img-thumbnail">
                            @if($image->is_main)
                                <span class="badge bg-success d-block mt-1">Главное</span>
                            @endif
                        </div>
                    @endforeach
                </div>
                <a href="/admin/products/{{ $product->id }}/images" class="btn btn-primary">Редактирование изображений</a>
            </div>
        </div>
    </div>
@endsection
