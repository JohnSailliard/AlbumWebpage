    <nav>
        <a class = "<?php
        if(PATH_PARTS['filename'] == "index"){
            print 'activePage';
        }
        ?>" href = "index.php" >Albums</a>

        <a class = "<?php
        if(PATH_PARTS['filename'] == "about"){
            print 'activePage';
        }
        ?>" href = "about.php" >Info</a>

        <div class="dropdown">
            <a class = "dropbtn" href="admin/update.php">Admin </a>
            <div class = "dropdown-content">
                <a href = "admin/insert.php">Insert</a>
                <a href = "admin/update.php">Update</a>
            </div>
        </div>
    </nav>