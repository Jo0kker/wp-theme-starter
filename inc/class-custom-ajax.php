<?php

/**
 * Ajax functions
 */
class CustomAjax extends Timber\Site
{
  /** Add timber support. */
    public function __construct()
    {

//        add_action('wp_ajax_coexya_loadmore_news', array($this, 'coexya_loadmore_news'));
//        add_action('wp_ajax_nopriv_coexya_loadmore_news', array($this, 'coexya_loadmore_news'));

//        add_action('rest_api_init', function () {
//            register_rest_route('coexya', 'offerCity', [
//            'methods' => 'GET',
//            'callback' => array($this, 'coexya_offerCity')
//            ]);
//        });

        parent::__construct();
    }

}

new CustomAjax();
