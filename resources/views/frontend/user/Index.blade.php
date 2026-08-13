@extends('layouts.frontend.user')

@section('title', __('menu.user.login'))

@section('user-content')
    <div class="container mx-auto max-w-4xl px-4 py-8">
        <div>
            <div class="navbar bg-base-100 rounded-box mb-6 px-6 shadow-md">
                <div class="flex-1">
                    <span class="text-primary text-xl font-bold">User Dashboard</span>
                </div>
                <div class="flex-none gap-4">
                    {{-- {{ if .user.Account }}
                <div class="badge badge-outline">{{ __('user.auth.account') }}：{{ .user.Account }}</div>
                {{ end }}
                {{ if .user.IsAdmin }}
                <div class="badge badge-secondary">{{ __('user.role_admin') }}</div>
                {{ else }}
                <div class="badge badge-primary">{{ __('user.role_member') }}</div>
                {{ end }} --}}
                </div>
            </div>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <div class="card bg-base-100 shadow-md">
                    <div class="card-body items-center text-center">
                        <div class="avatar placeholder mb-4">
                            <div class="text-neutral-content w-24 rounded-full">
                                {{-- {{if and .profile .profile.AvatarURL }}
                            <img src='{{ .profile.AvatarURL }}' alt="Avatar" />
                            {{else}}
                            <svg class="w-full h-full text-base-content/30" viewBox="0 0 250 250" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect width="250" height="250" fill="currentColor" class="opacity-10" />
                                <circle cx="125" cy="100" r="35" fill="currentColor" class="opacity-40" />
                                <path
                                    d="M60 190C60 156.863 86.863 130 120 130H130C163.137 130 190 156.863 190 190V205H60V190Z"
                                    fill="currentColor" class="opacity-40" />
                            </svg>
                            {{end}} --}}
                            </div>
                        </div>
                        <h2 class="card-title">
                            {{-- {{if and .profile .profile.Name}}
                        <p>{{ __('user.profile.settings.name') }}：{{.profile.Name}}</p>
                        {{else}}
                        {{ __('user.name_not_set') }}
                        {{end}} --}}
                        </h2>
                        <p class="text-base-content/60 text-sm">
                            {{-- {{if and .profile .profile.Email}}
                        {{.profile.Email}}
                        {{else}}
                        {{ __('user.email_not_set') }}
                        {{end}} --}}
                        </p>
                        <div class="divider my-2"></div>
                        <div class="flex items-center gap-2">
                            <span class="text-sm">{{ __('user.account_status') }}:</span>
                            {{-- {{if and .user .user.IsActive}}
                        <span class="badge badge-success">{{ __('user.status_enabled') }}</span>
                        {{else}}
                        <span class="badge badge-error">{{ __('user.status_disabled') }}</span>
                        {{end}} --}}
                        </div>
                    </div>
                </div>
                <div class="card bg-base-100 shadow-md md:col-span-2">
                    <div class="card-body">
                        {{-- {{if and .profile}}
                    <div>
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-bold">{{ __('user.profile_details') }}</h3>
                            <a class="btn btn-primary" href="/user/profile">
                                {{ __('user.fill_profile_now') }}
                            </a>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <span class="text-xs text-base-content/50 block">{{
                                    __('user.profile.settings.name') }}</span>
                                <p class="font-medium">{{.profile.Name}}</p>
                            </div>
                            <div>
                                <span class="text-xs text-base-content/50 block">{{
                                    __('user.profile.settings.email.title') }}</span>
                                <p class="font-medium">{{.profile.Email}}</p>
                            </div>
                            <div>
                                <span class="text-xs text-base-content/50 block">{{
                                    __('user.profile.settings.phone.title') }}</span>
                                <p class="font-medium">{{.profile.Phone}}</p>
                            </div>
                            <div>
                                <span class="text-xs text-base-content/50 block">{{
                                    __('user.profile.settings.bio.title') }}</span>
                                <p class="font-medium whitespace-pre-line">
                                    {{.profile.Bio}}
                                </p>
                            </div>
                        </div>
                    </div>
                    {{else}}
                    <div class="text-center py-8">
                        <div class="text-5xl mb-4">📝</div>
                        <h3 class="text-lg font-bold">{{ __('user.profile_incomplete') }}</h3>
                        <p class="text-base-content/60 mt-1 mb-6 text-sm">{{ __('user.profile_hint') }}</p>
                        <a class="btn btn-primary" href="/user/profile">
                            {{ __('user.fill_profile_now') }}
                        </a>
                    </div>
                    {{end}} --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
