<?php
    session_start();

    require "server.php";

    $username = $_SESSION['username'];
    $password = $_SESSION['password'];
    $user_id = $_SESSION['user_id'];


?>
<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="Task.css">
  </head>

  <body onload="setThemeFromCookie()" id="body">
    <div class="header">
      <h1 id="title">Task management application</h1>
    </div>
    <div class="wrap" style="margin-top:20px">
      <div class="account">
      <p style="text-align: center"><font size="4"><b>My Account:</b></font></p>
      <?php
        $username = mysqli_real_escape_string($conn, $username);
        $user_id = mysqli_real_escape_string($conn, $user_id);
        $sql = "SELECT * FROM register WHERE username = '$username' AND id = '$user_id'";
        if ($result = $conn->query($sql)) {
          while ($row = $result->fetch_assoc()) {
            // output data of each row
            echo "<div class='wrap'>
                    <p id='assas'><b>Username:</b>" . $row["username"] . "  </p>
                    <button name='username' onclick=\"document.getElementById('change_username').style.display='block'; return false\">Edit</button>
                  </div>";
            echo "<div class='wrap'>
                    <p><b>Email:</b> " . $row["email"] . " </p>
                    <button name='email' onclick=\"document.getElementById('change_email').style.display='block'; return false\">Edit</button>
                  </div>";
            echo "<div class='wrap'>
                    <p><b>Password:</b>***************</p>
                    <button name='password' onclick=\"document.getElementById('change_password').style.display='block'; return false\">Edit</button>
                  </div>";

          }
        }
      ?>
      <div class="wrap theme">
        <p><b>Theme:</b></p>
        <button type="button" name="dark_light" onclick="toggleDarkLight()" title="Toggle dark/light mode">🌛</button>
     </div>
      <div>
        <button class='log_out' name='log_out' style="margin-top:20px" onclick='return false'>Log out</button>
      </div>
      </div>

      <div class="task">
        <table align="center" id="tableau">
          <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Due date</th>
            <th>State</th>
            <th>Actions</th>
          </tr>
          <?php
            $username = mysqli_real_escape_string($conn, $username);
            $sql = "SELECT * FROM tasks WHERE username = '$username'";
            if ($result = $conn->query($sql)) {
              while ($row = $result->fetch_assoc()) {
                // output data of each row
                  echo "<tr>";
                  echo "<td style='text-align:center'>" . $row["task_name"] . " </td>";
                  echo "<td style='text-align:center'>" . $row["description"] . " </td>";
                  echo "<td style='text-align:center'>" . $row["due_date"]. "</td>";
                  echo "<td style='text-align: center'>
                            <button name='task_state' class='task_state' data-id ='" . $row['task_id'] ."' onclick='return false'> ".$row['task_state']."</button>
                         </td>";
                  echo "<td class='buttons' style='text-align: center'>
                          <div>
                            <button name='edit' class='edit' data-id = '" .$row['task_id']."' onclick='return false'>Edit</button>
                            <button name='remove' class='remove' data-id='" . $row['task_id'] ."' onclick='return false'>Remove</button>
                          </div>
                        </td>";
                  echo "</tr>";
                }
                echo "</table>";
                $result->free();
            }
            else{
              echo "0 results";
            }
          ?>
        </table>
      </div>

    </div>

    <div class="modal" id="add_tasks">
      <form class="modal-content"method="post" action="task_controller.php">
        <div class="container">
          <h2 class="mod">New task</h2>
            <div>
                <label for="task_name"><b>Name:</b></label>
                <textarea placeholder="Name" name="task_name" required></textarea>
            </div>
          <div>
            <label for="description" name="descrip"><b>Description:</b></label>
            <textarea placeholder="Brief description..." name="description" required></textarea>
          </div>
          <br>
          <div class="wrap">
            <div class="subwrap">
              <label for="datepick" name="datelabel"><b>Due date:</b></label>
              <input type="datetime-local" name="date">
            </div>
          </div>
          <div class="wrap">
            <div style="margin-top: 10px">
              <button type="submit" name="new">Add</button>
            </div>
          </div>
        </div>
      </form>
    </div>

    <div class="modal" id="edit_tasks">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="change_username">
      <form class="modal-content"method="post" action="task_controller.php">
        <div class="container">
          <h2 class="mod">Enter new username:</h2>
          <input type="text" placeholder="New username:" name="new_username" required>
          <button type="submit" name="changeUser">Change</button>
        </div>
      </form>
    </div>

    <div class="modal" id="change_email">
      <form class="modal-content"method="post" action="task_controller.php">
        <div class="container">
          <h2 class="mod">Enter new email:</h2>
          <input type="text" placeholder="New email:" name="new_email" required>
          <button type="submit" name="changeEmail">Change</button>
        </div>
      </form>
    </div>

    <div class="modal" id="change_password">
      <form class="modal-content"method="post" action="task_controller.php">
        <div class="container">
          <h2 class="mod">Enter new password:</h2>
          <input type="password" placeholder="New password:" name="new_password" required>
          <button type="submit" name="changePassword">Change</button>
        </div>
      </form>
    </div>

    <div class="modal" id="import_tasks">
        <form class="modal-content"method="post" action="task_controller.php">
            <div class="container">
                <h2 class="mod">Select the tasks to import:</h2>
                <form method="post" action="task_controller.php">
                <?php
                //we are first getting the name, date and id of the task
                    $xml = simplexml_load_file('http://students.emps.ex.ac.uk/dm656/tasks.php');
                    foreach ($xml->children() as $row) {
                        $id = $row->id;
                        $name = $row->name;
                        $due_date = $row->due;

                        $description_xml = simplexml_load_file('http://students.emps.ex.ac.uk/dm656/task.php/' . $id);
                        $description = $description_xml->description;


                        echo "<div class='wrap'>
                                <input type='checkbox' value=' $name, $description, $due_date, $id' name='check[]'>
                                <p>$name</p>
                                <p class = 'description'>$description</p>
                                <p>$due_date</p>
                              </div>";
                    }
                ?>
                    <div class="wrap">
                        <button type="submit" name="import_selected" onclick="Confirm()">Import</button>
                    </div>
                </form>
            </div>
        </form>
    </div>

    <div class="wrap">
        <button name="add" onclick="document.getElementById('add_tasks').style.display='block'">Add Tasks</button>
        <button name="import" onclick="document.getElementById('import_tasks').style.display='block'">Import Tasks</button>
    </div>

  </body>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script>

    // Get the modal
    var modal = document.getElementById('add_tasks');
    var modal1 = document.getElementById('edit_tasks');
    var modal2 = document.getElementById('change_username');
    var modal3 = document.getElementById('change_email');
    var modal4 = document.getElementById('change_password');
    var modal5 = document.getElementById('import_tasks');
    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
        if (event.target == modal1) {
            modal1.style.display = "none";
        }
        if (event.target == modal2) {
            modal2.style.display = "none";
        }
        if (event.target == modal3) {
            modal3.style.display = "none";
        }
        if (event.target == modal4) {
            modal4.style.display = "none";
        }
        if (event.target == modal5) {
            modal5.style.display = "none";
        }
      }

      function toggleDarkLight() {
        var body = document.getElementById('body')
        var currentClass = body.className
        var newClass = body.className == 'dark-mode' ? 'light-mode' : 'dark-mode'
        body.className = newClass

        document.cookie = 'theme=' + (newClass == 'light-mode' ? 'light' : 'dark')
        console.log('Cookies are now: ' + document.cookie)
      }

      function isDarkThemeSelected() {
        return document.cookie.match(/theme=dark/i) != null
      }

      function setThemeFromCookie() {
        var body_theme = isDarkThemeSelected() ? 'dark-mode' : 'light-mode'
        $('body').addClass(body_theme);
      }

      function Confirm(){
        confirm("Are you sure?");
      }

    $(document).ready(function(){
        $('.edit').click(function(){
            var task_id = $(this).data('id');
            // AJAX request
            $.ajax({
                url: 'edit_values.php',
                type: 'post',
                data: {task_id: task_id},
                success: function(response){
                    // Add response in Modal body
                    $('.modal-body').html(response);

                    // Display Modal
                    $('#edit_tasks').modal('show');
                }
            });
        });
        $('.task_state').click(function () {
            var task_id = $(this).data('id');
            // ajax request
            $.ajax({
                url: 'change_state.php',
                type: 'post',
                data: {task_id: task_id},
                success: function() {
                    location.reload();
                }
            });
        });
        $('.remove').click(function () {
            var task_id = $(this).data('id');
            console.log(task_id)
            // ajax request
            $.ajax({
                url: 'task_controller.php',
                type: 'post',
                data: {remove: task_id},
                success: function() {
                    location.reload();
                }
            });
        });
        $('.log_out').click(function () {
            console.log("a")
            $.ajax({
                url: 'task_controller.php',
                type: 'post',
                data: {log_out: "log_out"},
                success: function() {
                    window.top.location='http://students.emps.ex.ac.uk/arjd201/SignUp.php'
                }
            });
        });
    });


  </script>

</html>
