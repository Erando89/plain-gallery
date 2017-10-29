<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

$document = JFactory::getDocument();
$document->addStyleSheet('components/com_plaingallery/views/css/plaingallery.css');
$document->addCustomTag('<script type="text/javascript" src="components/com_plaingallery/views/js/plaingallery.js"></script>');

// Lightcase
$document->addStyleSheet('components/com_plaingallery/views/vendor/lightcase-2.4.0/css/lightcase.css');
$document->addCustomTag('<script type="text/javascript" src="components/com_plaingallery/views/vendor/lightcase-2.4.0/js/lightcase.js"></script>');
?>

<div class="albums">
<button type="button" class="btn btn-default">Zur&uuml;ck</button>
<?php 

// display albums
$titleImages = array();
for ($i = 1; $i < count($this->galleryFolders); $i++) {
	// skip folder if empty
	if (!empty($this->galleryFiles[$i])) {
		// find title image
		$titleImage = NULL;
		foreach($this->galleryFiles[$i] as $imageFromThisFolder):
			if (strpos($imageFromThisFolder, 'cover') !== false) {
				$titleImage = $imageFromThisFolder;
				break;
			}
		endforeach;
		$titleImage = $titleImage == NULL ? $this->galleryFiles[$i][0] : $titleImage;
		$titleImages[$i] = $titleImage;
		
		// find title text
		$split = preg_split("/(\\\|\/)/", $this->galleryFolders[$i]);
		$titleText = $split[count($split)-1];
		
		if ($titleImage== NULL || $titleText == NULL ) {
			continue;
		}
		?>
		<div class="album" data-gallery-id="<?php echo $i?>">
		  <img src="<?php echo $titleImage?>" alt="<?php echo $titleText?>" class="album-image">
		  <div class="overlay">
		    <div class="text"><?php echo $titleText?></div>
		  </div>
		</div>
		<?php 
	}
}
?>
</div>

<div class="gal-container">
<?php
$folderIndex = 1;
$activeGroupIndex = 0;
$activeGroup = NULL;
$oldGroup = NULL; // used to avoid having the same kind of row twice
$bigCol = 'col-md-8 col-sm-12 co-xs-12 gal-item';
$smallCol = 'col-md-4 col-sm-6 co-xs-12 gal-item';

$groupOfTwo1 = array($smallCol, $bigCol);
$groupOfTwo2 = array($bigCol, $smallCol);
$groupOfThree = array($smallCol, $smallCol, $smallCol);

foreach($this->galleryFiles as $galleryFolder):
// skip folder if empty
if (!empty($galleryFolder)) {
?>
<div id="gallery-<?php echo $folderIndex?>" class="gallery">
<?php
foreach($galleryFolder as $galleryFile):

if ($galleryFile == $titleImages[$folderIndex] && !$this->includeCover) {
	// skip cover file if used has chosen that option
	continue;
}

if ($activeGroup == NULL) {
	do {
		switch (random_int(1, 3)) {
			case 1:
				$activeGroup = $groupOfTwo1;
				break;
			case 2:
				$activeGroup = $groupOfTwo2;
				break;
			case 3:
				$activeGroup = $groupOfThree;
				break;
		}		
	} while ($oldGroup == $activeGroup);
}

// find file name
$split = preg_split("/(\\\|\/)/", $galleryFile);
$fileName = $split[count($split)-1];
$fileName = preg_replace('/\\.[^.\\s]{3,4}$/', '', $fileName);
?>
	<div class="<?php echo $activeGroup[$activeGroupIndex]?>">
	   <div class="box">
	   	  <a href="<?php echo $galleryFile ?>" data-rel="lightcase:gallery-<?php echo $folderIndex?>" <?php echo $this->nameAsDescription ? "title='".$fileName."'" : ""?>>
	      	<img src="<?php echo $galleryFile ?>">
	      </a>
	   </div>
	</div>
<?php
$activeGroupIndex++;

if ($activeGroupIndex == count($activeGroup)) {
	$oldGroup = $activeGroup;
	$activeGroup = NULL;
	$activeGroupIndex = 0;
}
endforeach;
?>
</div>
<?php
}
$folderIndex++;
endforeach;
?>
</div>

<script>
jQuery(document).ready(function($) {
	PlainGallery.init({
	    swipe: true,
	    labels: {
			'errorMessage': 'Source could not be found...',
			'sequenceInfo.of': ' VON ',
			'close': 'Close',
			'navigator.prev': 'Prev',
			'navigator.next': 'Next',
				'navigator.play': 'Play',
			'navigator.pause': 'Pause'
		}
	});
	$('a[data-rel^=lightcase]').lightcase();
});
</script>