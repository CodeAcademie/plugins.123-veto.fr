<?php

/**
 *
 */
class Meet
{
    private $id;
    private $name;
    private $firstname;
    private $mail;
    private $tel;
    private $date;
    private $animal;
    private $name_animal;
    private $message;
    private $motif;
    private $status = 0;
    private $hour_meet ="10:00";
    const MOTIFS = [
      "Vaccin"=> "Vaccin",
      "Medecine"=> "Médecine",
      "sterilisation"=> "Chirurgie : stérilisation",
      "detartrage"=> "Chirurgie : opération",
      "Autres"=> "Autres",
    ];
    const MOTIF_DURATION = [
      "Vaccin"=> 15,
      "Medecine"=> 30,
      "sterilisation"=> 60,
      "detartrage"=> 60,
      "Autres"=> 30,
    ];
    const STATUSES = [
      "0"=>"Non Confirmé",
      "1" => "Confirmé",
      "3" => "Mail Envoyé",
      "7" => "Passé",
      "9" => "A supprimer",
    ];
    const TABLE_NAME = "123v_code_academie";

  function __construct($data)
  {
    global $wpdb;
    $this->hydrate($data);

  }


  public function hydrate($donnees)
      {
          foreach ($donnees as $attribut => $valeur)
          {
          $methode = 'set'.str_replace(' ', '', ucwords(str_replace('_', ' ', $attribut)));

          if (is_callable(array($this, $methode)))
          {
              $this->$methode($valeur);
          }
          }
      }

    public function getId(){
      return $this->id;
    }
    public function setId($id){
      $this->id = $id;
    }
  public function setName($name){
    $this->name = $name;
  }
  public function getName(){
    return $this->name;
  }

  public function setFirstname($firstname){
    $this->firstname = $firstname;
  }
  public function getFirstname (){
    return $this->firstname;
  }
  public function setMail($mail){
    $this->mail = $mail;
  }
  public function getMail(){
    return $this->mail;
  }
  public function setTel($tel){
    $this->tel= $tel;
  }
  public function getTel(){
    return $this->tel;
  }
  public function setDate($date){
    $this->date = $date;
  }
  public function getDate(){
    return $this->date;
  }
  public function setAnimal($animal){
    $this->animal = $animal;
  }
  public function getAnimal(){
    return $this->animal;
  }
  public function setNameAnimal($name_animal){
    $this->name_animal = $name_animal;
  }
  public function getNameAnimal(){
    return $this->name_animal;
  }
  public function setMessage($message){
    $this->message = $message;
  }
  public function getMessage(){
    return $this->message;
  }
  public function setMotif($motif){
    $this->motif = $motif;
  }
  public function getMotif(){
    return $this->motif;
  }

  /**
  * Accepted statuses :
  *  0  : default value
  *  1  : confirmed
  *  3  : email_sent
  *  7  : in_the_past
  *  9  : deleted
  */
  public function setStatus($status){
    $this->status = $status;
  }
  public function getStatus(){
    return $this->status;
  }
  public function setHourMeet($hour_meet){
    $this->hour_meet = $hour_meet;
  }
  public function getHourMeet(){
    return $this->hour_meet;
  }
  public function getCalendarStartDate(){
    $hour = $this->hour_meet;
    if(is_null($hour)) $hour="00:00";
    return $this->date."T".$this->hour_meet.":00";
  }
  public function getCalendarEndDate(){
    $duration = Meet::MOTIF_DURATION[$this->motif];
    $from_date = $this->getCalendarStartDate();
    error_log("Generating End Date : $from_date + $duration");
    $from_date = str_replace("T", " ", $from_date);
    $to_date = date_create_from_format("Y-m-d H:i:s", $from_date);
    if($to_date){
      $to_date->add(new DateInterval("PT".$duration."M"));
      error_log("Generated End Date : ".$to_date->format("Y-m-dTH:i:s"));
      return $to_date->format("Y-m-d\TH:i:s");
    }
    // else return null;
  }

