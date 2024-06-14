<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExampleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('examples')->insert([
            [
                'id' => 1,
                'vocab_id' => 1,
                'sentence' => 'Mrs. Ha is my English teacher.',
                'meaning' => 'Cô Hà là giáo viên Tiếng Anh của tôi.'
            ],
            [
                'id' => 2,
                'vocab_id' => 2,
                'sentence' => 'Lan is a diligent student.',
                'meaning' => 'Lan là một học sinh chăm chỉ.'
            ],
            [
                'id' => 3,
                'vocab_id' => 3,
                'sentence' => 'Dr. Minh is a famous doctor in this hospital.',
                'meaning' => 'Bác sĩ Minh là một bác sĩ nổi tiếng ở bệnh viện này.'
            ],
            [
                'id' => 4,
                'vocab_id' => 4,
                'sentence' => 'My brother is a software engineer.',
                'meaning' => 'Anh trai tôi là một kỹ sư phần mềm.'
            ],
            [
                'id' => 5,
                'vocab_id' => 5,
                'sentence' => 'She wants to become a lawyer.',
                'meaning' => 'Cô ấy muốn trở thành một luật sư.'
            ],
            [
                'id' => 6,
                'vocab_id' => 6,
                'sentence' => 'The nurse took care of the patient all night.',
                'meaning' => 'Y tá đã chăm sóc bệnh nhân suốt đêm.'
            ],
            [
                'id' => 7,
                'vocab_id' => 7,
                'sentence' => 'My mother is an accountant at a big company.',
                'meaning' => 'Mẹ tôi là kế toán ở một công ty lớn.'
            ],
            [
                'id' => 8,
                'vocab_id' => 8,
                'sentence' => 'The architect designed our new house.',
                'meaning' => 'Kiến trúc sư đã thiết kế ngôi nhà mới của chúng tôi.'
            ],
            [
                'id' => 9,
                'vocab_id' => 9,
                'sentence' => 'He was promoted to manager last year.',
                'meaning' => 'Anh ấy đã được thăng chức lên quản lý năm ngoái.'
            ],
            [
                'id' => 10,
                'vocab_id' => 10,
                'sentence' => 'The chef prepared a delicious meal for us.',
                'meaning' => 'Đầu bếp đã chuẩn bị một bữa ăn ngon cho chúng tôi.'
            ]
        ]);
    }
}
