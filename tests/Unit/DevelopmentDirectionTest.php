<?php

namespace Tests\Unit;

use App\Models\DevelopmentDirection;
use App\Models\Technology;
use App\Models\UserTypes\Employee;
use Tests\TestCase;

class DevelopmentDirectionTest extends TestCase
{
    /** @test */
    public function it_belongs_to_many_technologies()
    {
        $direction = DevelopmentDirection::factory()->has(
            Technology::factory()->count($count = 3)
        )->create();

        $this->assertTrue($direction->technologies->contains(Technology::first()));
        $this->assertInstanceOf(Technology::class, $direction->technologies->first());
        $this->assertCount($count, $direction->technologies);
    }

    /** @test */
    public function it_belongs_to_many_employees()
    {
        $direction = DevelopmentDirection::factory()->has(
            Employee::factory()->count($count = 3), 'employees'
        )->create();

        $this->assertTrue($direction->employees->contains(Employee::first()));
        $this->assertInstanceOf(Employee::class, $direction->employees->first());
        $this->assertCount($count, $direction->employees);
    }
}
