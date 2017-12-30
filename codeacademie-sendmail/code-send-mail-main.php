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


wp_enqueue_style( 'plugin-stylesheet', plugins_url( '/style.css', __FILE__ ) );
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
    add_menu_page( 'CodeAcademie', 'CodeAcademie', 'read', 'CodeAcademie_Dashboard', 'nextMeet' );
    add_submenu_page( 'CodeAcademie_Dashboard', 'MonPlugin', 'Gestion des RDV', 'read', 'CodeAcademie_Dashboard', 'nextMeet');
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
function send() {
    send_rdv_mail();
    include('controller/archive.php');
}


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
// add_action( 'wp_loaded', 'cron_time' );
// require_once( dirname(__FILE__) . '/../../../wp-load.php' );

register_activation_hook(__FILE__, 'launch_cron_tasks');

function launch_cron_tasks(){
  if ( ! wp_next_scheduled( 'send_mail_cron' ) ) {
    error_log(" SCHEDULING NEXT CRON IN AN HOUR");
    wp_schedule_event( time(), 'hourly', 'send_mail_cron' );
  }
}

add_action( 'send_mail_cron', 'send_rdv_mail' );

// add_action( 'wp_loaded', 'send_rdv_mail' );

function send_rdv_mail() {
  $site_name = get_bloginfo('name');
  global $wpdb;
  $table_name = $wpdb->prefix . 'code_academie';
  $enddate = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + 2, date('Y')));
  $query = "SELECT * from $table_name where status < 3 and date < '".$enddate."'";
  error_log(" EXECUTING QUERY : ".$query);
  $results  = $wpdb->get_results($query);

  $mail_template_title = 'Votre Rendez-Vous chez %s';
  $mail_title = sprintf($mail_template_title, $site_name);

  $mail_template_content  = "Bonjour %s %s,\r\n
                             N'oubliez pas votre rendez-vous le %s à %s h ! Venez avec votre animal %s, ainsi que son carnet de santé. \r\n
                             La clinique vétérinaire";
  $record_upd= ['status'=>3];
  $record_to_upd = [];
  foreach ($results as $key => $row) {

      $meet = new Meet($row);
      $to_email = $meet->getMail();
      error_log("Testing email $to_email");
      if(!is_null($to_email) && filter_var($to_email, FILTER_VALIDATE_EMAIL)){
        $mail_content = sprintf($mail_template_content, $meet->getFirstname(), $meet->getName(), $meet->getDate(), $meet->getHourMeet(), $meet->getNameAnimal());
        error_log(" SENDING EMAIL to $to_email : $mail_content");
        $res = wp_mail( $to_email, $mail_title , $mail_content);
        error_log(" SENDING EMAIL result : $res");
        $meet->setStatus(3); // Setting email sent to corresponding meet record.
        $record = ["ID" => $meet->getId()];
        // $wpdb->update($table_name, $record_upd, $record, array('%d'), array('%d'));
      }
  }

  // wp_mail( $toemail, , $mail_content);
}
