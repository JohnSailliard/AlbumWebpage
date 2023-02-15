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

    //Creating and sanitizing an album Id variable
    $albumId = (isset($_GET['aid'])) ? (int) htmlspecialchars($_GET['aid']) : 0;

    //To get the name to still display after post
    if(isset($_POST['hidAID'])){
        $albumId = (int) htmlspecialchars($_POST['hidAID']);
    }

    //SQL to get the necessary values for the buy form
    $buyPageSql = 'SELECT fldName, fldArtist, fldPrice, fldSold, fldImage FROM tblAlbum WHERE pmkAlbumId = ?';

    //Filling in the ID value to display the right album info
    $data = array($albumId);

    //Getting the proper values from the database
    $albumToBuy = $thisDatabaseReader -> select($buyPageSql, $data); 

    //Putting album name and price into an easily accessible variable
    $albumName = $albumToBuy ? $albumName = $albumToBuy[0]['fldName'] : '';
    $albumPrice = $albumToBuy ? $albumPrice = $albumToBuy[0]['fldPrice'] : '';
    $albumSold = $albumToBuy ? $albumSold = $albumToBuy[0]['fldSold'] : '';

    //Base values for the form
    $buyerEmail = 'blank@gmail.com';
    $firstName = 'John';
    $lastName = 'Doe';
    $TAC = 1;
    $recieveEmail = 1;
    $properAge = 1;
    $comment = '';
    $alteredBy = "online";

    //Bool on whether or not to save data
    $saveData = true;
?>

<main>
    <!-- PHP to santize, validate, and upload form data -->
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
            if($boughtAID < 0 or $boughtAID > 5){
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
                $sql =  'INSERT INTO tblBoughtAlbums SET fpkAlbumId = ?, fpkEmail = ?';
                //Set of data for the insertion
                $data = array();
                $data[] = $albumId;
                $data[] = $buyerEmail;

                //Printing out the sql query generated
                if(DEBUG){
                print $thisDatabaseReader->displayQuery($sql, $data);
                }

                //SQL and array to record values to single table
                $sql2 = 'INSERT INTO tblBuyer SET pmkEmail = ?, fldFirstName = ?, fldLastName = ?, fldTAC = ?, fldCommunicate = ?, fldAge = ?, 
                    fldComment = ? ON DUPLICATE KEY UPDATE fldFirstName = ?, fldLastName = ?, fldTAC = ?, fldCommunicate = ?, fldAge = ?, fldComment = ?';
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
                print '<p class = "formSuccess">Success</p>';
                }else{
                print '<p class = "formFailure">Failed</p>';
                }

                //Mailing info to buyer
                $subject = 'Album Purchase Confirmation';
                $message = 'Thank you for purchasing the album ' . $albumName . ' for $' . $albumPrice . '. Your purchase has been recorded under the email ' . $buyerEmail . '. If any of this information is incorrect, please reply to the listed email above.';
                $header = "From: jsaillia@uvm.edu \r\n";
                $header .= "Reply to: jsaillia@uvm.edu \r\n";
                mail($buyerEmail, $subject, $message, $header);
            }
        }
    ?>

    <!-- Header printing the current album that is being purchased -->
    <h2>Buy '<?php print $albumName; ?>'</h2>
    <h2>Price: $<?php print $albumPrice; ?></h2>

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