<?php
include 'top.php';
?>

<main>

    <h2>Do You Want to Delete This Record?</h2>

    <?php
        //Getting the purchase Id and placing it in the variable
        $purchaseId = (isset($_GET['pid'])) ? (int) htmlspecialchars($_GET['pid']) : 0;

        //Trying to get the name to still display after post
        if(isset($_POST['hidPID'])){
            $purchaseId = (int) htmlspecialchars($_POST['hidPID']);
        } 
            
        //If statement to set values if they are accessable
        if($purchaseId > 0){
            $fillerData = array();
            $recordsSql = 'SELECT pmkPurchaseId, fpkEmail, fpkAlbumId, pmkEmail, fldFirstName, fldLastName,  
            fldTAC, fldCommunicate, fldName, fldArtist, fldPrice
            FROM tblBoughtAlbums
            JOIN tblBuyer ON pmkEmail = fpkEmail
            JOIN tblAlbum ON pmkAlbumId = fpkAlbumId
            WHERE pmkPurchaseId = ?';
            $fillerData[] = $purchaseId;
            $records = $thisDatabaseReader -> select($recordsSql, $fillerData);

            //Setting the default values if the values are accessible
            foreach($records as $record){
                $buyerEmail = $record['fpkEmail'];
                $firstName = $record['fldFirstName'];
                $lastName = $record['fldLastName'];
                $TAC = $record['fldTAC'];
                $recieveEmail = $record['fldCommunicate'];
                $alteredBy = htmlentities($_SERVER["REMOTE_USER"], ENT_QUOTES, "UTF-8");
            }

            //Displaying the data of the record about to be deleted
            if(is_array($records)){
                foreach($records as $record){
                    //Printing out each entries info
                    //Printing out each entries info
                    print '<table>';
                    print '<tr>';
                    print '<td><a class = "updateUser" href = "insert.php?pid=' . $record['pmkPurchaseId'] .'">' . $record['fpkEmail'] . '</a></td>';
                    print '<td>' . $record['fldFirstName'] . ' ' . $record['fldLastName'] . '</td>';
                    print '<td class = "deleteAlbumInfo">Purchased "' . $record['fldName'] . '" by "'. $record['fldArtist'] .'"</td>';
                    print '</tr>';
                    print '</table>';
                }
            }
        }

        //The delete array and SQL
        $deleteData = array();
        $deleteSql = 'DELETE FROM tblBoughtAlbums WHERE pmkPurchaseId = ?';
        $deleteData[] = $purchaseId;

        //If the data is once again selected to be deleted by click "delete"
        if(isset($_POST['btnSubmit'])){
            //Printing out array data if in the debug state
            if(DEBUG){
                print '<p>POST array:</p><pre>';
                print_r($_POST);
                print '</pre>';
            }

            //Printing out the sql query generated
            if(DEBUG){
                print $thisDatabaseReader->displayQuery($deleteSql, $deleteData);
            }

            //Printing output for success on update
            if($thisDatabaseWriter -> delete($deleteSql, $deleteData)){
                print '<p class = "formSuccess">Success</p>';
            }else{
                print '<p class = "formFailure">Failed</p>';
            }
        }
    ?>

    <!-- Form to double check intentions to delete -->
    <form method = "POST">

        <!-- Hidden input for the did -->
        <label for = "hidPID"></label>
        <input type = "hidden" value = "<?php print $purchaseId; ?>" name = "hidPID" id = "hidPID">

        <!-- Submit button -->
        <fieldset>
            <p>
                <input type = "submit" value = "Delete" tabindex = "999" name = "btnSubmit">
            </p>
        </fieldset>
    </form>
</main>

<?php
include 'footer.php';
?>