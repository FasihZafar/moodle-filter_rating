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
class star_rating implements templatable, renderable {

    /**
     * @var context
     */
    private $context;

    /**
     * @var bool
     */
    private $readonly;

    /**
     * @param context $context
     * @param bool $readonly Does not allow user ratings.
     */
    public function __construct(context $context, bool $readonly = false) {
        $this->context = $context;
        $this->readonly = $readonly;
    }

    public function export_for_template(renderer_base $output) {
        global $USER;

        $data = [
            'contextid' => $this->context->id,
            'readonly' => $this->readonly,
            'uniqueid' => uniqid(),
            'options' => json_encode([
                'numberofstars' => rating::get_number_of_stars(),
                'scale' => rating::get_scale()
            ])
        ];

        if (!$this->readonly && $rating = rating::get_record(['userid' => $USER->id, 'contextid' => $this->context->id])) {
            $starrating = $rating->get('rating') * rating::get_scale();
            $data['myrating'] = true;
        } else {
            $starrating = rating::get_average_rating($this->context) * rating::get_scale();
        }

        $data['stars'] = [];
        for ($i = 1; $i <= rating::get_number_of_stars(); $i++) {
            $data['stars'][] = [
                'fullstar' => $i <= $starrating,
                'halfstar' => $i > $starrating && $i < ($starrating + 1),
                'rating' => $i / rating::get_scale()
            ];
        }

        return $data;
    }
}