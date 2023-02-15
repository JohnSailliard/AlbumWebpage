<?php
include 'top.php';

    //SQL to get the necessary values for the main page
    $buyerReportSql = 'SELECT * FROM tblBuyer';

    //Empty data variable that will be filled with the reader
    $data ='';
    $buyers = $thisDatabaseReader -> select($buyerReportSql, $data);

    //SQL to get the amount bought
    $boughtSql = 'SELECT fpkEmail FROM tblBoughtAlbums';

    //Empty data variable and reader
    $data2 = '';
    $purchases = $thisDatabaseReader -> select($boughtSql, $data2);
?>

<main>
    <h2>Buyer Report</h2>

    <?php
        //Loop to print out the variables
        if(is_array($buyers)){
            foreach($buyers as $buyer){
            $amountBought = 0;
            print '<table class = "buyerReportTable">';
            print '<tr>';
            //Displaying tall buyer info
            print '<td>Email: ' . $buyer['pmkEmail'] . '</td>';
            print '<td>Name: ' . $buyer['fldFirstName'] . " " . $buyer['fldLastName'] . '</td>';
            print '<td>TAC(' . $buyer['fldTAC'] . ') R.E.(' . $buyer['fldCommunicate'] . ')</td>';
            //Getting the amount of each album sold
            if(is_array($purchases)){
                foreach($purchases as $purchase){
                    if($purchase['fpkEmail'] == $buyer['pmkEmail']){
                        $amountBought += 1;
                    }
                }
            }
            print '<td>Bought ' . $amountBought . ' albums</td>';
            print '</tr>';
            print '</table>';
            }
        }
    ?>
</main>

<?php
include 'footer.php';
?>