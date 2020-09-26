<?php

/**

 * @wordpress-plugin
 * Plugin Name:       Kamal voice note  
 * Description:       A voice recorder so that USER A can record, edit, and delete and USER B can ONLY listen
 * Version:           1.0.0
 */

add_action('init', 'kvn_init');
add_action('wp_enqueue_scripts', 'kvn_enqueue_script');
add_shortcode('kvn-recorder', 'kvn_recoder');
add_shortcode('kvn-player', 'kvn_player');

define('KVN_PLUGIN_PTH', plugin_dir_url(__FILE__));
function kvn_init()
{
}

function kvn_recoder($atts)
{

    global $post;
    if ($post) {
        $post_id = $post->ID;
        $argsThumb = array(
            'order'          => 'DESC',
            'post_type'      => 'attachment',
            'post_parent'    => $post_id,
            'post_mime_type' => 'audio/wav',
            'post_status'    => null
        );
        $attachments = get_posts($argsThumb);
    } else {
        $post_id = '';
        $attachments  = array();
    }

    ob_start();
    require realpath(dirname(__FILE__)) . "/template/recoder_view.php";
    $page = ob_get_contents();
    ob_end_clean();
    return $page;
}



function kvn_player($atts)
{
    $src = wp_get_attachment_url($atts['attachment_id']);
    ob_start();
    require realpath(dirname(__FILE__)) . "/template/player_view.php";
    $page = ob_get_contents();
    ob_end_clean();
    return $page;
}


function kvn_enqueue_script()
{
    wp_enqueue_script('kvn_recorder-js', plugin_dir_url(__FILE__) . 'assets/js/recorder.js', array('jquery'), '1.0');
}



function kvn_upload()
{

    if (empty($_FILES['audio_data'])) {
        return true;
    }

    require_once(ABSPATH . 'wp-admin/includes/image.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    $_FILES['audio_data']['name'] = $_FILES['audio_data']['name'] . '.wav';

    if (@$_POST['kvn_post_id']) {
        $post_id = @$_POST['kvn_post_id'];
    } else {
        $post_id = 0;
    }

    $attachment_id = media_handle_upload('audio_data',  $post_id);
    if (is_wp_error($attachment_id)) {
        $error_string = $attachment_id->get_error_message();
    } else {
        echo $attachment_id;
        if (!session_id()) {
            session_start();
        }
        $_SESSION['kvn_attchement'][] =  $attachment_id;
    }

    die();
}

add_action('wp_ajax_kvn_upload', 'kvn_upload');
// add_action('wp_ajax_nopriv_kvn_upload', 'kvn_upload');


function kvn_delete()
{
    $attachment_ids = ($_POST['attchement']);
    if ($attachment_ids) {
        foreach ($attachment_ids as $id) {
            wp_delete_attachment($id);
        }
    }
    echo 'done';
    die();
}

add_action('wp_ajax_kvn_delete', 'kvn_delete');
add_action('wp_ajax_nopriv_kvn_delete', 'kvn_delete');


function kvn_sync_attchment($post_id)
{
    if (!session_id()) {
        session_start();
    }
    $attachment_ids =  $_SESSION['kvn_attchement'];
    if ($attachment_ids) {
        foreach ($attachment_ids as $id) {
            $media_post = wp_update_post(array(
                'ID'            => $id,
                'post_parent'   => $post_id,
            ), true);
        }
    }
    unset($_SESSION['kvn_attchement']);
}
