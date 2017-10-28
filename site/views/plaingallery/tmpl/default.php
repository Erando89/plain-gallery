<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

$document = JFactory::getDocument();
$document->addStyleSheet('components/com_plaingallery/views/css/plaingallery.css');
$document->addCustomTag('<script type="text/javascript" src="components/com_plaingallery/views/js/plaingallery.js"></script>');
?>

<div class="albums">
<button type="button" class="btn btn-default">Zur&uuml;ck</button>
<?php 
// display albums
//var_dump($this->galleryFolders);

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
$i = 1;
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

// echo "<script>console.log('activeGroup:" . json_encode($activeGroup). "')</script>";

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

?>
	<div class="<?php echo $activeGroup[$activeGroupIndex]?>">
	   <div class="box">
	      <a href="#" data-toggle="modal" data-backdrop="true" data-target="#<?php echo $i ?>">
	      <img src="<?php echo $galleryFile ?>">
	      </a>
	      <div class="modal fade" id="<?php echo $i ?>" tabindex="-1" role="dialog">
	         <div class="modal-dialog" role="document">
	            <div class="modal-content">
	               <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
	               <div class="modal-body">
	                  <img src="<?php echo $galleryFile ?>">
	               </div>
	               <div class="col-md-12 description">
	                  <h4>This is the first one on my Gallery</h4>
	               </div>
	            </div>
	         </div>
	      </div>
	   </div>
	</div>
<?php
$i++;
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
PlainGallery.init();
</script>