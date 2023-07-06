<?php
    session_start();
    if(!$_SESSION['SESSION_ADMIN']){
        header("Location: /fun-olympics/home");
        die();
    }

    $name = $thumbnail = "";
    $nameError = $thumbnailError = "";
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $name = trim($_POST['name']);
        $thumbnail = trim($_POST['thumbnail']);

        if ($name == '') {
            $nameError = 'Title field is required!';
        }

        if ($thumbnail == '') {
            $thumbnailError = 'Thumbnail field is required!';
        }

        if ($nameError == "" && $thumbnailError =="") {
            require "../config.php";

            $sql = "INSERT INTO category(name, thumbnail) VALUES ('{$name}', '{$thumbnail}')";
            $result = mysqli_query($conn, $sql);

            if($result){
                header("location: category-d.php");
                $conn->close();
            }else{
                echo "Error Inserting record" . mysqli_error($conn);
            }
        }
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
        <link rel="stylesheet" type="text/css" href="../css/style.css">
    </head>
    <body>
        <?php require "../component/header.php" ?>
        <div class="contain-main">
            <?php require "../component/sidebar.php" ?>
            <div class="box">
                <div style="display: flex;justify-content: center; align-items: center; ">
                    <div>
                        <div style="display: flex; align-items:center; gap: 12px;">
                            <a style="height: 28px; width: 28px; border-radius: 50%; display: flex; justify-content: center; align-items: center; background-color: #225c55; color: white;" href="category-d.php">
                                <i class="fa fa-arrow-left" style="margin-top: 3px;"></i>
                            </a>
                            <h1 style="color: #225c55;">Create Category</h1>
                        </div>
                        <p style="color: #333">Unleash your control with our Category Dashboard. Create, edit, and delete broadcast categories effortlessly. Organize and refine with ease. Welcome to seamless category management.</p>
                    </div>
                    <img height="350px" src="/fun-olympics/images/categories-hero.png" />
                </div>
                <div class="form-container">
                    <form action="create.php" method="POST">
                        <div class="form-group" style="padding-bottom: 16px;">
                            <label for="name">Category Name</label>
                            <div class="in-box">
                                <input type="text" name="name" class="form-control" value="<?= $name; ?>">
                                <p class="text-danger-edit"> <?= $nameError ?> </p>
                            </div>
                        </div>

                        <div class="form-group" style="padding-bottom: 16px;">
                            <label for="thumbnail">Thumbnail</label>
                            <div class="in-box">
                                <input id="thumbnailInput" type="text" name="thumbnail" class="form-control" value="<?= $thumbnail; ?>">
                                <p class="text-danger-edit"> <?= $thumbnailError ?> </p>
                            </div>
                        </div>

                        <div id='thumbnailContainer' style="padding-bottom: 16px; display: none;">
                            <p style="font-size: 12px; padding-bottom: 6px;">Preview Thumbnail</p>
                            <img id='thumbnail' src="" width="100%" height='300px' style="object-fit: cover;" /> 
                        </div>

                        <input type="Submit" value="Create" class="btn-submit">
                    </form>     
                </div>
            </div>
        </div>
        <?php require "../component/footer.php" ?>
    </body>

    <script>
        const thumbnailContainer = document.getElementById('thumbnailContainer');
        const thumbnailInput = document.getElementById('thumbnailInput');
        const thumbnail = document.getElementById('thumbnail');

        if(thumbnailInput.value){
            thumbnailContainer.style.display = 'block';
            thumbnail.src = thumbnailInput.value;
        }

        const onThumbnailBlur = (event) => {
            console.log('blurred');
            if(event.target.value){
                thumbnailContainer.style.display = 'block';
            }else{
                thumbnailContainer.style.display = 'none';
            }
            thumbnail.src = event.target.value;
        };
        thumbnailInput.addEventListener("blur", onThumbnailBlur);
    </script>
</html>