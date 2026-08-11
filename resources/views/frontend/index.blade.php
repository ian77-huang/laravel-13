@extends('layouts.frontend.base')

@section('title', __('menu.index'))

@section('content')
    <x-layouts.mc>
        <x-fieldset class="mb-5">
            <x-slot:icon><x-icon.solid.book-bible /></x-slot:icon>
            <x-card>
                <section>
                    @if ($bible)
                        {{ $bible->text ?? '' }}( {{ $bible->book_name }} {{ $bible->chapter }}:{{ $bible->verse }} )
                    @else
                        <div>{{ __('bible.not_found') }}</div>
                    @endif
                </section>
            </x-card>
        </x-fieldset>
        <x-fieldset class="mb-5">
            <x-slot:icon><x-icon.brands.youtube /></x-slot:icon>
            <section
                class="justify=items-start grid w-full grid-cols-2 gap-1 md:grid-cols-3 md:gap-2.5 md:p-5 xl:grid-cols-4">
                @if ($youtubeChannels)
                    @foreach ($youtubeChannels as $channel)
                        <a class="link link-hover flex h-full" href="/youtube/channel/{{ $channel->id }}">
                            <x-card>
                                <div class="grid h-full grid-rows-[auto_auto_1fr]">
                                    <div>
                                        <x-img class="m-auto rounded-md" aria-label="{{ $channel->title }} {{ __('youtube.image') }}"
                                            alt="{{ $channel->title }} {{ __('youtube.image') }}" width="240" height="240"
                                            src="{{ resizeImageYoutubeChannel($channel, 240) }}" />
                                    </div>
                                    <div class="mb-1 mt-5 h-px border-t border-t-gray-200"></div>
                                    <div class="flex items-center justify-center">
                                        <div>{{ $channel->title }}</div>
                                    </div>
                                </div>
                            </x-card>
                        </a>
                    @endforeach
                @else
                    <div>{{ __('youtube.empty') }}</div>
                @endif
            </section>
        </x-fieldset>
    </x-layouts.mc>
@endsection
