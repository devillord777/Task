<?php
require_once('api.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link href="bootstrap\css\bootstrap.min.css" rel="stylesheet">
  <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
  <script src="bootstrap\js\bootstrap.min.js"></script>

  <link href="style.css" rel="stylesheet">
  <title>Test-task</title>
</head>

<body>
  <div class="container">
    <div id="form" class="row">
      <div class="col-md-6 offset-md-3 form">


        <form action="index.php" method="POST">
          <div class="form-group">
            <label for="url">Enter URL of Restoran</label>
            <input type="text" class="form-control" id="url" name="domain" required placeholder="somesite.com">
          </div>
          <button type="submit" class="btn btn-outline-success">Check</button>
        </form>


      </div>
    </div>
  </div>

  <?php
  if (isset($_POST['domain'])) {
    //create object off class
    $var = new Test;
    //check post data
    $data = $_POST;
    //result of request
    $result = $var->domain_search();

    $table = $var->create_table();
    if ($table == 'OK') {
      //save to database
      $save = $var->save_data();
      if ($save == 'OK') {
        $users = $var->get_data();

        if (empty($users)) { ?>
          <script>
            $element = document.getElementById('form');
            $element.innerHTML += "<div class='col-md-6 offset-md-3' style='color:red;text-align:center;'><h2>Users are Absent</h2></div>";
          </script>

        <?php } else {
                ?>
          <script>
            $element = document.getElementById('form');
            $element.innerHTML += "<div class='col-md-6 offset-md-3' style=' color:green;text-align:center;'><h2><?php echo $data['domain']; ?></h2></div>"
            $element.innerHTML += "<table id='tablebody' class='table table-hover'><thead><tr><th scope='col'>Email</th><th scope='col'>Firs Name</th><th scope='col'>Last Name</th><th scope='col'>Type</th></tr></thead><tbody>";
          </script>
          <?php

                  foreach ($users as $user) {
                    ?>
            <script>
              $table = document.getElementById('tablebody');
              $table.innerHTML += "<tr><td><?php echo $user['email'] ?></td><td><?php echo $user['first_name'] ?></td><td><?php echo $user['last_name'] ?></td><td><?php echo $user['types'] ?></td></tr>";
            </script>

          <?php
                  }
                  ?>

          <script>
            $table.innerHTML += "</tbody></table>";
          </script>
  <?php
        }
      } else {
        echo "<div class='col-md-6 offset-md-3' style='color:red;text-align:center;'>" . $save . "</div>";
      }
    }
  }
  ?>


</body>

</html>