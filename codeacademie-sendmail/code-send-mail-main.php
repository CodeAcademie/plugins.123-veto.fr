<?php
/*
Plugin Name: SendMail Code-academie
Plugin URI: http://code-academie.fr
Description: Un plugin d'envoie de mail, lors d'une prise de rendez vous
Version: 0.1
Author: Alice KD Francois M Romain S
Author URI: http://github.com/saromase
License: GPL2
*/

add_action( 'admin_menu', 'add_menu_plugin' );
register_activation_hook(__FILE__,'database_install');

function database_install() {
    global $wpdb;
	global $jal_db_version;
    $jal_db_version = '1.0';

	$table_name = $wpdb->prefix . 'code_academie';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
    timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
    id mediumint(9) NOT NULL AUTO_INCREMENT ,
    name varchar(255) NOT NULL ,
    firstname varchar(255) NOT NULL ,
    mail varchar(255) NOT NULL ,
    tel varchar(10) NOT NULL ,
    date DATE NOT NULL ,
    animal varchar(255) NOT NULL ,
    name_animal varchar(255) NOT NULL ,
    message varchar(1024) ,
    motif varchar(255) NOT NULL ,
    status tinyint(1) NOT NULL,
    hour_meet varchar(100),
    PRIMARY KEY  (id)
    ) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	add_option( 'jal_db_version', $jal_db_version );
}

function add_menu_plugin() {
    add_menu_page( 'CodeAcademie', 'CodeAcademie', 'read', 'CodeAcademie_Dashboard', 'nextMeet' );
    add_submenu_page( 'CodeAcademie_Dashboard', 'MonPlugin', 'Gestion des RDV', 'read', 'CodeAcademie_Dashboard', 'nextMeet');
    add_submenu_page( 'CodeAcademie_Dashboard', 'MonPlugin', 'Ajouter un RDV', 'read', 'addNewMeet', 'addNewMeet');
    add_submenu_page( 'CodeAcademie_Dashboard', 'MonPlugin', 'Archive', 'read', 'archive', 'archive');
}

function nextMeet() {
    include('controller/meet.php');
}

function addNewMeet() {
    include('controller/addNewMeet.php');
}
function archive() {
    include('controller/archive.php');
}
