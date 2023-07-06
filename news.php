<?php
    session_start();

    $sql;
    require "./config.php";

    $viewingNews = true;

    if(isset($_GET['id'])){
        $viewingNews = false;
        $newsId = (int)mysqli_real_escape_string($conn, $_GET['id']);
        $sql = "SELECT n.id, n.title, n.description, n.thumbnail, c.name as category, n.author, n.read_time, n.created_at FROM news n INNER JOIN category c on n.category_id = c.id WHERE n.id={$newsId};";
    }else{
        $viewingNews = true;
        $sql = "SELECT n.id, n.title, n.description, n.thumbnail, c.name as category, n.author, n.read_time, n.created_at FROM news n INNER JOIN category c on n.category_id = c.id;";
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>title-placeholder</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link
        rel="stylesheet"
        href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p"
        crossorigin="anonymous"
        />
        <link rel="stylesheet" href="./css/style.css">
    </head>
    <body>
        <?php require "./component/header.php" ?>
        <div class="contain-main">
            <?php require "./component/sidebar.php" ?>
            <div class="box">
                <div>
                    <?php
                        if($viewingNews){
                            echo '<img width="100%" height="350px" style="object-fit:cover;object-position:50% 20%; border-radius:4px" src="/fun-olympics/images/news-hero.jpg" alt="hero image"/>';
                            echo '<h1 style="color: #2c746b;margin-top: 12px;padding-bottom: 12px;">Latest News</h1>';
                            echo '<div class="grid-container" style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">';
                            if ($result = $conn->query($sql)){
                                if ($result->num_rows > 0){
                                    while($row = $result->fetch_assoc()){
                                        $dateString = "2023-07-01 18:21:00";
                                        $date = DateTime::createFromFormat('Y-m-d H:i:s', $dateString);
                                        $formattedDate = $date->format('M d, Y');
                                        echo "<div style='background-color: #DDD; border-radius: 12px; box-shadow: 6px 6px 20px 2px #888888;'>";
                                        echo "<img height='250px' width='100%' style='object-fit: cover; border-radius: 12px 12px 0 0;' src=" . $row['thumbnail'] . " />";
                                        echo "<div style='margin-block: 18px; margin-inline: 24px;'>";
                                        echo "<small style='font-size: 12px; text-transform: uppercase; font-weight: bold; letter-spacing: 1px;'>" . $row['category'] . "</small>";
                                        echo "<p style='font-size: 24px; font-weight: bold;color: #222;'>" . $row['title'] . "</p>";
                                        echo "<p style='margin-bottom: 2px;font-style: italic;'>". $row['author'] ." <small style='font-size: 12px;font-style: normal;'>(" .$row['read_time'] . " min read)</small> </p>";
                                        echo "<p style='margin-bottom: 12px;font-size: 13px;'>Last updated at ". $formattedDate ."</p>";
                                        echo "<p style='margin-bottom: 18px; max-height: 68px; overflow: hidden;display: -webkit-box; -webkit-box-orient: vertical;-webkit-line-clamp: 3;color: #333;'>" . $row['description'] . "</p>";
                                        echo '<a class="btn-submit" href="/fun-olympics/news.php?id=' . $row['id'] . '" style="display: block; text-align: center;">Read More</a>';
                                        echo "</div>";
                                        echo "</div>";
                                    }
                                }
                            }
                            echo '</div>';
                        }else{
                            // Single Broadcast
                            if($result = $conn->query($sql)){
                                if($result->num_rows == 1){
                                    $row = $result->fetch_assoc();
                                    $dateString = "2023-07-01 18:21:00";
                                    $date = DateTime::createFromFormat('Y-m-d H:i:s', $dateString);
                                    $formattedDate = $date->format('M d, Y');
                                    echo "<div>";
                                    echo '<img width="100%" height="600px" style="object-fit:cover;object-position:50% 50%; border-radius: 12px;" src="'. $row['thumbnail'] .'" alt="hero image"/>';
                                    echo "<p style='padding-top: 24px;font-size: 12px; text-transform: uppercase; font-weight: bold; letter-spacing: 1px;'>" . $row['category'] . "</p>";
                                    echo '<h1 style="display: flex; align-items: center; gap: 8px;">' . $row['title'];
                                    echo '</h1>';
                                    echo "<p style='margin-bottom: 2px;font-style: italic;'>". $row['author'] ." <small style='font-size: 12px;font-style: normal;'>(" .$row['read_time'] . " min read)</small> </p>";
                                    echo "<p style='margin-bottom: 18px;font-size: 13px;'>Last updated at ". $formattedDate ."</p>";
                                    echo '<p style="color: #333">' . $row['description'] . '</p>';
                                    echo '</div>';
                                }
                            }
                        }
                    ?> 
                </div>
            </div>
        </div>
        <?php require "./component/footer.php" ?>
    </body>
</html>