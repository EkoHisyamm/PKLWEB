<!-- Header Section Begin -->
<header class="header">
    <div class="container">
        <div class="row">
            <div class="col-lg-2">
                <div class="header__logo">
                    <a href="./index.php" style="display: block;">
                        <img class="navbar__img" style=" display: block; margin-left: auto; margin-right: auto;" src="img/logo.png" alt="">
                    </a>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="header__nav">
                    <nav class="header__menu mobile-menu">
                        <ul>
                            <li class=""><a href="./index.php">Home</a></li>
                            <li><a href="./categories.html">Categories <span class="arrow_carrot-down"></span></a>
                                <ul class="dropdown">
                                    <li><a href="./categories.html">Categories</a></li>
                                    <li><a href="./anime-details.php">Anime Details</a></li>
                                    <li><a href="./anime-watching.html">Anime Watching</a></li>
                                    <li><a href="./blog-details.html">Blog Details</a></li>
                                    <li><a href="./signup.html">Sign Up</a></li>
                                    <li><a href="./login.html">Login</a></li>
                                </ul>
                            </li>
                            <li><a href="./genre.php">Genre</a></li>
                            <li><a href="#">Jadwal Rilis</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
            <div class="navbar__search">
                <div class="header__right">
                    <form class="form-inline ml-3" action="search.php" method="GET">
                        <div class="input-group input-group-sm">
                            <input class="form-control form-control-navbar" value="<?php $_GET['judul'] ?>" type="search" name='judul' placeholder="Search" aria-label="Search">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>