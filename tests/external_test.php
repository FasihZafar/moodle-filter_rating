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
 * Test web services.
 *
 * @package    filter_rating
 * @copyright  2021 bdecent gmbh <https://bdecent.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;

require_once($CFG->libdir . '/externallib.php');
require_once($CFG->dirroot . '/webservice/tests/helpers.php');

use core_h5p\external;
use core_h5p\file_storage;
use core_h5p\local\library\autoloader;

/**
 * Test web services.
 *
 * @group filter_rating
 * @package    filter_rating
 * @copyright  2021 bdecent gmbh <https://bdecent.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class filter_rating_external_testcase extends externallib_advanced_testcase {

    protected function setUp(): void {
        parent::setUp();
        autoloader::register();
    }

    /**
     * Test web services.
     */
    public function test_create_rating() {
        $this->resetAfterTest();

        $course = $this->getDataGenerator()->create_course();
        $user = $this->getDataGenerator()->create_user();

        $rating = \filter_rating\external::create_rating(context_course::instance($course->id)->id, 50, $user->id);

        $this->assertEquals(50, $rating['rating']->rating);
        $this->assertEquals($user->id, $rating['rating']->userid);
        $this->assertEquals(context_course::instance($course->id)->id, $rating['rating']->contextid);
    }
}
