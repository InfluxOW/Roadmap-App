<?php

namespace Tests\Unit;

use App\Models\DevelopmentDirection;
use App\Models\Technology;
use App\Models\UserTypes\Employee;
use Tests\TestCase;

class DevelopmentDirectionTest extends TestCase
{
    /** @test */
    public function it_may_belong_to_many_employees()
    {
        $direction = DevelopmentDirection::factory()->hasAttached(
            Employee::factory()->count($count = 3)
        )->create();

        $this->assertTrue($direction->employees->contains(Employee::first()));
        $this->assertInstanceOf(Employee::class, $direction->employees->first());
        $this->assertCount($count, $direction->employees);
    }
}
