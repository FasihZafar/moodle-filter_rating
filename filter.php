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
 * Star Rating filter.
 *
 * @package    filter_rating
 * @copyright  2021 bdecent gmbh <https://bdecent.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/**
 * Allows embedding of Star Rating.
 *
 * @package    filter_rating
 * @copyright  2021 bdecent gmbh <https://bdecent.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class filter_rating extends moodle_text_filter {

    /**
     * Function filter replaces shortcodes with Star Rating embeds.
     *
     * @param  string $text    HTML content to process
     * @param  array  $options options passed to the filters
     * @return string
     */
    public function filter($text, array $options = array()) {
        global $PAGE, $USER, $OUTPUT;

        if (!is_string($text) or empty($text)) {
            // Non string data can not be filtered anyway.
            return $text;
        }

        $renderer = $PAGE->get_renderer('filter_rating');

        $text = str_replace('[rating]', $renderer->render(new \filter_rating\output\star_rating($this->context)), $text);
        $text = str_replace('[getrating]', $renderer->render(new \filter_rating\output\star_rating($this->context, true)), $text);
        $text = str_replace('[currentrating]', $renderer->render(new \filter_rating\output\current_rating($this->context)), $text);

        return $text;
    }
}
