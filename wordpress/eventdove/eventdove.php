<?php
/**
 * Plugin Name: Meeting of EventDove
 * Plugin URI: echoname.com
 * Version: 1.0
 * Author: Fred Zhou
 * Description: The meeting info for EventDove. Please <a href="options-general.php?page=eventdove/eventdove.php">Click Here</a> and fill out the token of eventdove after actived the plugin.
 **/

define("PLUG_EVENTDOVE_ROOT", dirname(__FILE__));

class wp_eventdove_meeting {

/*{{{ variable */
    var $package = "eventdove";
    var $page_title = 'EventDove Meeting Setting';
    var $menu_title = 'EventDove Meeting';

    static $taxonomy = "eventdove-meeting";
    static $terms = array("name" => "Eventdove", "slug" => "eventdove");
    static $option_name = 'eventdove_meeting_token';
/*}}}*/
/*{{{ contruct */
    function wp_eventdove_meeting() {
        register_activation_hook(__FILE__, array(&$this, "install"));
        register_uninstall_hook(__FILE__, array("wp_eventdove_meeting", "uninstall"));
        //register_deactivation_hook(__FILE__, array(&$this, "uninstall"));

        add_action("admin_menu", array(&$this, "add_options_page"));
        add_action("init", array(&$this, "nav_menu"));
        add_filter("template_include", array(&$this, "template_include"));
    }
/*}}}*/
/*{{{ install */
    function install() {
        global $wpdb;
        // Insert term
        $out = $wpdb->insert($wpdb->terms, array(
            "name" => self::$terms["name"],
            "slug" => self::$terms["slug"],
            "term_group" => 0,
        ));
        if ($out) {
            // Insert taxonomy
            $wpdb->insert($wpdb->term_taxonomy, array(
                "term_id" => $wpdb->insert_id,
                "taxonomy" => self::$taxonomy,
                "description" => $this->package,
                "parent" => 0,
                "count" => 0,
            ));
        }
        flush_rewrite_rules();
    }
/*}}}*/
/*{{{ uninstall */
    static function uninstall() {
        global $wpdb;
        // Delete terms
        $terms = get_terms(self::$taxonomy, "hide_empty=0");
        if ($terms) {
            foreach ($terms as $term) {
                wp_delete_term(intval($term->term_id), $term->taxonomy);
            }
        }
        // Delete options
        delete_option(self::$option_name);
    }
/*}}}*/
/*{{{ nav_menu */
    function nav_menu() {
        $category_labels = array(
            'name' => __($this->menu_title, self::$taxonomy),
            'all_items' => __( 'All Categories' ),
            'menu_name' => __( 'Categories' ),
        );

        register_taxonomy(self::$taxonomy, array(self::$terms["slug"]), array(
            'hierarchical' => false,
            'labels' => $category_labels,
            'show_ui' => true,
            'query_var' => true,
            'public'=> true,
            'rewrite' => true,
        ));
    }
/*}}}*/
/*{{{ template_include */
    function template_include($template){
        if (get_query_var("name") == self::$terms["slug"]) {
            if (!$_GET["id"]) {
                // List
                $template = PLUG_EVENTDOVE_ROOT . "/templates/event_list.php";
            } else {
                // Detail
                $template = PLUG_EVENTDOVE_ROOT . "/templates/event_detail.php";
            }
        }
        return $template;
    }
/*}}}*/
/*{{{ add_options_page */
    function add_options_page() {
        if (function_exists('add_options_page')) {
            add_options_page($this->page_title, $this->menu_title, 9, __FILE__, array(&$this, 'options_page'));
        }
    }
/*}}}*/
/*{{{ options_page() */
    function options_page() {
        $token = get_option(self::$option_name);
        $new = trim($_POST[self::$option_name]);
        if (isset($_POST[self::$option_name]) && $token != $new) {
            if (update_option(self::$option_name, $new)) {
                $message = _e("Success to save.");
            } else {
                $message = _e("Fail to save.");
            }
            $token = $new;
        }        
?>
<div class="wrap">
    <h2><?php _e('EventDove Meeting Setting'); ?></h2>
<?php if (isset($message)) : ?>
    <div style="background-color: #3f3f3f">
        <?php echo $message; ?>
    </div>
<?php endif;?>    
    <form action="<?php echo esc_attr(add_query_arg('save', 'true')); ?>" method="post"><?php wp_nonce_field('update-options');?>
        <p><?php _e('Token:'); ?>
        <input type="text" name="<?php echo self::$option_name; ?>" value="<?php echo $token; ?>" style="width:300px" /></p>
        <div class="submit"><input type="submit" value="<?php _e('Save'); ?> &raquo;" /></div>
    </form>
</div>
<?php
    }
/*}}}*/
/*{{{ url */
    function url($id) {
        $scheme = is_ssl() ? "https" : "http";
        $url = sprintf("?%s=%s", self::$taxonomy, self::$terms["slug"]);
        if ($id) {
            $url .= "&id=" . $id;
        }
        return site_url($url, $scheme);
    }
/*}}}*/
/*{{{ list_meeting */
    function list_meeting() {
        // AccessToken: 4e6101d2-cc78-4a6d-b823-bae3804aef1b
        // open.eventdove.com
        // /api/event_list.do?access_token=4e6101d2-cc78-4a6d-b823-bae3804aef1b
        // http://open.eventdove.com/api/event_list.do?access_token=54661928-2507-40e7-98be-cef1a937d191
        $token = get_option(self::$option_name);
        if (!$tmp = file_get_contents("http://open.eventdove.com/api/event_list.do?access_token={$token}")) {
            return false;
        }

        $events = array("upcoming" => array(), "past" => array(),);
        try {
            $data = json_decode($tmp, true);
            // Error
            // 60001: Invalid Token or audit fails
            // 60002: No such event or not the organizer
            // 69998: Did not pass validation
            // 69999: An unknown error
            $error = array("60001", "60002", "69998", "69999");
            if (in_array($data["errorCode"], $error)) {
                return false;
            }

            foreach ($data["returnObject"] as $val) {
                // eventTitle
                // subdomainName
                // http://subdomainName.eventdove.com
                $e = array(
                    "id" =>  $val["eventId"],
                    "link" => "http://" . $val["subdomainName"] . ".eventdove.com",
                    "title" =>  $val["eventTitle"],
                );
                $e["logoUrl"] = $e["link"] . $val["logoUrl"];
                $e["startTime"] = $val["startStringData"];
                $e["endTime"] = sprintf("%s/%s/%s", (1900 + $val["endTimestamp"]["year"]), (1 + $val["endTimestamp"]["month"]), $val["endTimestamp"]["date"]);
                $e["address"] = $val["eventAddress"];
                $e["brief"] = $val["brief"];

                switch ($val["pubStatus"]) {
                    // upcoming events: pubStatus =1 OR pubStatus = 2
                    case 1:
                    case 2:
                        $events["upcoming"][] = $e;
                        break;

                    // Past events:pubStatus = 3
                    case 3:
                        $events["past"][] = $e;
                        break;
                }
            }
        } catch(Exception $ex) {
            // Do nothing;
        }

        return $events;
    }
/*}}}*/
/*{{{ detail_meeting */
    function detail_meeting() {
        $event = $this->list_meeting();
        $out = array();
        $out["find"] = false;
        foreach ($event as $key => $val) {
            foreach ($val as $v) {
                if ($v["id"] == $_GET["id"]) {
                    $out["title"] = $v["title"];
                    $out["startTime"] = $v["startTime"];
                    $out["iframe"] = $v["link"];
                    $out["find"] = true;
                    break;
                }
            }
            if ($out["find"]) {
                break;
            }
        }

        return $out;
    }
/*}}}*/

}

$eventdove_meeting = new wp_eventdove_meeting();
