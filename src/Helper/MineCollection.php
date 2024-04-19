<?php

/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/10 12:56
 */
namespace Lany\MineAdmin\Helper;
use Illuminate\Support\Collection;

class MineCollection
{
    public static function boot(): void
    {
        Collection::macro('toTree', function(array $data = [], int $parentId = 0, string $id = 'id', string $parentField = 'parent_id', string $children = 'children') {
            $data = $data ?: $this->toArray();

            if (empty($data)) {
                return [];
            }

            $tree = [];

            foreach ($data as $value) {
                if ($value[$parentField] == $parentId) {
                    $child = $this->toTree($data, $value[$id], $id, $parentField, $children);
                    if (! empty($child)) {
                        $value[$children] = $child;
                    }
                    array_push($tree, $value);
                }
            }

            unset($data);
            return $tree;
        });
        Collection::macro('sysMenuToRouterTree', function() {
            $data = $this->toArray();
            if (empty($data)) {
                return [];
            }

            $routers = [];
            foreach ($data as $menu) {
                array_push($routers, MineCollection::setRouter($menu));
            }
            return $this->toTree($routers);
        });

        Collection::macro('toTree', function(array $data = [], int $parentId = 0, string $id = 'id', string $parentField = 'parent_id', string $children = 'children') {
            $data = $data ?: $this->toArray();

            if (empty($data)) {
                return [];
            }

            $tree = [];

            foreach ($data as $value) {
                if ($value[$parentField] == $parentId) {
                    $child = $this->toTree($data, $value[$id], $id, $parentField, $children);
                    if (! empty($child)) {
                        $value[$children] = $child;
                    }
                    array_push($tree, $value);
                }
            }

            unset($data);
            return $tree;
        });
    }

    public static function setRouter(&$menu): array
    {
        $route = ($menu['type'] == 'L' || $menu['type'] == 'I') ? $menu['route'] : '/' . $menu['route'];
        return [
            'id' => $menu['id'],
            'parent_id' => $menu['parent_id'],
            'name' => $menu['code'],
            'component' => $menu['component'],
            'path' => $route,
            'redirect' => $menu['redirect'],
            'meta' => [
                'type' => $menu['type'],
                'icon' => $menu['icon'],
                'title' => $menu['name'],
                'hidden' => ($menu['is_hidden'] === 1),
                'hiddenBreadcrumb' => false,
            ],
        ];
    }
}