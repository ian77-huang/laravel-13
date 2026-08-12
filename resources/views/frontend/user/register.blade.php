@extends('layouts.frontend.user')

@section('title', __('menu.user.login'))

@section('user-content')
    <script type="text/javascript">
        document.addEventListener('alpine:init', () => {
            Alpine.data('usereRgister', (initialTitle) => {
                return {
                    form: {
                        name: '',
                        email: '',
                        password: '',
                        password_confirmation: ''
                    },
                    __errorMessage: [],
                    minLengthAccount: parseInt('{{ constants('minLengthAccount') }}'),
                    minLengthPassword: parseInt('{{ constants('minLengthPassword') }}'),
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
                        if (field === 'name') {
                            if (!this.form.name) {
                                this.setErrorMessage(
                                    '{{ __('error.cannot_be_empty', ['Name' => __('user.auth.name')]) }}'
                                );
                            }
                        }
                        if (field === 'email') {
                            if (!this.form.email) {
                                this.setErrorMessage(
                                    '{{ __('error.cannot_be_empty', ['Name' => __('user.auth.email')]) }}'
                                );
                            } else if (this.form.email.length < this.minLengthAccount) {
                                this.setErrorMessage(
                                    '{{ __('error.min_characters_required', ['Name' => __('user.auth.email'), 'Length' => constants('minLengthAccount')]) }}'
                                );
                            }
                        }

                        if (field === 'password') {
                            if (!this.form.password) {
                                this.setErrorMessage(
                                    '{{ __('error.cannot_be_empty', ['Name' => __('user.auth.password')]) }}'
                                );
                            } else if (this.form.password.length < this.minLengthPassword) {
                                this.setErrorMessage(
                                    '{{ __('error.min_characters_required', ['Name' => __('user.auth.password'), 'Length' => constants('minLengthPassword')]) }}'
                                );
                            }

                            if (this.form.password && this.form.password_confirmation) {
                                this.validateField('confirmPassword');
                            }
                        }

                        if (field === 'confirmPassword') {
                            if (this.form.password_confirmation !== this.form.password) {
                                this.setErrorMessage('{{ __('error.passwords_do_not_match') }}');
                            }
                        }
                    },
                    validateAll() {
                        this.setErrorMessage("", true);
                        this.validateField('name');
                        this.validateField('email');
                        this.validateField('password');
                        return this.__errorMessage.length === 0;
                    },
                    async handleSubmit(event) {
                        if (!this.validateAll()) {
                            return;
                        }

                        const res = await $fetch.post('/user/register', this.form);
                        const data = await res.json();
                        if (res.status === 201) {
                            alert(data.message)
                            window.location.href = "/";
                        } else {
                            alert(data.message)
                        }
                    },
                }
            })
        })
    </script>
    <div class="divider">{{ __('user.register') }}</div>
    <form method="post" id="formRegister" @submit.prevent="handleSubmit" x-data="usereRgister">
        <div class="card bg-base-100 m-auto w-full max-w-sm shrink-0 shadow-2xl">
            <div class="card-body">
                <fieldset class="fieldset">
                    <label class="label">{{ __('user.auth.name') }}</label>
                    <input type="text" class="input" x-model="form.name"
                        placeholder='{{ __('placeholder.input.value', ['Name' => __('user.auth.name')]) }}'
                        autocomplete="username" />
                    <div class="divider"></div>
                    <label class="label">{{ __('user.auth.email') }}</label>
                    <input type="text" class="input" x-model="form.email"
                        placeholder='{{ __('placeholder.input.value', ['Name' => __('user.auth.email')]) }}'
                        autocomplete="username" />
                    <label class="label">{{ __('user.auth.password') }}</label>
                    <input type="password" name="password" x-model="form.password" class="input"
                        placeholder='{{ __('placeholder.input.value', ['Name' => __('user.auth.password')]) }}'
                        autocomplete="new-password" />
                    <label class="label">{{ __('user.auth.confirmPassword') }}</label>
                    <input type="password" nmae="password_confirmation" x-model="form.password_confirmation" class="input"
                        placeholder='{{ __('placeholder.input.value', ['Name' => __('user.auth.confirmPassword')]) }}'
                        autocomplete="new-password" />
                    <div x-show="getErrorMessage() !== ''" role="alert"
                        class="alert alert-error alert-soft mt-3 whitespace-pre-line">
                        <span x-html='getErrorMessage()'></span>
                    </div>
                    <button class="btn btn-primary mt-4">{{ __('user.register') }}</button>
                </fieldset>
            </div>
        </div>
    </form>
@endsection
