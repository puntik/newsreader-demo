<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('users')->insert([
			'username' => 'webmaster@rem.cz',
			'password' => password_hash('12345678', PASSWORD_DEFAULT),
			'name'     => 'webmaster',
			'active'   => true,
		]);
	}
}
