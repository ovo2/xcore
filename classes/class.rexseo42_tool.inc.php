<?php

class rexseo42_tool {
	var $title;
	var $description;
	var $link;

	function __construct($title, $description, $link) {
		$this->title = $title;
		$this->description = $description;
		$this->link = $link;
	} 

	function getTitle() {
		return $this->title;
	} 

	function getDescription() {
		return $this->description;
	} 

	function getLink() {
		return $this->link;
	} 
}
