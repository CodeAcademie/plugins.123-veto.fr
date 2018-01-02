<?php
/*
Plugin Name: SendMail Code-academie
Plugin URI: http://code-academie.fr
Description: Un plugin d'envoi de mail, lors d'une prise de rendez vous.
Version: 0.1
Author: Alice Kepl-Danet @alicekd - Francois Massiot @ fm35- Romain Seite @saromase - Erwann Duclos @Docusland
Author URI: http://github.com/saromase
License: GPL2
*/


add_action( 'admin_menu', 'add_menu_plugin' );
register_activation_hook(__FILE__,'database_install');
include('models/Meet.php');
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
    add_menu_page( 'CodeAcademie', 'Code Academie', 'read', 'CodeAcademie_Dashboard', 'calendar' );
    // add_submenu_page( 'CodeAcademie_Dashboard', 'MonPlugin', 'Calendrier', 'read', 'calendar', 'calendar');
    add_submenu_page( 'CodeAcademie_Dashboard', 'MonPlugin', 'Gestion des RDV', 'read', 'nextMeet');
    add_submenu_page( 'CodeAcademie_Dashboard', 'MonPlugin', 'Ajouter un RDV', 'read', 'addNewMeet', 'addNewMeet');
    add_submenu_page( 'CodeAcademie_Dashboard', 'MonPlugin', 'Archives', 'read', 'archive', 'archive');
    add_submenu_page( 'CodeAcademie_Dashboard', 'MonPlugin', 'Envoyer les mails', 'read', 'send', 'send');
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
function calendar(){
  include('controller/calendar.php');
  wp_enqueue_script('test', plugin_dir_url(__FILE__) . '/js/calendar.js');
}
function send() {
    send_rdv_mail();
    send_report();
    set_past_status();
    include('controller/archive.php');
}

function wpse27856_set_content_type(){
    return "text/html";
}
add_filter( 'wp_mail_content_type','wpse27856_set_content_type' );


/********************* SHOTCODE **************************/
add_shortcode( "codeacademie_form", "codeacademie_shortcode");

function store_meet_in_db(){
  global $wpdb;
  $table_name = $wpdb->prefix . 'code_academie';
  if (isset($_POST['submit'])) {

    $meet = new Meet($_POST);
      // error_log($_POST['motif']);
      $wpdb->insert(
          $table_name,
          $meet->toArr()
      );
      echo "<div class='codeacademie alert-success' style='float:left'> Message envoyé </div>";

  }
}
function codeacademie_shortcode() {
    ob_start();
    include('shortcode/createMeet.php');
    return ob_get_clean();
}

add_action( 'init', 'store_meet_in_db' );


/************************* CRON ***************************/
register_activation_hook(__FILE__, 'launch_cron_tasks');

function launch_cron_tasks(){
  if ( ! wp_next_scheduled( 'send_mail_cron' ) ) {
    error_log(" SCHEDULING NEXT MAIL IN AN HOUR");
    wp_schedule_event( time(), 'hourly', 'send_mail_cron' );
  }
  if ( ! wp_next_scheduled( 'send_mail_to_admin_cron' ) ) {
    error_log(" SCHEDULING NEXT ADMIN MAIL IN AN HOUR");
    wp_schedule_event( time(), 'hourly', 'send_admin_mail_cron' );
  }
  if ( ! wp_next_scheduled( 'setting_status_to_past' ) ) {
    wp_schedule_event( time(), 'hourly', 'setting_status_to_past' );
  }
}

add_action( 'send_mail_cron', 'send_rdv_mail' );
add_action( 'send_admin_mail_cron', 'send_report');
add_action('setting_status_to_past', 'set_past_status');
function set_past_status(){
  $filter = ['status <= 3',"date=CURDATE()", "hour_meet < '".date("H:i")."'"];
  $records = Meet::findFilter($filter);
  if(! is_null($records) ){
    foreach ($records as $key => $value) {
      $m = new Meet($value);
      $m->setStatus(7);
      $m->save();
    }
  }
}
function send_report(){

  $title = "123Veto : Email du jour ";
  $filter = ['status=0 OR status=3'];
  $order = ['date, status'];
  $records = Meet::findFilter($filter, $order);

  $message = "<h1>123 Veto : Rapport du jour :</h1>";
  $u=false;
  $message .="<h2>Aujourd'hui </h2>";
  if(! is_null($records)){
    foreach($records as $k=>$value){
      if($value->status == 0 && !$u) {$message .="<a href='".get_bloginfo('wpurl')."/wp-admin'><h2>A valider: </h2></a>" ; $u = true;}
      $message .="<li>".$value->name." ".$value->firstname."  : ".$value->date."</li>";
    }
  }
  error_log($message);
  $to_email = get_bloginfo('admin_email');
  $res = wp_mail( $to_email , $title , $message);
  error_log(" SENDING EMAIL to $to_email :-->RESULT= $res");
}
function send_rdv_mail() {
  $site_name = get_bloginfo('name');
  $enddate = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + 2, date('Y')));
  $filter = ["status < 3", "date < '".$enddate."'"];
  $results = Meet::findFilter($filter);
  $mail_template_title = '%s : Rendez-vous demain à %s';

  $mail_template_content  = "Bonjour %s %s,\r\nN'oubliez pas votre rendez-vous le %s à %s h ! Venez avec votre animal %s, ainsi que son carnet de santé. \r\nLa clinique vétérinaire";

  $record_to_upd = [];
  foreach ($results as $key => $row) {

      $meet = new Meet($row);
      $mail_title = sprintf($mail_template_title, $site_name, $meet->getHourMeet());
      $to_email = $meet->getMail();
      error_log("Testing email $to_email");
      if(!is_null($to_email) && filter_var($to_email, FILTER_VALIDATE_EMAIL)){
        $mail_content = sprintf($mail_template_content, $meet->getFirstname(), $meet->getName(), $meet->getDate(), $meet->getHourMeet(), $meet->getNameAnimal());
        $res = wp_mail( $to_email, $mail_title , $mail_content);
        error_log(" SENDING EMAIL to $to_email : $mail_content -->RESULT= $res");
        $meet->setStatus(3);
        $meet->save();
        // $wpdb->update($table_name, ['status'=>3], ["ID" => $meet->getId()], array('%d'), array('%d'));
      }
  }

  // wp_mail( $toemail, , $mail_content);
}
wp_enqueue_style( 'plugin-stylesheet', plugins_url( '/style.css', __FILE__ ) );
