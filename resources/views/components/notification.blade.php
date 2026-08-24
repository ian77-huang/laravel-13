@props([])
<div class="dropdown dropdown-end" x-cloak x-data="{ open: false }"
    x-init="$store.notification.fetch()"
    :class="open && 'dropdown-open'" @click.outside="open = false">
    <div role="button" class="btn btn-ghost btn-circle"
        @click="open = !open; $store.notification.fetch()">
        <div class="indicator">
            <span x-show="$store.notification.unreadCount > 0" x-text="$store.notification.unreadCount"
                class="indicator-item badge badge-secondary badge-sm">0</span>
            <button class="btn btn-square">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
            </button>
        </div>
    </div>

    <!-- 下拉通知選單內容 -->
    <div class="dropdown-content menu bg-base-100 rounded-box z-1 w-80 p-2 shadow">
        <div class="border-base-200 flex items-center justify-between border-b px-4 py-2">
            <span class="text-lg font-bold">{{ __('user.notifications.title') }}</span>
            <span x-show="$store.notification.unreadCount > 0"
                @click="$store.notification.markAllRead()"
                class="text-primary cursor-pointer text-xs hover:underline">{{ __('user.notifications.mark_all_read') }}</span>
        </div>

        <ul class="py-2">
            <template x-if="$store.notification.loading && !$store.notification.loaded">
                <li class="flex justify-center p-6">
                    <span class="loading loading-spinner loading-md text-primary"></span>
                </li>
            </template>

            <template x-if="$store.notification.loaded && $store.notification.items.length === 0">
                <li class="text-base-content/60 p-6 text-center text-sm">{{ __('user.notifications.empty') }}</li>
            </template>

            <template x-for="item in $store.notification.items" :key="item.id">
                <li>
                    <a class="flex flex-col items-start gap-1 p-3"
                        :class="!item.is_read && 'bg-base-200'"
                        @click="$store.notification.markRead(item)">
                        <div class="text-sm font-semibold" :class="!item.is_read && 'font-bold'"
                            x-text="item.message"></div>
                        <div class="text-base-content/70 w-full text-right text-xs"
                            x-text="$store.notification.formatTime(item.created_at)"></div>
                    </a>
                </li>
            </template>
        </ul>
    </div>
</div>
