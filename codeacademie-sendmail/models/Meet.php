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
    private $hour_meet;

  function __construct($data)
  {
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
    /*
    array(
        'name' => $_POST['name'],
        'firstname' => $_POST['first_name'],
        'mail' => $_POST['mail'],
        'tel' => $_POST['tel'],
        'date' => $_POST['date'],
        'animal' => $_POST['animal'],
        'name_animal' => $_POST['name_animal'],
        'message' => $_POST['message'],
        'motif' => $_POST['motif'],
        'status' => 0,
    )*/

  }

}

 ?>
