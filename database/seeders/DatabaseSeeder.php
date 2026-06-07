<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ParkingLocation;
use App\Models\ParkingSpot;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(['email' => 'admin@smartpark.com'], [
            'name'     => 'Admin',
            'email'    => 'admin@smartpark.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        User::firstOrCreate(['email' => 'user@smartpark.com'], [
            'name'     => 'Test User',
            'email'    => 'user@smartpark.com',
            'password' => Hash::make('password'),
            'role'     => 'user',
        ]);

        $locations = [
            [
                'name'          => 'BBI Centar Parking',
                'address'       => 'Trg djece Sarajeva bb',
                'city'          => 'Sarajevo',
                'description'   => 'Underground parking at BBI shopping centre.',
                'total_spots'   => 40,
                'latitude'      => 43.8563,
                'longitude'     => 18.4131,
                'hourly_rate'   => 2.50,
                'opening_hours' => '07:00 - 23:00',
            ],
            [
                'name'          => 'Skenderija Parking',
                'address'       => 'Hamdije Kreševljakovića 1',
                'city'          => 'Sarajevo',
                'description'   => 'Open-air parking near Skenderija sports centre.',
                'total_spots'   => 30,
                'latitude'      => 43.8530,
                'longitude'     => 18.4200,
                'hourly_rate'   => 1.50,
                'opening_hours' => '00:00 - 24:00',
            ],
            [
                'name'          => 'Baščaršija Parking',
                'address'       => 'Bravadžiluk 2',
                'city'          => 'Sarajevo',
                'description'   => 'Parking near the old bazaar.',
                'total_spots'   => 20,
                'latitude'      => 43.8600,
                'longitude'     => 18.4320,
                'hourly_rate'   => 3.00,
                'opening_hours' => '06:00 - 22:00',
            ],
        ];

        foreach ($locations as $data) {
            $loc = ParkingLocation::firstOrCreate(['name' => $data['name']], $data);

            if ($loc->spots()->count() === 0) {
                $letters = range('A', 'Z');
                $generated = 0; $rowIndex = 0; $num = 1;
                while ($generated < $loc->total_spots) {
                    ParkingSpot::create([
                        'parking_location_id' => $loc->id,
                        'spot_number'         => $letters[$rowIndex] . str_pad($num, 2, '0', STR_PAD_LEFT),
                        'type'                => 'standard',
                        'status'              => 'available',
                    ]);
                    $generated++; $num++;
                    if ($num > 20) { $num = 1; $rowIndex++; }
                }
            }
        }
    }
}