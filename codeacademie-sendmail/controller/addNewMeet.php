<?php get_header(); ?>




<section id="rdv">
<h1>Prise de rendez-vous</h1>

      <form action="" method="post">

        <div class="section">
          <div class="user">
            <input name="name"  placeholder="*Nom" required="required"><br />
          </div>
          <div class="user">
            <input name="first_name"  placeholder="*Prenom" required="required"><br />
          </div>
          <div class="mail">
            <input name="mail" placeholder="*E-mail"><br />
          </div>
          
          <div class="tel">
            <input name="tel" placeholder="*Téléphone" required="required"><br/>
          </div>

          

          <div class="date">
            <input type="date" required placeholder="*Date du rendez-vous : " name="date"><br>
          </div>

          <div class="animal">
            <input name="animal" placeholder="* Type d'animal"><br />
          </div>

          <p class="asterix">*  Champs obligatoires</p>

        </div> 
        <!-- fin de section1 -->

        <div class="section2">
          <div class="aniname">
            <input name="name_animal"  placeholder="Nom de l'animal" ><br />
          </div>
          <div class="motif">
              <select name="motif">
                <option class="motiv"  disabled selected style="display: none;">* Motif</option>
                <option value="Vaccin">Vaccin</option>
                <option value="Medecine">Médecine</option>
                <option value="sterilisation">Chirurgie: stérilisation</option>
                <option value="detartrage">Chirurgie: détartrage</option>
                <option value="Autres">Autres</option>
              </select>
          </div> 
        
          <div class="message">  
            <textarea name="message" placeholder="Votre message (facultatif)" class="big"></textarea>
          </div>

          <div class="bouton">
            <button type="submit" class="bouton-un" name="submit">Envoyer</button>
            <button type="reset" id="bouton-deux">Effacer</button>
          </div>
          
        </div> <!-- fin de section2 -->
      </form>
  </section>
  
 <?php 
global $wpdb;
$table_name = $wpdb->prefix . 'code_academie';
    if (isset($_POST['submit'])){
        error_log($_POST['motif']);
        $wpdb->insert( 
            $table_name, 
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
                'hour_meet' => null
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
        );
    }

?>