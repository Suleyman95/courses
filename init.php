<?php

$link = mysqli_connect("localhost", "root", "0202", "base");
if(!$link) { 
  // если была ошибка выводим ее описание
  echo mysqli_connect_error($link);
}
else {
  // устанавливаем кодировку
  mysqli_set_charset($link, "utf8");
}

session_start();

?>