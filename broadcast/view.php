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
        $sql = 'SELECT b.id, b.title, b.description, c.name as category, b.location, b.gender_representation, b.url, b.thumbnail, b.starts_at, b.ends_at FROM broadcast b INNER JOIN category c on b.category_id = c.id WHERE b.id = ?;';

        if($statement = $conn->prepare($sql)){
            $statement->bind_param("i", $c_id);
        }

        $c_id = trim($_GET['id']);

        if($statement->execute()){
            $result = $statement->get_result();

            if($result->num_rows == 1){
                $row = $result->fetch_assoc();

                $id = $row["id"];
                $title = $row["title"];
                $description = $row["description"];
                $category = $row["category"];
                $location = $row["location"];
                $genderRepresentation = $row["gender_representation"];
                $startsAt = $row["starts_at"];
                $endsAt = $row["ends_at"];
                $thumbnail = $row["thumbnail"];
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
                            <a style="height: 28px; width: 28px; border-radius: 50%; display: flex; justify-content: center; align-items: center; background-color: #225c55; color: white;" href="broadcast-d.php">
                                <i class="fa fa-arrow-left" style="margin-top: 3px;"></i>
                            </a>
                            <h1 style="color: #225c55;">View Broadcast Details</h1>
                        </div>
                        <p style="color: #333">Unleash your broadcasting prowess with our Broadcast Dashboard. Create, edit, view, and delete captivating shows effortlessly. Take control, captivate your audience, and make every broadcast count. Welcome to seamless broadcasting management.</p>
                    </div>
                    <img height="350px" src="/fun-olympics/images/broadcast-management-hero.png" />
                </div>
                <div class="box-user" style="padding-top: 12px;">
                    <table>
                        <tr>
                            <th>Id</th>
                            <td><?=$id?></td>
                        </tr>
                        <tr>
                            <th>Title</th>
                            <td><?=$title?></td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td><?=$description?></td>
                        </tr>
                        <tr>
                            <th>Category</th>
                            <td><?=$category?></td>
                        </tr>
                        <tr>
                            <th>Location</th>
                            <td><?=$location?></td>
                        </tr>
                        <tr>
                            <th>Gender Representation</th>
                            <td><?=$genderRepresentation?></td>
                        </tr>
                        <tr>
                            <th>Thumbnail</th>
                            <td><img height="500px" width="100%" style="object-fit: cover;" src="<?=$thumbnail?>"/></td>
                        </tr>
                        
                        <tr>
                            <th>Ends At</th>
                            <td><?=$endsAt?></td>
                        </tr>
                        <tr>
                            <th>Starts At</th>
                            <td><?=$startsAt?></td>
                        </tr>
                        <tr>
                            <th>Ends At</th>
                            <td><?=$endsAt?></td>
                        </tr>
                    </table>      
                </div>
            </div>
        </div>
        <?php require "../component/footer.php" ?>
    </body>
</html>