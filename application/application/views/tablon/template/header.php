<nav class="navbar" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <a href="<?php echo base_url();?>" class="navbar-brand">
                Fribone
            </a>
        </div>
        <ul class="nav navbar-nav">
            <li class="active">
                <a href="#">Home</a>
            </li>
            <li>
                <a href="#">Comunidad</a>
            </li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li>
                <a href="#" data-toggle="dropdown" role="button" id="drop6"><i class="ion-android-contact"></i>  <?php echo $username;?> <span class="caret"></span></a>
                <ul aria-labelledby="drop6" role="menu" class="dropdown-menu" id="menu3">
                    <li role="presentation"><a href="http://twitter.com/fat" tabindex="-1" role="menuitem">Cuenta</a></li>
                    <li class="divider" role="presentation"></li>
                    <li role="presentation">
                        <a href="<?php echo base_url(array('tablon','logout'));?>" tabindex="-1" role="menuitem">
                            Salir
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>