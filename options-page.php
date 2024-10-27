<?php

// create custom plugin settings menu

add_action('admin_menu', 'lfm_create_menu');



function lfm_create_menu() {



	//create new top-level menu

	add_menu_page('Adapa\'s Last.FM Plugin for WordPress Settings', 'Last.fm', 'manage_options', __FILE__, 'lfm_settings_page',plugins_url('/as.png', __FILE__),50);



	//call register settings function

	add_action( 'admin_init', 'register_lfm_settings' );



	if ( (bool) get_option('lfm_create_summary_page') ) {

		// Create post object

		$my_post = array(

			'post_title' => 'Music Summary',

			'post_type' => 'page',

			'post_content' => '[lfm_userinfo]Edit this page to change this introduction[/lfm_userinfo]<br /><br />'.

				'[lfm_rtracks_summary]<br /><br />[lfm_artists_summary]<br /><br />[lfm_albums_summary]<br /><br />'.

				'[lfm_tracks_summary]',

		     'post_status' => 'publish',

	     	);





		// Insert the post into the database

		wp_insert_post( $my_post );



		update_option('lfm_create_summary_page',false);

	}	



	if ( (bool) get_option('lfm_create_rtracks_page') ) {

		// Create post object

		$my_post = array(

			'post_title' => 'My Recent Tracks',

			'post_type' => 'page',

			'post_content' => '[lfm_rtracks_page]',

		     'post_status' => 'publish',

	     	);





		// Insert the post into the database

		wp_insert_post( $my_post );



		update_option('lfm_create_rtracks_page',false);

	}



	if ( (bool) get_option('lfm_create_artists_page') ) {

		// Create post object

		$my_post = array(

			'post_title' => 'My Top Artists',

			'post_type' => 'page',

			'post_content' => '[lfm_artists_page]',

		     'post_status' => 'publish',

	     	);





		// Insert the post into the database

		wp_insert_post( $my_post );



		update_option('lfm_create_artists_page',false);

	}



	if ( (bool) get_option('lfm_create_albums_page') ) {

		// Create post object

		$my_post = array(

			'post_title' => 'My Top Albums',

			'post_type' => 'page',

			'post_content' => '[lfm_albums_page]',

		     'post_status' => 'publish',

	     	);





		// Insert the post into the database

		wp_insert_post( $my_post );



		update_option('lfm_create_albums_page',false);

	}



	if ( (bool) get_option('lfm_create_tracks_page') ) {

		// Create post object

		$my_post = array(

			'post_title' => 'My Top Tracks',

			'post_type' => 'page',

			'post_content' => '[lfm_tracks_page]',

		     'post_status' => 'publish',

	     	);





		// Insert the post into the database

		wp_insert_post( $my_post );



		update_option('lfm_create_tracks_page',false);

	}

}





function register_lfm_settings() {

	//register our settings

	register_setting( 'lfm-settings-group', 'lfm_apikey' );

	register_setting( 'lfm-settings-group', 'lfm_username' );

	register_setting( 'lfm-settings-group', 'lfm_summ_limit' );

	register_setting( 'lfm-settings-group', 'lfm_page_limit' );

	register_setting( 'lfm-settings-group', 'lfm_create_summary_page' );

	register_setting( 'lfm-settings-group', 'lfm_create_rtracks_page' );

	register_setting( 'lfm-settings-group', 'lfm_create_artists_page' );

	register_setting( 'lfm-settings-group', 'lfm_create_albums_page' );

	register_setting( 'lfm-settings-group', 'lfm_create_tracks_page' );

}



