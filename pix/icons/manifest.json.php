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

?>{
 "short_name": "<?php echo $SITE->shortname; ?>",
 "name": "<?php echo $SITE->fullname; ?>",
 "display": "standalone",
 "theme_color": "#afcc91",
 "background_color": "#77a182",
 "icons": [
  {
   "src": "<?php echo $path; ?>/android-icon-36x36.png",
   "sizes": "36x36",
   "type": "image/png",
   "density": "0.75"
  },
  {
   "src": "<?php echo $path; ?>/android-icon-48x48.png",
   "sizes": "48x48",
   "type": "image/png",
   "density": "1.0"
  },
  {
   "src": "<?php echo $path; ?>/android-icon-72x72.png",
   "sizes": "72x72",
   "type": "image/png",
   "density": "1.5"
  },
  {
   "src": "<?php echo $path; ?>/android-icon-96x96.png",
   "sizes": "96x96",
   "type": "image/png",
   "density": "2.0"
  },
  {
   "src": "<?php echo $path; ?>/android-icon-144x144.png",
   "sizes": "144x144",
   "type": "image/png",
   "density": "3.0"
  },
  {
   "src": "<?php echo $path; ?>/android-icon-192x192.png",
   "sizes": "192x192",
   "type": "image/png",
   "density": "4.0"
  }
 ],
  "start_url": "<?php echo $CFG->wwwroot; ?>"
}
