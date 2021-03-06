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
 * Class renderer.
 *
 * @package    filter_rating
 * @copyright  2021 bdecent gmbh <https://bdecent.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace filter_rating\output;

defined('MOODLE_INTERNAL') || die();

/**
 * Class renderer.
 *
 * @package filter_rating
 */
class renderer extends \plugin_renderer_base {

    /**
     * Render rating widgets. Backwards compatible for Totara 12.12.
     *
     * @param \renderable $widget
     * @return bool|string
     * @throws \moodle_exception
     */
    public function render($widget) {
        $widgetclass = explode('\\', get_class($widget));
        $widgetclass = array_pop($widgetclass);
        return $this->render_from_template('filter_rating/' . $widgetclass, $widget->export_for_template($this));
    }
}
