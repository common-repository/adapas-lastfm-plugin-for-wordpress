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

function lfm_artists_summary_func($atts) {

    extract(shortcode_atts(array(
                'user' => get_option('lfm_username'),
                'limit' => get_option('lfm_summ_limit'),
                'period' => 'overall',
                    ), $atts));

    $raw = file_get_contents("http://ws.audioscrobbler.com/2.0/?method="
                    . "user.gettopartists&user=" . urlencode($user)
                    . "&period=" . urlencode($period)
                    . "&api_key=" . get_option('lfm_apikey'));

    $xml = new SimpleXMLElement($raw);
?>

    <div class="post">
        <h2 class="title">
            <a href="<?php echo($more); ?>">
                My Top Artists
            </a>
        </h2>
        <p class="meta">
            <small>
                Showing: <?php echo($limit); ?> artists
            <?php if ($more != null): ?>
                | <a href="<?php echo($more); ?>">
                    See more
                </a>
            <?php endif; ?>
            </small>
        </p>
        <div class="content">
            <ul>
            <?php
                $i = 0;
                foreach ($xml->topartists->artist as $artist):
                    if ($i++ == $limit)
                        break;
                    echo("<li><a href=\"{$artist->url}\">"
                    . "{$artist->name}</a></li>");
                endforeach;
            ?>
            </ul>
        </div><!-- content -->
    </div><!-- post -->

<?php
            }

            function lfm_artists_page_func($atts) {

                extract(shortcode_atts(array(
                            'user' => get_option('lfm_username'),
                            'limit' => get_option('lfm_page_limit'),
                            'period' => 'overall',
                                ), $atts));

                $raw = file_get_contents("http://ws.audioscrobbler.com/2.0/?"
                                . "method=user.gettopartists&user={$user}"
                                . "&api_key=" . get_option('lfm_apikey'));

                $xml = new SimpleXMLElement($raw);

                $i = 0;

                foreach ($xml->topartists->artist as $artist) {
                    if ($i++ == $limit)
                        break;

                    $raw2 = file_get_contents("http://ws.audioscrobbler.com/"
                                    . "2.0/?method=artist.getinfo&artist="
                                    . urlencode($artist->name)
                                    . "&api_key=" . get_option('lfm_apikey'));


                    $xml2 = new SimpleXMLElement($raw2);
?>
                    <div class="post">
                        <h2 class="title">
                            <a href="<?php echo $artist->url; ?>">
            <?php echo($artist->name); ?>
                </a>
            </h2>
            <p class="meta">
                <small>Rank: <?php echo($artist['rank']); ?>
                    | My Play Count:
            <?php echo($artist->playcount); ?>
                    | Scrobbled:
            <?php echo($xml2->artist->stats->playcount); ?>
                    times | Total listeners:
            <?php echo $xml2->artist->stats->listeners; ?>
                </small>
            </p>
            <div class="content">
                <img alt="<?php echo($artist->name); ?> Photo"
                     src="<?php echo($artist->image[2]); ?>"
                     style="float: left; margin-right: 10px;" />
                <p><?php echo $xml2->artist->bio->summary; ?></p>
                <p class="tags">
                                                                                                                                                        	Tags:
            <?php
                    foreach ($xml2->artist->tags->tag as $tag):
                        echo("<a href=\"{$tag->url}\">" . $tag->name . "</a> ");
                    endforeach;
            ?>
                </p><!-- tags -->
            </div>
        </div><!-- post -->
<?php
                }
            }

            function lfm_albums_summary_func($atts) {



                extract(shortcode_atts(array(
                            'user' => get_option('lfm_username'),
                            'limit' => get_option('lfm_summ_limit'),
                            'period' => 'overall',
                                ), $atts));

                $raw = file_get_contents("http://ws.audioscrobbler.com/"
                                . "2.0/?method="
                                . "user.gettopalbums&user=" . urlencode($user)
                                . "&period=" . urlencode($period)
                                . "&api_key=" . get_option('lfm_apikey'));

                $xml = new SimpleXMLElement($raw);
?>

                <div class="post">
                    <h2 class="title">
                        <a href="<?php echo($more); ?>">
                            My Top Albums
                        </a>
                    </h2>
                    <p class="meta">
                        <small>
                            Showing: <?php echo($limit); ?> albums
            <?php if ($more != null): ?>
                    | <a href="<?php echo($more); ?>">
                        See more
                    </a>
            <?php endif; ?>
                </small>
            </p>
            <div class="content">
                <ul>
            <?php
                    $i = 0;
                    foreach ($xml->topalbums->album as $album):
                        if ($i++ == $limit)
                            break;
                        echo("<li><a href=\"{$album->url}\">"
                        . "{$album->artist->name} "
                        . "- {$album->name}</a></li>");
                    endforeach;
            ?>
                </ul>
            </div><!-- content -->
        </div><!-- post -->

<?php
                }

                function lfm_albums_page_func($atts) {

                    extract(shortcode_atts(array(
                                'user' => get_option('lfm_username'),
                                'limit' => get_option('lfm_page_limit'),
                                'period' => 'overall',
                                    ), $atts));

                    $raw = file_get_contents("http://ws.audioscrobbler.com/2.0/?"
                                    . "method=user.gettopalbums&user={$user}"
                                    . "&api_key=" . get_option('lfm_apikey') . "");

                    $xml = new SimpleXMLElement($raw);

                    $i = 0;

                    foreach ($xml->topalbums->album as $album) {
                        if (++$i == 15)
                            break;

                        $raw2 = file_get_contents("http://ws.audioscrobbler.com/2.0/?"
                                        . "method=album.getinfo&artist="
                                        . urlencode($album->artist->name)
                                        . "&album=" . urlencode($album->name)
                                        . "&api_key=" . get_option('lfm_apikey') . "");

                        $xml2 = new SimpleXMLElement($raw2);
?>
                        <div class="post">
                            <h2 class="title"><a href="<?php echo $album->url; ?>"><?php echo($album->name); ?></a></h2>
                            <p class="meta"><small>Rank: <?php echo($album['rank']); ?> | My Play Count: <?php echo($album->playcount); ?> | Scrobbled: <?php echo($xml2->album->playcount); ?> times | Total listeners: <?php echo $xml2->album->listeners; ?></small></p>
                            <div class="content">
                                <img alt="<?php echo($album->name); ?> Cover Art"
                                    src="<?php echo($album->image[2]); ?>"
                                    style="float: left; margin-right: 10px;" />
                                <p><?php echo $xml2->album->wiki->summary; ?></p>
                                <p class="tags">
                                                                                                                                                                                            	Tags:
            <?php
                        foreach ($xml2->album->toptags->tag as $tag):
                            echo("<a href=\"{$tag->url}\">" . $tag->name . "</a> ");
                        endforeach;
            ?>
                    </p><!-- tags -->
                </div>
            </div><!-- post -->
<?php
                    }
                }

                function lfm_tracks_summary_func($atts) {



                    extract(shortcode_atts(array(
                                'user' => get_option('lfm_username'),
                                'limit' => get_option('lfm_summ_limit'),
                                'period' => 'overall',
                                    ), $atts));

                    $raw = file_get_contents("http://ws.audioscrobbler.com/2.0/?method="
                                    . "user.gettoptracks&user=" . urlencode($user)
                                    . "&period=" . urlencode($period)
                                    . "&api_key=" . get_option('lfm_apikey'));

                    $xml = new SimpleXMLElement($raw);
?>

                    <div class="post">
                        <h2 class="title">
                            <a href="<?php echo($more); ?>">
                                My Top Tracks
                            </a>
                        </h2>
                        <p class="meta">
                            <small>
                                Showing: <?php echo($limit); ?> tracks
            <?php if ($more != null): ?>
                        | <a href="<?php echo($more); ?>">
                            See more
                        </a>
            <?php endif; ?>
                    </small>
                </p>
                <div class="content">
                    <ul>
            <?php
                        $i = 0;
                        foreach ($xml->toptracks->track as $track):
                            if ($i++ == $limit)
                                break;
                            echo("<li><a href=\"{$track->url}\">{$track->artist->name} - {$track->name}</a></li>");
                        endforeach;
            ?>
                    </ul>
                </div><!-- content -->
            </div><!-- post -->

<?php
                    }

                    function lfm_tracks_page_func($atts) {



                        extract(shortcode_atts(array(
                                    'user' => get_option('lfm_username'),
                                    'limit' => get_option('lfm_page_limit'),
                                    'period' => 'overall',
                                        ), $atts));

                        $raw = file_get_contents("http://ws.audioscrobbler.com/2.0/?"
                                        . "method=user.gettoptracks&user={$user}"
                                        . "&api_key=" . get_option('lfm_apikey') . "");

                        $xml = new SimpleXMLElement($raw);

                        $i = 0;

                        foreach ($xml->toptracks->track as $track) {
                            if (++$i == 15)
                                break;

                            $raw2 = file_get_contents("http://ws.audioscrobbler.com/2.0/?"
                                            . "method=track.getinfo&artist="
                                            . urlencode($track->artist->name)
                                            . "&track=" . urlencode($track->name)
                                            . "&api_key=" . get_option('lfm_apikey') . "");

                            $xml2 = new SimpleXMLElement($raw2);
?>
                            <div class="post">
                                <h2 class="title"><a href="<?php echo $track->url; ?>"><?php echo($track->name); ?></a></h2>
                                <p class="meta"><small>Rank: <?php echo($track['rank']); ?>
                                        | My Play Count: <?php echo($track->playcount); ?>
                                        | Scrobbled: <?php echo($xml2->track->playcount); ?> times
                                        | Total listeners: <?php echo $xml2->track->listeners; ?>
                                    </small>
                                </p>
                                <div class="content">
                                    <img alt="<?php echo($xml2->track->album->name); ?> Cover Art"
                                         src="<?php echo($xml2->track->album->image[2]); ?>"
                                         style="float: left; margin-right: 10px;" />
                                    <p><?php echo $xml2->track->wiki->summary; ?></p>
                                    <p class="tags">
                                                                                                                                                                                                    	Tags:
            <?php
                            foreach ($xml2->track->toptags->tag as $tag):
                                echo("<a href=\"{$tag->url}\">" . $tag->name . "</a> ");
                            endforeach;
            ?>
                        </p><!-- tags -->
                    </div>
                </div><!-- post -->
<?php
                        }
                    }
