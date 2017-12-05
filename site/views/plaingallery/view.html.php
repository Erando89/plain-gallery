<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');
jimport('joomla.filesystem.folder');

/**
 * HTML View class for the PlainGallery Component
 */
class PlainGalleryViewPlainGallery extends JViewLegacy
{
	// Overwriting JView display method
	function display($tpl = null)
	{
		// Assign data to the view
		$app = JFactory::getApplication();
		
		$active = $app->getMenu()->getActive();
		if (NULL == $active) {
			$active = $app->getMenu()->getDefault();
		}
		
		if ( isset($active->query['galleryFolder']) ) {
			$galleryFolder = $active->query['galleryFolder'];
		}
		
		$nameAsDescription = false;
		if ( isset($active->query['nameAsDescription']) ) {
			$nameAsDescription= $active->query['nameAsDescription'] == "1";
		}
		
		$includeCover = false;
		if ( isset($active->query['includeCover']) ) {
			$includeCover = $active->query['includeCover'] == "1";
		}
		
		// Retrieve files from folders
		$galleryFolderArray = $this->listSubDirs($galleryFolder);
		for ($i = 1; $i < count($galleryFolderArray); $i++) {
			// JURI::base( true ).'/images/slideshow/slide_1.jpg'
			$array = array();
			foreach (scandir($galleryFolderArray[$i]) as $file) {
				$path = $galleryFolderArray[$i]."/".$file;
				if ($this->is_image($path)) {
					$imageLink = JURI::base( true ).'/'.substr($path, strpos($path,"images"));
					array_push($array, $imageLink);
				}
			}
			$galleryFilesArray[$i] = $array;
		}
		
		foreach ($galleryFolderArray as &$entry) {
			$entry = substr($entry, strpos($entry,"images")); 
		}
		unset($entry);
		
		$this->galleryFolders = $galleryFolderArray;
		$this->galleryFiles = $galleryFilesArray;
		$this->includeCover = $includeCover;
		$this->nameAsDescription = $nameAsDescription;

		// Display the view
		parent::display($tpl);
	}
	
	function listSubDirs($directory = null)
	{
		if (strpos($directory, 'images') === false) {
			$directory = JPATH_ROOT . "/images/" . $directory;
		}
		
		$iter = new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS),
				RecursiveIteratorIterator::SELF_FIRST,
				RecursiveIteratorIterator::CATCH_GET_CHILD // Ignore "Permission denied"
				);
		
		$paths = array($directory);
		foreach ($iter as $path => $dir) {
			if ($dir->isDir()) {
				if (!substr($path, 0, strlen(JPATH_ROOT)) === JPATH_ROOT) {
					$path = JPATH_ROOT . "/images/" . $path;
				}			
				
				$paths[] = $path;
			}
		}
		
		return $paths;		
	}
	
	function is_image($path)
	{
		if (!is_dir($path) && !$this->endsWith($path, '.') && !$this->endsWith($path, '..')) {
			
			$a = getimagesize($path);
			$image_type = $a[2];
			
			if(in_array($image_type , array(IMAGETYPE_GIF , IMAGETYPE_JPEG ,IMAGETYPE_PNG , IMAGETYPE_BMP)))
			{
				return true;
			}			
		}
		return false;
	}
	
	function endsWith($haystack, $needle)
	{
		$length = strlen($needle);
		
		return $length === 0 ||
		(substr($haystack, -$length) === $needle);
	}
	
}