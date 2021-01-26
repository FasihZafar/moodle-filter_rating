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

use context;
use filter_rating\local\rating;
use renderable;
use renderer_base;
use templatable;

defined('MOODLE_INTERNAL') || die();

/**
 * Class renderer.
 *
 * @package filter_rating
 */
class current_rating implements templatable, renderable {

    /**
     * @var context
     */
    private $context;

    /**
     * @param context $context
     */
    public function __construct(context $context) {
        $this->context = $context;
    }

    public function export_for_template(renderer_base $output) {
        return [
            'currentrating' => round(rating::get_average_rating($this->context) * rating::get_scale(), 1)
        ];
    }
}