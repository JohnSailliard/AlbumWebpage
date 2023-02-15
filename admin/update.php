<?php
include 'top.php';

    //SQL to get the needed values for the update page
    $updateSql = 'SELECT pmkPurchaseId, fpkEmail, fpkAlbumId, fldName
    FROM tblBoughtAlbums
    JOIN tblAlbum ON fpkAlbumId = pmkAlbumId';

    //Empty data variable
    $updateData ='';

    //Filling the data into a variable
    $records = $thisDatabaseReader -> select($updateSql, $updateData);
?>

<main>
    <h2>Update</h2>

    <?php
        //THIS PHP NEEDS TO BE UPDATED TO MATCH THE CURRENT VALUES
        //The if statement to print out the values from the array
        if(is_array($records)){
            foreach($records as $record){
            //Printing out each entries info
            print '<table>';
            print '<tr>';
            print '<td><a class = "updateUser" href = "insert.php?pid=' . $record['pmkPurchaseId'] .'">' . $record['fpkEmail'] . '</a> </td>';
            print '<td>' . $record['fldName'] . ' </td>';
            print '<td><a class = "deleteUser" href = "delete.php?pid=' . $record['pmkPurchaseId'] .'">Delete</a></td>';
            print '</tr>';
            print '</table>';
            }
        }
    ?>
</main>

<?php
include 'footer.php';
?>