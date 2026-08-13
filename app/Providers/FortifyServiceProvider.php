<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Http\Responses\LoginResponse;
use App\Http\Responses\PasswordUpdateResponse;
use App\Http\Responses\RegisterResponse;
use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Contracts\PasswordUpdateResponse as PasswordUpdateResponseContract;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(LoginResponseContract::class, LoginResponse::class);
        $this->app->singleton(RegisterResponseContract::class, RegisterResponse::class);
        $this->app->singleton(PasswordUpdateResponseContract::class, PasswordUpdateResponse::class);

        Fortify::loginView(function () {
            return view('frontend.user.login', [
                'isRemembered' => request()->hasCookie('remember_preference'),
                'email' => request()->cookie('email_preference'),
            ]);
        });
        Fortify::registerView(function () {
            return view('frontend.user.register');
        });
        Fortify::requestPasswordResetLinkView(function () {
            return view('frontend.user.reset-password');
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::redirectUserForTwoFactorAuthenticationUsing(RedirectIfTwoFactorAuthenticatable::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        RateLimiter::for('passkeys', function (Request $request) {
            $credentialId = $request->input('credential.id');

            return Limit::perMinute(10)->by(
                ($credentialId ?: $request->session()->getId()).'|'.$request->ip()
            );
        });

        Event::listen(Login::class, function ($event) {
            if (request()->boolean('remember')) {
                // 種一顆保存 30 天的 Cookie 記住偏好
                Cookie::queue('remember_preference', '1', 60 * 24 * 365);
                Cookie::queue('email_preference', request()->string('email'), 60 * 24 * 365);
            } else {
                // 如果沒勾，把 Cookie 刪除
                Cookie::queue(
                    Cookie::forget('remember_preference'),
                    Cookie::forget('email_preference')
                );
            }
        });

        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('email', $request->email)->first();

            if ($user && Hash::check($request->password, $user->password)) {
                // 現在抓得到了！
                if (! $user->is_active) {
                    throw ValidationException::withMessages([
                        'email' => [__('auth.disabled')],
                    ]);
                }

                return $user;
            }

            return null;
        });
    }
}
