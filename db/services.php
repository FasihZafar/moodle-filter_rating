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
 * External functions and service definitions.
 *
 * @package    filter_rating
 * @copyright  2021 bdecent gmbh <https://bdecent.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$functions = [
    'filter_rating_set_rating' => [
        'classpath'     => '',
        'classname'     => 'filter_rating\external',
        'methodname'    => 'set_rating',
        'description'   => 'Set rating.',
        'type'          => 'write',
        'ajax'          => true
    ],
    'filter_rating_get_average_rating' => [
        'classpath'     => '',
        'classname'     => 'filter_rating\external',
        'methodname'    => 'get_average_rating',
        'description'   => 'Get average rating.',
        'type'          => 'read',
        'ajax'          => true
    ]
];
