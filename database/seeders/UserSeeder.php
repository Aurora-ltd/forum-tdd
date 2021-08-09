<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Profile;
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
		$this->table2 = 'profiles';
        $this->filename = base_path().'/database/seeders/csvs/users.csv';
        $this->filename2 = base_path().'/database/seeders/csvs/profile.csv';
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

        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        
        // Uncomment the below to wipe the table clean before populating
        DB::table($this->table2)->truncate();
        DB::table($this->table)->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        $data = array_map('str_getcsv',file($this->filename));
        $header = $data[0];
        unset($data[0]);
        foreach ($data as $value) {
            $userData = array_combine($header,$value);
            $userData['password'] = bcrypt($userData['password']);
            User::create($userData);
        }

        $data2 = array_map('str_getcsv',file($this->filename2));
        $header2 = $data2[0];
        unset($data2[0]);
        foreach ($data2 as $value2) {
            $userData2 = array_combine($header2,$value2);
            Profile::create($userData2);
        }

        parent::run();

    }
}
