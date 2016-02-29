function editTodo(e) {
  e = e.parentNode.parentNode;
  $(e).addClass('open-edit-todo');
  e.getElementsByTagName('input')[0].value = e.getElementsByClassName('todo-task')[0].innerHTML;
}
function saveEditTodo(e, id){
  var text = e.parentNode.getElementsByTagName('input')[0].value;
  var body = 'func='+encodeURIComponent('saveEditTodo')+'&text='+encodeURIComponent(text)+'&id='+encodeURIComponent(id);
  var func = function(response, e) {
    e = e.parentNode;
    $(e).removeClass('open-edit-todo');
    e.getElementsByClassName('todo-task')[0].innerHTML = response;
  }
  server(body, func, e);
}
function editTask(e) {
  e = e.parentNode.parentNode;
  $(e).addClass('open-edit-task');
  $(e).removeClass('fa');
  e = e.parentNode;
  e.getElementsByClassName('edit-task-in')[0].getElementsByTagName('input')[0].value = e.getElementsByClassName('task')[0].getElementsByClassName('task-td')[0].innerHTML;
  e = e.getElementsByClassName('task')[0];
  $(e).addClass('open-edit-task');
}
function saveEditTask(e, id) {
    e = e.parentNode;
    var text = e.parentNode.getElementsByClassName('edit-task-in')[0].getElementsByTagName('input')[0].value;
    var body = 'func='+encodeURIComponent('saveEditTask')+'&text='+encodeURIComponent(text)+'&id='+encodeURIComponent(id);
    var func = function(response, e) {
      $(e).removeClass('open-edit-task');
      $(e).addClass('fa');
      e = e.parentNode;
      e.getElementsByClassName('task')[0].getElementsByClassName('task-td')[0].innerHTML = response;
      e = e.getElementsByClassName('task')[0];
      $(e).removeClass('open-edit-task');
    }
    server(body, func, e);
}
function addTask(e, id) {
  e = e.parentNode;
  text = e.getElementsByTagName('input')[0];
  if (!text.value) return;
  var body = 'func='+encodeURIComponent('addTask')+'&text='+encodeURIComponent(text.value)+'&id='+encodeURIComponent(id);
  var func = function(response, e) {
        var newTr = document.createElement('tr');
        newTr.innerHTML = response;
        e.nextElementSibling.getElementsByTagName('tbody')[0].appendChild(newTr);
        text.value = '';
    }
  server(body, func, e);
}
function addTodoList(text){
  var body = 'func='+encodeURIComponent('addTodo')+'&text='+encodeURIComponent(text);
  func = function(response){
        var todo = document.createElement('div');
        $(todo).addClass('todo-list');
        document.getElementById('todo-lists').appendChild(todo);
        todo.innerHTML = response;
  }
  server(body, func);
}
function rateUp(e, id, id_t) {
  var body = 'func='+encodeURIComponent('rateUp')+'&id='+encodeURIComponent(id)+'&id_t='+encodeURIComponent(id_t);
  var func = function(response, e) {
    e = e.parentNode.parentNode.parentNode.parentNode;
    if(e != e.parentNode.firstElementChild) {
      e.parentNode.insertBefore(e, e.previousElementSibling);
    }
  }
  server(body, func, e);
}
function rateDown(e, id, id_t) {
  var body = 'func='+encodeURIComponent('rateDown')+'&id='+encodeURIComponent(id)+'&id_t='+encodeURIComponent(id_t);
  var func = function(response, e) {
    e = e.parentNode.parentNode.parentNode.parentNode;
    if(e != e.parentNode.lastElementChild) {
      e.parentNode.insertBefore(e.nextElementSibling, e);
    }
  }
  server(body, func, e);
}
function taskCheck(e) {
  e = e.parentNode;
  var check = !e.getElementsByTagName('input')[0].checked;
  e.getElementsByTagName('input')[0].checked = check;
  e.style.background = check? '#eee':'#fff';
}
function falseCheck(e) {
  e = e.parentNode.parentNode.getElementsByTagName('input')[0];
  e.checked = !e.checked;
}
var E;
function modal(e, p){
  E = e;
  document.getElementById('mod').innerHTML = '<div>Are you sure?</div><div class="clear"></div><div onclick="m_ok()" class="cancel">Cancel</div><div onclick="del('+p+');"; m_ok();" class="ok">OK</div>';
  document.getElementById('modal').style.display = 'block';
}
function m_ok(){
  document.getElementById('modal').style.display = 'none';
}
function del(p){
  removeTask(E, p);
  document.getElementById('modal').style.display = 'none';
}
function removeTask(e, id){
  var l;
  el = document.getElementsByTagName('input');
  for(i = 0; i < el.length; i++) {
      if (el[i].type === 'checkbox' && el[i].checked) {
        id += ','+el[i].value;
        l = el[i].parentNode.parentNode;
        l.parentNode.removeChild(l);
        i--;
      }
    }
  var func = function(response, e) {
    e = e.parentNode.parentNode.parentNode;
    e.parentNode.removeChild(e);
    var l;
    e = document.getElementsByTagName('input');
    for(i = 0; i < e.length; i++) {
      if (e[i].type === 'checkbox' && e[i].checked) {
        l = e[i].parentNode.parentNode;
        l.parentNode.removeChild(l);
        i--;
      }
    }
  }
  var body = 'func='+encodeURIComponent('removeTask')+'&id='+encodeURIComponent(id);
  server(body, func, e);
}
function removeTodo(e, id){
  var body = 'func='+encodeURIComponent('removeTodo')+'&id='+encodeURIComponent(id);
  var func = function(response, e) {
      e = e.parentNode.parentNode.parentNode.parentNode;
      e.parentNode.removeChild(e);
  }
  server(body, func, e);
}
function server(body, func, e) {
  var xhr = new XMLHttpRequest();
  xhr.open('POST', 'server.php', true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.send(body);
  xhr.onreadystatechange = function() {
    if(xhr.readyState != 4) return;
    if (xhr.status == 200 && xhr.responseText) {
        var response = JSON.parse(xhr.responseText);
          func(response.res, e);
    }
  }
}
function login(s) {
  if (s === 'login') {
    var pwd = document.getElementsByName('password')[0].value;
    var user = document.getElementsByName('username')[0].value;
  } else if (s === 'exit') {
    m_ok();
  }
  var xhr = new XMLHttpRequest();
  xhr.open('POST', 'todo.php', true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  var body = s+'='+encodeURIComponent(true)+'&user='+encodeURIComponent(user)+'&pwd='+encodeURIComponent(pwd);
  xhr.send(body);
  xhr.onreadystatechange = function() {
    if(xhr.readyState != 4) return;
    if (xhr.status == 200) {
        var response = xhr.responseText;
        document.getElementById('main').innerHTML = response;
    }
  }
}
function reg() {
  var minLength = 4;
  var pwd = document.getElementsByName('password')[0].value;
  var pwdr = document.getElementsByName('password_r')[0].value;
  var user = document.getElementsByName('username')[0].value;
  if (pwd !== pwdr) {
    document.getElementById('msg').innerHTML = 'Passwords do not match!';
    return;
  } else if(pwd.length < minLength || pwdr.length < minLength){
    document.getElementById('msg').innerHTML = 'Min 4 characters!';
  }
  if (!user) {
    document.getElementById('msgu').innerHTML = 'Empty!';
    return;
  } else if (user.length < minLength) {
    document.getElementById('msgu').innerHTML = 'Min 4 characters!';
    return;
  } else {
    document.getElementById('msgu').innerHTML = '';
  }
  var xhr = new XMLHttpRequest();
  xhr.open('POST', 'reg_valid.php', true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  var body = 'user='+encodeURIComponent(user);
  xhr.send(body);
  xhr.onreadystatechange = function() {
    if(xhr.readyState != 4) return;
    if (xhr.status == 200) {
      if (!xhr.responseText) {
        register(user, pwd, pwdr);
      } else {
        document.getElementById('msgu').innerHTML = 'User already exists!';
      }
    }
  }
}
function register(user, pwd, pwdr) {
  var xhr = new XMLHttpRequest();
  xhr.open('POST', 'todo.php', true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  var body = 'register='+encodeURIComponent(true)+'&user='+encodeURIComponent(user)+'&pwd='+encodeURIComponent(pwd)+'&pwdr='+encodeURIComponent(pwdr);
  xhr.send(body);
  xhr.onreadystatechange = function() {
    if(xhr.readyState != 4) return;
    if (xhr.status == 200) {
      if(xhr.responseText) {
        document.getElementById('main').innerHTML = xhr.responseText;
      } else {
        document.getElementById('msgf').innerHTML = 'Failed to register!';
      }
    }
  }
}
function valid(){
  var pwd = document.getElementsByName('password')[0];
  var pwdr = document.getElementsByName('password_r')[0];
  if (pwd.value !== pwdr.value) {
    pwdr.style.outline = '1px solid red';
    document.getElementById('msg').innerHTML = 'Passwords do not match!';
  } else {
    pwdr.style.outline = 'none';
    document.getElementById('msg').innerHTML = '';
  }
}
function status(e, id) {
  var body = 'func='+encodeURIComponent('status')+'&id='+encodeURIComponent(id);
  var func = function(response, e) {
    var text = {
      "todo" : 0,
      "doing" : 1,
      "done" : 2};
    var i;
    for(key in text) {
      if($(e).hasClass(key)) {
        i = (text[key] + 1) % 3;     
        $(e).removeClass(key);
      }
    }
    var Class;
    for(key in text) {
      if(text[key] == i) Class = key;     
    }
    $(e).addClass(Class);
    e.innerHTML = Class;
  }
  server(body, func, e);
}