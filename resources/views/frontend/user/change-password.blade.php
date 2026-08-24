@extends('layouts.frontend.user')

@section('title', __('menu.user.change-password'))

@section('user-content')
    <script type="text/javascript">
        document.addEventListener('alpine:init', () => {
            Alpine.data('userChangePassword', (initialTitle) => {
                return {
                    loading: false,
                    form: {
                        oldPassword: '',
                        newPassword: '',
                        confirmNewPassword: ''
                    },
                    toast: {
                        show: false,
                        message: '',
                        type: 'success'
                    },
                    minLengthPassword: parseInt('{{ constants('minLengthPassword') }}'),
                    showToast(msg, type = 'success') {
                        this.toast.message = msg;
                        this.toast.type = type;
                        this.toast.show = true;
                        setTimeout(() => this.toast.show = false, 3000);
                    },
                    async handleSubmit(event) {
                        this.loading = true;
                        try {
                            if (this.form.oldPassword === "") {
                                throw Error(
                                    '{{ __('error.cannot_be_empty', ['Name' => __('user.old.password.title')]) }}'
                                );
                            }
                            if (this.form.newPassword === "") {
                                throw Error(
                                    '{{ __('error.cannot_be_empty', ['Name' => __('user.new.password.title')]) }}'
                                );
                            }
                            if (this.form.newPassword.length < this.minLengthPassword) {
                                throw Error(
                                    '{{ __('error.min_characters_required', ['Name' => __('user.new.password.title'), 'Length' => constants('minLengthPassword')]) }}'
                                );
                            }
                            if (this.form.confirmNewPassword !== this.form.newPassword) {
                                throw Error('{{ __('error.passwords_do_not_match') }}');
                            }
                            if (this.form.oldPassword === this.form.newPassword) {
                                throw Error('{{ __('error.auth.password.new.same.as.current') }}');
                            }

                            const res = await $fetch.put(`/user/user/password`, {
                                current_password: this.form.oldPassword,
                                password: this.form.newPassword,
                                password_confirmation: this.form.confirmNewPassword,
                            });
                            const data = await res.json();

                            if (res.status === 201) {
                                alert(data.message)

                                setTimeout(() => {
                                    $action.userLogout();
                                }, 1000);
                            } else {
                                const errors = data.errors ? Object.values(data.errors).flat() : [];
                                this.showToast(errors.join("\n") || (data.message || ''), 'error');
                            }
                        } catch (err) {
                            this.showToast(err.message, 'error');
                        } finally {
                            this.loading = false;
                        }
                    },
                }
            })
        })
    </script>
    <div class="divider">{{ __('user.change-password') }}</div>
    <div @submit.prevent="handleSubmit" x-data="userChangePassword">
        <div x-show="toast.show" x-transition class="toast toast-top toast-end z-50">
            <div class="alert" :class="toast.type === 'success' ? 'alert-success' : 'alert-error'">
                <span x-text="toast.message"></span>
            </div>
        </div>
        <form method="post">
            <div class="card bg-base-100 m-auto w-full max-w-sm shrink-0 shadow-2xl">
                <div class="card-body">
                    <fieldset class="fieldset">
                        <input class="hidden" type="email" name="email" autocomplete="username">
                        <label class="label">{{ __('user.old.password.title') }}</label>
                        <input type="password" x-model="form.oldPassword" class="input"
                            placeholder='{{ __('placeholder.input.value', ['Name' => __('user.old.password.title')]) }}'
                            autocomplete="new-password" required />
                        <div class="divider"></div>
                        <label class="label">{{ __('user.new.password.title') }}</label>
                        <input type="password" x-model="form.newPassword" class="input"
                            placeholder='{{ __('placeholder.input.value', ['Name' => __('user.new.password.title')]) }}'
                            autocomplete="new-password" required />
                        <label class="label">{{ __('user.confirm.password.title') }}</label>
                        <input type="password" x-model="form.confirmNewPassword" class="input"
                            placeholder='{{ __('placeholder.input.value', ['Name' => __('user.confirm.password.title')]) }}'
                            autocomplete="new-password" required />
                        <button class="btn btn-primary mt-4">{{ __('user.change-password') }}</button>
                    </fieldset>
                </div>
            </div>
        </form>
    </div>
@endsection
