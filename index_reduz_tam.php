<?php
function compress($source, $destination, $quality) {

    $info = getimagesize($source);

    if ($info['mime'] == 'image/jpeg')
        $image = imagecreatefromjpeg($source);

    elseif ($info['mime'] == 'image/gif')
        $image = imagecreatefromgif($source);

    elseif ($info['mime'] == 'image/png')
        $image = imagecreatefrompng($source);

    imagejpeg($image, $destination, $quality);

    return $destination;
}

$source_img = 'source.jpg';
$destination_img = 'destination.jpg';

//$d = compress($source_img, $destination_img, 90);

$destination_img = 'menor';

$i=0;
$types = array( 'png', 'jpg', 'jpeg', 'gif' );
if ( $handle = opendir('teste') ) {
    while ( $entry = readdir( $handle ) ) {
        $ext = strtolower( pathinfo( $entry, PATHINFO_EXTENSION) );
        if( in_array( $ext, $types ) ){
			//echo $entry;
			$d = compress("./teste/".$entry, $destination_img."_".$i.".".$ext, 40);
		}
		$i++;
    }
    closedir($handle);
}

?>
