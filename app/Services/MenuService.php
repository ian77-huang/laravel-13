<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class MenuService
{
    private function createMenu(string $name, string $url, ?array $childs = null): array
    {
        return ['name' => $name, 'url' => $url, 'childs' => $childs];
    }

    private function createChilds(string $name, string $url): array
    {
        return ['name' => $name, 'url' => $url];
    }

    public function frontend(): array
    {
        $users = [
        ];

        if (Auth::check()) {
            $users['/user/profile'] = $this->createChilds(trans('menu.user.profile'), '/user/profile');
            $users['/user/change-password'] = $this->createChilds(trans('menu.user.change-password'), '/user/change-password');
            $users['/user/logout'] = $this->createChilds(trans('menu.user.logout'), '/user/logout');
        } else {
            $users['/user/register'] = $this->createChilds(trans('menu.user.register'), '/user/register');
            $users['/user/login'] = $this->createChilds(trans('menu.user.login'), '/user/login');
        }

        $path = '/'.request()->path();
        if (isset($users[$path])) {
            unset($users[$path]);
        }

        return [
            'index' => $this->createMenu(trans('menu.index'), '/'),
            'user' => $this->createMenu(trans('menu.user'), '/user', $users),
        ];
    }

    public function admin(): array
    {
        return [
            $this->createMenu('index', '/'),
        ];
    }
}
