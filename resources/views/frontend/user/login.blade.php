@extends('layouts.frontend.user')

@section('title', __('menu.user.login'))

@section('user-content')
    <script type="text/javascript">
        @if ($isRemembered)
            let remember = true;
        @else
            let remember = false;
        @endif
        @if ($email)
            let email = "{{ $email }}";
        @else
            let email = "";
        @endif
        document.addEventListener('alpine:init', () => {
            Alpine.data('userLogin', (initialTitle) => {
                return {
                    form: {
                        email: email,
                        password: '',
                        remember: remember
                    },
                    __errorMessage: [],
                    getErrorMessage() {
                        return this.__errorMessage.join("\n");
                    },
                    setErrorMessage(value = "", reset = false) {
                        if (reset) {
                            this.__errorMessage = [];
                        }
                        if (value !== "") {
                            this.__errorMessage.push(value);
                        }
                    },
                    validateField(field) {
                        if (field === 'email') {
                            if (!this.form.email) {
                                this.setErrorMessage(
                                    '{{ __('error.cannot_be_empty', ['Name' => __('user.auth.email')]) }}'
                                );
                            }
                            if (!$validation.isEmail(this.form.email)) {
                                this.setErrorMessage(
                                    '{{ __('error.invalid_format', ['Name' => __('user.auth.email')]) }}'
                                );
                            }
                        }

                        if (field === 'password') {
                            if (!this.form.password) {
                                this.setErrorMessage(
                                    '{{ __('error.cannot_be_empty', ['Name' => __('user.auth.password')]) }}'
                                );
                            }
                        }
                    },
                    validateAll() {
                        this.setErrorMessage("", true);
                        this.validateField('email');
                        this.validateField('password');
                        return this.__errorMessage.length === 0;
                    },
                    async handleSubmit(event) {
                        if (!this.validateAll()) {
                            return;
                        }
                        const res = await $fetch.post('/user/login', this.form);

                        const data = await res.json();
                        if (res.status === 201) {
                            alert(data.message)
                            window.location.href = "/";
                        } else {
                            const errors = data.errors ? Object.values(data.errors).flat() : [];
                            this.setErrorMessage(errors.join("\n") || (data.message || ''));
                        }
                    },
                }
            })
        })
    </script>
    <div class="divider">{{ __('user.login') }}</div>
    <form method="post" id="formLogin" @submit.prevent="handleSubmit" x-data="userLogin">
        <div class="card bg-base-100 m-auto w-full max-w-sm shrink-0 shadow-2xl">
            <div class="card-body">
                <fieldset class="fieldset">
                    <label class="label">{{ __('user.auth.email') }}</label>
                    <input type="text" class="input" x-model="form.email"
                        placeholder='{{ __('placeholder.input.value', ['Name' => __('user.auth.email')]) }}'
                        autocomplete="username" />
                    <label class="label">{{ __('user.auth.password') }}</label>
                    <input type="password" name="password" x-model="form.password" class="input"
                        placeholder='{{ __('placeholder.input.value', ['Name' => __('user.auth.password')]) }}'
                        autocomplete="new-password" />
                    <label class="label mt-2.5">
                        <input type="checkbox" class="checkbox" x-model="form.remember" /> {{ __('user.auth.remember') }}
                    </label>
                    <div x-show="getErrorMessage() !== ''" role="alert"
                        class="alert alert-error alert-soft mt-3 whitespace-pre-line">
                        <span x-html='getErrorMessage()'></span>
                    </div>
                    <button class="btn btn-primary mt-4">{{ __('user.login') }}</button>
                </fieldset>
            </div>
        </div>
    </form>
@endsection
