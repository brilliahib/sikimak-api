<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ApplicationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $applyStatuses = ['CV Screening', 'Interview HR', 'Interview User', 'Offer'];
        $approvalStatuses = ['pending', 'accepted', 'rejected', 'ghosting'];
        $categories = ['Internship', 'Full-time', 'Part-time', 'Contract'];

        $data = [];

        for ($i = 0; $i < 10000; $i++) {
            $data[] = [
                'user_id' => 1,
                'title' => $faker->jobTitle(),
                'company_name' => $faker->company(),
                'company_location' => $faker->address(),
                'apply_status' => $faker->randomElement($applyStatuses),
                'approval_status' => $faker->randomElement($approvalStatuses),
                'application_category' => $faker->randomElement($categories),
                'notes' => $faker->optional()->sentence(),
                'deadline' => $faker->optional()->dateTimeBetween('now', '+1 year'),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if ($i % 1000 === 0 && $i > 0) {
                DB::table('applications')->insert($data);
                $data = [];
            }
        }

        if (!empty($data)) {
            DB::table('applications')->insert($data);
        }
    }
}
