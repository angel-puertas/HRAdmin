<?php
include_once('lib/db.php');
include_once('templates/header.tpl.php');
if(!empty($_SESSION) && $_SESSION['isAdmin'] == 0) {
    header("HTTP/1.1 403 Forbidden");
    die("You do not have permission to access this page.");
} else {
$sql = "SELECT * FROM users";
$result = $userDB->query($sql);
$users = [];

while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
    $users[] = $row;
}

if (isset($_POST['activate'])) {
    $userID = $_POST['userID'];
    $stmt = $userDB->prepare("UPDATE users SET isEmailConfirmed = 1 WHERE userID = ?");
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $stmt->close();
}

if (isset($_POST['lock'])) {
    $userID = $_POST['userID'];
    $stmt = $userDB->prepare("UPDATE users SET isLocked = 1 WHERE userID = ?");
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $stmt->close();
}

if (isset($_POST['unlock'])) {
    $userID = $_POST['userID'];
    $stmt = $userDB->prepare("UPDATE users SET isLocked = 0 WHERE userID = ?");
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $stmt->close();
}
?>

<div class='body'>
<input type="text" id='searchInput' placeholder='Search'></input>

<table>
    <thead>
        <tr>
            <th>No.</th>
            <th>Email</th>
            <th>Email Confirmed</th>
            <th>Is Locked?</th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user) { ?>
            <?php if ($user['isAdmin'] == 0) { ?>
            <tr>
                <td><?=$user['userID']?></td>
                <td id='email<?=$user['userID']?>'><?=$user['email']?></td>
                <td id='isEmailConfirmed<?=$user['userID']?>'><?=$user['isEmailConfirmed']?></td>
                <td id='isLocked<?=$user['userID']?>'><?=$user['isLocked']?></td>
                <td>
                    <form method='post'>
                        <input type='hidden' name='userID' value='<?=$user['userID']?>'>
                        <button type='submit' name='activate'>Activate</button>
                    </form>
                </td>
                <td>
                    <form method='post'>
                        <input type='hidden' name='userID' value='<?=$user['userID']?>'>
                        <button type='submit' name='lock'>Lock</button>
                    </form>
                </td>
                <td>
                    <form method='post'>
                        <input type='hidden' name='userID' value='<?=$user['userID']?>'>
                        <button type='submit' name='unlock'>Unlock</button>
                    </form>
                </td>
            </tr>
            <?php } ?>
        <?php } ?>
    </tbody>
</table>
</div>

<?php
}
include_once('templates/footer.tpl.php'); 
?>