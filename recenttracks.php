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

function lfm_rtracks_page_func($atts) {

    extract(shortcode_atts(array(
                'user' => get_option('lfm_username'),
                'limit' => get_option('lfm_page_limit'),
                    ), $atts));

    $raw = file_get_contents("http://ws.audioscrobbler.com/2.0/?method="
                    . "user.getrecenttracks&user={$user}"
                    . "&api_key=" . get_option('lfm_apikey') . "&limit={$limit}");

    $xml = new SimpleXMLElement($raw);

    foreach ($xml->recenttracks->track as $track) {
        $raw2 = file_get_contents("http://ws.audioscrobbler.com/2.0/?method="
                        . "track.getinfo&api_key=" . get_option('lfm_apikey')
                        . "&artist=" . urlencode($track->artist)
                        . "&track=" . urlencode($track->name));
        $xml2 = new SimpleXMLElement($raw2);
?>
        <div class="post">
            <h2 class="title">
                <a href="<?php echo $track->url; ?>">
<?php echo $track->artist; ?> - <?php echo $track->name; ?>
                </a>
            </h2>
            <p class="meta">
                <small>
<?php
        switch ((string) $track['nowplaying']):
            case 'true':
                echo('Now playing');
                break;
            default:
                echo("Listened: {$track->date}");
                break;
        endswitch;
?>
            | Scrobbled: <?php echo($xml2->track->playcount); ?> times
            | Total listeners: <?php echo($xml2->track->listeners); ?>
        </small>
    </p>
    <div class="content" style="min-height: 70px;">
        <?php if ( (bool) $xml2->track->album->image[0] ) { ?>

        <img alt="<?php echo($xml2->track->album->title); ?> Cover Art"
             src="<?php echo($xml2->track->album->image[0]); ?>"
             style="float: left; width: 64px; margin-right: 5px;" />
                <?php } ?>
<?php
            if ($xml2->track->wiki->summary != null) {
                echo($xml2->track->wiki->summary);
            } else if ( (bool) $xml2->track->album->title ) {
                echo("From the album {$xml2->track->album->title}");
            } else {
                echo("Could not find any information about this track.");
            }
?>
        <p class="tags">
                                    	Tags:
            <?php
            foreach ($xml2->track->toptags->tag as $tag):
                echo("<a href=\"{$tag->url}\">{$tag->name}</a> ");
            endforeach;
            ?>
        </p><!-- tags -->
    </div><!-- content -->
</div><!-- post -->
<?php
        } //track loop
    }

    function lfm_rtracks_summary_func($atts) {
        extract(shortcode_atts(array(
                    'user' => get_option('lfm_username'),
                    'limit' => get_option('lfm_summ_limit'),
                    'more' => null,
                        ), $atts));

        $raw = file_get_contents("http://ws.audioscrobbler.com/2.0/?method="
                        . "user.getrecenttracks&user={$user}"
                        . "&api_key=" . get_option('lfm_apikey') . "&limit={$limit}");
        $xml = new SimpleXMLElement($raw);
?>
        <div class="post">
            <h2 class="title">
		  <a href="http://ws.audioscrobbler.com/1.0/user/<?php echo($user); ?>/recenttracks.rss"
                     style="float:right;">
			<img alt="RSS Logo"
                            src="<?php echo(bloginfo('wpurl')); ?>/wp-content/plugins/adapa-lastfm/rss.png" />
		  </a>
                <a href="http://www.shiftout.com/music/recent-tracks/">
                    My Recent Tracks
                </a>
            </h2>
            <p class="meta"><small>
<?php
        switch ((string) $xml->recenttracks->track[0]['nowplaying']):
            case 'true':
                echo('Now listening');
                break;
            default:
                echo("Last listened: {$xml->recenttracks->track[0]->date}");
                break;
        endswitch;
?>
            | Showing: <?php echo($limit); ?> tracks
<?php if ($more != null): ?>
                | <a href="<?php echo($more); ?>">See more</a>
<?php endif; ?>
            </small></p>
        <div class="content">
            <ul>
<?php
                foreach ($xml->recenttracks->track as $track):
                    echo("<li><a href=\"{$track->url}\">{$track->artist} - {$track->name}</a></li>");
                endforeach;
?>
            </ul>
        </div><!-- content -->
    </div><!-- post -->
<?php
            }
