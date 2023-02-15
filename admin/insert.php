<?php
include 'top.php';

    //Get data function to sanitize values
    function getData($field){
        if(!isset($_POST[$field])){
        $data = "";
        }else{
        $data = trim($_POST[$field]);
        $data = htmlspecialchars($data, ENT_QUOTES);
        }
        return $data;
    }

    //Check for letters, numbers and dash, period, space and single quote only
    function verifyAlphaNum($testString) {
        return (preg_match ("/^([[:alnum:]]|-|\.| |\'|&|;|#)+$/", $testString));
    }

    //Creating and sanitizing an purchase Id variable
    $purchaseId = (isset($_GET['pid'])) ? (int) htmlspecialchars($_GET['pid']) : 0;

    //Base values for the form
    $buyerEmail = 'blank@gmail.com';
    $firstName = 'John';
    $lastName = 'Doe';
    $TAC = 1;
    $recieveEmail = 1;
    $properAge = 1;
    $comment = '';
    $alteredBy = htmlentities($_SERVER["REMOTE_USER"], ENT_QUOTES, "UTF-8");

    //Initializing the save data bool
    $saveData = true;

    //ALTER THIS TO FIT THE REQUIREMENTS OF THIS FORM
    //If statement to set values if they are accessable
    if($purchaseId > 0){
        $fillerData = array();
        $recordsSql = 'SELECT pmkPurchaseId, fpkEmail, fpkAlbumId, pmkEmail, fldFirstName, fldLastName,  
        fldTAC, fldCommunicate, fldAge, fldComment, fldName, fldArtist, fldPrice
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
           $properAge = $record['fldAge'];
           $comment = $record['fldComment'];
           $recieveEmail = $record['fldCommunicate'];
           $albumId = $record['fpkAlbumId'];
           $alteredBy = htmlentities($_SERVER["REMOTE_USER"], ENT_QUOTES, "UTF-8");
        }
     }
?>

