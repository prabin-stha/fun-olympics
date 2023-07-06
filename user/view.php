<?php
    session_start();
    if(!$_SESSION['SESSION_ADMIN']){
        header("Location: /fun-olympics/home");
        die();
    }  

    $name = $email = $id = $password = "";
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        require "../config.php";

        //prepare an sql statement
        $sql = "SELECT * FROM users WHERE id = ?";

        if($statement = $conn->prepare($sql)){
            $statement->bind_param("i", $c_id);
        }

        $c_id = trim($_GET['id']);

        if($statement->execute()){
            $result = $statement->get_result();

            if($result->num_rows == 1){
                //fetch result  row as a associative array
                $row = $result->fetch_assoc();

                $id = $row["id"];
                $name = $row["name"];
                $email = $row["email"];
                $password = $row["password"];
                $is_admin = $row["is_admin"] ? "Yes": "No";
                $is_verified = $row["is_verified"] ? "Yes": "No";
            }
            $statement->close();
        }
        $conn->close();

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
            table tr td {
                text-align: left;
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
                        <div style="display: flex; align-items:center; gap: 12px;">
                            <a style="height: 28px; width: 28px; border-radius: 50%; display: flex; justify-content: center; align-items: center; background-color: #225c55; color: white;" href="user-d.php">
                                <i class="fa fa-arrow-left" style="margin-top: 3px;"></i>
                            </a>
                            <h1 style="color: #225c55;">View User Details</h1>
                        </div>
                        <p style="color: #333">Empower. Customize. Simplify. Manage user accounts effortlessly with our User Management Hub. Create, edit, view, and delete users with ease. Take charge and shape your digital realm. Welcome to streamlined control!</p>
                    </div>
                    <img height="350px" src="/fun-olympics/images/user-management.png" />
                </div>
                <div class="box-user">
                    <table>
                        <tr>
                            <th>Id</th>
                            <td><?=$id?></td>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <td><?=$name?></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><?=$email?></td>
                        </tr>
                        <tr>
                            <th>Password</th>
                            <td><?=$password?></td>
                        </tr>
                        <tr>
                            <th>Verified</th>
                            <td><?=$is_verified?></td>
                        </tr>
                        <tr>
                            <th>Admin</th>
                            <td><?=$is_admin?></td>
                        </tr>
                    </table>      
                </div>
            </div>
        </div>
        
        <?php require "../component/footer.php" ?>
    </body>
</html>