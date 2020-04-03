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
 * Theme functions.
 *
 * @package    theme_ergo
 * @copyright  2020 David Herney
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Inject additional SCSS.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_ergo_get_extra_scss($theme) {
    $scss = $theme->settings->scss;

    $scss .= theme_ergo_set_bgsimg($theme);

    return $scss;
}

/**
 * Adds the page background image to CSS.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_ergo_set_bgsimg($theme) {
    global $OUTPUT;

    $bgimg = $theme->setting_file_url('pagebgimg', 'pagebgimg');
    $bgimg2 = $theme->setting_file_url('secondbgimg', 'secondbgimg');

    $headercss = ':root {';
    if (!is_null($bgimg)) {
        $headercss .= " --first-bg-url: url('$bgimg');";
    }

    if (!is_null($bgimg2)) {
        $headercss .= " --second-bg-url: url('$bgimg2');";
    }

    $headercss .= '}';

    return $headercss;
}

/**
 * Returns the main SCSS content.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_ergo_get_main_scss_content($theme) {
    global $CFG;

    $scss = file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/default.scss');

    // Ergo scss.
    $ergovariables = file_get_contents($CFG->dirroot . '/theme/ergo/scss/ergo/_variables.scss');
    $ergo = file_get_contents($CFG->dirroot . '/theme/ergo/scss/ergo.scss');

    // Combine them together.
    return $ergovariables . "\n" . $scss . "\n" . $ergo;
}

/**
 * Get SCSS to prepend.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_ergo_get_pre_scss($theme) {
    $scss = '';
    $configurable = [
        // Config key => [variableName, ...].
        'brandcolor' => ['brand-primary'],
        'navbarheadercolor' => 'navbar-header-color'
    ];

    // Prepend variables first.
    foreach ($configurable as $configkey => $targets) {
        $value = isset($theme->settings->{$configkey}) ? $theme->settings->{$configkey} : null;
        if (empty($value)) {
            continue;
        }
        array_map(function($target) use (&$scss, $value) {
            $scss .= '$' . $target . ': ' . $value . ";\n";
        }, (array) $targets);
    }

    // Prepend pre-scss.
    if (!empty($theme->settings->scsspre)) {
        $scss .= $theme->settings->scsspre;
    }

    return $scss;
}

/**
 * Serves any files associated with the theme settings.
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param context $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @param array $options
 * @return mixed
 */
function theme_ergo_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    $theme = theme_config::load('ergo');

    if ($context->contextlevel == CONTEXT_SYSTEM and $filearea === 'favicon') {
        return $theme->setting_file_serve('favicon', $args, $forcedownload, $options);
    } else if ($context->contextlevel == CONTEXT_SYSTEM and $filearea === 'pagebgimg') {
        return $theme->setting_file_serve('pagebgimg', $args, $forcedownload, $options);
    } else if ($context->contextlevel == CONTEXT_SYSTEM and $filearea === 'secondbgimg') {
        return $theme->setting_file_serve('secondbgimg', $args, $forcedownload, $options);
    } else if ($context->contextlevel == CONTEXT_SYSTEM and preg_match("/^sliderimage[1-9][0-9]?$/", $filearea) !== false) {
        return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
    } else if ($context->contextlevel == CONTEXT_SYSTEM and preg_match("/^newsimage[1-9][0-9]?$/", $filearea) !== false) {
        return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
    } else {
        send_file_not_found();
    }
}

/**
 * Get theme setting
 *
 * @param string $setting
 * @param bool $format
 * @return string
 */
function theme_ergo_get_setting($setting, $format = false) {
    $theme = theme_config::load('ergo');

    if (empty($theme->settings->$setting)) {
        return false;
    } else if (!$format) {
        return $theme->settings->$setting;
    } else if ($format === 'format_text') {
        return format_text($theme->settings->$setting, FORMAT_PLAIN);
    } else if ($format === 'format_html') {
        return format_text($theme->settings->$setting, FORMAT_HTML, array('trusted' => true, 'noclean' => true));
    } else {
        return format_string($theme->settings->$setting);
    }
}

/**
 * Fumble with Moodle's global navigation by leveraging Moodle's *_extend_navigation() hook.
 *
 * @param global_navigation $navigation
 */
function theme_ergo_boostnavigation_extend_navigation(global_navigation $navigation) {
    global $CFG;

    // Check if admin wanted us to remove the mycourses node from Boost's nav drawer.
    if ($mycoursesnode = $navigation->find('mycourses', global_navigation::TYPE_ROOTNODE)) {

        // Hide all courses below the mycourses node.
        $mycourseschildrennodeskeys = $mycoursesnode->get_children_key_list();
        foreach ($mycourseschildrennodeskeys as $k) {
            // If the admin decided to display categories, things get slightly complicated.
            if ($CFG->navshowmycoursecategories) {
                // We need to find all children nodes first.
                $allchildrennodes = theme_ergo_boostnavigation_get_all_childrenkeys($mycoursesnode->get($k));
                // Then we can hide each children node.
                // Unfortunately, the children nodes have navigation_node type TYPE_MY_CATEGORY or navigation_node type
                // TYPE_COURSE, thus we need to search without a specific navigation_node type.
                foreach ($allchildrennodes as $cn) {
                    $mycoursesnode->find($cn, null)->showinflatnavigation = false;
                }
            } else {
                // Otherwise we have a flat navigation tree and hiding the courses is easy.
                $mycoursesnode->get($k)->showinflatnavigation = false;
            }
        }
    }
}

/**
 * Moodle core does not have a built-in functionality to get all keys of all children of a navigation node,
 * so we need to get these ourselves.
 *
 * @param navigation_node $navigationnode
 * @return array
 */
function theme_ergo_boostnavigation_get_all_childrenkeys(navigation_node $navigationnode) {
    // Empty array to hold all children.
    $allchildren = array();

    // No, this node does not have children anymore.
    if (count($navigationnode->children) == 0) {
        return array();
    }

    // Get own children keys.
    $childrennodeskeys = $navigationnode->get_children_key_list();
    // Get all children keys of our children recursively.
    foreach ($childrennodeskeys as $ck) {
        $allchildren = array_merge($allchildren, theme_ergo_boostnavigation_get_all_childrenkeys($navigationnode->get($ck)));
    }

    // And add our own children keys to the result.
    $allchildren = array_merge($allchildren, $childrennodeskeys);

    // Return everything.
    return $allchildren;
}

