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

use filter_rating\local\rating;

defined('MOODLE_INTERNAL') || die();

/**
 * @group filter_rating
 */
class filter_rating_rating_test extends advanced_testcase {

    public function test_get_aggregate_rating() {
        $this->resetAfterTest();

        $user1 = $this->getDataGenerator()->create_user();
        $user2 = $this->getDataGenerator()->create_user();
        $user3 = $this->getDataGenerator()->create_user();
        $course = $this->getDataGenerator()->create_course();

        $context = context_course::instance($course->id);

        rating::create_rating($user1->id, $context, 25);
        rating::create_rating($user2->id, $context, 25);
        rating::create_rating($user3->id, $context, 50);

        $this->assertEquals(3, rating::get_rating_count($context, 'COUNT'));
        $this->assertEquals(33, rating::get_average_rating($context));
    }
}