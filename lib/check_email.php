<?php
include_once('db.php');

if(isset($_POST['email'])){
    $email = $_POST['email'];

    $stmt = $userDB->prepare("SELECT userID FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows > 0){
        $stmt->close();
        exit('taken');
    } else {
        $stmt->close();
        exit('available');
    }
}
?>