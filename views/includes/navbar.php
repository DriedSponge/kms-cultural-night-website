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
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="Food" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Food
                        </a>
                        <div class="dropdown-menu" aria-labelledby="Food">
                            <a class="dropdown-item" href="/food/tunisia">Tunisia</a>
                            <a class="dropdown-item" href="/food/japan">Japan</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="Music" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Music
                        </a>
                        <div class="dropdown-menu" aria-labelledby="MyProjects">
                            <a class="dropdown-item" href="/Music">Music</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Other
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item" href="/gatherings">Gatherings</a>
                            <a class="dropdown-item" href="/sports">Sports</a>
                        </div>
                    </li>
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
                                <?php 
                                $badge = IsNsd($_SESSION['email'],true)['badge'];
                                echo $badge; 
                                ?>

                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                                <a class="dropdown-item" href="/logout/"><i class="fas fa-sign-out-alt"></i> Logout</a>
                                
                            </div>
                        </li>
                    </ul>
            </div>
        <?php
                } ?>


        </nav>

    </div>
</div>