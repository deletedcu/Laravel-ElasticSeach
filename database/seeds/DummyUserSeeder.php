<?php

use Illuminate\Database\Seeder;

class DummyUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // Add users

        DB::table('users')->insert(
        [
            [
                'email_reciever' => 1,
                'email' => 'test@webbite.de',
                'username' => 'administrator',
                'username_sso' => 'administrator',
                'password' => bcrypt('webbite123'),
                'title' => 'Herr',
                'short_name' => 'Deadpool',
                'first_name' => 'Struktur',
                'last_name' => 'Administrator',
                'active' => 1,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],
            [
                'email_reciever' => 1,
                'email' => 'filip@webbite.de',
                'username' => 'batman',
                'username_sso' => 'batman',
                'password' => bcrypt('webbite123'),
                'title' => 'Herr',
                'short_name' => 'Batman',
                'first_name' => 'User',
                'last_name' => 'Dokumentverfasser',
                'active' => 1,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],
            [
                'email_reciever' => 1,
                'email' => 'marijan.gudelj@webbite.de',
                'username' => 'superman',
                'username_sso' => 'superman',
                'password' => bcrypt('webbite123'),
                'title' => 'Herr',
                'short_name' => 'Superman',
                'first_name' => 'User',
                'last_name' => 'Dokumentfreigeber',
                'active' => 1,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],
            [
                'email_reciever' => 1,
                'email' => 'info@webbite.de',
                'username' => 'info',
                'username_sso' => 'info',
                'password' => bcrypt('webbite123'),
                'title' => 'Herr',
                'short_name' => 'Max',
                'first_name' => 'Max',
                'last_name' => 'Mustermann',
                'active' => 1,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],
            [
                'email_reciever' => 1,
                'email' => 'verena@webbite.de',
                'username' => 'verena',
                'username_sso' => 'verena',
                'password' => bcrypt('webbite123'),
                'title' => 'Frau',
                'short_name' => 'Verena',
                'first_name' => 'Verena',
                'last_name' => 'Rometsch',
                'active' => 1,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ]
        ]

        );

        // Add users to mandants

        DB::table('mandant_users')->insert(
        [
            [
                'mandant_id' => 1,
                'user_id' => 1,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")

            ],
            [
                'mandant_id' => 1,
                'user_id' => 2,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")

            ],
            [
                'mandant_id' => 1,
                'user_id' => 3,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")

            ],
            [
                'mandant_id' => 1,
                'user_id' => 4,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")

            ],
            [
                'mandant_id' => 1,
                'user_id' => 5,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")

            ]
        ]

        );

        // Add mandant specific roles to users

        DB::table('mandant_user_roles')->insert(
        [
            [
                'mandant_user_id' => 1,
                'role_id' => 1,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],
            [
                'mandant_user_id' => 2,
                'role_id' => 11,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],
            [
                'mandant_user_id' => 2,
                'role_id' => 13,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],
            [
                'mandant_user_id' => 3,
                'role_id' => 8,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],
            [
                'mandant_user_id' => 3,
                'role_id' => 10,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],
            [
                'mandant_user_id' => 4,
                'role_id' => 8,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],
            [
                'mandant_user_id' => 4,
                'role_id' => 10,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],
            [
                'mandant_user_id' => 5,
                'role_id' => 8,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],
            [
                'mandant_user_id' => 5,
                'role_id' => 10,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ]
        ]
        );


    }
}
