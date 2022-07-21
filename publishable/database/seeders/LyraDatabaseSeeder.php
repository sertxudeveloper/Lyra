<?php

namespace SertxuDeveloper\Lyra\Database\Seeders;

use Illuminate\Database\Seeder;
use SertxuDeveloper\Lyra\Models\LyraUser;

class LyraDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void {
        LyraUser::factory(1)->create();
    }
}
