<?php use parts\Session; ?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="/"><img src="/assets/img/independentphp-logo.png"></a>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <?php if(!Session::exists('logged_in')) { ?>
            <li class="nav-item">
                <a class="nav-link" href="/login">Login</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/register">Register</a>
            </li>
            <?php } ?>
            <?php if(Session::exists('logged_in') && Session::get('user_role') == 'admin') { ?>
            <li class="nav-item">
                <a class="nav-link" href="/users">users</a>
            </li>
            <?php } ?>
            <?php if(Session::exists('logged_in')) { ?>
            <div class="collapse navbar-collapse">
                <li class="ml-auto nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php echo Session::get('username'); ?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="/profile/<?php echo Session::get('username')?>">Profile</a>
                        <a class="dropdown-item" href="/logout">Logout</a>
                    </div>
                </li>
            </div>
            <?php } ?>
        </ul>
  </div>
</nav>