@extends('layouts.base')

@section('layout-content')
<div class="navbar bg-base-100 shadow-sm" x-data="layoutApp">
  <div class="navbar-start">
    <div class="dropdown">
      <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" />
        </svg>
      </div>
      <ul tabindex="-1" class="menu menu-sm dropdown-content bg-base-100 rounded-box z-1 mt-3 w-52 p-2 shadow">
        {{-- {{range .Menus}}
        {{ if gt (len .Childs) 0 }}
        <li>
          <a>{{.Name}}</a>
          <ul class="p-2">
            {{ range .Childs }}
            <li><a href="{{.Url}}">{{.Name}}</a></li>
            {{ end }}
          </ul>
        </li>
        {{ else }}
        <li><a href="{{.Url}}">{{.Name}}</a></li>
        {{end}}
        {{end}} --}}
      </ul>
    </div>
    <a class="btn btn-ghost text-xl">rbbr</a>
  </div>
  <div class="navbar-center hidden lg:flex">
    <ul class="menu menu-horizontal px-1">
      {{-- {{range .Menus}}
      {{ if gt (len .Childs) 0 }}
      <li class="relative group flex items-center">
        <a class="cursor-pointer gap-0.5" href="{{.Url}}">
          {{ .Name }}
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
            <path fill-rule="evenodd"
              d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
              clip-rule="evenodd" />
          </svg>
        </a>
        <ul class="absolute left-0 top-full ml-0 hidden group-hover:block bg-base-100 rounded-box shadow p-2 z-10">
          {{ range .Childs }}
          <li>
            <a href="{{ .Url }}">{{ .Name }}</a>
          </li>
          {{ end }}
        </ul>
      </li>
      {{ else }}
      <li><a href="{{.Url}}">{{.Name}}</a></li>
      {{end}}
      {{end}} --}}
    </ul>
  </div>
  <div class="navbar-end gap-3">
    {{-- <div class="dropdown dropdown-end">
      <div tabindex="0" role="button" class="btn btn-ghost btn-circle">
        <div class="indicator">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
          </svg>
          <template x-if="unreadCount > 0">
            <span class="badge badge-xs badge-primary indicator-item"></span>
          </template>
        </div>
      </div>
      <ul tabindex="0"
        class="dropdown-content menu bg-base-100 rounded-box z-1 w-80 p-2 shadow border border-base-200">
        <li class="menu-title font-bold text-gray-700">通知訊息</li>
        <template x-for="notif in notifications" :key="notif.id">
          <li class="border-b border-base-200 text-xs">
            <div class="flex flex-col items-start gap-1 py-2">
              <div class="flex items-center gap-2">
                <!-- 根據 Enum Type 呈現不同 Badges -->
                <span class="badge badge-xs" :class="{
                          'badge-primary': notif.type === 'friend_request',
                          'badge-success': notif.type === 'friend_accept',
                          'badge-info': notif.type === 'system_notice'
                        }" x-text="getEventTypeLabel(notif.type)"></span>
                <span class="font-semibold text-gray-800" x-text="notif.sender ? notif.sender.username : '系統'"></span>
              </div>
              <div class="text-gray-600" x-text="notif.message"></div>
            </div>
          </li>
        </template>
        <template x-if="notifications.length === 0">
          <li class="text-center py-4 text-gray-400">目前沒有新通知</li>
        </template>
      </ul>
    </div> --}}
    {{-- <select class="select w-25" @change="LangHandler">
      <option {{if eq .Lang "en" }}selected{{end}} value="en">English</option>
      <option {{if eq .Lang "zh-TW" }}selected{{end}} value="zh-TW">繁體中文</option>
    </select> --}}
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
<main>@yield('content')</main>
<footer class="bg-white border-t border-gray-200 mt-20">
  <div class="max-w-6xl mx-auto px-4 py-6 text-center text-xs text-gray-400">
    &copy; 2026 Go Echo Web Project - Go 1.26.1 - Echo v5 - ServerName
    {{-- &copy; 2026 Go Echo Web Project - Go 1.26.1 - Echo v5 - ServerName({{.ServerName}}) --}}
  </div>
</footer>
@endsection
