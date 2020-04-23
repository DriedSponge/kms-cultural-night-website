<div class="container-fluid-lg">

    <div class="page-header">

        <nav class="navbar navbar-expand-lg navbar-dark nbth ">
            <a class="navbar-brand" href="/home"><strong>KMS Cultural Night</strong></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarmain" aria-controls="navbarmain" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars" style="color: black;"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarmain">

                <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                    <li class="nav-item" id="homelink"><a class="nav-link" href="/home/">Home</a></li>
                    <li class="nav-item" id="foodlink"><a class="nav-link" href="/food/">Food</a></li>
                    <li class="nav-item" id="musiclink"><a class="nav-link" href="/music/">Music</a></li>
                    <li class="nav-item" id="sportslink"><a class="nav-link" href="/sports/">Sports</a></li>
                    <li class="nav-item" id="gatheringslink"><a class="nav-link" href="/gatherings/">Gatherings</a></li>
                    <li class="nav-item" id="otherlink"><a class="nav-link" href="/other/">Other</a></li>
                </ul>

                <?php

                if (!isset($_SESSION['UserName'])) {
                ?>
                    <ul class="navbar-nav  mt-2 mt-lg-0">
                        <li class="nav-item" style="list-style-type:none;">
                            <a class="nav-link" href="/login/">Login</a>
                        </li>

                        <li class="nav-item" style="list-style-type:none;">
                            <a class="nav-link" href="/register/">Register</a>
                        </li>
                    </ul>
                <?php
                } else {
                ?>
                    <ul class="navbar-nav  mt-2 mt-lg-0">
                        <li class="nav-item dropdown" style="list-style-type:none;">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" style="color: white;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?=htmlspecialchars($_SESSION['UserName']);?>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                                <a class="dropdown-item" href="/logout/"><i class="fas fa-sign-out-alt"></i> Logout</a>
                                <a class="dropdown-item" href="/profile/<?=htmlspecialchars($_SESSION['UserName']);?>"><i class="fas fa-user"></i> My Profile</a>
                                <a class="dropdown-item" href="/account-settings/"><i class="fas fa-cog"></i> Settings</a>
                                <?php if(IsAdmin($_SESSION['gid'])){ ?>
                                <a class="dropdown-item" href="/admin/"><i class="fas fa-shield-alt"></i> Admin Panel</a>
                                <?php } ?>
                            </div>
                        </li>
                    </ul>
            </div>
        <?php
                } ?>


        </nav>

    </div>
</div>