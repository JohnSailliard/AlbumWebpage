    <nav>
        <a class = "<?php
        if(PATH_PARTS['filename'] == "index"){
            print 'activePage';
        }
        ?>" href = "../index.php" >Home</a>

        <a class = "<?php
        if(PATH_PARTS['filename'] == "insert"){
            print 'activePage';
        }
        ?>" href = "insert.php" >Insert</a>

        <a class = "<?php
        if(PATH_PARTS['filename'] == "update"){
            print 'activePage';
        }
        ?>" href = "update.php" >Update</a>

        <div class="dropdown">
            <a class = "dropbtn" href="update.php">Reports</a>
            <div class = "dropdown-content">
                <a href = "albumReport.php">Album Report</a>
                <a href = "buyerReport.php">Buyer Report</a>
            </div>
        </div>
    </nav>