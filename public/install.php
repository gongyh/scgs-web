<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>install</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
  <style>
    .header {
      width: 100%;
      height: 100px;
      background-image: linear-gradient(to right, rgb(93, 35, 146), rgb(39, 47, 151), rgb(240, 187, 231));
      font-size: 30px;
    }
  </style>
</head>

<body>
  <div class="header text-white text-center pt-3">Welcome to install scgs-web</div>

  <div class="row">
    <div class="col-md-4"></div>
    <div class="col-md-3 mt-5">
      <form action="" method="POST">
        <div class="form-group">
          <label for="database_name">Database Name</label>
          <input class="form-control" id="database_name" name="database_name">
        </div>
        <div class="form-group">
          <label for="exampleInputEmail1">Database Username</label>
          <input class="form-control" id="database_username" name="database_username">
        </div>
        <div class="form-group">
          <label for="exampleInputEmail1">Database Password</label>
          <input class="form-control" id="exampleInputEmail1" name="database_password">
          <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
        </div>
        <div class="form-group">
          <label for="exampleInputEmail1">Admin E-mail</label>
          <input class="form-control" id="exampleInputEmail1" name="admin_email">
        </div>
        <div class="form-group">
          <label for="exampleInputPassword1">Admin Password</label>
          <input type="password" class="form-control" id="exampleInputPassword1" name="admin_password">
          <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
        </div>
        <button type="submit" class="btn btn-primary" name="install">Submit</button>
      </form>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>

</html>

<?php
if (isset($_POST['install'])) {
    $database_name = $_POST['database_name'];
    $database_username = $_POST['database_username'];
    $database_password = $_POST['database_password'];
    $admin_email = $_POST['admin_email'];
    $admin_password = $_POST['admin_password'];
}

$env_file = "../.env";
if (is_writable($env_file)) {
}
