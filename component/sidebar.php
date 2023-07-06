<style>
    #sidebar.hide{
        display: none;
    }

    #sidebar{
        width: 200px;
        display: block;
    }

    #sidebar-show{
        transform: translateX(-50px);
        transition: all 300ms ease-out;
    }

    #sidebar-show{
        transform: translateX(-80px);
    }

    #sidebar.hide + #sidebar-show{
        transform: translateX(16px);
    }

    a{
        transition: all 200ms ease-out; 
    }

    a:hover{
        color: #225c55;
        transform: scale(0.95);
    }
</style>

<aside id="sidebar" class="<?php if($hideSidebar) echo "hide" ?>" style="padding-top: 24px; color: #333;">
    <section style="display: flex;flex-direction: column; gap: 4px;margin-bottom: 22px;">
            <div style="padding-bottom: 6px;">
            <div style="display: flex; align-items: center; justify-content: space-between;padding-top: 18px;">
                <h1 style="color: #225c55;padding-bottom: 0px;line-height: 0.8;">Browse</h1>
                <button style="cursor: pointer; font-size: 14px;border: none; display:flex; justify-content: flex-end; align-items: center; gap: 4px; color: #2c746b;" id="sidebar-hide"><iconify-icon icon="mdi:hide"></iconify-icon>Hide</button>
            </div>
            <small style="font-size: 10px;">BROADCASTS, SCHEDULE & RESULTS</small>
            </div>
        <p><a style="display: flex; align-items: center; gap:4px;<?php if(activeLinkContains('home')) echo "color: #2c746b; font-weight:bold;" ?>" href="/fun-olympics/home"><iconify-icon icon="fluent:live-20-filled"></iconify-icon>Live Broadcasts</a></p>
        <p><a style="display: flex; align-items: center; gap:4px;<?php if(activeLinkContains('schedule.php')) echo "color: #2c746b; font-weight:bold;" ?>" href="/fun-olympics/schedule.php"><iconify-icon icon="material-symbols:schedule"></iconify-icon>View Schedule</a></p>
        <p><a style="display: flex; align-items: center; gap:4px;<?php if(activeLinkContains('results.php')) echo "color: #2c746b; font-weight:bold;" ?>" href="/fun-olympics/results.php"><iconify-icon icon="game-icons:podium-winner"></iconify-icon>View Results</a></p>
    </section>

    <?php
    if($_SESSION['SESSION_ADMIN']){
        echo "<hr/>";
        echo "<section style='display: flex;flex-direction: column; gap: 4px;margin-bottom: 28px; padding-right: 12px;'>";
            echo '<div style="padding-bottom: 6px;">';
            echo '<h1 style="padding-top: 18px;color: #225c55;padding-bottom: 0px;line-height: 0.8;">Management</h1>';
            echo '<small style="font-size: 10px;">YOU\'RE AN ADMIN USER</small>';
            echo '</div>';
            echo '<p><a style="display: flex; align-items: center; gap:4px;';
            if(activeLinkContains('user/')) echo "color: #2c746b; font-weight:bold;";
            echo '" href="/fun-olympics/user/user-d.php"><iconify-icon icon="la:users"></iconify-icon>Users</a></p>';
            echo '<p><a style="display: flex; align-items: center; gap:4px;';
            if(activeLinkContains('broadcast/')) echo "color: #2c746b; font-weight:bold;";
            echo '" href="/fun-olympics/broadcast/broadcast-d.php"><iconify-icon icon="mdi:broadcast"></iconify-icon>Broadcasts</a></p>';
            echo '<p><a style="display: flex; align-items: center; gap:4px;';
            if(activeLinkContains('category/')) echo "color: #2c746b; font-weight:bold;";
            echo '" href="/fun-olympics/category/category-d.php"><iconify-icon icon="fluent:sport-16-filled"></iconify-icon>Categories</a></p>';
            echo '<p><a style="display: flex; align-items: center; gap:4px;';
            if(activeLinkContains('news/')) echo "color: #2c746b; font-weight:bold;";
            echo '" href="/fun-olympics/news/news-d.php"><iconify-icon icon="icons8:news"></iconify-icon>News</a></p>';
            echo '<p><a style="display: flex; align-items: center; gap:4px;';
            if(activeLinkContains('result/')) echo "color: #2c746b; font-weight:bold;";
            echo '" href="/fun-olympics/result/result-d.php"><iconify-icon icon="healthicons:award-trophy"></iconify-icon>Result</a></p>';
        echo '</section>';
    }
    ?>
</aside>
<button id="sidebar-show" style="cursor: pointer;position: fixed; left:18px; top: 130px; height: 42px; width: 42px; border-radius: 25px; border: 3px solid #2c746b; background-color: #2c746b;display: flex; align-items:center;justify-content: center;"><iconify-icon icon="ep:menu" style="font-size: 18px; color:white;"></iconify-icon></button>

<script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
<script>
    const showSidebarBtn = document.getElementById('sidebar-show');
    const hideSidebarBtn = document.getElementById('sidebar-hide');
    const sidebar = document.getElementById('sidebar');

    const shouldHideSidebar = JSON.parse(localStorage.getItem('hideSidebar'));

    if(shouldHideSidebar){
        sidebar.classList.add('hide');
    }
    
    showSidebarBtn.addEventListener("click", (e)=>{
        sidebar.classList.remove('hide');
        localStorage.setItem('hideSidebar', 'false');
    })

    hideSidebarBtn.addEventListener("click", (e)=>{
        sidebar.classList.add('hide');
        localStorage.setItem('hideSidebar', 'true');
    })
</script>