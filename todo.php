<?php
error_reporting(0);
header('Content-Type: text/html; charset=utf-8');
session_start();
  include 'connectDB.php';
  $user = mysql_escape_string($_POST['user']);
  $pwd = $_POST['pwd'];
  $pwdr = $_POST['pwdr'];
  if ($_POST['exit']) {
    session_destroy();
    session_start();
  }
  if ($_POST['login']) {
    $query = "SELECT * FROM users WHERE name = '".$user."' AND pass = '".$pwd."' LIMIT 1";
    $result = mysql_query($query);
    while($r = mysql_fetch_array($result)) {
     $_SESSION['id'] = $r['id'];
     $_SESSION['name'] = $r['name'];
    }
  if(empty($_SESSION['id'])) $er = true;
  }
?>
<div id="todo-lists">
<?php
if($_POST['register']) {
  $query = "SELECT * FROM users WHERE name = '".$user."' LIMIT 1";
  $result = mysql_query($query);
  while($r = mysql_fetch_array($result)) {
     $p = true;
  }
  if(!$p && $pwd === $pwdr && strlen($pwd) > 3 && strlen($user) > 3 && strlen($pwd) < 256 && strlen($user) < 256){
    $sql = "INSERT INTO users (name, pass) VALUES ('".$user."', '".$pwd."')";
    mysql_query($sql);
?>
<div id="login">
    <div class="login">Log in</div>
    <span style="color:green;">You have successfully signed up!</span><br>
    <span>Username</span>
    <input type="text" name="username">
    <span>Password</span>
    <input type="password" name="password">
    <div class="btns">
      <a href="#" class="reg left" onclick="login('r');">Register</a><a href="#" class="log big right" onclick="login('login');">Login</a>
      <div class="clear"></div>
    </div>
  </div>

<?php
  } else {
?>
<div id="login">
    <div class="login">Registration</div>
    <div id="msgf">ERROR!</div>
    <span>Username</span>
    <input type="text" name="username">
    <div id="msgu"></div>
    <span>Password</span>
    <input type="password" name="password" onkeyup="valid()">
    <span>Repeat Password</span>
    <input type="password" name="password_r" onkeyup="valid()">
    <div id="msg"></div>
    <div class="btns">
      <a href="#" class="log left" onclick="login('l');">Login</a><a href="#" class="reg big right" onclick="reg()">Register</a>
      <div class="clear"></div>
    </div>
  </div>
<?php    
  }
  exit();
}
if($_POST['r']) {

?>
  <div id="login">
    <div class="login">Registration</div>
    <div id="msgf"></div>
    <span>Username</span>
    <input type="text" name="username">
    <div id="msgu"></div>
    <span>Password</span>
    <input type="password" name="password" onkeyup="valid()">
    <span>Repeat Password</span>
    <input type="password" name="password_r" onkeyup="valid()">
    <div id="msg"></div>
    <div class="btns">
      <a href="#" class="log left" onclick="login('l');">Login</a><a href="#" class="reg big right" onclick="reg()">Register</a>
      <div class="clear"></div>
    </div>
  </div>
<?php
  exit();
}
if ($_POST['l'] || empty($_SESSION['id'])) {
?>
  <div id="login">
    <div class="login">Log in</div>
<?php if ($er) echo '<span style="color:red;">Wrong login or password!</span><br>';?>   
    <span>Username</span>
    <input type="text" name="username">
    <span>Password</span>
    <input type="password" name="password">
    <div class="btns">
      <a href="#" class="reg left" onclick="login('r');">Register</a><a href="#" class="log big right" onclick="login('login');">Login</a>
      <div class="clear"></div>
    </div>
  </div>
<?php
  exit();
}
?>
<div id="header">
  <div id="name"><?php echo $_SESSION['name']; ?></div>
  <div id="exit" onclick="login('exit')">exit</div>
</div>
<?php
    $user_id = $_SESSION['id'];
    $query = "SELECT COUNT(*) FROM users WHERE id = ".$user_id;
    $result = mysql_query($query);
    $r = mysql_fetch_array($result);
    if (!$r[0]) session_destroy();
    $query = "SELECT * FROM projects WHERE user_id = ".$user_id." ORDER BY id";
    $result = mysql_query($query);
    while($r = mysql_fetch_array($result)) {
    $id = $r['id'];
?>
<div class="todo-list">
<div class="wrap-todo-head">
  <div class="todo-head">
    <div class="todo-task"><?php echo $r['name'];?></div>

    <div class="edit-todo-text">
      <input type="text">
    </div>

    <div class="edit-todo">
      <img src="img/pencil.png" onclick="editTodo(this)" alt="">
      <img src="img/bin.png" onclick="removeTodo(this, <?php echo $id; ?>)" alt="">
    </div>
    <div class="save-edit-todo" onclick="saveEditTodo(this, <?php echo $id; ?>)">Save</div>
  </div>
</div>
    <div class="add-task">
      <div class="add-plus"></div>
      <div class="add-input"><input type="text"></div>
      <div class="add-btn" onclick="addTask(this, <?php echo $id; ?>)">Add Task</div>
  </div>
  <div class="tasks">
    <table>
      <tbody>
      <?php
        $class = array("todo", "doing", "done");
        $q = "SELECT * FROM tasks WHERE project_id='".$id."' AND user_id = ".$user_id." ORDER BY rating";
        $res = mysql_query($q);
        while($task = mysql_fetch_array($res)) {    
      ?>
      <tr>
      <td><div class="status <?php echo $class[$task['status']]; ?>" onclick="status(this, <?php echo $task['id']; ?>);"><?php echo $class[$task['status']]; ?></div></td>
        <td class="check-task" onclick="taskCheck(this)"><input type="checkbox" onclick="falseCheck(this)" value="<?php echo $task['id']; ?>"></td>
        <td class="task" onclick="taskCheck(this)">
            <div class="task-td"><?php echo $task['name']; ?></div>
            <div class="edit-task-in" onclick="falseCheck(this)"><input type="text"></div>
        </td>
        <td class="edit-task fa">
          <div class="edit-task-b">
            <div class="rate"><img src="img/arrow-up.png" onclick="rateUp(this, <?php echo $id; ?>, <?php echo $task['id']; ?>)" alt=""><img src="img/arrow-down.png" onclick="rateDown(this, <?php echo $id; ?>, <?php echo $task['id']; ?>)" alt=""></div>
            <img src="img/pencil.png" class="pencil" onclick="editTask(this, <?php echo $task['id']; ?>)" alt=""> <img src="img/bin.png" onclick="modal(this, '<?php echo $task['id']; ?>')" alt="">
          </div>
          <div class="save-edit-task" onclick="saveEditTask(this, <?php echo $task['id']; ?>)">Save</div>
        </td>
      </tr>
      <?php
        }
      ?>
    </tbody>
    </table>
  </div>
  </div>
<?php
  }
?>
</div>
<div style="text-align: center;">
  <div class="add-todo-btn" onclick="addTodoList('New Todo')">Add TODO List</div>
</div>
