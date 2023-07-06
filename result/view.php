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
        $sql = 'SELECT r.id, r.award, r.winner, r.runner_up, c.name as category, r.third, r.awarded_date FROM result r INNER JOIN category c on r.category_id = c.id WHERE r.id = ?;';

        if($statement = $conn->prepare($sql)){
            $statement->bind_param("i", $c_id);
        }

        $c_id = trim($_GET['id']);

        if($statement->execute()){
            $result = $statement->get_result();

            if($result->num_rows == 1){
                $row = $result->fetch_assoc();

                $id = $row["id"];
                $award = $row["award"];
                $category = $row["category"];
                $winner = $row["winner"];
                $runnerUp = $row["runner_up"];
                $third = $row["third"];
                $awardedDate = $row["awarded_date"];
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
                            <a style="height: 28px; width: 28px; border-radius: 50%; display: flex; justify-content: center; align-items: center; background-color: #225c55; color: white;" href="result-d.php">
                                <i class="fa fa-arrow-left" style="margin-top: 3px;"></i>
                            </a>
                            <h1 style="color: #225c55;">View Result Details</h1>
                        </div>
                        <p style="color: #333">Celebrate triumphs with our Results Dashboard. Create, edit, view, and delete winning country records effortlessly. Embrace the spirit of victory, honor achievements, and cherish the moments that define greatness. Welcome to a world of results redefined.</p>
                    </div>
                    <img height="350px" src="/fun-olympics/images/rewards.png" />
                </div>
                <div class="box-user" style="padding-top: 12px;">
                    <table>
                        <tr>
                            <th>Id</th>
                            <td><?=$id?></td>
                        </tr>
                        <tr>
                            <th>Award</th>
                            <td><?=$award?></td>
                        </tr>
                        <tr>
                            <th>Category</th>
                            <td><?=$category?></td>
                        </tr>
                        <tr>
                            <th>Winner</th>
                            <td><?=$winner?></td>
                        </tr>
                        <tr>
                            <th>Runner Up</th>
                            <td><?=$runnerUp?></td>
                        </tr>
                        <tr>
                            <th>Third</th>
                            <td><?=$third?></td>
                        </tr>
                        
                        <tr>
                            <th>Awarded At</th>
                            <td><?=$awardedDate?></td>
                        </tr>
                    </table>      
                </div>
            </div>
        </div>
        <?php require "../component/footer.php" ?>
    </body>
</html>