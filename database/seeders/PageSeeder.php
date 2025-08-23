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
                'title_az' => 'Haqqımızda',
                'title_ru' => 'О нас',
                'title_en' => 'About Us',

                // Контент
                'content_az' => '<p>Kontent AZ üçün</p>',
                'content_ru' => '<p>Контент для RU</p>',
                'content_en' => '<p>Content for EN</p>',

                // SEO
                'seo_title_az' => 'Haqqımızda - Şirkətimiz haqqında',
                'seo_title_ru' => 'О нас - Информация о компании',
                'seo_title_en' => 'About Us - Company Information',

                'seo_description_az' => 'Şirkətimiz və fəaliyyəti haqqında ətraflı məlumat.',
                'seo_description_ru' => 'Подробная информация о нашей компании и деятельности.',
                'seo_description_en' => 'Detailed information about our company and activities.',

                'seo_keywords_az' => 'haqqımızda, şirkət, məlumat',
                'seo_keywords_ru' => 'о нас, компания, информация',
                'seo_keywords_en' => 'about us, company, information',

                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        Page::updateOrCreate(
            ['slug' => 'contact'],
            [
                // Заголовки
                'title_az' => 'Əlaqə',
                'title_ru' => 'Контакты',
                'title_en' => 'Contact',

                // Контент
                'content_az' => '<p>Əlaqə məlumatları burada</p>',
                'content_ru' => '<p>Контактная информация здесь</p>',
                'content_en' => '<p>Contact information here</p>',

                // SEO
                'seo_title_az' => 'Əlaqə - Bizimlə əlaqə saxlayın',
                'seo_title_ru' => 'Контакты - Свяжитесь с нами',
                'seo_title_en' => 'Contact - Get in touch with us',

                'seo_description_az' => 'Bizimlə əlaqə saxlamaq üçün ünvan və telefon məlumatları.',
                'seo_description_ru' => 'Наш адрес и контактные телефоны для связи.',
                'seo_description_en' => 'Our address and contact details to reach us.',

                'seo_keywords_az' => 'əlaqə, ünvan, telefon',
                'seo_keywords_ru' => 'контакты, адрес, телефон',
                'seo_keywords_en' => 'contact, address, phone',

                'is_active' => true,
                'sort_order' => 2,
            ]
        );
    }
}
