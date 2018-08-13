<nav class="navbar navbar-default">
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="doctor_home.php">The Local Hospital</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="doctor_home.php">Appointments</a></li>
        <li><a href="doctor_profile.php">Profile</a></li>
      </ul>
      <form class="navbar-form navbar-left" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
        <div class="form-group">
          <input type="text" name="app_date" class="form-control" placeholder="YYYY-MM-DD">
        </div>
        <button type="submit" class="btn btn-default">Search Date</button>
      </form>
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dr/<?php echo $_SESSION['doc_name']; ?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="doctor_profile.php?do=Edit">Edit Profile</a></li>
            <li><a href="contact.php">Contact Admin</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="logout.php">Logout</a></li>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>