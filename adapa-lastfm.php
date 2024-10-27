<?php



/*

  Plugin Name: Adapa's Last.FM plugin for WordPress

  Plugin URI: http://www.shiftout.com/p/software/lastfm-shortcode

  Description: Allows for shortcode to be added to a page or post showing your last.fm statistics and includes a sidebar widget for showing a link to your profile and your currently playing track.

  Version: 0.2.1

  Author: Iain Learmonth

  Author URI: http://www.shiftout.com/

  License: GPLv2 or above

 */



/*  Copyright 2010 Iain Learmonth (email : adapa@shiftout.com)



  This program is free software; you can redistribute it and/or modify

  it under the terms of the GNU General Public License, version 2, as

  published by the Free Software Foundation.



  This program is distributed in the hope that it will be useful,

  but WITHOUT ANY WARRANTY; without even the implied warranty of

  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the

  GNU General Public License for more details.



  You should have received a copy of the GNU General Public License

  along with this program; if not, write to the Free Software

  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

 */



function lfm_needs_settings_callback() {

?>

    <p style="background-color: pink; border: 1px solid black;">You either have 

        not set your Last.FM username or API key. To use Adapa&#x27;s

        Last.FM plugin for WordPress, you need to do this. These settings can be    

        found on the <a href="admin.php?page=adapas-lastfm-plugin-for-wordpress/options-page.php">

        Adapa's Last.FM Plugin for WordPress options</a> page.</p>

<?php



}



if (get_option('lfm_apikey') == null ||

        get_option('lfm_username' == null)) {

    add_action('admin_notices', 'lfm_needs_settings_callback');

} else {



    /** Classic Shortcodes **/



    require_once 'recenttracks.php';

    add_shortcode('lfm_rtracks_page', 'lfm_rtracks_page_func');

    add_shortcode('lfm_rtracks_summary', 'lfm_rtracks_summary_func');



    require_once 'userinfo.php';

    add_shortcode('lfm_userinfo', 'lfm_userinfo_func');



    require_once 'charts.php';

    add_shortcode('lfm_artists_page', 'lfm_artists_page_func');

    add_shortcode('lfm_artists_summary', 'lfm_artists_summary_func');

    add_shortcode('lfm_albums_page', 'lfm_albums_page_func');

    add_shortcode('lfm_albums_summary', 'lfm_albums_summary_func');

    add_shortcode('lfm_tracks_page', 'lfm_tracks_page_func');

    add_shortcode('lfm_tracks_summary', 'lfm_tracks_summary_func');



    /** Classic Widget **/



    require_once 'widget.php';

    add_action('widgets_init', create_function('', 'return register_widget("LFM_Widget");'));

}



require_once 'options-page.php';

