<?php

/**
 * Theme configuration inside of a subclass of Timber\Site
 */
class CustomSite extends Timber\Site
{
  /** Add timber support. */
    public function __construct()
    {
        add_action('init', array($this, 'theme_init'));
        add_action('wp_enqueue_scripts', array($this, 'theme_enqueue_scripts'), 20);
        add_action('admin_enqueue_scripts', array($this, 'theme_admin_enqueue_scripts'));
        add_action('admin_menu', array($this, 'theme_admin_menu'));
        add_action('after_setup_theme', array($this, 'theme_theme_supports'));
        add_action('login_enqueue_scripts', array($this, 'theme_login_style'));


        add_filter('timber/context', array($this, 'add_to_context'));
        add_filter('timber/twig', array($this, 'add_to_twig'));

        add_filter('menu_order', array($this, 'theme_menu_order'));
        add_filter('custom_menu_order', array($this, 'theme_menu_order'));
        add_filter('body_class', array($this, 'theme_body_class'));

        add_filter('tiny_mce_before_init', array($this, 'theme_tinymce_init'));
        add_filter('gform_display_add_form_button', '__return_false');

        parent::__construct();
    }

    public function theme_init()
    {
      // Remove post tags
        register_taxonomy('post_tag', array());
    }

  /*
   * Enqueue styles & scripts
   */
    public function theme_enqueue_scripts()
    {
        global $wp_query;

        wp_dequeue_style('searchwp-live-search');
        wp_enqueue_style('theme-styles', get_template_directory_uri() . '/assets/css/styles.min.css', false, '1.0.0', 'all');

        wp_deregister_script('jquery');
//        wp_enqueue_script('jquery', get_template_directory_uri() . '/assets/js/libs/jquery.min.js', array(), '3.6.0', true);
        wp_enqueue_script('modernizr', get_template_directory_uri() . '/assets/js/libs/modernizr.min.js', array(), '1.0.0', true);
        wp_enqueue_script('theme-vendors', get_template_directory_uri() . '/assets/js/vendors.js', array(), '1.0.0', true);

        wp_register_script('theme-front', get_template_directory_uri() . '/assets/js/scripts-front.js', array('jquery'), '1.0.0', true);
        wp_localize_script('theme-front', 'theme_params', array(
        'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php',
        'posts' => json_encode($wp_query->query_vars),
        'current_page' => get_query_var('paged') ? get_query_var('paged') : 1,
        'max_page' => $wp_query->max_num_pages
        ));
        wp_enqueue_script('theme-front');
    }

  /*
   * Enqueue admin scripts
   */
    public function theme_admin_enqueue_scripts()
    {
        wp_register_script('theme-admin', get_template_directory_uri() . '/assets/js/scripts-admin.js', array('jquery'), '1.0.0', true);
        wp_localize_script('theme-admin', 'theme_params', array(
        'theme_url' => get_template_directory_uri()
        ));
        wp_enqueue_script('theme-admin');
    }

  /*
   * Custom admin menu
   */
    public function theme_admin_menu()
    {
      // Remove post tags from menu
        remove_menu_page('edit-tags.php?taxonomy=post_tag');
        remove_menu_page('edit-comments.php');
    }

    public function theme_menu_order($menu_ord)
    {
        return array(
        'index.php',               // Dashboard
        'separator1',              // Separator
        'edit.php?post_type=page', // Pages
        'edit.php',                // Posts
        'separator2',              // Separator
        'edit.php?post_type=fiche',
        'edit.php?post_type=news',
        'separator3',                    // Separator
        'upload.php',                    // Media
        'separator4',                    // Separator
        'users.php',                     // Users
        'separator5',                    // Separator
        'admin.php?page=theme-options', // Settings
        'options-general.php',           // Settings
        'themes.php',                    // Appearance
        'tools.php',                     // Tools
        'separator-last',                // Separator
        'plugins.php',                   // Plugins
        'edit.php?post_type=acf-field-group'
        );
    }

