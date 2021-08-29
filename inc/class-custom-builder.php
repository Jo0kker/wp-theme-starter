<?php

/**
 * Builder config
 */
class CustomBuilder extends Timber\Site
{
    /** Add timber support. */
    public function __construct()
    {

        add_filter('timber/context', array($this, 'add_to_context'));
        parent::__construct();
    }

    /** Check modules
     *
     * @param string $context context['this'] Being the Twig's {{ this }}.
     */
    public function check_modules()
    {
        $builder_context                    = [];
        $builder_context['modules_context'] = [];
        $module_i                           = 1;

        if (have_rows('modules')) {
            while (have_rows('modules')) : the_row();
                $layout = get_row_layout();

                switch ($layout) {
                    case 'module_news':
                        $terms = get_sub_field('module_news_categories');
                        $args  = [
                            'post_type' => 'post',
                            'numberposts' => 10,
                            'category' => $terms
                        ];

                        $builder_context['modules_context']['module_' . $module_i] = get_posts($args);
                        break;

                    case 'module_references':
                        $refs = get_sub_field('module_references_selection');
                        $args = [
                            'post_type' => 'reference',
                            'include' => $refs
                        ];

                        $builder_context['modules_context']['module_' . $module_i] = get_posts($args);
                        break;

                    case 'module_form':
                        $form_id = get_sub_field('module_form_id');
                        $form    = gravity_form($form_id, false, false, false, null, true, false, false);

                        $builder_context['modules_context']['module_' . $module_i] = $form;
                        break;
                }
                ++$module_i;
            endwhile;
        }

        return $builder_context;
    }

    /** Add some context
     *
     * @param string $context context['this'] Being the Twig's {{ this }}.
     */
    public function add_to_context($context)
    {
        $builder_context = $this->check_modules();
        $merge_context   = array_merge($context, $builder_context);

        return $merge_context;
    }
}

new CustomBuilder();
