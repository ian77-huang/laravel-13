@extends('layouts.frontend')

@section('title', '首頁')

@section('content')
    <div class="card bg-base-100 card-md mt-5 w-full shadow-sm">
        <div class="card-body">
            <h2 class="card-title">每日經文</h2>
            <div class="p-5 text-xl">
                @if ($bible)
                    <div>{{ $bible->text ?? '' }}</div>
                    <div class="mt-2">( {{ $bible->book_name }} {{ $bible->chapter }}:{{ $bible->verse }} )</div>
                @else
                    <div>找不到經文</div>
                @endif
            </div>
        </div>
    </div>
    <div class="card bg-base-100 card-md mt-5 w-full shadow-sm">
        <div class="card-body">
            <h2 class="card-title">Youtube Channel</h2>
            <div class="p-5 text-xl">
                <section
                    class="justify=items-start grid w-full grid-cols-2 gap-1 md:grid-cols-3 md:gap-2.5 md:p-5 xl:grid-cols-4">
                    @if ($youtubeChannels)
                        @foreach ($youtubeChannels as $channel)
                            <a class="link link-hover" href="/youtube/channel/{{ $channel->id }}">
                                <div class="card bg-base-100 card-md mt-5 w-full shadow-sm">
                                    <div class="card-body">
                                        <div class="grid h-full grid-rows-[auto_auto_1fr]">
                                            <div>
                                                <Image class="m-auto rounded-md" aria-label="{{ $channel->title }} 圖片"
                                                    alt="{{ $channel->title }} 圖片" width="240" height="240"
                                                    src="{{ resizeImageYoutubeChannel($channel, 240) }}" />
                                            </div>
                                            <div class="mb-1 mt-5 h-px border-t border-t-gray-200"></div>
                                            <div class="flex items-center justify-center">
                                                <div>{{ $channel->title }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    @else
                        <div>無 Youtube Channel</div>
                    @endif
                </section>
            </div>
        </div>
    </div>
@endsection