  /** Add some context
   *
   * @param string $context context['this'] Being the Twig's {{ this }}.
   */
    public function add_to_context($context)
    {
//      $get_svg_menu = static function ($menu) {
//        $menu->svg = new Timber\Term($menu['object_id']);
//        return $menu;
//      };

      $main_menu = new Timber\Menu('main_menu');
      $main_menu_svg = [];




      foreach ($main_menu->items as $key => $value) {

        $main_menu_svg[] = new Timber\Term($value->object_id);
      }

//      $main_menu->items = array_map($get_svg_menu , $main_menu->items);

      $context['main_menu'] = $main_menu;
      $context['main_menu_svg'] = $main_menu_svg;


      $context['top_menu'] = new Timber\Menu('top_menu');
//      $context['top_menu'] = Timber::get_posts(['post_type' => 'fiche']);
//      $context['top_menu_terms'] = Timber::get_terms(['post_type' => 'fiche', 'taxonomy' => 'category-fiche',]);

      $context['footer_menu'] = new Timber\Menu('footer');

      $context['options'] = get_fields('option');
      $context['site'] = $this;
      $context['site']->blog_url = get_permalink(get_option('page_for_posts'));
      $context['site']->lang = explode('-', $this->language)[0];

      if (function_exists('yoast_breadcrumb')) {
          $context['breadcrumb'] = yoast_breadcrumb(
              '<div class="c-breadcrumb h-color-grey-1" aria-label="breadcrumb" role="navigation" itemscope itemtype="http://schema.org/BreadcrumbList">',
              '</div>',
              false
          );
      }

      return $context;
    }

  /** Add some custom function
   *
   * @param \Twig\Environment $twig
   */
    public function add_to_twig($twig)
    {
      // Adding pll__ function.
        $twig->addFunction(new Timber\Twig_Function('pll__', 'pll__'));

        return $twig;
    }

  /*
   * Theme supports
   */
    public function theme_theme_supports()
    {
      // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

      // Let WordPress manage the document title.
        add_theme_support('title-tag');

      // Enable support for Post Thumbnails on posts and pages.
        add_theme_support('post-thumbnails');

      /* Register menus */
        add_theme_support('menus');
        register_nav_menus(array(
        'main_menu' => __('Menu Principal', 'theme'),
        'top_menu' => __('Menu en avant', 'theme'),
        'footer_menu' => __('Menu secondaire', 'theme'),
        ));
    }

  /*
   * Login logo
   */
    public function theme_login_style()
    {
        ?>
    <style type="text/css">
      #login h1 a, .login h1 a {
        background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/assets/img/favicons/android-chrome-192x192.png);
      }
    </style>
        <?php
    }

  /*
   * Add custom body class
   */
    public function theme_body_class($classes)
    {
        if (is_front_page()) {
            $classes[] = 'home-page';
        } elseif (is_home() || is_post_type_archive('event') || is_category()) {
            $classes[] = 'news-page';
        } elseif (is_post_type_archive('reference')) {
            $classes[] = 'references-page';
        } elseif (is_page_template('contact-us.php')) {
            $classes[] = 'contact-page';
        } elseif (is_search()) {
            $classes[] = 'search-results-page';
            unset($classes[array_search('search-results', $classes)]);
        }

        return $classes;
    }

  /**
   * Add custom format.
   * @param array $init
   * @return array
   */
    public function theme_tinymce_init($init)
    {
        $formats = array(
        'p' => __('Paragraphe', 'theme'),
        'h1' => __('Titre 1', 'theme'),
        'h2' => __('Titre 2', 'theme'),
        'h3' => __('Titre 3', 'theme')
        );

        array_walk($formats, function ($key, $val) use (&$block_formats) {
            $block_formats .= esc_attr($key) . '=' . esc_attr($val) . ';';
        }, $block_formats = '');

        $init['block_formats'] = $block_formats;

      // Define the style_formats array
        $style_formats = array(
        array(
        'title' => 'Bouton',
        'selector' => 'a',
        'classes' => 'a-button as--secondary',
        'styles' => array(
          'color' => '#292929',
          'textDecoration' => 'none',
          'background' => '#cacaca'
        )
        ),
        array(
        'title' => 'Blue bold',
        'selector' => 'strong',
        'classes' => 'h-color-primary',
        'styles' => array(
          'color' => '#118ab2',
          'fontWeight' => 'bold'
        )
        )
        );
        $init['style_formats'] = json_encode($style_formats);

        return $init;
    }
}

new CustomSite();
