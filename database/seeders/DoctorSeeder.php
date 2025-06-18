<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Doctor;

class DoctorSeeder extends Seeder
{
    public function run()
    {
        Doctor::updateOrCreate(
            ['doctor_id' => 'D001'],
            [
                'nama' => 'Dr. Arief Budiman',
                'spesialisasi' => 'Dokter Umum',
                'hari' => 'Senin - Sabtu',
                'mulai_praktek' => '08:00',
                'selesai_praktek' => '14:00',
                'foto' => 'assets/img/doctors/doctors-1.jpg',
            ]
        );

        Doctor::updateOrCreate(
            ['doctor_id' => 'D002'],
            [
                'nama' => 'Dr. Batari Nandini',
                'spesialisasi' => 'Dokter Kebidanan',
                'hari' => 'Senin - Sabtu',
                'mulai_praktek' => '14:00',
                'selesai_praktek' => '20:00',
                'foto' => 'assets/img/doctors/doctors-2.jpg',
            ]
        );
    }
}
