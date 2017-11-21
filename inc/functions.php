<?php
/**
 * добавление новой группы в БД
 * 
 * @param string $name 		   Имя группы
 * @param string $start_date   День начала занятий. 
 * 							   Задается в формате 2017-11-31
 * @param string $lessons_time Время начала занятий. Задаетя в формате 15:00
 * @param string $lessons_days В какие дни будут проходить уроки. 
 *  						   Формат строки +-+-+--. Каждая позиция символа 
 *                             означает соответствующий день недели.
 * @return bool|int Возвращает ID добавленной группы, либо false 
 * 							   в случае ошибки
*/
function add_group( $name, $start_date, $lessons_time, $lessons_days ) {
	global $link;
	$q = mysqli_query( $link, "INSERT INTO groups VALUES ('$name', $start_date, 0, $lessons_time, $lessons_days)" );
	if( $q ) {
		return mysqli_insert_id( $link );
	}
	else {
		return false;
	}
}

/**
 * добавление студента в БД
 * 
 * @param string $firstname Имя студента 
 * @param string $lastname  Фамилия студента
 * @param int    $group_id  ID группы, в которой учится студент
 * @return bool|int Возвращает ID добавленного студента, либо false в 
 * 				    случае ошибки
 */
function add_student( $firstname, $lastname, $group_id ) {
	global $link;
	$q = mysqli_query( $link, "INSERT INTO groups ( firstname, lastname, group_id ) VALUES ( '$firstname', '$lastname', $group_id )" );
	if( $q ) {
		return mysqli_insert_id( $link );
	}
	else {
		return false;
	}
}

/**
 * Изменение e-mail адреса студента
 * 
 * @param int    $student_id ID студента
 * @param string $email   Адрес эл.почты
 * @return bool Возвращает true в случае успеха и false в случае ошибки
 */
function set_email( $student_id, $email ) {
	global $link;
	$q = mysqli_query( $link, "UPDATE students SET `email` = '$email' WHERE `id` = $student_id" );
	if( $q ) {
		return true;
	}
	else {
		return false;
	}
}

/**
 * Изменение пароля студента
 * 
 * @param int    $student_id 
 * @param string $password
 * @return bool
 */
function set_password( $student_id, $password ) {
	global $link;
	$q = mysqli_query( $link, "UPDATE students SET `password` = '$password' WHERE `id` = $student_id" );
	if( $q ) {
		return true;
	}
	return false;
} 

/**
 * Изменение номера студента
 * 
 * @param int    $student_id 
 * @param string $phone
 * @return bool
 */
function set_phone( $student_id, $phone ) {
	global $link;
	$q = mysqli_query( $link, "UPDATE students SET `phone` = '$phone' WHERE `id` = $student_id" );
	if( $q ) {
		return true;
	}
	else {
		return false;
	}
}

/**
 * Добавление описания
 * 
 * @param int    $student_id
 * @param string $about 
 * @return bool
 */
function set_about( $student_id, $about ) {
	global $link;
	$q = mysqli_query( $link, "UPDATE students SET `about` = '$about' WHERE `id` = $student_id" );
	if( $q ) {
		return true;
	}
	else {
		return false;
	}
}

/**
 * Добавление заметки
 * 
 * @param int    $student_id
 * @param string $note 
 * @return bool
 */
function set_note( $student_id, $note ) {
	global $link;
	$q = mysqli_query( $link, "UPDATE students SET `note` = '$note' WHERE `id` = $student_id" );
	if( $q ) {
		return true;
	}
	else {
		return false;
	}
}

/**
 * Активация аккаунта
 * 
 * @param int $id ID аккаунта, который активируем
 * @return bool 
 */
function activate_account( $id ) {
	global $link;
	$q = mysqli_connect( $link, "UPDATE students SET `activated` = 1 WHERE `id` = $id" );
	if( $q ) {
		return true;
	}
	else {		
		return false;
	}
}

/**
 * Установка переменной сессии
 * 
 * @param string $name  Имя переменной 
 * @param string $value Значение переменной
 */
function set_session_var( $name, $value ) {
	$_SESSION[$name] = $value;
}

/**
 * Проверка существования пользователя с указанным e-mail
 * 
 * @param string $mail E-mail для проверки
 * 
 * @return bool
 */
function user_exists_by_email( $mail ) {
	global $link;
	$q = mysqli_query( $link, "SELECT count(*) FROM students WHERE `email` = '$mail'" );
	$res = mysqli_fetch_row( $q )[0];
	return (bool) $res;
}

/**
 * Проверка существования пользователя по ID
 * 
 * @param int $id 
 * 
 * @return bool
 */
function users_exists_by_id( $id ) {
	global $link;
	$q = mysqli_query( $link, "SELECT count(*) FROM students WHERE `id` = $id" );
	$res = mysqli_fetch_row( $q )[0];
	return (bool) $res;
}

/**
 * (временная) Авторизация пользователя. Если логин и пароль 
 * переданы в функцию верно, то необходимо установить
 * для пользователя переменную сессии auth равным единице.
 * 
 * @param string $mail     E-mail пользователя
 * @param string $password Пароль пользователя
 * 
 * @return bool Прошли ли авторизация успешно
 */
function authorize( $mail, $password ) {
	global $link;
	$q = mysqli_query( $link, "SELECT COUNT(*) FROM students WHERE `email` = '$mail' AND `password` = '$password'" );
	$res = mysqli_fetch_row( $q )[0];
	if( $res ) {
		set_session_var("auth", 1);
		return true;
	}
	return false;
}

/**
 * Авторизован ли пользователь 
 * 
 * @return bool 
 */
function is_auth( ) {
	if( $_SESSION['auth'] ) {
		return true;
	}
	return false;
}

/**
 * Функция для подсчета количества студентов
 * 
 * @param int $group_id (optional) Если этот параметр передан, то 
 *                                 подсчитываем только для одной группы
 * 
 * @return int Количество студентов
 */
function count_users( $group_id = false ) {
	global $link;
	if( $group_id !== false ) {
		$q = mysqli_query( $link, "SELECT COUNT(*) FROM `students` WHERE `group_id` = $group_id" );
		return mysqli_fetch_row( $q )[0];
	}
	else {
		$q = mysqli_query( $link, "SELECT COUNT(*) FROM `students`" );
		return mysqli_fetch_row( $q )[0];
	}

}

/**
 * (временная) Выход из аккаунта
 */
function logout( ) {
	session_destroy();
}

/**
 * Количество пройденных уроков у указанной группы
 * 
 * @param int $group_id 
 * 
 * @return int
 */
function complete_lessons( $group_id ) {
	global $link;
	$q = mysqli_query( $link, "SELECT `completed_lessons` FROM `groups` WHERE `id` = $group_id" );
	return mysqli_fetch_row( $q )[0];
}

/**
 * Извлечение указанного поля из БД
 * 
 * @param int    $student_id Для какого студента извлекаем
 * @param string $column     Какое поле извлекаем
 * 
 * @return string
 */
function userinfo( $student_id, $column ) {
	gloabl $link;
	$q = mysqli_query( $link, "SELECT '$column' FROM `students` WHERE `id` = $student_id" );
	return mysqli_fetch_row( $q )[0];
}
