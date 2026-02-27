<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Building;
use App\Models\Company;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TestUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Service Provider Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmo-properties.co.za'],
            [
                'name' => 'GMO Admin',
                'email' => 'admin@gmo-properties.co.za',
                'password' => Hash::make('admin123'),
                'type' => 'service_provider_admin',
                'is_active' => true,
            ]
        );
        $admin->assignRole('service_provider_admin');

        // Create Test Company 1
        $company1 = Company::firstOrCreate(
            ['slug' => 'premium-properties'],
            [
                'name' => 'Premium Properties',
                'slug' => 'premium-properties',
                'email' => 'info@premiumproperties.co.za',
                'phone' => '+27123456789',
                'address' => '123 Main Street, Cape Town, 8001',
                'subscription_plan' => 'professional',
                'feature_access' => ['buildings', 'units', 'tenants', 'contracts', 'invoices', 'maintenance', 'reports'],
                'is_active' => true,
                'subscription_expires_at' => now()->addYear(),
            ]
        );

        // Company 1 Admin
        $company1Admin = User::firstOrCreate(
            ['email' => 'admin@premiumproperties.co.za'],
            [
                'company_id' => $company1->id,
                'name' => 'John Smith',
                'email' => 'admin@premiumproperties.co.za',
                'password' => Hash::make('company123'),
                'type' => 'company_admin',
                'is_active' => true,
            ]
        );
        $company1Admin->assignRole('company_admin');

        // Company 1 Property Manager
        $company1Manager = User::firstOrCreate(
            ['email' => 'manager@premiumproperties.co.za'],
            [
                'company_id' => $company1->id,
                'name' => 'Sarah Johnson',
                'email' => 'manager@premiumproperties.co.za',
                'password' => Hash::make('manager123'),
                'type' => 'property_manager',
                'is_active' => true,
            ]
        );
        $company1Manager->assignRole('property_manager');

        // Create Building for Company 1
        $building1 = Building::firstOrCreate(
            [
                'company_id' => $company1->id,
                'code' => 'PP-001',
            ],
            [
                'company_id' => $company1->id,
                'name' => 'Sunset Apartments',
                'code' => 'PP-001',
                'address' => '456 Ocean Drive',
                'city' => 'Cape Town',
                'province' => 'Western Cape',
                'postal_code' => '8001',
                'property_type' => 'residential',
                'total_units' => 20,
                'occupied_units' => 5,
                'is_active' => true,
            ]
        );

        // Create Units for Company 1
        for ($i = 1; $i <= 5; $i++) {
            Unit::firstOrCreate(
                [
                    'company_id' => $company1->id,
                    'building_id' => $building1->id,
                    'unit_number' => 'A' . str_pad((string)$i, 2, '0', STR_PAD_LEFT),
                ],
                [
                    'company_id' => $company1->id,
                    'building_id' => $building1->id,
                    'unit_number' => 'A' . str_pad((string)$i, 2, '0', STR_PAD_LEFT),
                    'unit_type' => $i <= 2 ? 'one_bedroom' : 'two_bedroom',
                    'monthly_rent' => $i <= 2 ? 8500.00 : 12000.00,
                    'deposit' => $i <= 2 ? 8500.00 : 12000.00,
                    'bedrooms' => $i <= 2 ? 1 : 2,
                    'bathrooms' => 1,
                    'square_meters' => $i <= 2 ? 45.0 : 65.0,
                    'status' => $i <= 2 ? 'occupied' : 'available',
                    'is_active' => true,
                ]
            );
        }

        // Company 1 Tenant
        $tenant1 = User::firstOrCreate(
            ['email' => 'tenant@premiumproperties.co.za'],
            [
                'company_id' => $company1->id,
                'name' => 'Michael Brown',
                'email' => 'tenant@premiumproperties.co.za',
                'password' => Hash::make('tenant123'),
                'type' => 'tenant',
                'phone' => '+27987654321',
                'id_number' => '9001015800088',
                'employment_type' => 'employed',
                'is_active' => true,
            ]
        );
        $tenant1->assignRole('tenant');

        // Create Test Company 2
        $company2 = Company::firstOrCreate(
            ['slug' => 'student-housing-sa'],
            [
                'name' => 'Student Housing SA',
                'slug' => 'student-housing-sa',
                'email' => 'info@studenthousing.co.za',
                'phone' => '+27987654321',
                'address' => '789 University Road, Johannesburg, 2000',
                'subscription_plan' => 'basic',
                'feature_access' => ['buildings', 'units', 'tenants', 'contracts', 'invoices'],
                'is_active' => true,
                'subscription_expires_at' => now()->addMonths(6),
            ]
        );

        // Company 2 Admin
        $company2Admin = User::firstOrCreate(
            ['email' => 'admin@studenthousing.co.za'],
            [
                'company_id' => $company2->id,
                'name' => 'Lisa Williams',
                'email' => 'admin@studenthousing.co.za',
                'password' => Hash::make('company123'),
                'type' => 'company_admin',
                'is_active' => true,
            ]
        );
        $company2Admin->assignRole('company_admin');

        // Create Building for Company 2
        $building2 = Building::firstOrCreate(
            [
                'company_id' => $company2->id,
                'code' => 'SH-001',
            ],
            [
                'company_id' => $company2->id,
                'name' => 'Campus Residences',
                'code' => 'SH-001',
                'address' => '321 Campus Way',
                'city' => 'Johannesburg',
                'province' => 'Gauteng',
                'postal_code' => '2000',
                'property_type' => 'student_accommodation',
                'total_units' => 50,
                'occupied_units' => 30,
                'is_active' => true,
            ]
        );

        // Create Units for Company 2
        for ($i = 1; $i <= 10; $i++) {
            Unit::firstOrCreate(
                [
                    'company_id' => $company2->id,
                    'building_id' => $building2->id,
                    'unit_number' => 'R' . str_pad((string)$i, 3, '0', STR_PAD_LEFT),
                ],
                [
                    'company_id' => $company2->id,
                    'building_id' => $building2->id,
                    'unit_number' => 'R' . str_pad((string)$i, 3, '0', STR_PAD_LEFT),
                    'unit_type' => 'shared',
                    'monthly_rent' => 3500.00,
                    'deposit' => 3500.00,
                    'bedrooms' => 2,
                    'bathrooms' => 1,
                    'square_meters' => 30.0,
                    'status' => $i <= 6 ? 'occupied' : 'available',
                    'is_active' => true,
                ]
            );
        }

        // Company 2 Tenant (Student)
        $tenant2 = User::firstOrCreate(
            ['email' => 'student@studenthousing.co.za'],
            [
                'company_id' => $company2->id,
                'name' => 'Emma Davis',
                'email' => 'student@studenthousing.co.za',
                'password' => Hash::make('tenant123'),
                'type' => 'tenant',
                'phone' => '+27111222333',
                'id_number' => '0101015800088',
                'student_number' => 'STU2024001',
                'employment_type' => 'student',
                'source_of_funding' => 'NSFAS',
                'is_active' => true,
            ]
        );
        $tenant2->assignRole('tenant');

        $this->command->info('Test users and companies created successfully!');
        $this->command->info('');
        $this->command->info('=== CREDENTIALS ===');
        $this->command->info('');
        $this->command->info('SERVICE PROVIDER ADMIN:');
        $this->command->info('  Email: admin@gmo-properties.co.za');
        $this->command->info('  Password: admin123');
        $this->command->info('  URL: /admin/dashboard');
        $this->command->info('');
        $this->command->info('COMPANY 1 - Premium Properties:');
        $this->command->info('  Company Admin:');
        $this->command->info('    Email: admin@premiumproperties.co.za');
        $this->command->info('    Password: company123');
        $this->command->info('    URL: /premium-properties/dashboard');
        $this->command->info('  Property Manager:');
        $this->command->info('    Email: manager@premiumproperties.co.za');
        $this->command->info('    Password: manager123');
        $this->command->info('    URL: /premium-properties/dashboard');
        $this->command->info('  Tenant:');
        $this->command->info('    Email: tenant@premiumproperties.co.za');
        $this->command->info('    Password: tenant123');
        $this->command->info('    URL: /tenant/dashboard');
        $this->command->info('');
        $this->command->info('COMPANY 2 - Student Housing SA:');
        $this->command->info('  Company Admin:');
        $this->command->info('    Email: admin@studenthousing.co.za');
        $this->command->info('    Password: company123');
        $this->command->info('    URL: /student-housing-sa/dashboard');
        $this->command->info('  Tenant (Student):');
        $this->command->info('    Email: student@studenthousing.co.za');
        $this->command->info('    Password: tenant123');
        $this->command->info('    URL: /tenant/dashboard');
        $this->command->info('');
    }
}
