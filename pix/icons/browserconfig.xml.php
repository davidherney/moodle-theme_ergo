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
 * Ergo theme.
 *
 * @package    theme_ergo
 * @copyright  2020 David Herney
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once('../../../../config.php');

$path = $CFG->wwwroot . '/theme/ergo/pix/icons';

?><?xml version="1.0" encoding="utf-8"?>
<browserconfig><msapplication><tile><square70x70logo src="<?php echo $path; ?>/ms-icon-70x70.png"/><square150x150logo src="<?php echo $path; ?>/ms-icon-150x150.png"/><square310x310logo src="<?php echo $path; ?>/ms-icon-310x310.png"/><TileColor>#0d1b29</TileColor></tile></msapplication></browserconfig>
