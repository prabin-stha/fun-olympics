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
    </head>
    <body>
        <?php require "../component/header.php" ?>
        <div class="contain-main">
            <?php require "../component/sidebar.php" ?>
            <div class="box">
                <div style="display: flex;justify-content: center; align-items: center; ">
                    <div>
                        <h1 style="color: #225c55;">Broadcast Dashboard</h1>
                        <p style="color: #333">Unleash your broadcasting prowess with our Broadcast Dashboard. Create, edit, view, and delete captivating shows effortlessly. Take control, captivate your audience, and make every broadcast count. Welcome to seamless broadcasting management.</p>
                    </div>
                    <img height="350px" src="/fun-olympics/images/broadcast-management-hero.png" style="" />
                </div>
                <a style="display: inline-flex; align-items: center; gap: 12px;margin-bottom: 16px; max-width: fit-content;" href="./create.php">
                    <p style="display: inline; font-weight: bold; font-size: 18px; padding-top:4px;color: #225c55;">Add Broadcast</p>
                    <div style="height: 38px; width: 38px; border-radius: 50%; display: flex; justify-content: center; align-items: center; background-color: #225c55; color: white;">
                        <i class="fa fa-plus"></i>
                    </div>
                </a>
                <?php 
                require "../config.php";
                
                $sql = 'SELECT b.id, b.title, c.name as category, b.location, b.gender_representation, b.starts_at, b.ends_at FROM broadcast b INNER JOIN category c on b.category_id = c.id;';
                $result = $conn->query($sql);

                if ($result){
                    if ($result->num_rows > 0){
                    echo "<table>";
                    echo "<tr><th>ID</th><th>Title</th><th>Category</th><th>Location</th><th>Gender Representation</th><th>Starts At</th><th>Ends At</th><th>&nbsp;&nbsp;&nbsp;&nbsp;Actions&nbsp;&nbsp;&nbsp;&nbsp;</th></tr>";
                    while($row = $result->fetch_assoc()){
                        echo "<tr>";
                        echo "<td>" . $row["id"] . "</td>";
                        echo "<td>" . $row["title"] . "</td>";
                        echo "<td>" . $row["category"] . "</td>";
                        echo "<td>" . $row["location"] . "</td>";
                        echo "<td>" . $row["gender_representation"] . "</td>";
                        echo "<td>" . $row["starts_at"] . "</td>";
                        echo "<td>" . $row["ends_at"] . "</td>";
                        echo "<td>";
                        echo "<a class='view' href='./view.php?id=" . $row["id"] . "'><i class='fa fa-eye'></i> | </a>";
                        echo "<a class='edit' href='./edit.php?id=" . $row["id"] . "'><i class='fa fa-edit'></i> | </a>";
                        echo "<a class= 'delete' onClick=\"javascript: return confirm('Are you sure you want to delete this broadcast?');\" href='./delete.php?id=" . $row["id"] . "'><i class='fa fa-trash'></i></a>";
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