function lfm_settings_page() {



	if ( get_option('lfm_summ_limit') == null )

	{

		update_option('lfm_summ_limit',5);

	}

	if ( get_option('lfm_page_limit') == null )

	{

		update_option('lfm_page_limit',20);

	}



?>



<div class="wrap">

<h2>Adapa's Last.FM Plugin for WordPress</h2>



<p>Here you can configure various settings related to Adapa's Last.FM Plugin for WordPress.</p>



<form method="post" action="options.php">

    <?php settings_fields( 'lfm-settings-group' ); ?>



<h3>General Settings</h3>



    <table class="form-table">

        <tr valign="top">

        <th scope="row">API Key:</th>

        <td><input type="text" name="lfm_apikey" value="<?php echo get_option('lfm_apikey'); ?>" /></td><td>If you do not yet have an API key, you can easily sign up for one <a href="http://www.last.fm/api/account">here</a>.</td>

        </tr>

         

        <tr valign="top">

        <th scope="row">Username:</th>

        <td><input type="text" name="lfm_username" value="<?php echo get_option('lfm_username'); ?>" /></td><td>This is the same username that you would use to sign in to the Last.FM website, or your media player that scrobbles to Last.FM.</td>

        </tr>

    </table>



    <p class="submit">

    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />

    </p>



<h3>Shortcode Settings</h3>



    <p>For more information on using shortcodes, see <a href="http://orbiter.shiftout.com/projects/lfm_wp/wiki/ShortCode">here</a>.</p>



    <table class="form-table">

        <tr valign="top">

        <th scope="row">Default limit for summary: </th>

        <td><input type="text" name="lfm_summ_limit" value="<?php echo get_option('lfm_summ_limit'); ?>" /></td><td>By setting this limit, it will control how many results are shown when using summary shortcodes. Remember, you can override the default value when using the shortcode.</td>

	</tr>



	<tr valign="top">

        <th scope="row">Default limit for page: </th>

        <td><input type="text" name="lfm_page_limit" value="<?php echo get_option('lfm_page_limit'); ?>" /></td><td>By setting this limit, it will control how many results are shown when using page shortcodes. Remember, you can override the default value when using the shortcode.</td>

        </tr>

    </table>

    

    <p class="submit">

    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />

    </p>



<h3>Last.FM Pages</h3>

<p>This plugin can auto-generate pages with your Last.FM information if you wish. You will be free to edit these pages under the pages tab to your left at a later time, for example, to change the order that they appear in your navigation menu, or to change their hierarchy.</p>



<ul>

<li>

<?php echo '<input name="lfm_create_summary_page" type="checkbox" value="1" class="code" ' . checked( 1, get_option('lfm_create_summary_page'), false ) . ' /> Auto create summary page'; ?>

</li>

<li>

<?php echo '<input name="lfm_create_rtracks_page" type="checkbox" value="1" class="code" ' . checked( 1, get_option('lfm_create_rtracks_page'), false ) . ' /> Auto create recent tracks'; ?>

</li>

<li>

<?php echo '<input name="lfm_create_artists_page" type="checkbox" value="1" class="code" ' . checked( 1, get_option('lfm_create_artists_page'), false ) . ' /> Auto create top artists page'; ?>

</li>

<li>

<?php echo '<input name="lfm_create_albums_page" type="checkbox" value="1" class="code" ' . checked( 1, get_option('lfm_create_albums_page'), false ) . ' /> Auto create top albums page'; ?>

</li>

<li>

<?php echo '<input name="lfm_create_tracks_page" type="checkbox" value="1" class="code" ' . checked( 1, get_option('lfm_create_tracks_page'), false ) . ' /> Auto create top tracks page'; ?>

</li>

</ul>



    <p class="submit">

    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />

    </p>



</form>



<h3>Widgets</h3>

This plugin also includes a sidebar widget. You can enable widgets on the Widgets page under the Appearance menu. This plugin supports multiple instances of the same widget, and therefore, you could have multiple instances showing links and currently playing tracks for multiple users. By default, if you do not specify a username, the widget will use the username you've specified above.



<form action="widgets.php" method="get">

    <p class="submit">

    <input type="submit" class="button-primary" value="<?php _e('Go to Widgets') ?>" />

    </p>

</form>



</div>

<?php } 



