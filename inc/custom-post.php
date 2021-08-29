<?php
add_action('init', function () {


  /* Register "witness" post type */
  register_extended_post_type('fiche', [
    'show_in_feed'       => true,
    'archive'            => [
      'nopaging' => true
    ],
    'admin_cols' => [
      'category' => [
        'taxonomy'		=> 'category-fiche',
        'title' => 'Categorie'
      ]
    ],
    'dashboard_activity' => true,
    'menu_icon'          => 'dashicons-awards',
    'supports'           => array('title', 'thumbnail')
  ], [
    'singular' => 'Fiche',
    'plural'   => 'Fiches',
    'slug'     => 'Fiches'
  ]);


  register_extended_taxonomy('category-fiche', 'fiche', [
    'meta_box'   => 'radio',
    'admin_cols' => [
      'updated' => [
        'title_cb'    => function () {
          return '<em>Last</em> Updated';
        },
        'meta_key'    => 'updated_date',
        'date_format' => 'd/m/Y'
      ]
    ]
  ], [
    'singular' => 'Catégorie Fiche',
    'plural'   => 'Catégories Fiche',
    'slug' => 'categories'
  ]);

  /* Register "witness" post type */
  register_extended_post_type('news', [
    'show_in_feed'       => true,
    'archive'            => [
      'nopaging' => true
    ],
    'admin_cols' => [
      'category' => [
        'taxonomy'		=> 'category-news',
        'title' => 'Categorie'
      ]
    ],
    'dashboard_activity' => true,
    'menu_icon'          => 'dashicons-awards',
    'supports'           => array('title', 'thumbnail')
  ], [
    'singular' => 'Actualité',
    'plural'   => 'Actualités',
    'slug'     => 'actualite'
  ]);

  register_extended_taxonomy('category-news', 'news', [
    'meta_box'   => 'radio',
    'admin_cols' => [
      'updated' => [
        'title_cb'    => function () {
          return '<em>Last</em> Updated';
        },
        'meta_key'    => 'updated_date',
        'date_format' => 'd/m/Y'
      ]
    ]
  ], [
    'singular' => __('Catégorie actualite', 'cancerenv'),
    'plural'   => __('Catégories actualite', 'cancerenv')
  ]);

});
