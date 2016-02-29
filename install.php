<?php
	include 'connectDB.php';

	$sql = 'CREATE DATABASE todo_list';
	mysql_query($sql);
	mysql_select_db('todo_list');
	$sql = 'CREATE TABLE tasks (id INT NOT NULL auto_increment, name VARCHAR(255), status TINYINT, project_id INT, rating INT, user_id INT, PRIMARY KEY(id), INDEX (project_id), INDEX (user_id)) ENGINE=MyISAM';
	mysql_query($sql);
	$sql = 'CREATE TABLE projects (id INT NOT NULL auto_increment, name VARCHAR(255), user_id INT, PRIMARY KEY(id), INDEX (user_id)) ENGINE=MyISAM';
	mysql_query($sql);
	$sql = 'CREATE TABLE users (id INT NOT NULL auto_increment, name VARCHAR(255), pass VARCHAR(255), PRIMARY KEY(id)) ENGINE=MyISAM';
	mysql_query($sql);
	echo 'OK!';
?>