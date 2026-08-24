@extends('layouts.frontend.user')

@section('title', __('menu.user'))

@section('user-content')
    <script type="text/javascript">
        document.addEventListener('alpine:init', () => {
            Alpine.data('friendsApp', () => {
                return {
                    users: @json($users),
                    loading: false,
                    search: '',
                    toast: {
                        show: false,
                        message: '',
                        type: 'success',
                    },

                    get filteredUsers() {
                        const keyword = this.search.trim().toLowerCase();

                        if (keyword === '') {
                            return this.users;
                        }

                        return this.users.filter(user =>
                            (user.name || '').toLowerCase().includes(keyword) ||
                            (user.account || '').toLowerCase().includes(keyword)
                        );
                    },

                    showToast(message, type = 'success') {
                        this.toast.message = message;
                        this.toast.type = type;
                        this.toast.show = true;
                        setTimeout(() => {
                            this.toast.show = false;
                        }, 3000);
                    },

                    normalizeUser(user) {
                        user.submitting = false;
                        user.friend_status = user.friend_status ?? 'none';

                        return user;
                    },

                    async init() {
                        await this.fetchUsers();
                    },

                    async fetchUsers() {
                        this.loading = true;
                        try {
                            const res = await $fetch.get('/api/user/friends/list');
                            const data = await res.json();
                            this.users = data.users.map(user => this.normalizeUser(user));
                        } catch (err) {
                            console.error(err);
                            this.showToast(@json(__('user.friendships.action.failed')), 'error');
                        } finally {
                            this.loading = false;
                        }
                    },

                    async friendAction(user, action) {
                        const methods = { request: 'post', accept: 'post', reject: 'post', remove: 'delete' };

                        user.submitting = true;
                        try {
                            const res = await $fetch[methods[action]](`/api/user/friends/${action}/${user.id}`);
                            const data = await res.json();

                            if (!res.ok) {
                                this.showToast(data.message ?? @json(__('user.friendships.action.failed')), 'error');

                                return;
                            }

                            user.friend_status = data.data.friend_status;
                            this.showToast(data.message);
                        } catch (err) {
                            console.error(err);
                            this.showToast(@json(__('user.friendships.action.failed')), 'error');
                        } finally {
                            user.submitting = false;
                        }
                    },
                }
            })
        })
    </script>

    <div class="divider">{{ __('user.friendships.title') }}</div>

    <div x-data="friendsApp()">
        <div class="mx-auto max-w-4xl px-4">
            <!-- 頂部 Header -->
            <div class="navbar bg-base-100 rounded-box mb-6 flex justify-between px-4 shadow-sm">
                <div class="text-lg font-bold">社群好友系統</div>
            </div>

            <!-- 搜尋與使用者列表 -->
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold">探索使用者</h1>
                <input type="text" x-model="search" placeholder="{{ __('user.friendships.search.placeholder') }}"
                    class="input input-bordered w-full max-w-xs" />
            </div>

            <!-- Toast -->
            <div x-show="toast.show" x-transition.opacity role="alert"
                :class="toast.type === 'success' ? 'alert-success' : 'alert-error'"
                class="toast toast-end fixed z-50">
                <span x-text="toast.message"></span>
            </div>

            <template x-if="loading">
                <div class="my-12 flex justify-center">
                    <span class="loading loading-spinner loading-lg text-primary"></span>
                </div>
            </template>

            <template x-if="!loading && filteredUsers.length === 0">
                <div class="text-base-content/60 my-12 text-center">沒有符合的使用者</div>
            </template>

            <!-- 使用者卡片列表 -->
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2" x-show="!loading">
                <template x-for="user in filteredUsers" :key="user.id">
                    <div class="card card-side bg-base-100 border-base-300 items-center border p-4 shadow-sm">
                        <div class="avatar">
                            <div class="h-14 w-14 rounded-full">
                                <img :src="user.avatar_url || 'https://api.dicebear.com/7.x/bottts/svg?seed=' + encodeURIComponent(user.name || user.account || 'user')"
                                    :alt="user.name" />
                            </div>
                        </div>

                        <div class="card-body px-4 py-0 min-w-0">
                            <h2 class="card-title text-base truncate" x-text="(user.name || user.account)"></h2>
                            <p class="text-xs text-gray-400" x-text="`ID: ${user.id}`"></p>
                        </div>

                        <div class="card-actions justify-end items-center">
                            <!-- 狀態 1：可加好友 -->
                            <template x-if="user.friend_status === 'none'">
                                <button class="btn btn-primary btn-sm" :disabled="user.submitting"
                                    @click="friendAction(user, 'request')">
                                    <span x-show="user.submitting" class="loading loading-spinner loading-xs"></span>
                                    加為好友
                                </button>
                            </template>

                            <!-- 狀態 2：已發送邀請 -->
                            <template x-if="user.friend_status === 'pending_sent'">
                                <div class="flex w-full flex-wrap justify-center items-center gap-2">
                                    <button class="btn btn-ghost btn-sm text-gray-400" disabled>邀請已送出</button>
                                    <button class="btn btn-outline btn-error btn-sm" :disabled="user.submitting"
                                        @click="friendAction(user, 'reject')">取消</button>
                                </div>
                            </template>

                            <!-- 狀態 3：收到邀請 -->
                            <template x-if="user.friend_status === 'pending_received'">
                                <div class="flex w-full flex-wrap justify-center items-center gap-2">
                                    <span class="badge badge-warning p-3">等待您確認</span>
                                    <button class="btn btn-success btn-sm" :disabled="user.submitting"
                                        @click="friendAction(user, 'accept')">接受</button>
                                    <button class="btn btn-outline btn-error btn-sm" :disabled="user.submitting"
                                        @click="friendAction(user, 'reject')">拒絕</button>
                                </div>
                            </template>

                            <!-- 狀態 4：已是好友 -->
                            <template x-if="user.friend_status === 'friend'">
                                <div class="flex w-full flex-wrap justify-center items-center gap-2">
                                    <div class="badge badge-success p-3 text-white">✓ 好友</div>
                                    <button class="btn btn-outline btn-error btn-sm" :disabled="user.submitting"
                                        @click="friendAction(user, 'remove')">刪除好友</button>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
@endsection
