<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Mustache helper to load a theme configuration.
 *
 * @package    theme_ergo
 * @copyright  2020 David Herney
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_ergo\util;

use theme_config;
use stdClass;

defined('MOODLE_INTERNAL') || die();

/**
 * Helper to load a theme configuration.
 *
 * @package    theme_ergo
 * @copyright  2020 David Herney
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class theme_settings {

    /**
     * Get config theme footer itens
     *
     * @return array
     */
    public function footer_items() {
        $theme = theme_config::load('ergo');

        $templatecontext = [];

        $templatecontext['footercontent'] = $theme->settings->footercontent;
        $templatecontext['footerinfo'] = $theme->settings->footerinfo;

        return $templatecontext;
    }

    /**
     * Get config theme slideshow
     *
     * @return array
     */
    public function slideshow() {
        global $OUTPUT;

        $theme = theme_config::load('ergo');

        $templatecontext = [];

        $slidercount = 10;
        $j = 0;

        for ($i = 1; $i <= $slidercount; $i++) {
            $sliderimage = "sliderimage{$i}";
            $slidertitle = "slidertitle{$i}";
            $slidercap = "slidercap{$i}";
            $sliderorder = "sliderorder{$i}";

            $image = $theme->setting_file_url($sliderimage, $sliderimage);

            if ((empty($image) && empty($theme->settings->$slidertitle) && empty($theme->settings->$slidercap)) ||
                    empty($theme->settings->$sliderorder)) {
                continue;
            }

            $templatecontext['slides'][$j]['order'] = $theme->setting_file_url($sliderorder, $sliderorder);
            $templatecontext['slides'][$j]['key'] = $j;
            $templatecontext['slides'][$j]['active'] = false;

            if (empty($image)) {
                $image = $OUTPUT->image_url('slide_default', 'theme');
            }
            $templatecontext['slides'][$j]['image'] = $image;
            $templatecontext['slides'][$j]['title'] = $theme->settings->$slidertitle;
            $templatecontext['slides'][$j]['caption'] = $theme->settings->$slidercap;

            if ($i === 1) {
                $templatecontext['slides'][$j]['active'] = true;
            }

            $j++;
        }

        if (isset($templatecontext['slides'])) {
            usort($templatecontext['slides'], function($a, $b) {
                return ($a['order'] < $b['order']) ? -1 : 1;
            });
        }

        $templatecontext['hasslides'] = $j;

        return $templatecontext;
    }

    /**
     * Get config theme news items
     *
     * @return array
     */
    public function news_items() {
        global $OUTPUT;

        $theme = theme_config::load('ergo');

        $templatecontext = [];
        $templatecontext['news'] = array();

        $newscount = 10;
        $j = 0;
        for ($i = 1; $i <= $newscount; $i++) {
            $newsimage = 'newsimage' . $i;
            $newscontent = 'newscontent' . $i;
            $newsurl = 'newslink' . $i;
            $newsorder = 'newsorder' . $i;

            if ((empty($theme->settings->$newsimage) && empty($theme->settings->$newscontent)) ||
                    empty($theme->settings->$newsorder)) {
                continue;
            }

            $templatecontext['news'][$j]['order'] = $theme->settings->$newsorder;

            if (!empty($theme->settings->$newsimage)) {
                $templatecontext['news'][$j]['image'] = $theme->setting_file_url($newsimage, $newsimage);
            }

            $templatecontext['news'][$j]['content'] = '';
            if (!empty($theme->settings->$newscontent)) {
                $templatecontext['news'][$j]['content'] = theme_ergo_get_setting($newscontent, 'format_html');
            }

            if (!empty($theme->settings->$newsurl)) {
                $templatecontext['news'][$j]['url'] = $theme->settings->$newsurl;
            }

            $j++;
        }

        if (isset($templatecontext['news'])) {
            usort($templatecontext['news'], function($a, $b) {
                return ($a['order'] < $b['order']) ? -1 : 1;
            });
        }

        $templatecontext['hasnews'] = $j;

        return $templatecontext;
    }

    /**
     * Get config theme logos items
     *
     * @return array
     */
    public function logos_items() {
        global $OUTPUT;

        $theme = theme_config::load('ergo');

        $templatecontext = [];
        $templatecontext['logos'] = array();

        $logoscount = 10;
        $j = 0;
        for ($i = 1; $i <= $logoscount; $i++) {
            $logosimage = 'logosimage' . $i;
            $logoscontent = 'logoscontent' . $i;
            $logosurl = 'logoslink' . $i;
            $logosorder = 'logosorder' . $i;

            if ((empty($theme->settings->$logosimage) && empty($theme->settings->$logoscontent)) ||
                    empty($theme->settings->$logosorder)) {
                continue;
            }

            $templatecontext['logos'][$j]['order'] = $theme->settings->$logosorder;

            if (!empty($theme->settings->$logosimage)) {
                $templatecontext['logos'][$j]['image'] = $theme->setting_file_url($logosimage, $logosimage);
            }

            $templatecontext['logos'][$j]['content'] = '';
            if (!empty($theme->settings->$logoscontent)) {
                $templatecontext['logos'][$j]['content'] = theme_ergo_get_setting($logoscontent, 'format_html');
            }

            if (!empty($theme->settings->$logosurl)) {
                $templatecontext['logos'][$j]['url'] = $theme->settings->$logosurl;
            }

            $j++;
        }

        if (isset($templatecontext['logos'])) {
            usort($templatecontext['logos'], function($a, $b) {
                return ($a['order'] < $b['order']) ? -1 : 1;
            });
        }

        $templatecontext['haslogos'] = $j;

        return $templatecontext;
    }

    /**
     * Get config theme stats items
     *
     * @return array
     */
    public function stats_items() {
        global $OUTPUT;

        $theme = theme_config::load('ergo');

        $templatecontext = [];
        $templatecontext['stats'] = array();

        $statscount = 10;
        $j = 0;
        for ($i = 1; $i <= $statscount; $i++) {
            $statsimage = 'statsimage' . $i;
            $statscontent = 'statscontent' . $i;
            $statsurl = 'statslink' . $i;
            $statsorder = 'statsorder' . $i;

            if ((empty($theme->settings->$statsimage) && empty($theme->settings->$statscontent)) ||
                    empty($theme->settings->$statsorder)) {
                continue;
            }

            $templatecontext['stats'][$j]['order'] = $theme->settings->$statsorder;

            if (!empty($theme->settings->$statsimage)) {
                $templatecontext['stats'][$j]['image'] = $theme->setting_file_url($statsimage, $statsimage);
            }

            $templatecontext['stats'][$j]['content'] = '';
            if (!empty($theme->settings->$statscontent)) {
                $templatecontext['stats'][$j]['content'] = theme_ergo_get_setting($statscontent, 'format_html');
            }

            if (!empty($theme->settings->$statsurl)) {
                $templatecontext['stats'][$j]['url'] = $theme->settings->$statsurl;
            }

            $j++;
        }

        $templatecontext['hasstats'] = $j;

        return $templatecontext;
    }

    /**
     * Get config theme menu items
     *
     * @return array
     */
    public function menu_items() {
        global $OUTPUT;

        $theme = theme_config::load('ergo');

        $templatecontext = [];
        $templatecontext['logos'] = array();

        $logoscount = 10;
        $j = 0;
        for ($i = 1; $i <= $logoscount; $i++) {
            $logosimage = 'logosimage' . $i;
            $logoscontent = 'logoscontent' . $i;
            $logosurl = 'logoslink' . $i;
            $logosorder = 'logosorder' . $i;

            if ((empty($theme->settings->$logosimage) && empty($theme->settings->$logoscontent)) ||
                    empty($theme->settings->$logosorder)) {
                continue;
            }

            $templatecontext['logos'][$j]['order'] = $theme->setting_file_url($logosorder, $logosorder);

            if (!empty($theme->settings->$logosimage)) {
                $templatecontext['logos'][$j]['image'] = $theme->setting_file_url($logosimage, $logosimage);
            }

            $templatecontext['logos'][$j]['content'] = '';
            if (!empty($theme->settings->$logoscontent)) {
                $templatecontext['logos'][$j]['content'] = theme_ergo_get_setting($logoscontent, 'format_html');
            }

            if (!empty($theme->settings->$logosurl)) {
                $templatecontext['logos'][$j]['url'] = $theme->settings->$logosurl;
            }

            $j++;
        }

        $templatecontext['haslogos'] = $j;

        return $templatecontext;
    }

    /**
     * Get config theme social networks items
     *
     * @return array
     */
    public function socialnet_items() {
        global $OUTPUT;

        $theme = theme_config::load('ergo');

        $templatecontext = [];
        $templatecontext['socialnet'] = array();

        if (!empty($theme->settings->socialnet)) {
            $links = explode("\n", $theme->settings->socialnet);

            foreach($links as $link) {
                $slides = explode('|', $link);
                $uri = $slides[0];
                $icon = count($slides) > 1 ? $slides[1] : 'external-link-square';
                $title = count($slides) > 2 ? $slides[2] : '';
                $color = count($slides) > 3 ? 'style="color: ' . $slides[3] . ';"' : '';
                $templatecontext['socialnet'][] = sprintf('<a href="%s" target="_blank" title="%s" %s><i class="%s"></i></a>', $uri, $title, $color, $icon);
            }
        }

        return $templatecontext;
    }

    /**
     * Get config theme general vars
     *
     * @return array
     */
    public function generalvars() {
        global $CFG, $OUTPUT, $SITE;

        $theme = theme_config::load('ergo');

        $logo = $OUTPUT->get_logo_url(0, 0);

        $templatecontext = [];
        $templatecontext['wwwroot'] = $CFG->wwwroot;
        $templatecontext['wwwcurrenttheme'] = $CFG->wwwroot . '/theme/ergo';
        $templatecontext['googlefont'] = $theme->settings->googlefont;
        $templatecontext['logourl'] = $logo;
        $templatecontext['sitename'] = $SITE->fullname;
        $templatecontext['output'] = $OUTPUT;


        return $templatecontext;
    }

}
