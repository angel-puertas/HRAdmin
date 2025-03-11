<?php 
require_once('lib/db.php');

$sql = "SELECT * FROM categories";
$result = $categoryDB->query($sql);
$categories = [];

while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
    $categories[] = $row;
}

if(isset($_POST['add'])) {
    $name = ucwords($_POST['name']);

    if (!empty($name)) {
        $sql = "INSERT INTO categories (name)
            VALUES (
            '".$personDB->real_escape_string($name)."'
        )";
        $categoryDB->query($sql);

        $sql = "SELECT * FROM categories";
        $result = $categoryDB->query($sql);
        $categories = [];
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $categories[] = $row;
        }
    } else {
        echo "Please enter correct data";
    }
}

if (isset($_POST['delete'])) {
    $sql = "DELETE FROM categories WHERE categoryID = '".intval($_POST['categoryID'])."'";
    $categoryDB->query($sql);

    $sql = "SELECT * FROM categories";
    $result = $categoryDB->query($sql);
    $categories = [];
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        $categories[] = $row;
    }    
}

if (isset($_POST['edit'])) {
    $sql = "UPDATE categories
        SET name = '".$categoryDB->real_escape_string($_POST['name'])."'
        WHERE categoryID = '".$_POST['categoryID']."'";
    $categoryDB->query($sql);

    $sql = "SELECT * FROM categories";
    $result = $categoryDB->query($sql);
    $categories = [];
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        $categories[] = $row;
    }    
}
?>

<?php require_once('templates/header.tpl.php') ?>
<div class='body'>
    <table>
        <thead>
            <tr>
                <th><button id='add-button' onclick="openModal('categoryAdd')">Add</button></th>
                <th>No.</th>
                <th>Name</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $category) { ?>
                <tr>
                    <td><button onclick="openModal('categoryEdit', <?=$category['categoryID']?>)">Edit</button></td>
                    <td><?=$category['categoryID']?></td>
                    <td id='name<?=$category['categoryID']?>'><?=$category['name']?></td>
                    <td>
                        <form method='post'>
                            <input type='hidden' name='categoryID' value='<?=$category['categoryID']?>'>
                            <button type='submit' name='delete'>Delete</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php require_once('templates/footer.tpl.php') ?>