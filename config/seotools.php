<?php

return [
    'meta' => [
        /*
         * The default configurations to be used by the meta generator.
         */
        'defaults'       => [
            'title'        => 'Comprador - Best computer parts & Computer Hardware', // set false to total remove
            'titleBefore'  => false, // Put defaults.title before page title, like 'It's Over 9000! - Dashboard'
            'description'  => 'Shop the computer parts, graphics cards, processors, gaming accessories and computer hardware. Best prices, official warranty, free shipping in Azerbaijan.', // set false to total remove
            'separator'    => ' - ',
            'keywords'     => ['computer parts', 'computer hardware', 'graphics cards', 'processors', 'gaming accessories', 'azerbaijan', 'baku'],
            'canonical'    => null, // Set to null or 'full' to use Url::full(), set to 'current' to use Url::current(), set false to total remove
            'robots'       => 'index,follow', // Set to 'all', 'none' or any combination of index/noindex and follow/nofollow
        ],
        /*
         * Webmaster tags are always added.
         */
        'webmaster_tags' => [
            'google'    => null,
            'bing'      => null,
            'alexa'     => null,
            'pinterest' => null,
            'yandex'    => null,
            'norton'    => null,
        ],

        'add_notranslate_class' => false,
    ],
    'opengraph' => [
        /*
         * The default configurations to be used by the opengraph generator.
         */
        'defaults' => [
            'title'       => 'Comprador - Best computer store & Computer Hardware', // set false to total remove
            'description' => 'Shop the latest computer parts, graphics cards, processors, gaming accessories and computer hardware. Best prices, official warranty, free shipping in Azerbaijan.', // set false to total remove
            'url'         => null, // Set null for using Url::current(), set false to total remove
            'type'        => 'website',
            'site_name'   => 'Comprador Gaming',
            'images'      => ['/images/og-default.jpg'],
        ],
    ],
    'twitter' => [
        /*
         * The default values to be used by the twitter cards generator.
         */
        'defaults' => [
            'card'        => 'summary_large_image',
            'site'        => '@compradorgaming',
            'creator'     => '@compradorgaming',
            'title'       => 'Comprador Gaming - Best Gaming Gear & Computer Hardware',
            'description' => 'Shop the latest gaming gear, graphics cards, processors, gaming accessories and computer hardware.',
            'image'       => '/images/twitter-default.jpg',
        ],
    ],
    'json-ld' => [
        /*
         * The default configurations to be used by the json-ld generator.
         */
        'defaults' => [
            'title'       => 'Comprador Computer store', // set false to total remove
            'description' => 'Your ultimate destination for gaming gear and computer hardware', // set false to total remove
            'url'         => null, // Set to null or 'full' to use Url::full(), set to 'current' to use Url::current(), set false to total remove
            'type'        => 'WebSite',
            'images'      => [],
        ],
    ],
];
