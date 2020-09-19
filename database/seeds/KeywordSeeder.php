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
        DB::table('keywords')->insert(['category' => 'programming', 'name' => 'struct']);
        DB::table('keywords')->insert(['category' => 'programming', 'name' => 'conversion']);
        DB::table('keywords')->insert(['category' => 'programming', 'name' => 'binary']);
        DB::table('keywords')->insert(['category' => 'programming', 'name' => 'ide']);
        DB::table('keywords')->insert(['category' => 'programming', 'name' => 'toolchain']);
        DB::table('keywords')->insert(['category' => 'programming', 'name' => 'goto']);
        DB::table('keywords')->insert(['category' => 'programming', 'name' => 'operators']);
        DB::table('keywords')->insert(['category' => 'programming', 'name' => 'booleans']);
        DB::table('keywords')->insert(['category' => 'programming', 'name' => 'number-bases']);
        DB::table('keywords')->insert(['category' => 'programming', 'name' => 'promotions']);
        DB::table('keywords')->insert(['category' => 'programming', 'name' => 'types']);
        DB::table('keywords')->insert(['category' => 'programming', 'name' => 'algorithms']);
        DB::table('keywords')->insert(['category' => 'programming', 'name' => 'big-o']);
        DB::table('keywords')->insert(['category' => 'programming', 'name' => 'arrays']);
        DB::table('keywords')->insert(['category' => 'programming', 'name' => 'recursion']);
        DB::table('keywords')->insert(['category' => 'programming', 'name' => 'unions']);
        DB::table('keywords')->insert(['category' => 'programming', 'name' => 'compiler']);
    }
}
