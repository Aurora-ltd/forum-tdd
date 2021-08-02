<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Flynsarmy\CsvSeeder\CsvSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends CsvSeeder
{
    public function __construct()
	{
		$this->table = 'users';
        $this->filename = base_path().'/database/seeders/csvs/0099_users.csv';
	}

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()

    {
        // Recommended when importing larger CSVs
        DB::disableQueryLog();

        // Uncomment the below to wipe the table clean before populating
        DB::table($this->table)->truncate();

        $data = array_map('str_getcsv',file($this->filename));
        $header = $data[0];
        unset($data[0]);
        foreach ($data as $value) {
            $userData = array_combine($header,$value);
            User::create($userData);
        }

        parent::run();

    }
}