  public function toArr(){
    $attrs = ['name', 'firstname', 'mail', 'tel', 'date', 'animal', 'name_animal', 'message', 'motif', 'status'];
    $res = [];
    foreach ($attrs as $valeur)
    {
      $methode = 'get'.str_replace(' ', '', ucwords(str_replace('_', ' ', $valeur)));
      if (is_callable(array($this, $methode)))
      {
          $temp = $this->$methode();
          if($temp != null)
            $res[$valeur] = $temp;
      }
    }
    return $res;
  }
  /***************** DAO **************/
  public function save(){
    global $wpdb;
    if(is_null($this->getId())){
      $filter = [
        "name='".$this->getName()."'",
        "date='".$this->getDate()."'",
        "motif='".$this->getMotif()."'",
        "name_animal='".$this->getNameAnimal()."'"
      ];
      if(count(Meet::findFilter($filter))==0){
      $wpdb->insert(
          Meet::TABLE_NAME,
          array(
              'name' => $this->getName(),
              'firstname' => $this->getFirstname(),
              'mail' => $this->getMail(),
              'tel' => $this->getTel(),
              'date' => $this->getDate(),
              'animal' => $this->getAnimal(),
              'name_animal' => $this->getNameAnimal(),
              'message' => $this->getMessage(),
              'motif' => $this->getMotif(),
              'status' => 0,
              'hour_meet' => $this->getHourMeet(),
          ),
          array(
              '%s',
              '%s',
              '%s',
              '%s',
              '%s',
              '%s',
              '%s',
              '%s',
              '%s',
              '%d',
              '%s',
          )
      );}
      else{
        error_log("RECORD ALREADY EXISTS");
      }
    }
    else{
      $wpdb->update(
          Meet::TABLE_NAME,
          array(
              'name' => $this->getName(),
              'firstname' => $this->getFirstname(),
              'mail' => $this->getMail(),
              'tel' => $this->getTel(),
              'date' => $this->getDate(),
              'animal' => $this->getAnimal(),
              'name_animal' => $this->getNameAnimal(),
              'message' => $this->getMessage(),
              'motif' => $this->getMotif(),
              'status' => $this->getStatus(),
              'hour_meet' => $this->getHourMeet()
          ),
          array( 'id' => $this->getId() ),
          array(
              '%s',
              '%s',
              '%s',
              '%s',
              '%s',
              '%s',
              '%s',
              '%s',
              '%s',
              '%d',
              '%s',
          ),
          array( '%d' )
      );
    }
  }
  public static function delete($id){
    global $wpdb;
    error_log("Deleting $id");
    $meet = Meet::findById($id)[0];
    if($meet->status == 9){
      $wpdb->delete(Meet::TABLE_NAME,['id' => $id ]);
    }else{
      $wpdb->update(Meet::TABLE_NAME,['status' => 9],['id' => $id ]);
    }
  }
  /***************** FIND *************/
  public static function findAll() {
    global $wpdb;
    $result = $wpdb->get_results( "SELECT * FROM ".Meet::TABLE_NAME);
    return $result;
  }
  public static function findById($id){
    global $wpdb;
    $result = $wpdb->get_results( "SELECT * FROM ".Meet::TABLE_NAME." WHERE id = $id ");
    return $result;
  }
  public static function findFilter($arr, $order = null){

    $query = "SELECT * from ". Meet::TABLE_NAME;
    if(! is_null($arr)){
      if(count($arr)>0) $query .= " WHERE ";
      foreach ($arr as $key => $value) {
        if($key > 0) $query .= " AND ";
        $query .=" $value ";
      }
    }

    if(! is_null($order)){
      if(count($order)>0) $query .= " ORDER BY ";
      foreach ($order as $key => $value) {
        if($key > 0) $query .= " AND ";
        $query .=" $value ";
      }
    }
    global $wpdb;
    error_log("findFilter EXECUTING QUERY : ".$query);
    $results  = $wpdb->get_results($query);
    return $results;
  }
}

 ?>
