<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        Page::updateOrCreate(
            ['slug' => 'about'],
            [
                // Заголовки
                'title' => [
                    'az' => 'Haqqımızda',
                    'ru' => 'О нас',
                    'en' => 'About Us',
                ],

                // Контент
                'content' => [
                    'az' => '<p>Kontent AZ üçün</p>',
                    'ru' => '<p>Контент для RU</p>',
                    'en' => '<p>Content for EN</p>',
                ],

                // SEO
                'seo_title' => [
                    'az' => 'Haqqımızda - Şirkətimiz haqqında',
                    'ru' => 'О нас - Информация о компании',
                    'en' => 'About Us - Company Information',
                ],

                'seo_description' => [
                    'az' => 'Şirkətimiz və fəaliyyəti haqqında ətraflı məlumat.',
                    'ru' => 'Подробная информация о нашей компании и деятельности.',
                    'en' => 'Detailed information about our company and activities.',
                ],

                'seo_keywords' => [
                    'az' => 'haqqımızda, şirkət, məlumat',
                    'ru' => 'о нас, компания, информация',
                    'en' => 'about us, company, information',
                ],

                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        Page::updateOrCreate(
            ['slug' => 'contact'],
            [
                'title' => [
                    'az' => 'Əlaqə',
                    'ru' => 'Контакты',
                    'en' => 'Contact',
                ],

                'content' => [
                    'az' => '<p>Əlaqə məlumatları burada</p>',
                    'ru' => '<p>Контактная информация здесь</p>',
                    'en' => '<p>Contact information here</p>',
                ],

                'seo_title' => [
                    'az' => 'Əlaqə - Bizimlə əlaqə saxlayın',
                    'ru' => 'Контакты - Свяжитесь с нами',
                    'en' => 'Contact - Get in touch with us',
                ],

                'seo_description' => [
                    'az' => 'Bizimlə əlaqə saxlamaq üçün ünvan və telefon məlumatları.',
                    'ru' => 'Наш адрес и контактные телефоны для связи.',
                    'en' => 'Our address and contact details to reach us.',
                ],

                'seo_keywords' => [
                    'az' => 'əlaqə, ünvan, telefon',
                    'ru' => 'контакты, адрес, телефон',
                    'en' => 'contact, address, phone',
                ],

                'is_active' => true,
                'sort_order' => 2,
            ]
        );
    }
}
