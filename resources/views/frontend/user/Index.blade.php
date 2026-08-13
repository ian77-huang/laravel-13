@extends('layouts.frontend.user')

@section('title', __('menu.user'))

@section('user-content')
    <div class="container mx-auto max-w-4xl">
        <div>
            <div class="navbar bg-base-100 rounded-box mb-6 px-6 shadow-md">
                <div class="flex-1">
                    <span class="text-primary text-xl font-bold">User Dashboard</span>
                </div>
                <div class="flex-none gap-4">
                    @if ($user && $user->is_admin)
                        <div class="badge badge-secondary">{{ __('user.role_admin') }}</div>
                    @else
                        <div class="badge badge-primary">{{ __('user.role_member') }}</div>
                    @endif
                    @if ($user && $user->email)
                        <div class="badge badge-outline">{{ __('user.auth.account') }}：{{ $user->email }}</div>
                    @endif
                </div>
            </div>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <div class="card bg-base-100 shadow-md">
                    <div class="card-body items-center text-center">
                        <div class="avatar placeholder mb-4">
                            <div class="text-neutral-content w-24 rounded-full">
                                @if ($profile && $profile->avatar_url)
                                    <img src='{{ $profile->avatar_url }}' alt="Avatar" />
                                @else
                                    <svg class="text-base-content/30 h-full w-full" viewBox="0 0 250 250" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <rect width="250" height="250" fill="currentColor" class="opacity-10" />
                                        <circle cx="125" cy="100" r="35" fill="currentColor"
                                            class="opacity-40" />
                                        <path
                                            d="M60 190C60 156.863 86.863 130 120 130H130C163.137 130 190 156.863 190 190V205H60V190Z"
                                            fill="currentColor" class="opacity-40" />
                                    </svg>
                                @endif
                            </div>
                        </div>
                        <h2 class="card-title">
                            @if ($user && $user->name)
                                <p>{{ __('user.profile.settings.name') }}：{{ $user->name }}</p>
                            @else
                                {{ __('user.name_not_set') }}
                            @endif
                        </h2>
                        <p class="text-base-content/60 text-sm">
                            @if ($user && $user->email)
                                {{ $user->email }}
                            @else
                                {{ __('user.email_not_set') }}
                            @endif
                        </p>
                        <div class="divider my-2"></div>
                        <div class="flex items-center gap-2">
                            <div class="flex flex-col">
                                <span class="mb-1 text-sm">{{ __('user.account_status') }}</span>
                                @if ($user && $user->is_active)
                                    <span class="badge badge-success">{{ __('user.status_enabled') }}</span>
                                @else
                                    <span class="badge badge-error">{{ __('user.status_disabled') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card bg-base-100 shadow-md md:col-span-2">
                    <div class="card-body">
                        @if ($profile)
                            <div>
                                <div class="mb-6 flex items-center justify-between">
                                    <h3 class="text-lg font-bold">{{ __('user.profile_details') }}</h3>
                                    <a class="btn btn-primary" href="/user/profile">
                                        {{ __('user.fill_profile_now') }}
                                    </a>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <span
                                            class="text-base-content/50 block text-xs">{{ __('user.profile.settings.name') }}</span>
                                        <p class="font-medium">{{ $profile->name }}</p>
                                    </div>
                                    <div>
                                        <span
                                            class="text-base-content/50 block text-xs">{{ __('user.profile.settings.email.title') }}</span>
                                        <p class="font-medium">{{ $profile->email }}</p>
                                    </div>
                                    <div>
                                        <span
                                            class="text-base-content/50 block text-xs">{{ __('user.profile.settings.phone.title') }}</span>
                                        <p class="font-medium">{{ $profile->phone }}</p>
                                    </div>
                                    <div>
                                        <span
                                            class="text-base-content/50 block text-xs">{{ __('user.profile.settings.bio.title') }}</span>
                                        <p class="whitespace-pre-line font-medium">
                                            {{ $profile->bio }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="py-8 text-center">
                                <div class="mb-4 text-5xl">📝</div>
                                <h3 class="text-lg font-bold">{{ __('user.profile_incomplete') }}</h3>
                                <p class="text-base-content/60 mb-6 mt-1 text-sm">{{ __('user.profile_hint') }}</p>
                                <a class="btn btn-primary" href="/user/profile">
                                    {{ __('user.fill_profile_now') }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
