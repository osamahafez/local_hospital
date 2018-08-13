<nav class="navbar navbar-inverse">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="dashboard.php"><i class="glyphicon glyphicon-dashboard"></i> Dashboard</a>
    </div>
    <div class="collapse navbar-collapse" id="app-nav">
      <ul class="nav navbar-nav">
        <li><a href="doctors.php">Doctors</a></li>
        <li><a href="appointments.php">Appointments</a></li>
        <li><a href="contacts.php">Messages</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $_SESSION['user_admin']; ?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="admins.php">Manage Admins</a></li>
            <li><a href="admins.php?do=Edit&userid=<?php echo $_SESSION['id_admin'] ?>">Edit Profile</a></li>
            <li><a href="logout.php">Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>