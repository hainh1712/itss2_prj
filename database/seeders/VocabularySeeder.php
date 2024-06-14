<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class VocabularySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('vocabularies')->insert([
            [
                'id' => 1,
                'name' => 'teacher',
                'meaning' => 'Giáo viên'
            ],
            [
                'id' => 2,
                'name' => 'student',
                'meaning' => 'Học sinh'
            ],
            [
                'id' => 3,
                'name' => 'doctor',
                'meaning' => 'Bác sĩ'
            ],
            [
                'id' => 4,
                'name' => 'engineer',
                'meaning' => 'Kỹ sư'
            ],
            [
                'id' => 5,
                'name' => 'lawyer',
                'meaning' => 'Luật sư'
            ],
            [
                'id' => 6,
                'name' => 'nurse',
                'meaning' => 'Y tá'
            ],
            [
                'id' => 7,
                'name' => 'accountant',
                'meaning' => 'Kế toán'
            ],
            [
                'id' => 8,
                'name' => 'architect',
                'meaning' => 'Kiến trúc sư'
            ],
            [
                'id' => 9,
                'name' => 'manager',
                'meaning' => 'Quản lý'
            ],
            [
                'id' => 10,
                'name' => 'chef',
                'meaning' => 'Đầu bếp'
            ]
        ]);
    }
}
