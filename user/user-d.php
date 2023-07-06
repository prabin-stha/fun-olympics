<?php
    session_start();
    if(!$_SESSION['SESSION_ADMIN']){
        header("Location: /fun-olympics/home");
        die();
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>title-placeholer</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link
        rel="stylesheet"
        href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p"
        crossorigin="anonymous"
        />

        <link rel="stylesheet" type="text/css" href="../css/style.css">

        <style>
            th{
                font-weight: bold;
                color: black;
            }
        </style>
    </head>
    <body>
        <?php require "../component/header.php" ?>
        <div class="contain-main">
            <?php require "../component/sidebar.php" ?>
            <div class="box">
                <div style="display: flex;justify-content: center; align-items: center; ">
                    <div>
                        <h1 style="color: #225c55;">User Dashboard</h1>
                        <p style="color: #333">Empower. Customize. Simplify. Manage user accounts effortlessly with our User Management Hub. Create, edit, view, and delete users with ease. Take charge and shape your digital realm. Welcome to streamlined control!</p>
                    </div>
                    <img height="350px" src="/fun-olympics/images/user-management.png" />
                </div>
                <a style="display: inline-flex; align-items: center; gap: 12px;margin-bottom: 16px; max-width: fit-content;" href="./create.php">
                    <p style="display: inline; font-weight: bold; font-size: 18px; padding-top:4px;color: #225c55;">Add User</p>
                    <div style="height: 38px; width: 38px; border-radius: 50%; display: flex; justify-content: center; align-items: center; background-color: #225c55; color: white;">
                        <i class="fa fa-plus"></i>
                    </div>
                </a>
                <?php 
                require "../config.php";
                
                $sql = 'SELECT * FROM users';

                if ($result = $conn->query($sql)){
                    if ($result->num_rows > 0){
                    echo "<table>";
                    echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Verfied</th><th>Admin</th><th>&nbsp;&nbsp;&nbsp;&nbsp;Actions&nbsp;&nbsp;&nbsp;&nbsp;</th></tr>";
                    while($row = $result->fetch_assoc()){
                        echo "<tr>";
                        echo "<td>" . $row["id"] . "</td>";
                        echo "<td>" . $row["name"];
                        if($_SESSION["SESSION_ID"] == $row["id"]) echo "<span style='font-weight: bold; padding-left: 6px; font-size: 10px; color: #225c55;'>(YOU)</span>";
                        echo "</td>";
                        echo "<td>" . $row["email"] . "</td>";
                        echo "<td>" . ($row["is_verified"] ? "Yes": "No") . "</td>";
                        echo "<td>" . ($row["is_admin"] ? "Yes": "No") . "</td>";
                        echo "<td>";
                        echo "<a class='view' href='./view.php?id=" . $row["id"] . "'><i class='fa fa-eye'></i> | </a>";
                        echo "<a class='edit' href='./edit.php?id=" . $row["id"] . "'><i class='fa fa-edit'></i> | </a>";
                        echo "<a class= 'delete' onClick=\"javascript: return confirm('Are you sure you want to delete this user?');\" href='./delete.php?id=" . $row["id"] . "'><i class='fa fa-trash'></i></a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                    }
                }
                ?>        
            </div>
        </div>
        <?php require "../component/footer.php" ?>
    </body>
</html>