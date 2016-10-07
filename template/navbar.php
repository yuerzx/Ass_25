
<!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Ruthless Real Estate Management System</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="property.php">Property</a></li>
                <li><a href="client.php">Client</a></li>
                <li><a href="Type.php">Type</a></li>
                <?php if(loginStatus()){
                ?>
                    <li><a href="index.php"><span class="glyphicon glyphicon-user" aria-hidden="true">
                                <?= htmlspecialchars(ucwords($_SESSION['userName'])); ?>
                            </span></a></li>
                    <li>
                        <a href="logout.php">
                            <span class="glyphicon glyphicon-off" aria-hidden="false">
                                LogOut
                            </span>
                        </a>
                    </li>
                <?php
                }
                ?>

            </ul>
        </div><!--/.navbar-collapse -->
    </div>
</nav>