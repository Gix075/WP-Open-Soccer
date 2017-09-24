<?php

/**
 * 
 * @link              https://github.com/Gix075
 * @since             0.1.0
 * @package           Sports_Open_Data
 *
 * @wordpress-plugin
 * Plugin Name:       WP Open Soccer
 * Plugin URI:        https://github.com/Gix075/WP-Open-Soccer
 * Description:       Cool soccer shortcodes
 * Version:           0.1.0
 * Author:            Gix075
 * Author URI:        https://github.com/Gix075
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-open-soccer
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'PLUGIN_VERSION', '1.0.0' );


add_action('admin_menu', 'sportsOpenData_setup_menu');
 
function sportsOpenData_setup_menu(){
     add_menu_page( 'WP Open Soccer', 'WP Open Soccer', 'manage_options', 'wp-open-soccer-plugin', 'sportsOpenData_adminInit' );
}
 
function sportsOpenData_adminInit(){
    $markup = '<h1>WP Sports Open Data</h1>';
    $markup .= '<p><strong>This plugin is at ALPHA version,</strong><br>';
    $markup .= 'and it works just as a collection of cool shortcodes</p>';
    $markup .= '<h2>How to use</h2>';
    $markup .= '<p>You can choose from several shortcodes. Each shortcode provide a different widget, such as "standings", "stats" and more, that you can use with some custom options.</p>';
    $markup .= '<p><strong>[OPENSOCCER_STANDINGS league="" season="" view="" layout=""]</strong></p>';
    
    echo $markup;
}

/* ************************************************ */
/* SEASON STANDINGS by OSD Api */
/* ************************************************ */

$a = array();
function sportsOpenData_standings_shortcode($atts) {

   global $a;

    $a = shortcode_atts( array(
        'element' => "#standingsTable",
        'league' => 'serie-b',
        'season' => '16-17',
        'view' => 'simple', // simple,advanced,extended,full
        'template' => 'table', // table,tabless
        'images' => '/wp-content/uploads/football-widgets/team-logos/'
    ), $atts );

    wp_enqueue_script(
        'jQueryTeamStandings',
        plugin_dir_url( __FILE__ ) . 'assets/js/jquery.gopensoccer.standings.js',
        array('jquery'),
        true
    );

    function addScriptToFooter_widgetStandings() {

        global $a;

        $script = "<script>".PHP_EOL;
        $script .= "jQuery('".$a['element']."').gOpenSoccer_getStandings(".PHP_EOL;
        $script .= "    {";
        $script .= "        apiBaseURL: 'http://soccer.sportsopendata.net/v1/leagues/',".PHP_EOL;
        $script .= "        season: '".$a['season']."',".PHP_EOL;
        $script .= "        league: '".$a['league']."',".PHP_EOL;
        $script .= "        view: '".$a['view']."',".PHP_EOL;
        $script .= "        template: '".$a['template']."'".PHP_EOL;

        $script .= "    }".PHP_EOL;
        $script .= ");".PHP_EOL;
        $script .= "</script>   ".PHP_EOL;

        echo $script;

    }

    add_action('wp_footer', 'addScriptToFooter_widgetStandings', 50);

    $table_head = array();
    $table_head[0] = "Posizione";
    $table_head[1] = "Squadra";
    $table_head[2] = "Punti";
    $table_head[3] = "Partite";

    switch($a['view']) {
        case "advanced":
            $table_head[4] = "Vittorie";
            $table_head[5] = "Pareggi";
            $table_head[6] = "Sconfitte";
            $table_head[7] = "Goal Fatti";
            $table_head[8] = "Goal Subiti";
            $table_head[9] = "Differenza";
            break;
        case "extended":
            $table_head[4] = "Vittorie";
            $table_head[5] = "Pareggi";
            $table_head[6] = "Sconfitte";
            $table_head[7] = "Goal Fatti";
            $table_head[8] = "Goal Subiti";
            $table_head[9] = "Differenza";
            $table_head[10] = "Vittorie in casa";
            $table_head[11] = "Pareggi in casa";
            $table_head[12] = "Sconfitte in casa";
            $table_head[13] = "Vittorie in trasferta";
            $table_head[14] = "Pareggi in trasferta";
            $table_head[15] = "Sconfitte in trasferta";
            break;
        case "full":
            $table_head[4] = "Vittorie";
            $table_head[5] = "Pareggi";
            $table_head[6] = "Sconfitte";
            $table_head[7] = "Goal Fatti";
            $table_head[8] = "Goal Subiti";
            $table_head[9] = "Differenza";
            $table_head[10] = "Vittorie in casa";
            $table_head[11] = "Pareggi in casa";
            $table_head[12] = "Sconfitte in casa";
            $table_head[13] = "Goal fatti in casa";
            $table_head[14] = "Goal subiti in casa";
            $table_head[15] = "Differenza reti in casa";
            $table_head[16] = "Vittorie in trasferta";
            $table_head[17] = "Pareggi in trasferta";
            $table_head[18] = "Sconfitte in trasferta";
            $table_head[19] = "Goal fatti in trasferta";
            $table_head[20] = "Goal subiti in trasferta";
            $table_head[21] = "Differenza reti in trasferta";
            
            break;
    }

    if($a['template'] == "table") {
        $thead = "";
        foreach ($table_head as $key => $th) {
            $thead .= "<th>".$th."</th>";
        }
        $thead = '<thead><tr>'.$thead.'</tr></thead>';
        $markup = '<div class="sportdata_widgetStandings-tableresponsivator"><table class="sportdata_widgetStandings-table">'.$thead.'<tbody id="'.str_replace('#', '', $a['element']).'"></tbody></table></div>';
    }else{
        $markup = '<div id="'.str_replace('#', '', $a['element']).'" class="sportdata_standingsTool-wrapper"></div>';
    }

    return $markup;
}
add_shortcode( 'OPENSOCCER_STANDINGS', 'sportsOpenData_standings_shortcode' );
