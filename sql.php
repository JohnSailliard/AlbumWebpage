<?php
include 'top.php';
?>

<main>
    <h2>Create 'Album' Table</h2>
    <p>
        CREATE TABLE tblAlbum(
            pmkAlbumId int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            fldName varchar(40) NOT NULL,
            fldArtist varchar(35) NOT NULL,
            fldPrice float(11, 2) NOT NULL,
            fldSold boolean NOT NULL,
            fldImage varchar(30)
        );
    </p>

    <h2>Create 'Buyer' Table</h2>
    <p>
        CREATE TABLE tblBuyer(
            pmkEmail varchar(50) NOT NULL,
            fldFirstName varchar(50) NOT NULL,
            fldLastName varchar(60) NOT NULL,
            fldTAC tinyint(1) NOT NULL DEFAULT 1,
            fldCommunicate tinyint(1) NOT NULL DEFAULT 1
        );
    </p>

    <h2>Create 'BoughtAlbums' Table</h2>
    <p>
        CREATE TABLE tblBoughtAlbums(
            pmkPurchaseId int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            fpkAlbumId int(11) NOT NULL,
            fpkEmail varchar(50) NOT NULL
        );
    </p>

    <h2>Create 'Admin' Table</h2>
    <p>
        CREATE TABLE tblAdmins(
            pmkAdminId int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            fldNetId varchar(10) NOT NULL
        );
    </p>

    <h2>Create 'AlbumInfo' Table</h2>
    <p>
        CREATE TABLE tblBuyer(
            pmkEmail varchar(50) NOT NULL,
            fldFirstName varchar(50) NOT NULL,
            fldLastName varchar(60) NOT NULL,
            fldTAC tinyint(1) NOT NULL DEFAULT 1,
            fldCommunicate tinyint(1) NOT NULL DEFAULT 1,
    		fldAge	tinyint(1) NOT NULL DEFAULT 1,
    		fldComment text NOT NULL
        );
    </p>

    <h2>Selects</h2>
    <!-- Index -->
    <p>SELECT pmkAlbumId, fldName, fldArtist, fldPrice, fldImage FROM tblAlbum</p>
    <!-- Buy Album-->
    <p>SELECT fldName, fldArtist, fldPrice, fldSold, fldImage FROM tblAlbum WHERE pmkAlbumId = ?</p>
    <!-- Info 1 -->
    <p>SELECT fpkAlbumId, fldSongs, fldBackground, fldRecording, fldReception, fldName
        FROM tblAlbumInfo
        JOIN tblAlbum ON pmkAlbumId = fpkAlbumId
        WHERE fpkAlbumId = ?</p>

    <h2>Inserts/Updates</h2>
    <!-- Buy Album -->
    <p>INSERT INTO tblBoughtAlbums SET fpkAlbumId = ?, fpkEmail = ?</p>
    <p>INSERT INTO tblBuyer SET pmkEmail = ?, fldFirstName = ?, fldLastName = ?, fldTAC = ?, fldCommunicate = ? 
        ON DUPLICATE KEY UPDATE fldFirstName = ?, fldLastName = ?, fldTAC = ?, fldCommunicate = ?</p>
    <p>UPDATE tblAlbum SET fldSold = ? WHERE pmkAlbumId = ?</p>

    <h2>Admin Selects</h2>
    <!-- Album Report -->
    <p>SELECT fldSold, fpkAlbumId
        FROM tblAlbum
        JOIN tblBoughtAlbums ON fpkAlbumId = pmkAlbumId</p>
    <!-- Album Report -->
    <p>SELECT * FROM tblBuyer</p>
    <!-- Album Report -->
    <p>SELECT fpkEmail FROM tblBoughtAlbums'</p>
    <!-- Delete -->
    <p>SELECT pmkPurchaseId, fpkEmail, fpkAlbumId, pmkEmail, fldFirstName, fldLastName,  
        fldTAC, fldCommunicate, fldName, fldArtist, fldPrice
        FROM tblBoughtAlbums
        JOIN tblBuyer ON pmkEmail = fpkEmail
        JOIN tblAlbum ON pmkAlbumId = fpkAlbumId
        WHERE pmkPurchaseId = ?</p>
    <!-- Insert -->
    <p>SELECT pmkPurchaseId, fpkEmail, fpkAlbumId, pmkEmail, fldFirstName, fldLastName,  
        fldTAC, fldCommunicate, fldName, fldArtist, fldPrice
        FROM tblBoughtAlbums
        JOIN tblBuyer ON pmkEmail = fpkEmail
        JOIN tblAlbum ON pmkAlbumId = fpkAlbumId
        WHERE pmkPurchaseId = ?</p>
    <!-- Update -->
    <p>SELECT pmkPurchaseId, fpkEmail, fpkAlbumId, fldName
        FROM tblBoughtAlbums
        JOIN tblAlbum ON fpkAlbumId = pmkAlbumId</p>

    <h2>Admin Inserts/Updates/Delete</h2>
    <!-- Insert -->
    <p>INSERT INTO tblBoughtAlbums SET fpkAlbumId = ?, fpkEmail = ?
        ON DUPLICATE KEY UPDATE pmkEmail = ?</p>
    <!-- Insert -->
    <p>INSERT INTO tblBuyer SET pmkEmail = ?, fldFirstName = ?, fldLastName = ?, fldTAC = ?, fldCommunicate = ? 
        ON DUPLICATE KEY UPDATE pmkEmail = ?, fldFirstName = ?, fldLastName = ?, fldTAC = ?, fldCommunicate = ?</p>
    <!-- Insert -->
    <p>UPDATE tblAlbum SET fldSold = ? WHERE pmkAlbumId = ?</p>
    <!-- Delete -->
    <p>DELETE FROM tblBoughtAlbums WHERE pmkPurchaseId = ?</p>
</main>

<?php
include 'footer.php'
?>