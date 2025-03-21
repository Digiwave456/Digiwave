@extends('layouts.admin')
@section('content')
    <div class="category-create">
        <form action="/category-create/" method="POST">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">Название</label>
                <input type="text" class="form-control" id="title" name="title">
            </div>
            <input type="submit" value="Потвердить">
        </form>
    </div>
@endsection
