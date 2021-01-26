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
 * Version details
 *
 * @package    filter_rating
 * @copyright  2021 bdecent gmbh <https://bdecent.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace filter_rating\local;

use coding_exception;
use context;
use core\invalid_persistent_exception;
use core\persistent;
use dml_exception;
use external_description;
use external_single_structure;
use external_value;
use lang_string;

defined('MOODLE_INTERNAL') || die();

class rating extends persistent {

    /** @var string Table name */
    const TABLE = 'filter_rating';

    /**
     * Return the definition of the properties of this model.
     *
     * @return array
     */
    protected static function define_properties() {
        return [
            'userid' => [
                'type' => PARAM_INT,
            ],
            'contextid' => [
                'type' => PARAM_INT,
            ],
            'rating' => [
                'type' => PARAM_INT,
            ]
        ];
    }

    /**
     * Validate the rating value.
     *
     * @param int $value
     * @return true|lang_string
     */
    protected function validate_rating($value) {
        if ($value > 100 || $value < 0) {
            return new lang_string('invalidrating', 'filter_rating');
        }

        return true;
    }

    /**
     * Get number of stars to display.
     *
     * @return int
     * @throws dml_exception
     */
    public static function get_number_of_stars(): int {
        return get_config('filter_rating', 'number_of_stars') ?: 5;
    }

    /**
     * Get scale for ratings.
     *
     * @return int
     * @throws dml_exception
     */
    public static function get_scale(): float {
        $maxnumberofstars = self::get_number_of_stars();

        return $maxnumberofstars / 100;
    }

    /**
     * Get aggregate ratings for a context.
     *
     * @param context $context
     * @param string $aggregate
     * @return int
     * @throws dml_exception
     */
    protected static function get_aggregate_rating(context $context, $aggregate = 'AVG'): int {
        global $DB;

        return (int)$DB->get_field_sql("SELECT $aggregate(rating) FROM {filter_rating} WHERE contextid = :contextid", [
            'contextid' => $context->id
        ]);
    }

    /**
     * Get average rating for a context.
     *
     * @param context $context
     * @return int
     * @throws dml_exception
     */
    public static function get_average_rating(context $context): int {
        return (int)self::get_aggregate_rating($context, 'AVG');
    }

    /**
     * Get number of ratings for a context.
     *
     * @param context $context
     * @return int
     * @throws dml_exception
     */
    public static function get_rating_count(context $context): int {
        return (int)self::get_aggregate_rating($context, 'COUNT');
    }

    /**
     * Create a new rating.
     *
     * @param int $userid
     * @param int $contextid
     * @param int $rating
     * @return rating
     * @throws invalid_persistent_exception|coding_exception
     */
    public static function create_rating(int $userid, context $context, int $rating): rating {
        return (new self(0, (object)[
            'userid' => $userid,
            'contextid' => $context->id,
            'rating' => $rating
        ]))->create();
    }

    /**
     * Get return structure for web services.
     *
     * @return external_description
     */
    public static function get_external_description(): external_description {
        return new external_single_structure([
            'userid' => new external_value(PARAM_INT, 'User who submitted rating'),
            'contextid' => new external_value(PARAM_INT, 'Context for rating'),
            'rating' => new external_value(PARAM_INT, 'Raw rating value'),
            'usermodified' => new external_value(PARAM_INT),
            'timecreated' => new external_value(PARAM_INT),
            'timemodified' => new external_value(PARAM_INT),
        ]);
    }
}