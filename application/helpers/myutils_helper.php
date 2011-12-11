<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// datetime --------------------------------------------------------------------


/**
 * Date au format SQL Datetime depuis un timestamp unix
 * @param unix_time: timestamp unix (NULL pour date courante)
 */
function date_db_format($unix_time = NULL)
{
	date_default_timezone_set('Europe/Paris');
	return strftime('%Y-%m-%d %H:%M:%S', $unix_time == NULL ? time() : $unix_time);
}

/**
 * Generate well-formated dates from datetime value (string)
 * @return	the string containing the formatted date
 */
function time_to_str($str_datetime)
{
	$d = new DateTime($str_datetime);
	return $str_datetime ? $d->format('d/m/Y') : '-';
}

function time_to_str_short($str_datetime)
{
	$d = new DateTime($str_datetime);
	return $str_datetime ? $d->format('d/m/y') : '-';
}

function time_to_str_long($str_datetime)
{
	$d = new DateTime($str_datetime);
	return $str_datetime ? $d->format('d/m/Y à H:i') : '-';
}

// str ------------------------------------------------------------------------

/**
 * Mettre un nom au pluriel selon une valeur
 */
function plural($name, $count)
{
	if($name[strlen($name)-1] != 's')
		return $count > 1 ? $name.'s' : $name;
	else
		return $name;
}

// images ---------------------------------------------------------------------

/**
 * Ajouter _thumb au nom de fichier d'une image
 */
function filename_to_thumb($filename)
{
	return substr($filename, 0, strlen($filename)-4).'_thumb'.substr($filename, -4, strlen($filename));
}

/**
 * Obtenir le répertoire d'upload
 */
function img_upload_path()
{
	return base_url().'uploads/images/';
}

/**
 * Retourne le message d'erreur généré par la class d'upload quand aucun document n'a été spécifié
 */
function no_file_uploaded()
{
	return '<p>You did not select a file to upload.</p>';
}

// misc ------------------------------------------------------------------------

/**
 * Détermine si une adresse e-mail est valide
 * @return true si l'adresse est bonne, sinon false
 */
function check_email($email)
{
	return preg_match('/^([\w._-]+@[0-9a-z._-]+?\.[a-z]{2,4})$/i', $email);
}

/**
 * Détermine si une date de naissance est valide
 */
function check_bithdate($date)
{
	return preg_match('/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/i', $date);
}

/**
 * Afficher un tableau sous form de liste
 */
function get_printable_array($array)
{
	$list = '<ul class="list">';
	foreach($array as $item)
		$list .= '<li>'.$item.'</li>';
	$list .= '</ul>';
	return $list;
}
