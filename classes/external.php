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
 * Web service functions for Star Rating.
 *
 * @package    filter_rating
 * @copyright  2021 bdecent gmbh <https://bdecent.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace filter_rating;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');

use coding_exception;
use context;
use core\invalid_persistent_exception;
use external_api;
use external_description;
use external_function_parameters;
use external_value;
use external_single_structure;
use filter_rating\local\rating;
use invalid_parameter_exception;

/**
 * Web service functions for Star Rating.
 *
 * @package    filter_rating
 * @copyright  2021 bdecent gmbh <https://bdecent.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class external extends external_api {

    /**
     * set_rating_parameters parameters.
     *
     * @return external_function_parameters
     */
    public static function set_rating_parameters() {
        return new external_function_parameters([
            'contextid' => new external_value(PARAM_INT, 'Context for rating'),
            'rating' => new external_value(PARAM_INT, 'Rating value 0 - 100'),
            'userid' => new external_value(PARAM_INT, 'User submitting rating', VALUE_DEFAULT)
        ]);
    }

    /**
     * Create a new rating.
     *
     * @param int $contextid
     * @param int $rating
     * @param int|null $userid
     * @return array
     * @throws coding_exception
     * @throws invalid_persistent_exception
     * @throws invalid_parameter_exception
     */
    public static function set_rating(int $contextid, int $rating, int $userid = null) {
        global $USER;

        $params = self::validate_parameters(self::set_rating_parameters(), [
            'contextid' => $contextid,
            'rating' => $rating,
            'userid' => $userid
        ]);

        require_login();

        if (!$params['userid']) {
            $params['userid'] = $USER->id;
        }

        $contextid = context::instance_by_id($params['contextid']);

        if ($rating = rating::get_record(['contextid' => $params['contextid'], 'userid' => $params['userid']])) {
            $rating->set('rating', $params['rating']);
            $rating->update();
        } else {
            $rating = rating::create_rating($params['userid'], $contextid, $params['rating']);
        }

        return [
            'rating' => $rating->to_record()
        ];
    }

    /**
     * set_rating_parameters returns.
     *
     * @return external_description
     */
    public static function set_rating_returns() {
        return new external_single_structure([
            'rating' => rating::get_external_description()
        ]);
    }

    /**
     * get_average_rating parameters.
     *
     * @return external_function_parameters
     */
    public static function get_average_rating_parameters() {
        return new external_function_parameters([
            'contextid' => new external_value(PARAM_INT, 'Context for rating'),
        ]);
    }

    /**
     * Get average rating.
     *
     * @param int $contextid
     * @param int $rating
     * @param int|null $userid
     * @return int
     * @throws coding_exception
     * @throws invalid_persistent_exception
     * @throws invalid_parameter_exception
     */
    public static function get_average_rating(int $contextid) {
        $params = self::validate_parameters(self::get_average_rating_parameters(), [
            'contextid' => $contextid
        ]);

        require_login();

        return rating::get_average_rating(context::instance_by_id($params['contextid']));
    }

    /**
     * get_average_rating returns.
     *
     * @return external_description
     */
    public static function get_average_rating_returns() {
        return new external_value(PARAM_INT);
    }
}
