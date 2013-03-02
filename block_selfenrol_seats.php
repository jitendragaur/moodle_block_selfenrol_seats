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
 * Selfenrol plugin seats display  block.
 *
 * @package    block
 * @subpackage selfenrol_seats
 * @copyright  2012 Jitendra Gaur (jitendra.gaur@me.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

class block_selfenrol_seats extends block_base {

    public function init() {
        $this->title = get_string('pluginname', 'block_selfenrol_seats');
    }

    public function instance_allow_multiple() {
        return true;
    }

    public function has_config() {
        return true;
    }

    function instance_allow_config() {
        return true;
    }

    function hide_header() {
        if (empty($this->config->hide_block_header)) {
            return false;
        } else {
            return $this->config->hide_block_header;
        }
    }

    public function applicable_formats() {
        return array(
            'site-index' => false,
            'course-view' => true,
            'course-view-social' => false,
            'mod' => false,
            'mod-quiz' => false
        );
    }

    function specialization() {

        // load userdefined title and make sure it's never empty
        if (empty($this->config->title)) {
            $this->title = get_string('pluginname', 'block_selfenrol_seats');
        } else {
            $this->title = $this->config->title;
        }
    }

    public function get_content() {
        global $DB;

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->text = '';
        $this->content->footer = '';

        $course = $this->page->course;
        //get one enable selferol instance from the course
        if ($result = $DB->get_record('enrol', array('courseid' => $course->id,
            'enrol' => 'self', 'status' => 0, 'id' => $this->config->selfenrol_instance))) {

            $total_seats = ($result->customint3 > 0) ? (int) $result->customint3 : get_string('unlimited', 'block_selfenrol_seats');
            $total_enrolled_users = $DB->count_records('user_enrolments', array('enrolid' => $result->id));
            $remaining_seats = $total_seats - $total_enrolled_users;

            //replace coursename
            $content = preg_replace('/\[\[coursename\]\]/', format_string($course->fullname), $this->config->content);
            //replace totalseats
            $content = preg_replace('/\[\[total_seats\]\]/', $total_seats, $content);

            //replace total_users
            //replace remaining_seats            
            if (gettype($total_seats) == 'string') {
                $content = preg_replace('/\[\[remaining_seats\]\]/', $total_seats, $content);
            } else {
                $content = preg_replace('/\[\[remaining_seats\]\]/', $remaining_seats, $content);
            }
        } else {
            $content = get_string('enableselfenroll', 'block_selfenrol_seats');
        }
        $this->content->text = $content;


        return $this->content;
    }

}