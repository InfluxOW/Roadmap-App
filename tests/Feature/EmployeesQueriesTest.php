<?php

namespace Tests\Feature;

use App\Models\UserTypes\Manager;
use Tests\TestCase;

class EmployeesQueriesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    /** @test */
    public function employees_can_be_filtered_by_name()
    {
        $manager = Manager::first();
        $name = $manager->employees->first()->name;

        $this->actingAs($manager)
            ->get(
                route(
                    'employees.index',
                    ['filter[name]' => $name]
                ))
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['name' => $name]);
    }

    /** @test */
    public function employees_can_be_filtered_by_username()
    {
        $manager = Manager::first();
        $username = $manager->employees->first()->username;

        $this->actingAs($manager)
            ->get(
                route(
                    'employees.index',
                    ['filter[username]' => $username]
                ))
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['username' => $username]);
    }

    /** @test */
    public function employees_can_be_filtered_by_email()
    {
        $manager = Manager::first();
        $email = $manager->employees->first()->email;

        $this->actingAs($manager)
            ->get(
                route(
                    'employees.index',
                    ['filter[email]' => $email]
                ))
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['email' => $email]);
    }

    /** @test */
    public function employees_can_be_filtered_by_sex()
    {
        $manager = Manager::first();
        $sex = $manager->employees->first()->sex;

        $this->actingAs($manager)
            ->get(
                route(
                    'employees.index',
                    ['filter[sex]' => $sex]
                ))
            ->assertOk()
            ->assertJsonCount($manager->employees->where('sex', $sex)->count(), 'data')
            ->assertJsonFragment(['sex' => $sex]);
    }

    /** @test */
    public function employees_can_be_filtered_by_position()
    {
        $manager = Manager::first();
        $position = $manager->employees->first()->position;

        $this->actingAs($manager)
            ->get(
                route(
                    'employees.index',
                    ['filter[position]' => $position]
                ))
            ->assertOk()
            ->assertJsonCount($manager->employees->where('position', $position)->count(), 'data')
            ->assertJsonFragment(['position' => $position]);
    }

    //    TODO: remained assertions

}
