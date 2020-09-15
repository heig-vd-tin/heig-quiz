<?php

use Illuminate\Database\Seeder;

class KeywordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('keywords')->insert([
            'name' => 'structure'
        ]);

        DB::table('keywords')->insert([
            'name' => 'conversion'
        ]);

        DB::table('keywords')->insert([
            'name' => 'binnaire'
        ]);

        DB::table('keywords')->insert([
            'name' => 'foo'
        ]);

        DB::table('keywords')->insert([
            'name' => 'bar'
        ]);
    }
}
