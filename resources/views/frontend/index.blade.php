@extends('layouts.frontend')

@section('title', '首頁')

@section('content')
<div class="card w-full bg-base-100 card-md shadow-sm mt-5">
    <div class="card-body">
        <h2 class="card-title">每日經文</h2>
         <div class="text-xl p-5">
            @if ($bible)
                <div>{{ $bible->text ?? '' }}</div>
                <div class="mt-2">( {{ $bible->book_name }} {{ $bible->chapter }}:{{ $bible->verse }} )</div>
            @else
                <div>找不到經文</div>
            @endif
         </div>
    </div>
</div>
@endsection
