<?php
include 'top.php';

    //SQL to get the necessary values for the main page
    $indexSql = 'SELECT pmkAlbumId, fldName, fldArtist, fldPrice, fldImage FROM tblAlbum';

    //Empty data variable that will be filled with the reader
    $data ='';
    $albums = $thisDatabaseReader -> select($indexSql, $data);
?>

<main>
    <h3>Buy an Album!</h3>

    <?php
        //Loop to print out the variables
        if(is_array($albums)){
            foreach($albums as $album){
            print '<table>';
            print '<tr>';
            //Displaying the image as a link to the buy page, passing the album Id
            print '<td><a class = "albumPicButton" href = "buyAlbum.php?aid=' . $album['pmkAlbumId'] .'">';
            print '<figure class = "albumPic">';
            print '<img alt = "' . $album['fldName'] . '" src = "images/' . $album['fldImage'] .'">';
            print '<figcaption>' . $album['fldName'] . '</figcaption>';
            print '</figure></a></td>';
            //Displaying the rest of the info
            print '<td class = "tableAlbumArtist">' . $album['fldArtist'] . '</td>';
            print '<td class = "tableAlbumPrice">Price: $' . $album['fldPrice'] . '</td>';
            print '<td><a class = "infoButton" href = "info' . $album['pmkAlbumId']. '.php">Info</td>';
            print '</tr>';
            print '</table>';
            }
        }
    ?>
</main>

<?php
include 'footer.php';
?>