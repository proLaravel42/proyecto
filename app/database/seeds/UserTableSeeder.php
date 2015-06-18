<?php


class UserTableSeeder extends Seeder {

	public function run()
	{
		User::create([
			'first_name' => 'Fernando',
			'last_name'  => 'Galarza Rodriguez',
			'username'   => 'seifer13',
			'email'      => 'seifer_013@hotmail.com',
			'password'   => '12345'
			//'password'   =>  Hash::make('admin')
			]);
		}

	}
