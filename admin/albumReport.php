<?php
include 'top.php';

    //SQL to get the necessary values for the main page
    $albumReportSql = 'SELECT * FROM tblAlbum ';

    //Empty data variable that will be filled with the reader
    $data ='';
    $albums = $thisDatabaseReader -> select($albumReportSql, $data);

    //SQL to get the amount bought
    $boughtSql = 'SELECT fldSold, fpkAlbumId
    FROM tblAlbum
    JOIN tblBoughtAlbums ON fpkAlbumId = pmkAlbumId';

    //Empty data variable and reader
    $data2 = '';
    $purchases = $thisDatabaseReader -> select($boughtSql, $data2);
?>

<main>
    <h2>Album Report</h2>

    <?php
        //Loop to print out the variables
        if(is_array($albums)){
            foreach($albums as $album){
            $amountSold = 0;
            print '<table class = "albumReportTable">';
            print '<tr>';
            //Displaying tall album info
            print '<img alt = "' . $album['fldName'] . '" src = "../images/' . $album['fldImage'] .'">';
            print '<td>' . $album['fldName'] . '</td>';
            print '<td>' . $album['fldArtist'] . '</td>';
            print '<td>' . $album['fldPrice'] . '</td>';
            //Getting the amount of each album sold
            if(is_array($purchases)){
                foreach($purchases as $purchase){
                    if($purchase['fpkAlbumId'] == $album['pmkAlbumId']){
                        $amountSold += 1;
                    }
                }
            }
            print '<td>Sold: ' . $amountSold . '</td>';
            print '</tr>';
            print '</table>';
            }
        }
    ?>
</main>

<?php
include 'footer.php';
?>