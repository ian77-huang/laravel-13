@extends('layouts.frontend.base')

@section('content')
    <x-layouts.mc>
        <section>
            <div class="flex w-full flex-row gap-5" x-data="usersMenus">
                <ul class="list bg-base-100 rounded-box w-60 shadow-md">
                    <li class="p-4 pb-2 text-xs tracking-wide opacity-60">{{ __('user.title') }}</li>
                    @if (isset($menus['user']['childs']))
                        @foreach ($menus['user']['childs'] as $childs)
                            <li class="list-row mx-1 last:mb-1 hover:bg-gray-200">
                                <div class="list-col-grow">{{ $childs['name'] }}</div>
                                <button class="btn btn-square btn-ghost" @click="gotoUserUrl" data-url="{{ $childs['url'] }}">
                                    <svg class="size-[1.2em]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                        <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2" fill="none"
                                            stroke="currentColor">
                                            <path d="M6 3L20 12 6 21 6 3z"></path>
                                        </g>
                                    </svg>
                            </li>
                        @endforeach
                    @endif
                </ul>
                <section class="flex-1">
                    <div class="w-full">
                        @yield('user-content')
                    </div>
                </section>
            </div>
        </section>
    </x-layouts.mc>
@endsection
