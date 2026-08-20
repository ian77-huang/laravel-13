@props([])
<div class="dropdown dropdown-end" x-cloak>
    <div tabindex="0" role="button" class="btn btn-ghost btn-circle">
        <div class="indicator">
            <span x-show="$store.notification.hasUnread()" x-text="$store.notification.unreadCount()"
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
    <!-- -->
    <!-- 下拉通知選單內容 -->
    <div tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-1 w-80 p-2 shadow">
        <div class="border-base-200 flex items-center justify-between border-b px-4 py-2">
            <span class="text-lg font-bold">通知</span>
            <span class="text-primary cursor-pointer text-xs hover:underline">全部標為已讀</span>
        </div>

        <ul class="py-2">
            <li>
                <a class="flex flex-col items-start gap-1 p-3">
                    <div class="text-sm font-semibold">系統更新通知</div>
                    <div class="text-base-content/70 text-xs">系統將於今晚 12:00 進行例行維護。</div>
                </a>
            </li>
            <li>
                <a class="flex flex-col items-start gap-1 p-3">
                    <div class="text-sm font-semibold">新訊息</div>
                    <div class="text-base-content/70 text-xs">您有一則來自管理員的新訊息。</div>
                </a>
            </li>
        </ul>

        <div class="border-base-200 border-t p-2 text-center">
            <a class="text-primary block text-xs hover:underline">查看所有通知</a>
        </div>
    </div>
</div>
