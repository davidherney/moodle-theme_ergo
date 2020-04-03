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
 * A one column layout.
 *
 * @package    theme_ergo
 * @copyright  2020 David Herney
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$bodyattributes = $OUTPUT->body_attributes([]);

$templatecontext = [
    'bodyattributes' => $bodyattributes
];

$themesettings = new \theme_ergo\util\theme_settings();
$templatecontext = array_merge($templatecontext, $themesettings->generalvars());

$OUTPUT->doctype(); // Call to fix Doctype loading error in some pages with columns2 layout.
echo $OUTPUT->render_from_template('theme_ergo/columns1', $templatecontext);

