<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * pEEnterest Plugin
 * Copyright Rene Merino, <rmerino@amayamedia.com>
 */
$plugin_info = array(
	'pi_name'			=> 'pEEnterest',
	'pi_version'		=> '0.1',
	'pi_author'			=> 'Rene Merino',
	'pi_author_url'		=> 'http://www.amayamedia.com',
	'pi_description'	=> 'Generates pins from the given board id, token can be generated here https://developers.pinterest.com/docs/api/access_token/',
	'pi_usage'			=> Peenterest::usage()
);

/**
* The pEEnterest plugin will generate a list of pins from the given board ID
*
* It'll return most of the Pin object properties as variables
*
* @package pEEnterest
*/
class Peenterest
{

	public $return_data = '';
	public $api_url = 'https://api.pinterest.com/v1/boards/';
	private $EE;
	
	function __construct()
	{
		// Get global object
		$this->EE =& get_instance();

		$tagdata = $this->EE->TMPL->tagdata;

		// Check to see if it's a one-off tag or a pair
		$mode = ($tagdata) ? "pair" : "single";

		$plugin_vars = array(
			"id"			=> "pin_id",
			"url"			=> "pin_url",
			"link"			=> "pin_link",
			"creator"		=> "pin_creator",
			"board"			=> "pin_board",
			"created"		=> "pin_created",
			"note"			=> "pin_note",
			"color"			=> "pin_color",
			"counts"		=> "pin_counts",
			"media"			=> "pin_media",
			"attribution"	=> "pin_attribution",
			"image"			=> "pin_image",
			"metadata"		=> "pin_metadata"
		);

		$pins_data = array();

		foreach ($plugin_vars as $var) {
			$pins_data[$var] = false;
		}

		/**
		 * Deal with the parameters
		 */
		$access_token 	= ($this->EE->TMPL->fetch_param('token')) ? $this->EE->TMPL->fetch_param('token') : '';
		$board_id		= ($this->EE->TMPL->fetch_param('board_id')) ? $this->EE->TMPL->fetch_param('board_id') : '';
		$images			= ($this->EE->TMPL->fetch_param('images')) ? $this->EE->TMPL->fetch_param('images') : 'no';
		$creator		= ($this->EE->TMPL->fetch_param('creator')) ? $this->EE->TMPL->fetch_param('creator') : 'no';
		$counts			= ($this->EE->TMPL->fetch_param('counts')) ? $this->EE->TMPL->fetch_param('counts') : 'no';
		$media			= ($this->EE->TMPL->fetch_param('media')) ? $this->EE->TMPL->fetch_param('media') : 'no';

		/**
		 * Lets start building the url query!
		 */
		$urlData = array(
			'access_token'	=> $access_token,
			'fields'		=> 'id,url,link,board(id,name,url),created_at,note,color,attribution,metadata'
		);

		/**
		 * Do we need to get more fields?
		 */
		$moreFields = false;
		if ($images == 'yes' || $creator == 'yes' || $counts == 'yes' || $media == 'yes') {
			$moreFields = true;
		}

		if ($moreFields) {
			if ($images == 'yes') {
				$urlData['fields'] .= ',image[original,small,medium,large]';
			}
			if ($creator == 'yes') {
				$urlData['fields'] .= ',creator(id,first_name,last_name,url)';
			}
			if ($counts == 'yes') {
				$urlData['fields'] .= ',counts';
			}
			if ($media == 'yes') {
				$urlData['fields'] .= ',media';
			}
		}

		// Builds url query
		$urlQuery = http_build_query($urlData);

		$url = $this->api_url . $board_id . '/pins/?' . $urlQuery;

		$response = json_decode(file_get_contents($url), true);
		$output_array = array();
		
		// Prepare data
		foreach ($response['data'] as $index => $pins) {
			$output_array[$index] = array();
			foreach ($pins as $key => $value) {
				if (is_array($value)) {
					if ($key == 'image') {
						$images_array = array();
						foreach ($value as $size => $image) {
							$images_array[$size][] = $image;
						}
						$output_array[$index][$key][] = $images_array;
					} else {
						$output_array[$index][$key] = array($value);
					}
				} else {
					$output_array[$index][$key] = $value;
				}
			}
		}

		// Parse the variables
		$this->return_data = $this->EE->TMPL->parse_variables($tagdata, $output_array);

		// Return the parse data
		$this->return_data = $this->return_data;

		return;
	}

	public static function usage()
	{
		ob_start();
?>
pEEnterest is a plugin that will generate a list of pins from the given board id.

Token can be generated here
https://developers.pinterest.com/docs/api/access_token/

{exp:peenterest token="ACCESS_TOKEN" board_id="BOARD_ID" images="yes|no" creator="yes|no" counts="yes|no" media="yes|no"}
	{id}
	{url}
	{link}
	{created_at}
	{note}
	{color}
	{creator}
		{id}
		{first_name}
		{last_name}
		{url}
	{/creator}
	{media}
		{type}
	{/media}
	{board}
		{id}
		{url}
		{name}
	{/board}
	{counts}
		{likes}
		{comments}
		{repins}
	{/counts}
	{image}
		{original} // or small|medium|large
			{url}
			{width}
			{height}
		{/original}
	{/image}
{/exp:peenterest}
<?php
		$buffer = ob_get_contents();

		ob_end_clean();

		return $buffer;
	}
}