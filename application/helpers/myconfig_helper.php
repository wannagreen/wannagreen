<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function cfg_image_thumb($img_name) {
	return array(
		'source_image' => 'uploads/images/'.$img_name,
		'create_thumb' => TRUE,
		'maintain_ratio' => TRUE,
		'width' => 50,
		'height' => 50
	);
}

function cfg_image_regular($img_name) {
	return array(
		'source_image' => 'uploads/images/'.$img_name,
		'create_thumb' => FALSE,
		'maintain_ratio' => TRUE,
		'width' => 150,
		'height' => 150
	);
}
