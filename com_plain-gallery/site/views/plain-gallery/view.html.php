<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * HTML View class for the PlainGallery Component
 *
 * @since  0.0.1
 */
class PlainGalleryViewPlainGallery extends JViewLegacy
{
	/**
	 * Display the Hello World view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 */
	function display($tpl = null)
	{
		// Assign data to the view
		$this->msg = 'Plain Gallery!';

		// Display the view
		parent::display($tpl);
	}
}