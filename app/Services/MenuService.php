<?php

namespace App\Services;

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
        return [
            $this->createMenu(trans('menu.index'), '/'),
            $this->createMenu(trans('menu.user'), '/user', [
                $this->createChilds(trans('menu.user.login'), '/user/login'),
            ]),
        ];
    }

    public function admin(): array
    {
        return [
            $this->createMenu('index', '/'),
        ];
    }
}
