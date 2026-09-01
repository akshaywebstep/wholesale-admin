<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserAndRoleManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $staff;
    protected Role $superAdminRole;
    protected Role $managerRole;

    protected function setUp(): void
    {
        parent::setUp();

        $this->superAdminRole = Role::create([
            'name'   => 'Super Admin',
            'status' => 'ACTIVE',
        ]);

        $this->managerRole = Role::create([
            'name'   => 'Manager',
            'status' => 'ACTIVE',
        ]);

        $this->admin = User::create([
            'name'      => 'Admin Master',
            'email'     => 'admin@wholesale.com',
            'password'  => Hash::make('Admin123!'),
            'user_type' => 'ADMIN',
            'status'    => 'ACTIVE',
        ]);

        $this->staff = User::create([
            'name'      => 'Staff Member',
            'email'     => 'staff@wholesale.com',
            'password'  => Hash::make('Staff123!'),
            'user_type' => 'STAFF',
            'status'    => 'ACTIVE',
        ]);
        $this->staff->roles()->sync([$this->managerRole->id]);
    }

    public function test_user_create_form_does_not_contain_super_admin_role(): void
    {
        $response = $this->actingAs($this->admin, 'web')->get(route('admin.users.create'));

        $response->assertStatus(200);
        $response->assertSee('Manager');
        $response->assertDontSee('Super Admin (');
    }

    public function test_user_edit_form_does_not_contain_super_admin_role(): void
    {
        $response = $this->actingAs($this->admin, 'web')->get(route('admin.users.edit', $this->staff));

        $response->assertStatus(200);
        $response->assertSee('Manager');
        $response->assertDontSee('Super Admin (');
    }

    public function test_cannot_assign_super_admin_role_when_creating_staff_user(): void
    {
        $response = $this->actingAs($this->admin, 'web')->post(route('admin.users.store'), [
            'name'      => 'Malicious Admin',
            'email'     => 'malicious@wholesale.com',
            'password'  => 'Secret123!',
            'user_type' => 'STAFF',
            'status'    => 'ACTIVE',
            'role_id'   => $this->superAdminRole->id,
        ]);

        $response->assertSessionHasErrors('role_id');
        $this->assertDatabaseMissing('users', ['email' => 'malicious@wholesale.com']);
    }

    public function test_cannot_create_duplicate_role_name_case_insensitively(): void
    {
        $response = $this->actingAs($this->admin, 'web')->post(route('admin.roles.store'), [
            'name'   => 'manager',
            'status' => 'ACTIVE',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_cannot_create_reserved_super_admin_role(): void
    {
        $response = $this->actingAs($this->admin, 'web')->post(route('admin.roles.store'), [
            'name'   => 'Super Admin',
            'status' => 'ACTIVE',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_admin_has_all_permissions_automatically(): void
    {
        $this->assertTrue($this->admin->hasPermission('ADMIN', 'Product', 'DELETE'));
        $this->assertTrue($this->admin->hasPermission('ADMIN', 'Role', 'DELETE'));
        $this->assertTrue($this->admin->hasPermission('ADMIN', 'User', 'CREATE'));
    }

    public function test_staff_without_permissions_is_denied_access(): void
    {
        // Manager role has no permissions yet
        $this->assertFalse($this->staff->hasPermission('ADMIN', 'Role', 'CREATE'));

        $response = $this->actingAs($this->staff, 'web')->get(route('admin.roles.create'));
        $response->assertStatus(403);
    }

    public function test_user_type_is_immutable_on_user_update(): void
    {
        $customer = User::create([
            'name'          => 'Retail Buyer',
            'email'         => 'retail@buyer.com',
            'password'      => Hash::make('Buyer123!'),
            'user_type'     => 'CUSTOMER',
            'business_name' => 'City Vape Shop',
            'status'        => 'ACTIVE',
        ]);

        // Attempt to change user_type to STAFF during update
        $response = $this->actingAs($this->admin, 'web')->put(route('admin.users.update', $customer), [
            'name'      => 'Retail Buyer Updated',
            'email'     => 'retail@buyer.com',
            'user_type' => 'STAFF', // Should be ignored and preserved as CUSTOMER
            'status'    => 'ACTIVE',
        ]);

        $response->assertRedirect(route('admin.users.index', ['tab' => 'customers']));
        $customer->refresh();
        $this->assertEquals('CUSTOMER', $customer->user_type);
        $this->assertEquals('Retail Buyer Updated', $customer->name);
    }

    public function test_super_admin_role_is_locked_on_edit_page(): void
    {
        $response = $this->actingAs($this->admin, 'web')->get(route('admin.users.edit', $this->admin));
        $response->assertStatus(200);
        $response->assertSee('Permanent Master Role');
        $response->assertSee('Super Admin (Master Wholesaler Account)');
        $response->assertDontSee('<select name="role_id"', false);
    }

    public function test_manager_role_is_locked_when_manager_is_logged_in(): void
    {
        $perm = Permission::create([
            'panel'  => 'ADMIN',
            'module' => 'User',
            'action' => 'UPDATE',
        ]);
        $this->managerRole->permissions()->attach($perm->id);

        $response = $this->actingAs($this->staff, 'web')->get(route('admin.users.edit', $this->staff));
        $response->assertStatus(200);
        $response->assertSee('Locked Role');
        $response->assertDontSee('<select name="role_id"', false);
    }
}
