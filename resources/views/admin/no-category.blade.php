@extends('layouts.app')

@section('content')
    <h1>Новые товары (без категории)</h1>
    <form method="POST" action="{{ route('admin.bulk-update') }}">
        @csrf
        <div class="mb-3">
            <select name="category_id" class="form-select" required>
                <option value="">Выберите категорию</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary mb-3">Назначить выбранным</button>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th><input type="checkbox" id="selectAll"></th>
                <th>Название</th>
                <th>SKU</th>
                <th>Цена</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach($products as $product)
                <tr>
                    <td><input type="checkbox" name="product_ids[]" value="{{ $product->id }}"></td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->sku }}</td>
                    <td>{{ number_format($product->price, 2) }} ₽</td>
                    <td>
                        <form method="POST" action="{{ route('admin.update-category', $product->id) }}">
                            @csrf
                            <select name="category_id" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">Назначить</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="d-flex flex-column align-items-center mt-4">
            {{ $products->links('vendor.pagination.custom') }}
            <span class="text-muted small mt-2">
        Отображено {{ $products->firstItem() }}–{{ $products->lastItem() }} из {{ $products->total() }} товаров
             </span>
        </div>
    </form>

    <script>
        document.getElementById('selectAll').onclick = function() {
            document.querySelectorAll('input[name="product_ids[]"]').forEach(cb => cb.checked = this.checked);
        }
    </script>
@endsection
