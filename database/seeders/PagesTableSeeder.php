<?php

namespace Database\Seeders;

use App\Entity\Page\Page;
use Illuminate\Database\Seeder;

class PagesTableSeeder extends Seeder
{
    public function run(): void
    {
        // Agar 'about' sahifasi allaqachon bo'lsa — qayta yaratmaymiz
        if (Page::where('slug', 'about')->exists()) {
            return;
        }

        $lorem = 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. '
            . 'Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, '
            . 'when an unknown printer took a galley of type and scrambled it to make a type specimen book.';

        // ===== Root sahifalar =====
        $about = Page::create([
            'title'       => 'About',
            'menu_title'  => 'About',
            'slug'        => 'about',
            'content'     => $lorem,
            'description' => 'About the company',
        ]);

        // About ostidagi bola sahifa (path: about/company)
        Page::create([
            'title'       => 'About Company',
            'menu_title'  => 'About Company',
            'slug'        => 'company',
            'content'     => 'About our company page.',
            'description' => 'Company info',
            'parent_id'   => $about->id,
        ]);

        Page::create([
            'title'       => 'Privacy',
            'menu_title'  => 'Privacy',
            'slug'        => 'privacy',
            'content'     => 'Privacy policy. ' . $lorem,
            'description' => 'Privacy policy',
        ]);

        Page::create([
            'title'       => 'Delivery',
            'menu_title'  => 'Delivery',
            'slug'        => 'delivery',
            'content'     => 'Delivery information. ' . $lorem,
            'description' => 'Delivery info',
        ]);

        Page::create([
            'title'       => 'Payment',
            'menu_title'  => 'Payment',
            'slug'        => 'payment',
            'content'     => '123',
            'description' => 'Payment methods',
        ]);

        Page::create([
            'title'       => 'Quality',
            'menu_title'  => 'Quality',
            'slug'        => 'quality',
            'content'     => 'Quality guarantee. ' . $lorem,
            'description' => 'Quality info',
        ]);
    }
}
