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
class block_selfenrol_seats_edit_form extends block_edit_form {

    protected function specific_definition($mform) {
        // Fields for editing HTML block title and contents.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block_selfenrol_seats'));

        $mform->addElement('text', 'config_title', get_string('blocktitle', 'block_selfenrol_seats'));
        $mform->setDefault('config_title', get_string('blocktitledefault', 'block_selfenrol_seats'));
        $mform->setType('config_title', PARAM_MULTILANG);

        global $COURSE, $DB;

        //content
        $mform->addElement('textarea', 'config_content', get_string('blockcontent', 'block_selfenrol_seats')
                , 'wrap="virtual" rows="3" cols="80"');
        $mform->setDefault('config_content', get_string('blockcontentdefault', 'block_selfenrol_seats'));
        $mform->setType('config_content', PARAM_CLEANHTML);

        //hideheader 
        $mform->addElement('advcheckbox', 'config_hide_block_header', get_string('hide_block_header', 'block_selfenrol_seats'), '', NULL, array(0, 1));

        //selfenrol_instance
        $sql = "SELECT e.id, e.name FROM `mdl_enrol` e
	WHERE e.courseid = {$COURSE->id} AND e.enrol = 'self' AND e.status = 0";
        $instances = $DB->get_records_sql($sql);

        $select_instance = array();

        if ($instances) {
            foreach ($instances as $instance) {
                $select_instance[$instance->id] = $instance->name;
            }
        }

        $mform->addElement('select', 'config_selfenrol_instance', get_string('selfenrol_instance', 'block_selfenrol_seats'), $select_instance);
    }

}
