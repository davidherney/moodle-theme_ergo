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
 * Frontpage layout.
 *
 * @package    theme_ergo
 * @copyright  2020 David Herney
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

user_preference_allow_ajax_update('drawer-open-nav', PARAM_ALPHA);
user_preference_allow_ajax_update('sidepre-open', PARAM_ALPHA);

require_once($CFG->libdir . '/behat/lib.php');

$extraclasses = [];

$themesettings = new \theme_ergo\util\theme_settings();

$blocksposthtml = $OUTPUT->blocks('side-post');
$haspostblocks = strpos($blocksposthtml, 'data-block=') !== false;

$blockscontenthtml = $OUTPUT->blocks('side-cont');
$hascontentblocks = strpos($blockscontenthtml, 'data-block=') !== false;

if (isloggedin() && !isguestuser()) {
    $blockshtml = $OUTPUT->blocks('side-pre');
    $hasblocks = strpos($blockshtml, 'data-block=') !== false;

    $navdraweropen = (get_user_preferences('drawer-open-nav', 'true') == 'true');
    $draweropenright = (get_user_preferences('sidepre-open', 'true') == 'true');

    if ($navdraweropen) {
        $extraclasses[] = 'drawer-open-left';
    }

    if ($draweropenright && $hasblocks) {
        $extraclasses[] = 'drawer-open-right';
    }

    $bodyattributes = $OUTPUT->body_attributes($extraclasses);
    $regionmainsettingsmenu = $OUTPUT->region_main_settings_menu();
    $templatecontext = [
        'sidepreblocks' => $blockshtml,
        'hasblocks' => $hasblocks,
        'bodyattributes' => $bodyattributes,
        'navdraweropen' => $navdraweropen,
        'draweropenright' => $draweropenright,
        'regionmainsettingsmenu' => $regionmainsettingsmenu,
        'hasregionmainsettingsmenu' => !empty($regionmainsettingsmenu),
        'hasdrawertoggle' => true,
        'sidepostblocks' => $blocksposthtml,
        'haspostblocks' => $haspostblocks,
        'contentblocks' => $blockscontenthtml,
        'hascontentblocks' => $hascontentblocks,
    ];

    // Improve boost navigation.
    theme_ergo_boostnavigation_extend_navigation($PAGE->navigation);

    $templatecontext['flatnavigation'] = $PAGE->flatnav;

    $templatecontext = array_merge($templatecontext, $themesettings->footer_items(), $themesettings->news_items(),
        $themesettings->slideshow(), $themesettings->socialnet_items(), $themesettings->logos_items(),
        $themesettings->stats_items(), $themesettings->generalvars());

    $OUTPUT->doctype(); // Call to fix Doctype loading error in some pages with columns2 layout.
    echo $OUTPUT->render_from_template('theme_ergo/frontpage', $templatecontext);
} else {
    $extraclasses[] = 'slideshow';
    $extraclasses[] = 'notloggedin';

    $bodyattributes = $OUTPUT->body_attributes($extraclasses);
    $regionmainsettingsmenu = $OUTPUT->region_main_settings_menu();

    $templatecontext = [
        'bodyattributes' => $bodyattributes,
        'cansignup' => $CFG->registerauth == 'email' || !empty($CFG->registerauth),
        'sidepostblocks' => $blocksposthtml,
        'haspostblocks' => $haspostblocks,
        'contentblocks' => $blockscontenthtml,
        'hascontentblocks' => $hascontentblocks,
    ];

    $templatecontext = array_merge($templatecontext, $themesettings->footer_items(), $themesettings->news_items(),
        $themesettings->slideshow(), $themesettings->socialnet_items(), $themesettings->logos_items(),
        $themesettings->stats_items(), $themesettings->generalvars());

    $OUTPUT->doctype(); // Call to fix Doctype loading error in some pages with columns2 layout.
    echo $OUTPUT->render_from_template('theme_ergo/frontpage_guest', $templatecontext);
}
