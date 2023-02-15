<?php
include 'top.php';

    //SQL to get the necessary values for the main page
    $albumInfoSql = 'SELECT fpkAlbumId, fldSongs, fldBackground, fldRecording, fldReception, fldName
    FROM tblAlbumInfo
    JOIN tblAlbum ON pmkAlbumId = fpkAlbumId
    WHERE fpkAlbumId = ?';

    //Setting variable for albumId for the Queen page
    $albumId = 3;

    //Empty data variable that will be filled with the reader
    $data = array($albumId);
    $records = $thisDatabaseReader -> select($albumInfoSql, $data);
?>

<main>
    <?php
        if(is_array($records)){
            foreach($records as $record){
                //Printing the header
                print'<h2>Info About ' . $record['fldName'] . '</h2>';

                //Printing out the actual info
                print '<h2>Songs</h2>';
                print '<p class = infoSongs>';
                print $record['fldSongs'];
                print '</p>';

                //Printing out the actual info
                print '<h2>Background</h2>';
                print '<p class = infoBackground>';
                print $record['fldBackground'];
                print '</p>';

                //Printing out the actual info
                print '<h2>Recording</h2>';
                print '<p class = infoRecording>';
                print $record['fldRecording'];
                print '</p>';

                //Printing out the actual info
                print '<h2>Reception</h2>';
                print '<p class = infoReception>';
                print $record['fldReception'];
                print '</p>';
            }
        }
    ?>

    <p class = "infoFooter"><cite>Information for this page is from 
        <a href = "https://en.wikipedia.org/wiki/Love_over_Gold" target = "_blank">Wikipedia</a></cite>
</main>

<?php
include 'footer.php'
?>