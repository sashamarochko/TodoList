<?php
session_start();
include 'connectDB.php';
$id = $_POST['id'];
$id_t = $_POST['id_t'];
$func = $_POST['func'];
$user_id = $_SESSION['id'];
$text = trim($_POST['text']);
switch ($func) {
	case 'addTodo':
		$query = "INSERT INTO projects (name, user_id) VALUES ('".$text."', ".$user_id.")";
		mysql_query($query);
		$id = mysql_insert_id();
		$res = '<div class="wrap-todo-head"><div class="todo-head"><div class="todo-task">'.$text.'</div><div class="edit-todo-text"><input type="text"></div><div class="edit-todo"><img src="img/pencil.png" onclick="editTodo(this)" alt=""> <img src="img/bin.png" onclick="removeTodo(this, '.$id.')" alt=""></div><div class="save-edit-todo" onclick="saveEditTodo(this, '.$id.')">Save</div></div></div><div class="add-task"><div class="add-plus"></div><div class="add-input"><input type="text"></div><div class="add-btn" onclick="addTask(this, '.$id.')">Add Task</div></div><div class="tasks"><table><tbody></tbody></table></div>';
		$js = array("status"=>true, "res"=>$res);
		echo json_encode($js);
	break;
	case 'addTask':
		$query = "SELECT rating FROM tasks WHERE project_id = ".$id." ORDER BY rating DESC LIMIT 1";
		$r = mysql_fetch_array(mysql_query($query));
		$rate = $r[0] + 1;
		$query = "INSERT INTO tasks (project_id, name, rating, user_id, status) VALUES ('".$id."', '".$text."', ".$rate.", ".$user_id.", 'todo')";
		mysql_query($query);
		$id_t = mysql_insert_id();
		$res = '<td><div class="status todo" onclick="status(this, '.$id_t.');">todo</div></td><td class="check-task" onclick="taskCheck(this)"><input type="checkbox" onclick="falseCheck(this)" value="'.$id_t.'"></td><td class="task" onclick="taskCheck(this)"><div class="task-td">'.$text.'</div><div class="edit-task-in" onclick="falseCheck(this)"><input type="text"></div></td><td class="edit-task fa"><div class="edit-task-b"><div class="rate"><img src="img/arrow-up.png" onclick="rateUp(this, '.$id.', '.$id_t.')" alt=""><img src="img/arrow-down.png" onclick="rateDown(this, '.$id.', '.$id_t.')" alt=""></div> <img src="img/pencil.png" class="pencil" onclick="editTask(this)" alt=""> <img src="img/bin.png" onclick="modal(this, '.$id.')" alt=""></div><div class="save-edit-task" onclick="saveEditTask(this, '.$id_t.')">Save</div></td>';
		$js = array("status"=>true, "res"=>$res);
		echo json_encode($js);
	break;
	case 'removeTodo':
		$query = "DELETE FROM projects WHERE id = ".$id." AND user_id = ".$user_id;
		mysql_query($query);
		$query = "DELETE FROM tasks WHERE project_id = ".$id." AND user_id = ".$user_id;
		mysql_query($query);
		$js = array("status"=>true, "res"=>$res);
		echo json_encode($js);
	break;
	case 'removeTask':
		$id = explode(',', $id);
		for($i = 0; $i < count($id); $i++) {
			$query = "DELETE FROM tasks WHERE id = ".$id[$i]." AND user_id = ".$user_id;
			mysql_query($query);
		}
		$js = array("status"=>true);
		echo json_encode($js);
	break;
	case 'saveEditTask':
		$query = "UPDATE tasks SET name = '".$text."' WHERE id = ".$id." AND user_id = ".$user_id;
		mysql_query($query);
		$js = array("status"=>true, "res"=>$text);
		echo json_encode($js);
	break;
	case 'saveEditTodo':
		$query = "UPDATE projects SET name = '".$text."' WHERE id = ".$id." AND user_id = ".$user_id;
		mysql_query($query);
		$js = array("status"=>true, "res"=>$text);
		echo json_encode($js);
	break;
	case 'rateUp':
	case 'rateDown':
		$query = "SELECT COUNT(*) FROM tasks WHERE project_id = ".$id." AND user_id = ".$user_id;
		$r = mysql_fetch_array(mysql_query($query));
		$count = $r[0];
		$query = "SELECT COUNT(*) FROM tasks WHERE project_id = ".$id." AND id=".$id_t." AND user_id = ".$user_id;
		$r = mysql_fetch_array(mysql_query($query));
		$s = $r[0];
		if ($count > 1 && $s) {
			$query = "SELECT rating FROM tasks WHERE id = ".$id_t;
			$r = mysql_fetch_array(mysql_query($query));
			$rate = $r[0];
			$query = "SELECT id, rating FROM tasks WHERE rating ".($func==='rateUp'? '<':'>').$rate." AND user_id = ".$user_id." ORDER BY rating ".($func==='rateUp'? 'DESC':'ASC')." LIMIT 1";
			$r = mysql_fetch_array(mysql_query($query));
			$rate_r = $r['rating'];
			$id_r = $r['id'];
			$query = "UPDATE tasks SET rating = ".$rate." WHERE id = ".$id_r." AND user_id = ".$user_id;
			mysql_query($query);
			$query = "UPDATE tasks SET rating = ".$rate_r." WHERE id = ".$id_t." AND user_id = ".$user_id;
			mysql_query($query);
		}
		$js = array("status"=>true, "res"=>$text);
		echo json_encode($js);
	break;
	case 'status':
		$query = "UPDATE tasks SET status = (status + 1) % 3 WHERE id = ".$id." AND user_id = ".$user_id;
		mysql_query($query);
		$js = array("status"=>true, "res"=>$text);
		echo json_encode($js);
	break;

}
?>