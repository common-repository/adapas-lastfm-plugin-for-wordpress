<?php
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

/**
 * LFM_Widget Class
 */
class LFM_Widget extends WP_Widget {

    /** constructor */
    function LFM_Widget() {
        $widgetops = array('description' => 'Adapa\'s Last.FM plugin for WordPress. Show a link to your profile, and the track you are currently listening to.');
        parent::WP_Widget(false, $name = "Last.FM", $widgetops);
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {
        

        extract($args);
        $title = apply_filters('widget_title', $instance['title']);
        $username = $instance['username'];
        if ( $title == null )
        {
            $title = "Last.FM";
        }
        if ( $username == null )
        {
            $username = get_option('lfm_username');
        }
?>
<?php echo $before_widget; ?>
<?php echo $before_title . $title . $after_title; ?>
<?php
        $raw = file_get_contents("http://ws.audioscrobbler.com/2.0/?method="
                        . "user.getinfo&user=" . $username . "&api_key=".get_option('lfm_apikey')."");
        $xml = new SimpleXMLElement($raw);
        $user = $xml->user;

        $raw = file_get_contents("http://ws.audioscrobbler.com/2.0/?method="
                        . "user.getrecenttracks&user=" . $username . "&api_key=".get_option('lfm_apikey')."");
        $xml = new SimpleXMLElement($raw);

?>
<img src="<?php echo($user->image[1]); ?>" style="float: right; margin: 10px; width: 50px !important; display:block;" />

<p style="padding: 10px;">
    <a href="<?php echo($user->url); ?>"><big><strong><?php echo($user->name); ?></strong></big> <small>on last.fm</small></a>
</p>

<ul><?php switch ((string) $xml->recenttracks->track[0]['nowplaying']):
            case 'true':
                echo("<li>Now scrobbling</li><li>Current track:</li><li><a href=\"{$xml->recenttracks->track[0]->url}\"><marquee>{$xml->recenttracks->track[0]->artist} - {$xml->recenttracks->track[0]->name}</marquee></a></li>");
                break;
            default:
                echo("<li>Last scrobbled:</li><li>{$xml->recenttracks->track[0]->date}</li>");
                break;
        endswitch; ?></ul>
<br style="clear: both;" />
<?php echo $after_widget; ?>
<?php
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['username'] = $new_instance['username'];
        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {
        $title = esc_attr($instance['title']);
        $username = esc_attr($instance['username']);
?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">
<?php _e('Title:'); ?>
                <input class="widefat"
                       id="<?php echo $this->get_field_id('title'); ?>"
               name="<?php echo $this->get_field_name('title'); ?>"
               type="text" value="<?php echo $title; ?>" />
    </label>
    <label for="<?php echo $this->get_field_id('username'); ?>">
<?php _e('Username:'); ?> 
        <input class="widefat"
               id="<?php echo $this->get_field_id('username'); ?>"
               name="<?php echo $this->get_field_name('username'); ?>"
               type="text" value="<?php echo $username; ?>" />
    </label>
</p>
<?php
    }

}

// class LFM_Widget
