<?php

namespace Database\Seeders;

use App\Models\CategoryPermission;
use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categoryPermissions = [
            [
                'name' => 'Users',
                'permission' => 'CRUD',
                'permission_common' => ["Users Login As"],
            ],
            [
                'name' => 'Role',
                'permission' => 'CRUD',
                'permission_common' => ["Role Set Permission"],
            ],
            [
                'name' => 'Permission',
                'permission' => 'CRUD',
            ],
            [
                'name' => 'Users Menu',
                'permission' => 'CRUD',
            ],
            [
                'name' => 'Activity Log',
                'permission' => ['Activity Log Show', 'Activity Log Detail', 'Activity Log Delete'],
            ],
            [
                'name' => 'Users Management',
                'permission' => ['Users Management Show'],
            ],
            [
                'name' => 'Dashboard',
                'permission' => ['Dashboard Show'],
            ],
            [
                'name' => 'My Profile',
                'permission' => ['Profile Show', 'Profile Change Role'],
            ],
            [
                'name' => 'Referensi',
                'permission' => ['Referensi Show'],
            ],
            [
                'name' => 'Master',
                'permission' => ['Master Show'],
            ],
            [
                'name' => 'Identity',
                'permission' => 'CRUD',
            ],
            [
                'name' => 'API',
                'permission' => ['API Show'],
            ],
            [
                'name' => 'Sidebar Menu',
                'permission' => [
                    'Menu Sidebar Show',
                    // 'Master Umum Sidebar Show',
                    // 'Laporan Sidebar Show',
                    'Setting Sidebar Show',
                ]
            ],

            // Todo: Batas Core

            [
                'name' => 'Referensi',
                'permission' => ['Referensi Show'],
            ],


        ];


        $listCrud = ["Show", "Add", "Edit", "Detail", "Delete"];

        foreach ($categoryPermissions as $category) {

            $existingCategoryPermission = CategoryPermission::where('name', $category['name'])->first();
            if (!$existingCategoryPermission) {
                $data_insert = $category;
                unset($data_insert['permission']);
                unset($data_insert['permission_common']);
                $existingCategoryPermission = CategoryPermission::create($data_insert);
            }
            $listPermission = [];

            // Jika ada Permission CRUD
            if ($category['permission'] == 'CRUD') {
                foreach ($listCrud as $key => $value) {
                    $listPermission[] = $category['name']." ".$value;
                }
            }else{
                foreach ($category['permission'] as $key => $value) {
                    $listPermission[] = $value;
                }
            }

            // Jika ada Permission Tambahan
            if (isset($category['permission_common'])) {
                foreach ($category['permission_common'] as $key => $value) {
                    $listPermission[] = $value;
                }
            }


            // Membuat Permission
            foreach ($listPermission as $key => $value) {
                $existingPermission = Permission::where('name', $value)->first();
                $permissionData = [
                    "category_permission_id" => $existingCategoryPermission->id,
                    "name" => $value,
                ];

                if (!$existingPermission) {
                    // Jika tidak ada, buat Permission baru
                    Permission::create($permissionData);
                } else {
                    // Jika sudah ada, Anda dapat memutuskan apakah ingin melakukan sesuatu atau melewatinya
                    // Contoh: Update data yang sudah ada
                    $existingPermission->update($permissionData);
                }
            }
        }
        $this->command->info('Category Permission Seeder table seeded!');
    }
}
