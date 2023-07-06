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
        $sql = "SELECT n.id, n.title, n.description, n.created_at, c.name as category, n.author, n.thumbnail, n.read_time FROM news n inner join category c on n.category_id = c.id WHERE n.id = ?";

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
                $title = $row["title"];
                $description = $row["description"];
                $createdAt = $row["created_at"];
                $category = $row["category"];
                $author = $row["author"];
                $thumbnail = $row["thumbnail"];
                $readTime = $row["read_time"];
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
                            <a style="height: 28px; width: 28px; border-radius: 50%; display: flex; justify-content: center; align-items: center; background-color: #225c55; color: white;" href="news-d.php">
                                <i class="fa fa-arrow-left" style="margin-top: 3px;"></i>
                            </a>
                            <h1 style="color: #225c55;">View News Details</h1>
                        </div>
                        <p style="color: #333">Elevate your news game with our News Dashboard. Create, edit, view, and delete news articles seamlessly. Stay informed, tell compelling stories, and make a lasting impact. Welcome to the world of dynamic news management.</p>
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
                            <th>Author</th>
                            <td><?=$author?></td>
                        </tr>
                        <tr>
                            <th>Thumbnail</th>
                            <td><img height="500px" width="100%" style="object-fit: cover;" src="<?=$thumbnail?>"/></td>
                        </tr>
                        <tr>
                            <th>Read Time</th>
                            <td><?=$readTime?> min</td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td><?=$createdAt?></td>
                        </tr>
                    </table>      
                </div>
            </div>
        </div>
        
        <?php require "../component/footer.php" ?>
    </body>
</html>