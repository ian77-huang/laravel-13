@extends('layouts.frontend')

@section('title', '首頁')

@section('content')
    <div class="card bg-base-100 shadow">
        <div class="card-body">
            <h1 class="card-title">12355</h1>
            @if ($bible)
                <p>{{ $bible->text ?? '' }}({{ $bible->book_name }} {{ $bible->chapter }}:{{ $bible->verse }})</p>
            @else
                <p>找不到經文</p>
            @endif
        </div>
    </div>
@endsection