<main>
    <h2>Insert</h2>

    <!-- NEED TO UPDATE THESE VALUES FOR THE ADMIN INSERT -->
    <?php
        //If submit button is hit then post, with debug possibility
        if(isset($_POST['btnSubmit'])){
            if(DEBUG){
                print '<p>POST array:</p><pre>';
                print_r($_POST);
                print '</pre>';
            }

            //Sanitize data
            $buyerEmail = filter_var($_POST['txtBuyerEmail'], FILTER_SANITIZE_EMAIL);
            $firstName = trim((string) getData('txtFirstName'));
            $lastName = trim((string) getData('txtLastName'));
            $TAC = (int) getData('chkTAC');
            $recieveEmail = (int) getData('chkRecieveEmail');
            $properAge = (int) getData('chkAge');
            $comment = trim((string) getData('txtComment'));
            $boughtAID = (int) getData('hidAID');
            $alteredBy = trim((string) getData("hidAltered"));

            //Validate data
            if(!filter_var($buyerEmail, FILTER_VALIDATE_EMAIL)){
                print '<p class = "mistake">Please enter a valid email address</p>';
                $saveData = false;
            }
            if(!verifyAlphaNum($firstName)){
                print '<p class = "mistake">Invalid character, please use only letters</p>';
                $saveData = false;
            }
            if(!verifyAlphaNum($lastName)){
                print '<p class = "mistake">Invalid character, please use only letters</p>';
                $saveData = false;
            }
            if($TAC < 0 or $TAC > 1){
                print '<p class = "mistake">Please choose a valid TAC acceptance status</p>';
                $saveData = false;
            }
            if($recieveEmail < 0 or $recieveEmail > 1){
                print '<p class = "mistake">Please choose a valid email response acceptance status</p>';
                $saveData = false;
            }
            if($properAge < 0 or $properAge > 1){
                print '<p class = "mistake">Please enter whether or not you are old enough to buy this album</p>';
                $saveData = false;
            }
            if(!verifyAlphaNum($comment)){
                print '<p class = "mistake">Please enter a comment message with only letters</p>';
                $saveData = false;
            }
            if($boughtAID < 0 or $boughtAID > 3){
                print '<p class = "mistake">Please buy a valid album</p>';
                $saveData = false;
            }
            if(!verifyAlphaNum($alteredBy)){
                print '<p class = "mistake">Submit with a valid account</p>';
                $saveData = false;
            }

            //Uploading form information
            if($saveData){
                //SQL and array to record values to grouped table
                $sql =  'INSERT INTO tblBoughtAlbums SET fpkAlbumId = ?, fpkEmail = ?
                ON DUPLICATE KEY UPDATE pmkEmail = ?';
                //Set of data for the insertion
                $data = array();
                $data[] = $albumId;
                $data[] = $buyerEmail;
                //On duplicate key
                $data[] = $buyerEmail;

                //Printing out the sql query generated
                if(DEBUG){
                print $thisDatabaseReader->displayQuery($sql, $data);
                }

                //SQL and array to record values to single table
                $sql2 = 'INSERT INTO tblBuyer SET pmkEmail = ?, fldFirstName = ?, fldLastName = ?, fldTAC = ?, fldCommunicate = ?, fldAge = ?, 
                    fldComment = ?ON DUPLICATE KEY UPDATE pmkEmail = ?, fldFirstName = ?, fldLastName = ?, fldTAC = ?, fldCommunicate = ?';
                //In the case of a non duplicate key
                $data2 = array();
                $data2[] = $buyerEmail;
                $data2[] = $firstName;
                $data2[] = $lastName;
                $data2[] = $TAC;
                $data2[] = $recieveEmail;
                $data2[] = $properAge;
                $data2[] = $comment;
                //In the case of a dupicate key
                $data2[] = $buyerEmail;
                $data2[] = $firstName;
                $data2[] = $lastName;
                $data2[] = $TAC;
                $data2[] = $recieveEmail;
                $data2[] = $properAge;
                $data2[] = $comment;

                //SQL and array to update the 'sold' value of the album
                $sql3 = 'UPDATE tblAlbum SET fldSold = ? WHERE pmkAlbumId = ?';
                $data3 = array();
                $data3[] = 1;
                $data3[] = $albumId;

                //Printing out the sql query generated
                if(DEBUG){
                print $thisDatabaseReader->displayQuery($sql2, $data2);
                }

                //Printing output for success
                if($thisDatabaseWriter -> insert($sql3, $data3) && $thisDatabaseWriter -> insert($sql2, $data2) 
                    && $thisDatabaseWriter -> insert($sql, $data)){
                print '<p>Success</p>';
                }else{
                print '<p>Failed</p>';
                }
            }
        }
    ?>

    <form action = "<?php print PHP_SELF; ?>" id = "frmBuy" method = "post">
         <!-- Text area for email address -->
         <fieldset>
            <p>
               <label for = "txtBuyerEmail">Email:</label>
               <input type = "text" value = "<?php print $buyerEmail; ?>"  name = "txtBuyerEmail" id = "txtBuyerEmail">
            </p>
         </fieldset>

         <!-- Text area for first name -->
         <fieldset>
            <p>
               <label for = "txtFirstName">First Name:</label>
               <input type = "text" value = "<?php print $firstName; ?>"  name = "txtFirstName" id = "txtFirstName">
            </p>
         </fieldset>

         <!-- Text area for last name -->
         <fieldset>
            <p>
               <label for = "txtLastName">Last Name:</label>
               <input type = "text" value = "<?php print $lastName; ?>"  name = "txtLastName" id = "txtLastName">
            </p>
         </fieldset>

         <!-- Checkbox for TAC -->
         <fieldset>
            <p>
               <label for = "chkTAC">I agree to the terms and conditions:</label>
               <input type = "checkbox" value = "<?php print $TAC; ?>" name = "chkTAC" id = "chkTAC"> 
            </p>
         </fieldset>

         <!-- Checkbox for recieving email -->
         <fieldset>
            <p>
               <label for = "chkRecieveEmail">I want to recieve emails:</label>
               <input type = "checkbox" value = "<?php print $recieveEmail; ?>" name = "chkRecieveEmail" id = "chkRecieveEmail"> 
            </p>
         </fieldset>

         <!-- Checkbox for Age -->
         <fieldset>
            <p>
               <label for = "chkAge">If this album is explicit, I am over 18:</label>
               <input type = "checkbox" value = "<?php print $properAge; ?>" name = "chkAge" id = "chkAge"> 
            </p>
         </fieldset>

         <!-- Text area comment -->
         <fieldset>
            <p>
               <label for = "txtComment">Comments:</label>
               <textarea value = "<?php print $comment; ?>"  name = "txtComment" id = "txtComment" rows="4" cols="50"></textarea>
            </p>
         </fieldset>

         <!-- Hidden input for the AID -->
         <label for = "hidAID"></label>
         <input type = "hidden" value = "<?php print $albumId; ?>" name = "hidAID" id = "hidAID">

         <!-- Hidden input for the altered field -->
         <label for = "hidAltered"></label>
         <input type = "hidden" value = "<?php print $alteredBy; ?>" name = "hidAltered" id = "hidAltered">

         <!-- Submit button -->
         <fieldset>
            <p>
               <input type = "submit" value = "Buy" tabindex = "999" name = "btnSubmit">
            </p>
         </fieldset>
    </form>
</main>

<?php
include 'footer.php';
?>