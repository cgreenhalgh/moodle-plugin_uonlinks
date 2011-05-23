<?php

/**
 * 
 * Simple block that could hold local links specific to a module.
 * This version assumes that the first 'word' in the course short name is the Module code.
 * 
 * @author Chris Greenhalgh, cmg@cs.nott.ac.uk
 *
 */
class block_uonlinks extends block_list {
	function init() {
		$this->title = get_string('pluginname', 'block_uonlinks');
	}
	/** 
	 * only visible on course pages
	 */
	function applicable_formats() {
    	return array('course-view' => true);
	}
	
	/** 
	 * allow editing
	 */
//	function instance_allow_config() {
//		return true;
//	}
	/** 
	 * Content is a list of links...
	 */
	function get_content() {
		global $CFG, $COURSE, $OUTPUT;
		if ($this->content !== null) {
			return $this->content;
		}

		// the empty content
		$this->content = new stdClass;
		$this->content->items = array();
		$this->content->icons = array();
		$this->content->footer = '';

		// find module code heuristic
		$modulecode = $COURSE->shortname; // idnumber??
		if (!isset($modulecode)) {
			$this->get_content_error('Course short name not set');
			return;
		}
	    if (!preg_match("([A-Za-z0-9]*)", $modulecode, $match)) {
			$this->get_content_error('Course short name not a module code');
			return;
	   	}
		$modulecode = $match[0];
				
		// add links...
		// library reading list
		$this->add_content_item('<a href="'.$this->get_reading_list_url($modulecode).'">Search for reading list</a>');
		// portal past papers with SSO?
		// timetable for module?
		// module specification?? (saturn doesn't keep historical specifications correctly!)
		// any more??
	}
	/**
	 * add an item to content 
	 */
	function add_content_item($html) {
		global $OUTPUT;
		
		$defaulticon = '<img src="'.$OUTPUT->pix_url('i/item').'" class="icon" alt=""/>';
		$this->content->icons[] = $defaulticon;
		$this->content->items[] = $html;
	}
	/**
	 * an error...
	 */
	function get_content_error($err) {
		add_content_item('Error: '.$err);
	}
	/**
	 * guess readinglist url 
	 */
	function get_reading_list_url($modulecode) {
		return 'http://www.nottingham.ac.uk/is/gateway/readinglists/local/displaylist?module='.$modulecode;
	}
}