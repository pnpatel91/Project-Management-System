<?php

use App\User;
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

        $user = new User();
        $user->name = 'Admin';
        $user->email = 'admin@admin.com';
        $user->position = 'Owner';
        $user->biography = '<p>Admin&nbsp;Biography</p>';
        $user->dateOfBirth = '2003-04-30';
        $user->password = bcrypt('password'); // password
        $user->save();
        $user->assignRole('admin');

        $user = new User();
        $user->name = 'Management';
        $user->email = 'author@management.com';
        $user->position = 'Manager';
        $user->biography = '<p>Management&nbsp;Biography</p>';
        $user->dateOfBirth = '2003-04-30';
        $user->password = bcrypt('password'); // password
        $user->save();
        $user->assignRole('management');

        $user = new User();
        $user->name = 'Normal Staff';
        $user->email = 'staff@staff.com';
        $user->position = 'Staff';
        $user->biography = '<p>Staff&nbsp;Biography</p>';
        $user->dateOfBirth = '2003-04-30';
        $user->password = bcrypt('password'); // password
        $user->save();
        $user->assignRole('staff');

        $user = new User();
        $user->name = 'Normal Accountant';
        $user->email = 'accountant@accountant.com';
        $user->position = 'Accountant';
        $user->biography = '<p>accounting&nbsp;Biography</p>';
        $user->dateOfBirth = '2003-04-30';
        $user->password = bcrypt('password'); // password
        $user->save();
        $user->assignRole('accounting');

    }
}
