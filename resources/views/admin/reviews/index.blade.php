@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Управление отзывами</h1>

        {{-- Статус-фильтры --}}
        <div class="mb-4">
            <div class="btn-group">
                <a href="{{ route('admin.reviews.index', ['status' => 'new']) }}"
                   class="btn {{ $status == 'new' ? 'btn-primary' : 'btn-outline-primary' }}">
                    Новые ({{ $counts['new'] }})
                </a>
                <a href="{{ route('admin.reviews.index', ['status' => 'approved']) }}"
                   class="btn {{ $status == 'approved' ? 'btn-success' : 'btn-outline-success' }}">
                    Одобренные ({{ $counts['approved'] }})
                </a>
                <a href="{{ route('admin.reviews.index', ['status' => 'rejected']) }}"
                   class="btn {{ $status == 'rejected' ? 'btn-danger' : 'btn-outline-danger' }}">
                    Отклоненные ({{ $counts['rejected'] }})
                </a>
                <a href="{{ route('admin.reviews.index', ['status' => '']) }}"
                   class="btn {{ empty($status) ? 'btn-secondary' : 'btn-outline-secondary' }}">
                    Все ({{ $counts['total'] }})
                </a>
            </div>
        </div>

        {{-- Таблица отзывов --}}
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Пользователь</th>
                    <th>Товар</th>
                    <th>Оценка</th>
                    <th>Отзыв</th>
                    <th>Статус</th>
                    <th>Дата</th>
                </tr>
                </thead>
                <tbody>
                @forelse($reviews as $review)
                    <tr>
                        <td>{{ $review->id }}</td>
{{--                        <td>{{ $review->user->login() }}</td>--}}
                        <td>{{ $review->product->name }}</td>
                        <td>
                            <div class="text-warning">
                                @for($i = 1; $i <= 5; $i++)
                                    {{ $i <= $review->rating ? '★' : '☆' }}
                                @endfor
                            </div>
                        </td>
                        <td>{{ Str::limit($review->comment, 50) }}</td>
                        <td>
                        <span class="badge bg-{{ $review->status == 'approved' ? 'success' : ($review->status == 'rejected' ? 'danger' : 'warning') }}">
                            {{ $review->status == 'approved' ? 'Одобрен' : ($review->status == 'rejected' ? 'Отклонен' : 'Новый') }}
                        </span>
                        </td>
                        <td>{{ $review->created_at->format('d.m.Y H:i') }}</td>
                        <td>
                            <form action="{{ route('admin.reviews.update-status', $review) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="form-select form-select-sm d-inline-block w-auto" onchange="this.form.submit()">
                                    <option value="new" {{ $review->status == 'new' ? 'selected' : '' }}>Новый</option>
                                    <option value="approved" {{ $review->status == 'approved' ? 'selected' : '' }}>Одобрить</option>
                                    <option value="rejected" {{ $review->status == 'rejected' ? 'selected' : '' }}>Отклонить</option>
                                </select>
                            </form>

                            <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="d-inline" onsubmit="return confirm('Удалить отзыв?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">🗑</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">Нет отзывов</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{ $reviews->links() }}
    </div>
@endsection
