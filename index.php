<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Landing Page of Fun Olympics</title>
    <link
        rel="stylesheet"
        href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p"
        crossorigin="anonymous"
        />
    <link rel="stylesheet" href="./css/landing.css" />
    <link rel="stylesheet" href="./css/style.css">
  </head>
  <body>
    <?php require "./component/header.php" ?>
    <main>
      <div class="big-wrapper dark">
        <img src="./img/shape.png" alt="" class="shape" />

        <div class="showcase-area">
          <div class="container">
            <div class="left">
              <div class="big-title">
              <h1 style="padding-bottom: 24px;color: #2c746b;">Fun Olympics Yokyo - 2023</h1>
                <h3 style='color: #222'>
                  Experience the Joy of Playful Competition and Live Olympic
                  Broadcasting
                </h3>
              </div>
              <p class="text" style='color: #444'>
                Welcome to Fun Olympics! As the live broadcasting of the
                Olympics takes place, immerse yourself in the electric
                atmosphere of Yokyo City, cheering for your favorite athletes
                while engaging in our lighthearted challenges and experiencing
                the joy of sports with fellow enthusiasts. Come be a part of
                this unforgettable celebration of sports at Fun olympics in
                Yokyo City!
              </p>
              <div class="cta">
                <a href="./home" class="btn-submit">WATCH NOW</a>
              </div>
            </div>

            <div class="right" style="margin-left: 16px;">
              <img src="./images/pic1.png" alt=" Image" class="img" />
            </div>
          </div>
        </div>
      </div>
    </main>
    <?php require "./component/footer.php" ?>
  </body>
</html>
