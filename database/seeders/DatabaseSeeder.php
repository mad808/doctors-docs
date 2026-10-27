<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Clinic;
use App\Models\DoctorProfile;
use App\Models\Location;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Core Users
        $admin = User::updateOrCreate(
            ['phone' => '+99362240774'],
            [
                'name' => 'Eziz Admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin123'),
                'phone_verified_at' => now(),
                'role' => 'admin',
                'status' => 1,
            ]
        );

        $patient = User::updateOrCreate(
            ['phone' => '+99365000002'],
            [
                'name' => 'Merdan Näsag',
                'email' => 'patient@prodoctors.tm',
                'password' => Hash::make('password'),
                'phone_verified_at' => now(),
                'role' => 'patient',
                'status' => 1,
            ]
        );

        // 2. Locations (Regions & Cities)
        $ashgabatRegion = Location::updateOrCreate(
            ['name_tk' => 'Aşgabat'],
            ['name_ru' => 'Ашхабад', 'name_en' => 'Ashgabat', 'parent_id' => null, 'status' => 1]
        );

        $ashgabatCity = Location::updateOrCreate(
            ['name_tk' => 'Aşgabat şäheri'],
            ['name_ru' => 'город Ашхабад', 'name_en' => 'Ashgabat city', 'parent_id' => $ashgabatRegion->id, 'status' => 1]
        );

        $maryRegion = Location::updateOrCreate(
            ['name_tk' => 'Mary welaýaty'],
            ['name_ru' => 'Марыйский велаят', 'name_en' => 'Mary velayat', 'parent_id' => null, 'status' => 1]
        );

        $maryCity = Location::updateOrCreate(
            ['name_tk' => 'Mary şäheri'],
            ['name_ru' => 'город Мары', 'name_en' => 'Mary city', 'parent_id' => $maryRegion->id, 'status' => 1]
        );

        // 3. Specialties
        $specialtiesList = [
            ['name_tk' => 'Kardiolog', 'name_ru' => 'Кардиолог', 'name_en' => 'Cardiologist', 'icon' => 'bi bi-heart-pulse'],
            ['name_tk' => 'Stomatolog', 'name_ru' => 'Стоматолог', 'name_en' => 'Dentist', 'icon' => 'bi bi-emoji-smile'],
            ['name_tk' => 'Pediatr', 'name_ru' => 'Педиатр', 'name_en' => 'Pediatrician', 'icon' => 'bi bi-people'],
            ['name_tk' => 'Terapewt', 'name_ru' => 'Терапевт', 'name_en' => 'Therapist', 'icon' => 'bi bi-clipboard2-pulse'],
            ['name_tk' => 'Oftalmolog', 'name_ru' => 'Офтальмолог', 'name_en' => 'Ophthalmologist', 'icon' => 'bi bi-eye'],
            ['name_tk' => 'Nevropatolog', 'name_ru' => 'Невролог', 'name_en' => 'Neurologist', 'icon' => 'bi bi-activity'],
        ];

        $specialtyModels = [];
        foreach ($specialtiesList as $s) {
            $specialtyModels[] = Specialty::updateOrCreate(['name_tk' => $s['name_tk']], $s + ['status' => 1]);
        }

        // 4. Clinics
        $clinic1 = Clinic::updateOrCreate(
            ['name_tk' => 'Halkara Saglyk Merkezi'],
            [
                'name_ru' => 'Международный медицинский центр',
                'name_en' => 'International Medical Center',
                'location_id' => $ashgabatCity->id,
                'address_tk' => 'Aşgabat ş., Görogly köç., 10',
                'address_ru' => 'г. Ашхабад, ул. Гёроглы, 10',
                'address_en' => 'Ashgabat, Gorogly str., 10',
                'phone_1' => '+993 12 90-00-01',
                'phone_2' => '+993 12 90-00-02',
                'website' => 'https://saglyk.gov.tm',
                'rating' => 4.9,
                'reviews_count' => 18,
                'status' => 1
            ]
        );

        $clinic2 = Clinic::updateOrCreate(
            ['name_tk' => 'Lukmançylyk Diagnostika Merkezi'],
            [
                'name_ru' => 'Диагностический медицинский центр',
                'name_en' => 'Diagnostic Medical Center',
                'location_id' => $ashgabatCity->id,
                'address_tk' => 'Aşgabat ş., Arçabil şaýoly, 45',
                'address_ru' => 'г. Ашхабад, проспект Арчабил, 45',
                'address_en' => 'Ashgabat, Archabil ave., 45',
                'phone_1' => '+993 12 48-11-22',
                'rating' => 4.7,
                'reviews_count' => 12,
                'status' => 1
            ]
        );

        // 5. Doctors
        $doctorsData = [
            [
                'name' => 'Dr. Myrat Myradow',
                'phone' => '+99365000003',
                'title_tk' => 'Ýokary derejeli Kardiolog',
                'title_ru' => 'Врач-кардиолог высшей категории',
                'title_en' => 'Senior Cardiologist Specialist',
                'experience' => 14,
                'fee' => 150.00,
                'rating' => 4.9,
                'spec_index' => 0,
            ],
            [
                'name' => 'Dr. Selbi Annanurowa',
                'phone' => '+99365000004',
                'title_tk' => 'Çagalar lukmany (Pediatr)',
                'title_ru' => 'Ведущий врач-педиатр',
                'title_en' => 'Lead Pediatrician',
                'experience' => 9,
                'fee' => 120.00,
                'rating' => 5.0,
                'spec_index' => 2,
            ],
            [
                'name' => 'Dr. Begenç Amanow',
                'phone' => '+99365000005',
                'title_tk' => 'Baş Stomatolog',
                'title_ru' => 'Главный врач-стоматолог',
                'title_en' => 'Chief Dentist Specialist',
                'experience' => 11,
                'fee' => 200.00,
                'rating' => 4.8,
                'spec_index' => 1,
            ],
            [
                'name' => 'Dr. Aýgül Sopyýewa',
                'phone' => '+99365000006',
                'title_tk' => 'Nevropatolog, Ylymlaryň kandidaty',
                'title_ru' => 'Врач-невропатолог, к.м.н.',
                'title_en' => 'Neurologist, MD',
                'experience' => 16,
                'fee' => 180.00,
                'rating' => 4.9,
                'spec_index' => 5,
            ]
        ];

        foreach ($doctorsData as $d) {
            $userDoc = User::updateOrCreate(
                ['phone' => $d['phone']],
                [
                    'name' => $d['name'],
                    'password' => Hash::make('password'),
                    'phone_verified_at' => now(),
                    'role' => 'doctor',
                    'status' => 1,
                ]
            );

            $profile = DoctorProfile::updateOrCreate(
                ['user_id' => $userDoc->id],
                [
                    'title_tk' => $d['title_tk'],
                    'title_ru' => $d['title_ru'],
                    'title_en' => $d['title_en'],
                    'experience_years' => $d['experience'],
                    'consultation_fee' => $d['fee'],
                    'rating' => $d['rating'],
                    'reviews_count' => rand(8, 25),
                    'is_verified' => true,
                    'status' => 1,
                    'bio_tk' => 'Halkara standartlary esasynda näsaglary kabul edýär we bejerýär.',
                    'bio_ru' => 'Прием и комплексное лечение пациентов по международным стандартам.',
                    'bio_en' => 'Comprehensive diagnosis and medical care under modern standards.',
                ]
            );

            $profile->specialties()->sync([$specialtyModels[$d['spec_index']]->id]);
            $profile->clinics()->sync([$clinic1->id, $clinic2->id]);
        }

        // 6. Banners
        Banner::truncate();
        Banner::create([
            'image' => 'assets/img/sliders/banner1.jpg',
            'title_tk' => 'Ýokary derejeli lukmanlar elmydama hyzmatyňyzda',
            'title_ru' => 'Квалифицированные врачи всегда к вашим услугам',
            'title_en' => 'Qualified doctors always at your medical service',
            'order' => 1,
            'status' => 1,
        ]);
        Banner::create([
            'image' => 'assets/img/sliders/banner2.jpg',
            'title_tk' => 'Onlaýn kabul edişe ýazylyň',
            'title_ru' => 'Онлайн запись на прием в один клик',
            'title_en' => 'Book medical appointments online',
            'order' => 2,
            'status' => 1,
        ]);
    }
}
