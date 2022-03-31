<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Webpatser\Uuid\Uuid;

class CaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('lawcase')->insert([
            'case_uuid' => Uuid::generate()->string,
            'status' => 'active',
            'type' => 'probate',
            'number' => '1',
            'name' => 'Probate from seeder',
            'description' => 'Seeded probate case',
            'court_name' => 'McLennan County',
            'opposing_councel' => 'N/A/',
            'claim_reference_number' => '1',
            'city' => 'Waco',
            'state' => 'TX',
            'open_date' => '2019-02-14 00:00:00',
        ]);
    }
}
