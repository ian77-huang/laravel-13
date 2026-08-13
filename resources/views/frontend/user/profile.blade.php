@extends('layouts.frontend.user')

@section('title', __('menu.user.profile'))

@section('user-content')
    <script type="text/javascript">
        document.addEventListener('alpine:init', () => {
            Alpine.data('userProfile', (initialTitle) => {
                return {
                    loading: false,
                    isEmailDisable: true,
                    form: {
                        id: null,
                        name: @json($profile->name ?? ''),
                        email: @json($profile->email ?? ''),
                        phone: @json($profile->phone ?? ''),
                        bio: @json($profile->bio ?? ''),
                        avatar_url: ''
                    },
                    toast: {
                        show: false,
                        message: '',
                        type: 'success'
                    },
                    showToast(msg, type = 'success') {
                        this.toast.message = msg;
                        this.toast.type = type;
                        this.toast.show = true;
                        setTimeout(() => this.toast.show = false, 3000);
                    },
                    async initData() {
                        try {
                            // const res = await fetch('/api/user/profile');
                            // if (!res.ok) throw new Error('{{ __('user.profile.read.failed') }}');
                            // rs = await res.json();
                            // if (rs.data !== null) {
                            //     this.form = rs.data;
                            // }
                            this.isEmailDisable = (this.form.email !== "")
                        } catch (err) {
                            this.showToast(err.message, 'error');
                        }
                    },
                    validateAll() {
                        try {
                            if (this.form.name === "") {
                                throw '{{ __('error.cannot_be_empty', ['Name' => __('user.profile.settings.name')]) }}';
                            }
                            if (this.form.email === "") {
                                throw '{{ __('error.cannot_be_empty', ['Name' => __('user.profile.settings.email')]) }}';
                            }
                            if (this.form.phone === "") {
                                throw '{{ __('error.cannot_be_empty', ['Name' => __('user.profile.settings.phone')]) }}';
                            }
                            if (this.form.phone.length !== 10) {
                                throw '{{ __('user.profile.settings.phone.invalid_length') }}';
                            }
                            if (this.form.bio === "") {
                                throw '{{ __('error.cannot_be_empty', ['Name' => __('user.profile.settings.bio')]) }}';
                            }
                        } catch (err) {
                            this.showToast(err, 'error');
                            return false;
                        }
                        return true;
                    },
                    async handleSubmit(event) {
                        if (!this.validateAll()) {
                            return;
                        }
                        this.loading = true;
                        try {
                            const res = await fetch('/user/profile', {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    name: this.form.name,
                                    phone: this.form.phone,
                                    email: this.form.email,
                                    bio: this.form.bio,
                                })
                            });

                            const data = await res.json();
                            if (!res.ok) throw new Error(data.error ||
                                '{{ __('user.profile.save.failed') }}');

                            this.showToast('{{ __('user.profile.update.success') }}', 'success');
                        } catch (err) {
                            this.showToast(err.message, 'error');
                        } finally {
                            this.loading = false;
                        }
                    },
                    async uploadAvatar(event) {
                        const file = event.target.files[0];
                        if (!file) return;

                        maxSize = parseInt("{{ $maxSize }}")
                        if (file.size > maxSize) {
                            this.showToast('{{ __('file.upload.size_exceeded', ['Size' => '1']) }}',
                                'error');
                            return;
                        }

                        const formData = new FormData();
                        formData.append('avatar', file);

                        this.loading = true;
                        try {
                            const res = await fetch('/api/user/profile/avatar', {
                                method: 'POST',
                                body: formData
                            });

                            const data = await res.json();
                            if (!res.ok) throw new Error(data.error ||
                                '{{ __('file.upload.failed') }}');

                            // 更新前端對應的 avatar_url (包含防止快取的隨機參數)
                            this.form.avatar_url = data.avatar_url;
                            this.showToast('{{ __('avatar.update.success') }}', 'success');
                        } catch (err) {
                            this.showToast(err.message, 'error');
                        } finally {
                            this.loading = false;
                            // 清空 file input 的值以利重複選取同檔案
                            event.target.value = '';
                        }
                    }
                }
            })
        })
    </script>
    <div class="divider">{{ __('user.profile.title') }}</div>
    <div x-data="userProfile()" x-init="initData()">
        <form method="post" @submit.prevent="handleSubmit">
            <div class="mx-auto max-w-4xl px-4">
                <div x-show="toast.show" x-transition class="toast toast-top toast-end z-50">
                    <div class="alert" :class="toast.type === 'success' ? 'alert-success' : 'alert-error'">
                        <span x-text="toast.message"></span>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                    <div class="card bg-base-100 h-fit shadow-xl">
                        <div class="card-body items-center text-center">
                            <input type="file" id="avatarInput" class="hidden" accept="image/*"
                                @change="uploadAvatar($event)" />
                            <div class="avatar placeholder group relative mb-2 cursor-pointer"
                                @click="document.getElementById('avatarInput').click()">
                                <template x-if="form.avatar_url">
                                    <img :src="form.avatar_url" alt="Avatar"
                                        class="h-full w-full object-cover transition group-hover:opacity-50" />
                                </template>
                                <template x-if="!form.avatar_url">
                                    <svg class="text-base-content/30 h-full w-full" viewBox="0 0 250 250" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <rect width="250" height="250" fill="currentColor" class="opacity-10" />
                                        <circle cx="125" cy="100" r="35" fill="currentColor"
                                            class="opacity-40" />
                                        <path
                                            d="M60 190C60 156.863 86.863 130 120 130H130C163.137 130 190 156.863 190 190V205H60V190Z"
                                            fill="currentColor" class="opacity-40" />
                                    </svg>
                                </template>
                                <div
                                    class="absolute inset-0 flex items-center justify-center rounded-full bg-black/40 text-xs font-semibold text-white opacity-0 transition group-hover:opacity-100">
                                    {{ __('user.profile.changePicture') }}
                                </div>
                            </div>
                            <h2 class="card-title" x-text="form.name"></h2>
                            <p class="text-base-content/60 text-sm" x-text="form.email"></p>
                            <div class="badge badge-primary mt-2">{{ __('user.profile.basicMember') }}</div>
                        </div>
                    </div>
                    <div class="card bg-base-100 gap-6 p-5 shadow-xl md:col-span-2">
                        <form @submit.prevent="submitForm" class="card-body gap-6">
                            <h2 class="card-title border-b pb-2 text-xl font-bold">{{ __('user.profile.settings.title') }}
                            </h2>
                            <div class="grid gap-4">
                                <div class="form-control w-full">
                                    <label class="label"><span
                                            class="label-text font-medium">{{ __('user.profile.settings.name') }}</span></label>
                                    <input type="text" x-model="form.name" class="input input-bordered w-full"
                                        required />
                                </div>
                                <div class="form-control w-full">
                                    <label class="label"><span
                                            class="label-text font-medium">{{ __('user.profile.settings.email') }}</span></label>
                                    <input type="email" x-model="form.email" class="input input-bordered w-full"
                                        :disabled="isEmailDisable" />
                                    <template x-if="isEmailDisable">
                                        <label class="label">
                                            <span
                                                class="label-text-alt text-base-content/50">{{ __('user.profile.settings.email.readonly') }}</span>
                                        </label>
                                    </template>
                                </div>
                            </div>

                            <div class="form-control w-full">
                                <label class="label"><span
                                        class="label-text font-medium">{{ __('user.profile.settings.phone') }}</span></label>
                                <input type="tel" x-model="form.phone" class="input input-bordered w-full"
                                    placeholder="0912345678" />
                            </div>
                            <div class="form-control grid w-full gap-2.5">
                                <label class="label"><span
                                        class="label-text font-medium">{{ __('user.profile.settings.bio') }}</span></label>
                                <textarea x-model="form.bio" class="textarea textarea-bordered h-24 w-full"
                                    placeholder='{{ __('user.profile.settings.bio.placeholder') }}'></textarea>
                            </div>

                            <div class="card-actions justify-end border-t pt-4">
                                <button type="button" @click="initData()" class="btn btn-ghost"
                                    :disabled="loading">{{ __('user.profile.settings.button.reset') }}</button>
                                <button type="submit" class="btn btn-primary" :disabled="loading">
                                    <span class="loading loading-spinner" x-show="loading"></span>
                                    {{ __('user.profile.settings.button.save') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
