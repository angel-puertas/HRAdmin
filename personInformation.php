<?php
require_once('lib/db.php');

$sql = "SELECT * FROM people";
$result = $personDB->query($sql);
$people = [];

while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
    $people[] = $row;
}

/*//////////////////////////////////////////////////////////////
                            ADD PERSON
//////////////////////////////////////////////////////////////*/
if(isset($_POST['add'])) {
    $firstName = ucwords($_POST['firstName']);
    $lastName = ucwords($_POST['lastName']);
    $OIB = $_POST['OIB'];
    $yearOfBirth = $_POST['yearOfBirth'];
    $educationLevel = ucwords($_POST['educationLevel']);
    $yearsOfExperience = $_POST['yearsOfExperience'];
    $jobCategories = $_POST['jobCategories'];
    $resume = ucwords($_POST['resume']);

    if (!empty($firstName) && !empty($lastName) && !empty($OIB) && !empty($yearOfBirth) && !empty($educationLevel) && $yearsOfExperience >= 0 && !empty($jobCategories) && !empty($resume)) {
        $sql = "INSERT INTO people (firstName, lastName, OIB, yearOfBirth, educationLevel, yearsOfExperience, jobCategories, resume)
            VALUES (
            '".$personDB->real_escape_string($firstName)."',
            '".$personDB->real_escape_string($lastName)."',
            '".$personDB->real_escape_string($OIB)."',
            '".$personDB->real_escape_string($yearOfBirth)."',
            '".$personDB->real_escape_string($educationLevel)."',
            '".$personDB->real_escape_string($yearsOfExperience)."',
            '".$personDB->real_escape_string($jobCategories)."',
            '".$personDB->real_escape_string($resume)."'
        )";
        $personDB->query($sql);

        $sql = "SELECT * FROM people";
        $result = $personDB->query($sql);
        $people = [];
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $people[] = $row;
        }
    } else {
        echo "Please enter correct data";
    }
}

/*//////////////////////////////////////////////////////////////
                            DELETE PERSON
//////////////////////////////////////////////////////////////*/
if (isset($_POST['delete'])) {
    $sql = "DELETE FROM people WHERE personID = '".intval($_POST['personID'])."'";
    $personDB->query($sql);

    $sql = "SELECT * FROM people";
    $result = $personDB->query($sql);
    $people = [];
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        $people[] = $row;
    }    
}
    
/*//////////////////////////////////////////////////////////////
                            EDIT PERSON
//////////////////////////////////////////////////////////////*/
if (isset($_POST['edit'])) {
    $sql = "UPDATE people
        SET firstName = '".$personDB->real_escape_string($_POST['firstName'])."',
            lastName = '".$personDB->real_escape_string($_POST['lastName'])."',
            OIB = '".$personDB->real_escape_string($_POST['OIB'])."',
            yearOfBirth = '".$personDB->real_escape_string($_POST['yearOfBirth'])."',
            educationLevel = '".$personDB->real_escape_string($_POST['educationLevel'])."',
            yearsOfExperience = '".$personDB->real_escape_string($_POST['yearsOfExperience'])."',
            jobCategories = '".$personDB->real_escape_string($_POST['jobCategories'])."',
            resume = '".$personDB->real_escape_string($_POST['resume'])."'
        WHERE personID = '".$_POST['personID']."'";
    $personDB->query($sql);

    $sql = "SELECT * FROM people";
    $result = $personDB->query($sql);
    $people = [];
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        $people[] = $row;
    }    
}
?>

<!-- /*//////////////////////////////////////////////////////////////
                                  PAGE
//////////////////////////////////////////////////////////////*/ -->
<?php require_once('templates/header.tpl.php'); ?>

<div class='body'>
    <input type="text" id='searchInput' placeholder='Search'></input>

    <table>
        <thead>
            <tr>
                <th><button id='add-button' onclick="openModal('add')">Add</button></th>
                <th>No.</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Personal ID (OIB)</th>
                <th>Year of Birth</th>
                <th>Education Level</th>
                <th>Years of Experience</th>
                <th>Job Categories</th>
                <th>Resume</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($people as $p) { ?>
                <tr>
                    <td><button onclick="openModal('edit', <?=$p['personID']?>)">Edit</button></td>
                    <td><?=$p['personID']?></td>
                    <td id='firstName<?=$p['personID']?>'><?=$p['firstName']?></td>
                    <td id='lastName<?=$p['personID']?>'><?=$p['lastName']?></td>
                    <td id='OIB<?=$p['personID']?>'><?=$p['OIB']?></td>
                    <td id='yearOfBirth<?=$p['personID']?>'><?=$p['yearOfBirth']?></td>
                    <td id='educationLevel<?=$p['personID']?>'><?=$p['educationLevel']?></td>
                    <td id='yearsOfExperience<?=$p['personID']?>'><?=$p['yearsOfExperience']?></td>
                    <td id='jobCategories<?=$p['personID']?>'><?=$p['jobCategories']?></td>
                    <td id='resume<?=$p['personID']?>'><?=$p['resume']?></td>
                    <td>
                        <form method='post'>
                            <input type='hidden' name='personID' value='<?=$p['personID']?>'>
                            <button type='submit' name='delete'>Delete</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<script src='javascript/live-search.js?v=1.0'></script>
<?php require_once('templates/footer.tpl.php'); ?>