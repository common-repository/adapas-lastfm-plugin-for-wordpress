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

function lfm_userinfo_func($atts, $content = null) {
    extract(shortcode_atts(array(
                'user' => get_option('lfm_username'),
                    ), $atts));

                    

    $raw = file_get_contents("http://ws.audioscrobbler.com/2.0/?method="
            ."user.getinfo&user=" . $user . "&api_key=".get_option('lfm_apikey')."");

    $xml = new SimpleXMLElement($raw);

    $user = $xml->user;
?>
    <div class="post">
        <h2 class="title"><a href="<?php echo $user->url; ?>">
        <?php echo $user->realname; ?> at last.fm</a>
            (<a href="<?php echo $user->url; ?>"><?php echo $user->name; ?></a>)
        </h2>
        <p class="meta"><small>Total Plays: <?php echo $user->playcount; ?>
                | Registered: <?php echo($user->registered); ?></small></p>
        <div class="content">
            <img alt="<?php echo($user->name); ?>"  src="<?php echo($user->image[3]); ?>"
                 style="float: right; margin-left: 10px;" />
                 <?php echo($content); ?>
        </div><!-- content -->
    </div><!-- post -->

<?php
}
