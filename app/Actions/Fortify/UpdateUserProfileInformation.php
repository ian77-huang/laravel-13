<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, string>  $input
     *
     * @throws ValidationException
     */
    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone' => ['nullable', 'string', 'max:50'],
            'bio' => ['nullable', 'string'],
            'avatar_url' => ['nullable', 'string', 'max:512'],
        ])->validateWithBag('updateProfileInformation');

        if ($input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail) {
            DB::transaction(function () use ($user, $input) {
                $this->updateVerifiedUser($user, $input);
            });

            $user->sendEmailVerificationNotification();

            return;
        }

        DB::transaction(function () use ($user, $input) {
            $user->forceFill([
                'name' => $input['name'],
                'email' => $input['email'],
            ])->save();

            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                $this->profileAttributes($user, $input),
            );
        });
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  array<string, string>  $input
     */
    protected function updateVerifiedUser(User $user, array $input): void
    {
        $user->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
            'email_verified_at' => null,
        ])->save();

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $this->profileAttributes($user, $input),
        );
    }

    /**
     * Get the user_profile attributes to sync from the validated input.
     *
     * @param  array<string, string|null>  $input
     * @return array<string, string|null>
     */
    protected function profileAttributes(User $user, array $input): array
    {
        return [
            'name' => $input['name'],
            'email' => $input['email'],
            'phone' => $input['phone'] ?? null,
            'bio' => $input['bio'] ?? null,
            'avatar_url' => $input['avatar_url'] ?? null,
        ];
    }
}
