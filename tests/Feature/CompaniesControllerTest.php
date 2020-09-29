<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\UserTypes\Admin;
use App\Models\UserTypes\Employee;
use App\Models\UserTypes\Manager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CompaniesControllerTest extends TestCase
{
    public $employee;
    public $manager;
    public $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->manager = Manager::factory()->create();
        $this->employee = Employee::factory()->create();
        $this->admin = Admin::factory()->create();
    }

    /** @test */
    public function an_employee_cannot_view_companies()
    {
        $this->actingAs($this->employee)
            ->get(route('companies.index'))
            ->assertForbidden();
    }

    /** @test */
    public function an_employee_cannon_view_a_specific_company()
    {
        $this->actingAs($this->employee)
            ->get(route('companies.show', $this->employee->company))
            ->assertForbidden();
    }

    /** @test */
    public function a_manager_redirects_to_his_company_page_trying_to_view_companies()
    {
        $this->actingAs($this->manager)
            ->get(route('companies.index'))
            ->assertRedirect(route('companies.show', $this->manager->company));
    }

    /** @test */
    public function a_manager_can_only_view_his_company_page()
    {
        $company = Company::factory()->create();

        $this->actingAs($this->manager)
            ->get(route('companies.show', $company))
            ->assertForbidden();
    }
}
