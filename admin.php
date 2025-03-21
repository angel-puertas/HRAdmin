<?php
include_once('lib/db.php');
include_once('templates/header.tpl.php');

// Deny access if account is not admin
if(empty($_SESSION) || !isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] != 1) {
    header("HTTP/1.1 403 Forbidden");
    die("You do not have permission to access this page.");
}

$sql = "SELECT * FROM users";
$result = $userDB->query($sql);
$users = [];
while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
    $users[] = $row;
}

/*//////////////////////////////////////////////////////////////
                            ACTIONS
//////////////////////////////////////////////////////////////*/
if (isset($_POST['updateMaxFailedLogins'])) {
    $maxFailedLogins = (int) $_POST['maxFailedLogins'];
    $stmt = $userDB->prepare("UPDATE settings SET settingValue = ? WHERE settingKey = 'maxFailedLogins'");
    $stmt->bind_param("i", $maxFailedLogins);
    $stmt->execute();
    $stmt->close();
    header("Location: /HRAdmin/admin.php");
}

if (isset($_POST['updateRememberMeDaysToExpiration'])) {
    $rememberMeDaysToExpiration = (int) $_POST['rememberMeDaysToExpiration'];
    $stmt = $userDB->prepare("UPDATE settings SET settingValue = ? WHERE settingKey = 'rememberMeDaysToExpiration'");
    $stmt->bind_param("i", $rememberMeDaysToExpiration);
    $stmt->execute();
    $stmt->close();
    header("Location: /HRAdmin/admin.php");
}

if (isset($_POST['activate'])) {
    $userID = $_POST['userID'];
    $stmt = $userDB->prepare("UPDATE users SET isEmailConfirmed = 1 WHERE userID = ?");
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $stmt->close();
    header("Location: /HRAdmin/admin.php");
}

if (isset($_POST['lock'])) {
    $userID = $_POST['userID'];
    $stmt = $userDB->prepare("UPDATE users SET isLocked = 1 WHERE userID = ?");
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $stmt->close();
    header("Location: /HRAdmin/admin.php");
}

if (isset($_POST['unlock'])) {
    $userID = $_POST['userID'];
    $stmt = $userDB->prepare("UPDATE users SET isLocked = 0 WHERE userID = ?");
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $stmt->close();
    header("Location: /HRAdmin/admin.php");
}
?>

<!-- /*//////////////////////////////////////////////////////////////
                                  PAGE
//////////////////////////////////////////////////////////////*/ -->
<div class='body'>
<h2>System Configuration</h2>
<div>
    <form method='post'>
        <label for='maxFailedLogins'>Max Failed Logins:</label>
        <input type='text' name='maxFailedLogins'>
        <button type='submit' name='updateMaxFailedLogins'>Update</button>
    </form>
    <form method='post'>
        <label for='rememberMeDaysToExpiration'>Remember Me days to expiration:</label>
        <input type='text' name='rememberMeDaysToExpiration'>
        <button type='submit' name='updateRememberMeDaysToExpiration'>Update</button>
    </form>
</div>

<br>
<h2>User Management</h2>
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

<?php include_once('templates/footer.tpl.php'); ?>