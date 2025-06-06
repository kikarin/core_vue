<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\UsersMenu;

class UsersMenuSeeder extends Seeder
{
    public function run(): void
    {
        $usersMenus = [
            [
                'nama' => 'Menu',
                'kode' => 'MENU-SIDEBAR',
                'url' => '#',
                'icon' => null,
                'rel' => 0,
                'urutan' => 1,
                'permission_id' => 'Menu Sidebar Show',
                'children' => [
                    [
                        'nama' => 'Dashboard',
                        'icon' => '<i class="ti ti-dashboard"></i>',
                        'urutan' => 1,
                    ],
                ],
            ],
            [
                'nama' => 'Settings',
                'kode' => 'SETTING-SIDEBAR',
                'url' => '#',
                'icon' => null,
                'rel' => 0,
                'urutan' => 1000,
                'permission_id' => 'Setting Sidebar Show',
                'children' => [
                    [
                        'nama' => 'Menu & Permissions',
                        'kode' => 'USERS-MANAGEMENT',
                        'url' => '#',
                        'icon' => '<i class="ti ti-users-group"></i>',
                        'urutan' => 999,
                        'permission_id' => 'Users Management Show',
                        'children' => [
                            [
                                'nama' => 'Menu',
                                'kode' => 'USERS-MENU',
                                'url' => 'users-menu',
                                'urutan' => 1,
                                'permission_id' => 'Users Menu Show',
                            ],
                            [
                                'nama' => 'Role',
                                'urutan' => 2,
                            ],
                            [
                                'nama' => 'Permission',
                                'urutan' => 3,
                            ],
                            [
                                'nama' => 'Activity Log',
                                'urutan' => 4,
                            ],
                        ],
                    ],
                    [
                        'nama' => 'Users',
                        'icon' => '<i class="ti ti-users"></i>',
                        'urutan' => 998,
                    ],
                    [
                        'nama' => 'Master',
                        'url' => '#',
                        'icon' => '<i class="ti ti-list"></i>',
                        'urutan' => 1,
                        'children' => [
                            [
                                'nama' => 'Identity',
                                'urutan' => 1,
                            ],
                        ],
                    ],
                ],
            ],
        ];


        $this->insertMenus($usersMenus);
    }

    // Recursive untuk insert menu & child-nya
    private function insertMenus(array $menus, $parentId = 0)
    {
        foreach ($menus as $menuData) {
            $children = $menuData['children'] ?? null;
            unset($menuData['children']);

            // Generate kode kalau belum ada
            if (!isset($menuData['kode'])) {
                $menuData['kode'] = str_replace(' ', '-', strtoupper($menuData['nama']));
            }

            // Generate URL kalau belum ada
            if (!isset($menuData['url'])) {
                $menuData['url'] = str_replace(' ', '-', strtolower($menuData['nama']));
            }

            $menuData['rel'] = $parentId;

            // Cek permission_id
            if (isset($menuData['permission_id'])) {
                if (is_string($menuData['permission_id'])) {
                    $permission = Permission::where("name", $menuData['permission_id'])->first();
                    $menuData['permission_id'] = $permission?->id;
                }
            } else {
                $permission = Permission::where("name", $menuData['nama'] . " Show")->first();
                $menuData['permission_id'] = $permission?->id;
            }

            // Cek apakah sudah ada, kalau ada update, kalau belum insert
            $existingMenu = UsersMenu::where('kode', $menuData['kode'])->first();

            if ($existingMenu) {
                $existingMenu->update($menuData);
                $menuId = $existingMenu->id;
            } else {
                $newMenu = UsersMenu::create($menuData);
                $menuId = $newMenu->id;
            }

            // Recursive ke children kalau ada
            if ($children) {
                $this->insertMenus($children, $menuId);
            }
        }
    }
}
