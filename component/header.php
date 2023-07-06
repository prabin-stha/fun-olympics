<?php
    session_start();
    function active($page){
        $url_array =  explode('/', $_SERVER['REQUEST_URI']);
        $url = end($url_array);
        if($page == $url){
            return TRUE;
        } 
        return FALSE;
    }

    function activeLinkContains($keyword){
        if (strpos($_SERVER['REQUEST_URI'], $keyword) !== false){
            return TRUE;
        }
        return FALSE;
    }
?>

<style>
  /* Header */
header {
  display: flex;
  position: sticky;
  top: 0;
  left: 0;
  right: 0;
  justify-content: space-between;
  align-items: center;
  padding: 18px 64px;
  background-color: white;
  border-bottom: 2px solid rgba(0, 0, 0, 0.1);
  z-index: 1000;
}

header .logo img {
  cursor: pointer;
  height: 2rem;
  width: 2rem;
  margin-right: 1rem;
}

header .h {
  display: flex;
  align-items: center;
  z-index: 1000;
}
ul li {
  display: inline-block;
  padding-right: 1.5rem;
}
a {
  text-decoration: none;
  cursor: pointer;
  color: inherit;
}

.menu-nav ul li {
  transition: 0.2s;
}

.menu-nav ul li:hover {
  color: #49c5b6;
  transform: scale(0.9);
}

.menu-nav ul li:nth-child(1) {
  padding-left: 1rem;
}

.fas {
  padding-right: 8px;
}

.logout {
  transition: all 0.3 ease-out;
  cursor: pointer;
}

.logout:hover {
  color: red;
  transform: scale(0.9);
}

.login-btn {
  transition: 0.3;
  cursor: pointer;
}

.login-btn:hover {
  color: blue;
  transform: scale(0.9);
}

#myBtn {
  display: none; /* Hidden by default */
  position: fixed; /* Fixed/sticky position */
  bottom: 20px; /* Place the button at the bottom of the page */
  right: 30px; /* Place the button 30px from the right */
  z-index: 99; /* Make sure it does not overlap */
  border: none; /* Remove borders */
  outline: none; /* Remove outline */
  background-color: #2c746b; /* Set a background color */
  color: white; /* Text color */
  cursor: pointer; /* Add a mouse pointer on hover */
  padding: 8px;
  border-radius: 10px; /* Rounded corners */
  font-size: 18px; /* Increase font size */
  transition: transform 200ms ease-out;
}

#myBtn:hover {
  transform: scale(0.9);
}
</style>

<header>
  <div class="h">
    <a class="logo" href="/fun-olympics"><img
        src="/fun-olympics/images/brand.jpg" /></a>
    <nav>
      <div class="menu-nav">
        <ul>
          <li><a style="<?php if(activeLinkContains('home') or active('schedule.php')) echo "color: #2c746b; font-weight:bold;" ?>" href="/fun-olympics/home">Home</a></li>
          <li><a style="<?php if(active('gallery.php')) echo "color: #2c746b; font-weight:bold;" ?>" href="/fun-olympics/gallery.php">Gallery</a></li>
          <li><a style="<?php if(activeLinkContains('news.php')) echo "color: #2c746b; font-weight:bold;" ?>" href="/fun-olympics/news.php">News</a></li>
        </ul>
      </div>
    </nav>
  </div>
  <div>

  <?php
    if(isset($_SESSION['SESSION_ID'])){
      $notificationPath = $_SERVER['DOCUMENT_ROOT'];
      $notificationPath .= '/fun-olympics/notify.php';
      include $notificationPath;
    }
  ?>

  <?php
  $path = $_SERVER['DOCUMENT_ROOT'];
  $path .= "/fun-olympics/config.php";

  include $path;

  $query = mysqli_query($conn, "SELECT * FROM users WHERE email='{$_SESSION['SESSION_EMAIL']}'");

  if(!active('login.php') and !active('register.php') and !active('forgot-password.php') and !active('change-password.php')){
    if (mysqli_num_rows($query) == 1) {
      echo "<a class='logout' href='/fun-olympics/logout.php' onClick=\"javascript: return confirm('Are you sure you want to logout?');\">Logout</a>";
    } else {
      echo "<a class='login-btn' style='cursor:pointer;' href='/fun-olympics/login.php'>Login</a>";
    }
  }
  ?>
  </div>
</header>
<button onclick="topFunction()" id="myBtn" title="Go to top"><iconify-icon icon="ep:caret-top" style="font-size: 32px;"></iconify-icon></button>

<script>
  // Get the button:
let mybutton = document.getElementById("myBtn");

// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
  if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
    mybutton.style.display = "block";
  } else {
    mybutton.style.display = "none";
  }
}

// When the user clicks on the button, scroll to the top of the document
function topFunction() {
  document.body.scrollTop = 0; // For Safari
  document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
}
</script>