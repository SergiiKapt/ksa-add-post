<?php

class KSA_Add_Post_Shortcode
{
    public static function run()
    {
        add_shortcode('ksa_add_post_form', [__CLASS__, 'ksa_add_post_form_shortcode']);
        add_action('wp_enqueue_scripts', [__CLASS__, 'ksa_addp_custom_scripts']);
        add_action('wp_enqueue_scripts', [__CLASS__, 'ksa_addp_translate']);
        add_action('wp_ajax_ksa_addp_submit', [__CLASS__, 'ksa_addp_submit_callback']);
        add_action('wp_ajax_nopriv_ksa_addp_submit', [__CLASS__, 'ksa_addp_submit_callback']);
    }

    public static function ksa_add_post_form_shortcode()
    {
        if (is_user_logged_in()):

            echo get_option('admin_email');
            echo get_option('bloginfo');




            ?>
            <div id="ksa__addp" class="ksa__addp">
                <div class="title__wrap">
                    <p class="title"><?php echo __('Create post') ?></p>
                </div>
                <?php wp_nonce_field('ksa_addp_nonce'); ?>
                <div class="field__wrap">
                    <label for="#ksa__addp__title" class="label label__title"><?php echo __('Title:') ?></label>
                    <input id="ksa__addp__title" class="input__title" type="text" name="title">
                </div>
                <div class="field__wrap">
                    <label for="#ksa__addp__text" class="label label__text"><?php echo __('Text:') ?></label>
                    <textarea id="ksa__addp__text" class="input__text" name="text" id="" cols="30"
                              rows="3"></textarea>
                </div>
                <div class="btn__wrap">
                    <input id="ksa__addp__submit" class="btn submit ksa__addp__submit" type="submit"
                           name="lwCSFormSubmit" value="<?php echo __('Create post') ?>">
                </div>
            </div>
        <?php
        endif;
    }

    public
    static function ksa_addp_custom_scripts()
    {

        wp_enqueue_script('ksa-addp-js', KSA_ADDP_P_URI . 'assets/js/ksa-addp.js', array('jquery'), null, true);
        wp_enqueue_style('ksa-addp-style', KSA_ADDP_P_URI . 'assets/css/style.css');
        wp_localize_script('ksa-addp-js', 'ksa_addp_data',
            array(
                'url' => admin_url('admin-ajax.php'),
                'home_url' => home_url(),
            )
        );

        wp_register_script('ksa-addp-translate-js', plugins_url('/js/translate.js'), array('wp-i18n'));
    }


    public
    static function ksa_addp_translate()
    {
        $jsfile_url = plugins_url('/js/ksa-addp.js');

        wp_enqueue_script('my-script', $jsfile_url);
        wp_set_script_translations('my-script', 'myl10n', plugins_url('/js/translate.js'));
    }

    public
    static function ksa_addp_submit_callback()
    {
        if (is_user_logged_in()) {

            require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

            if (!check_ajax_referer('ksa_addp_nonce')) {
                wp_die();
            }

            if (post_exists($_POST['title'])) {
                $response = ['status' => 'error', 'msg' => __('Post with this title already exists')];
            } else {
                self::createPost($_POST);
                self::sendMAil($_POST['title'], $_POST['text']);
                $response = ['status' => 'success', 'msg' => __('Post was created!')];
            }

            echo json_encode($response);
        }

        die;
    }

    public
    static function createPost($data)
    {
        $post_data = array(
            'post_title' => sanitize_text_field($data['title']),
            'post_content' => $data['text'],
            'post_status' => 'pending',
            'post_author' => get_current_user_id(),
        );

    }
    public static function sendMAil($title, $text)
    {
        $headers = "From: " . get_option('blogname') . "\r\n";
        $msg = __('Title') . ": " . $title . ".\r\n";
        $msg .= __('Text') . ":\r\n" . $text . ".\r\n";
        $theme = __('create new post from KSASK add post shortcode');

        wp_mail(get_option('admin_email'), $theme, $msg, $headers);
    }
}
