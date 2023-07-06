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
                        <h1 style="color: #225c55;">Result Dashboard</h1>
                        <p style="color: #333">Celebrate triumphs with our Results Dashboard. Create, edit, view, and delete winning country records effortlessly. Embrace the spirit of victory, honor achievements, and cherish the moments that define greatness. Welcome to a world of results redefined.</p>
                    </div>
                    <img height="350px" src="/fun-olympics/images/rewards.png" style="" />
                </div>
                <a style="display: inline-flex; align-items: center; gap: 12px;margin-bottom: 16px; max-width: fit-content;" href="./create.php">
                    <p style="display: inline; font-weight: bold; font-size: 18px; padding-top:4px;color: #225c55;">Add Result</p>
                    <div style="height: 38px; width: 38px; border-radius: 50%; display: flex; justify-content: center; align-items: center; background-color: #225c55; color: white;">
                        <i class="fa fa-plus"></i>
                    </div>
                </a>
                <?php 
                require "../config.php";
                
                $sql = 'SELECT r.id, r.award, r.winner, r.runner_up, c.name as category, r.third, r.awarded_date FROM result r INNER JOIN category c on r.category_id = c.id;';
                $result = $conn->query($sql);

                if ($result){
                    if ($result->num_rows > 0){
                    echo "<table>";
                    echo "<tr><th>ID</th><th>Award</th><th>Category</th><th>Winner</th><th>Runner Up</th><th>Third</th><th>Awarded Date</th><th>&nbsp;&nbsp;&nbsp;&nbsp;Actions&nbsp;&nbsp;&nbsp;&nbsp;</th></tr>";
                    while($row = $result->fetch_assoc()){
                        echo "<tr>";
                        echo "<td>" . $row["id"] . "</td>";
                        echo "<td>" . $row["award"] . "</td>";
                        echo "<td>" . $row["category"] . "</td>";
                        echo "<td>" . $row["winner"] . "</td>";
                        echo "<td>" . $row["runner_up"] . "</td>";
                        echo "<td>" . $row["third"] . "</td>";
                        echo "<td>" . $row["awarded_date"] . "</td>";
                        echo "<td>";
                        echo "<a class='view' href='./view.php?id=" . $row["id"] . "'><i class='fa fa-eye'></i> | </a>";
                        echo "<a class='edit' href='./edit.php?id=" . $row["id"] . "'><i class='fa fa-edit'></i> | </a>";
                        echo "<a class= 'delete' onClick=\"javascript: return confirm('Are you sure you want to delete this result?');\" href='./delete.php?id=" . $row["id"] . "'><i class='fa fa-trash'></i></a>";
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