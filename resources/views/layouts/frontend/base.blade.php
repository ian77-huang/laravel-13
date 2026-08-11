@extends('layouts.base')

@section('layout-content')
    <div class="navbar bg-base-100 shadow-sm" x-data="App">
        <div class="navbar-start">
            <div class="dropdown">
                <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" />
                    </svg>
                </div>
                <ul tabindex="-1" class="menu menu-sm dropdown-content bg-base-100 rounded-box z-1 mt-3 w-52 p-2 shadow">
                    @if ($menus)
                        @foreach ($menus as $menu)
                            @if ($menu['childs'])
                                <li>
                                    <a href="{{ $menu['url'] }}">$menu['name']</a>
                                    <ul class="p-2">
                                        @foreach ($menu['childs'] as $child)
                                            <li>
                                                <a href="{{ $child['url'] }}">{{ $child['name'] }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @else
                                <li>
                                    <a href="{{ $menu['url'] }}">{{ $menu['name'] }}</a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                </ul>
            </div>
            <a class="btn btn-ghost text-xl">rbbr</a>
        </div>
        <div class="navbar-center hidden lg:flex">
            <ul class="menu menu-horizontal px-1">
                @if ($menus)
                    @foreach ($menus as $menu)
                        @if ($menu['childs'])
                            <li class="group relative flex items-center">
                                <a class="cursor-pointer gap-0.5" href="{{ $menu['url'] }}">
                                    {{ $menu['name'] }}
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                        class="size-5">
                                        <path fill-rule="evenodd"
                                            d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </a>
                                <ul
                                    class="bg-base-100 rounded-box absolute left-0 top-full z-10 ml-0 hidden p-2 shadow group-hover:block">
                                    @foreach ($menu['childs'] as $child)
                                        <li>
                                            <a href="{{ $child['url'] }}">{{ $child['name'] }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @else
                            <li>
                                <a href="{{ $menu['url'] }}">{{ $menu['name'] }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            </ul>
        </div>
        <div class="navbar-end gap-3">
            <x-locale-switcher />
            {{-- {{ if .IsSignedIn }}
    <button class="btn btn-square" @click="LogoutHandler">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
        class="size-6">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
      </svg>
    </button>
    {{ else }}
    <button class="btn btn-square" @click="LoginHandler">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
        class="size-6">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H2.25" />
      </svg>

    </button>
    {{ end }} --}}
        </div>
    </div>
    <main>
        <div class="m-auto my-10 w-[90%]">
            <section>
                <div class="w-full">
                    @yield('content')
                </div>
            </section>
        </div>
    </main>
    <footer class="mt-20 border-t border-gray-200 bg-white">
        <div class="mx-auto max-w-6xl px-4 py-6 text-center text-xs text-gray-400">
            &copy; 2026 Laravel 13 Web Project - ServerName({{ env('INSTANCE_NAME') }})
        </div>
    </footer>
@endsection
