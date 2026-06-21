@extends('layouts.app')

@section('content')
    <h1>Товары с категориями</h1>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Название</th>
            <th>Текущая категория</th>
            <th>Изменить</th>
        </tr>
        </thead>
        <tbody>
        @foreach($products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->category->name ?? 'Нет' }}</td>
                <td>
                    <form method="POST" action="{{ route('admin.update-category', $product->id) }}">
                        @csrf
                        <div class="input-group">
                            <select name="category_id" class="form-select">
                                <option value="">Без категории</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary">Сохранить</button>
                        </div>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $products->links() }}
@endsection
