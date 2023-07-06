<?php
    session_start();
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
        <link rel="stylesheet" type="text/css" href="/fun-olympics/css/style.css">
    </head>
    <body>
        <?php require "./component/header.php" ?>
        <div class="contain-main">
            <?php require "./component/sidebar.php" ?>
            <div class="box">
                <div>
                    <img width="100%" height="350px" style="object-fit:cover;object-position:50% 65%; border-radius:4px" src='/fun-olympics/images/results-hero.jpg' alt="hero image"/>
                    <h1 style="color: #2c746b;margin-top: 12px;padding-bottom: 12px;">Recent Results</h1>

                    <?php 
                        require "./config.php";
                        
                        $sql = "SELECT r.id, r.award, r.winner, r.runner_up, c.name as category, r.third, r.awarded_date FROM result r INNER JOIN category c on r.category_id = c.id ORDER BY r.awarded_date DESC;";

                        if ($result = $conn->query($sql)){
                            if ($result->num_rows > 0){
                            echo"<div style='display: flex; gap: 50px; align-items: flex-start;'>";
                            echo "<div style='display: grid; grid-template-column: 1fr;gap: 18px; justify-content: flex-start;margin-top: 12px;'>";
                            while($row = $result->fetch_assoc()){
                                $date = DateTime::createFromFormat('Y-m-d H:i:s', $row['awarded_date']);
                                $formattedDate = $date->format('M d, Y h:i A');
                                echo "<div style='position: relative; display: inline-block;width: fit-content;'>";
                                echo "<img style='object-fit: cover;' width='500px' height='200px' src='/fun-olympics/images/podium.png'/>";
                                echo "<p style='position: absolute; right: 72%; top: 65px; font-weight: bold; color: #333; font-size: 18px;'>" . $row['runner_up'] . "</p>";
                                echo "<p style='position: absolute; right: 44%; top: 35px; font-weight: bold; color: #333; font-size: 18px;'>" . $row['winner'] . "</p>";
                                echo "<p style='position: absolute; left: 72%; top: 90px; font-weight: bold; color: #333; font-size: 18px;'>" . $row['third'] . "</p>";
                                echo "<p style='color: #333; text-align: center; font-weight: bold; font-size: 12px; letter-spacing:1px; text-transform: uppercase;'>" . $row['category'] ."</p>";
                                echo "<p style='color: #333; text-align: center;'>" . $row['winner'] . ' won the <em style="font-weight: bold;">' . $row['award'] ."</em></p>";
                                echo "<p style='color: #333; text-align: center;'>" . $formattedDate . "</p>";                                
                                echo "</div>";
                            }
                            echo "</div>";
                            echo "<div style='width: 100%; padding-inline: 24px; border-left: 2px solid rgba(0, 0, 0, 0.2);padding-left: 60px;'>";
                            echo ' <h2 style="color: #2c746b;margin-top: 12px;padding-bottom: 24px;margin-top: 0;text-align:center;">Featured News</h2>';
                            echo "<div style='display: flex; flex-direction: column; gap: 24px;'>";
                            $sqlNews = "SELECT n.id, n.title, n.description, n.thumbnail, c.name as category, n.author, n.read_time, n.created_at FROM news n INNER JOIN category c on n.category_id = c.id LIMIT 3;";
                            if ($result = $conn->query($sqlNews)){
                                if ($result->num_rows > 0){
                                    while($row = $result->fetch_assoc()){
                                        $dateString = "2023-07-01 18:21:00";
                                        $date = DateTime::createFromFormat('Y-m-d H:i:s', $dateString);
                                        $formattedDate = $date->format('M d, Y');
                                        echo "<div style='background-color: #DDD; border-radius: 12px; box-shadow: 6px 6px 20px 2px #888888;'>";
                                        echo "<img height='350px' width='100%' style='object-fit: cover; border-radius: 12px 12px 0 0;' src=" . $row['thumbnail'] . " />";
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
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                            }
                        }
                    ?>     
                    
                </div>
            </div>
        </div>
        <?php require "./component/footer.php" ?>
    </body>
</html>