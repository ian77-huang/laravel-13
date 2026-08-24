@props([])
<div x-data class="toast toast-bottom toast-end z-50" x-cloak>
    <template x-for="item in $store.toast.items" :key="item.id">
        <div role="alert" class="alert alert-vertical sm:alert-horizontal min-w-75">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                :class="`text-${item.type} h-6 w-6 shrink-0 stroke-current`">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <h3 class="text-base font-bold" x-show="item.title" x-text="item.title"></h3>
                <div class="text-xl" x-text="item.message"></div>
            </div>
            <div @click="$store.toast.close(item.id)" class="cursor-pointer text-xl" aria-label="關閉">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                    <path
                        d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
                </svg>
            </div>
        </div>
    </template>
</div>
