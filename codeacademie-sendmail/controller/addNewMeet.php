


<section id="codeacademie">

      <form action="" method="post">
        <h1>Prise de rendez-vous</h1>

        <div class="section">
          <div class="user">
            <label for="name"> Nom * : </label>
            <input name="name"  placeholder="*Nom" required="required"><br />
          </div>
          <div class="user">
            <label for="name"> Prénom * : </label>
            <input name="first_name"  placeholder="*Prenom" required="required"><br />
          </div>
          <div class="mail">
            <label for="name"> E-mail * : </label>
            <input name="mail" placeholder="*E-mail"><br />
          </div>

          <div class="tel">
            <label for="name"> Téléphone * : </label>
            <input name="tel" placeholder="*Téléphone" required="required"><br/>
          </div>



          <div class="date">
            <label for="name"> Date * : </label>
            <input type="date" required placeholder="*Date du rendez-vous : " name="date"><br>
          </div>

          <div class="animal">
            <label for="name"> Type Animal * : </label>
            <input name="animal" placeholder="* Type d'animal"><br />
          </div>


          <div class="aniname">
              <label for="name"> Nom de l'animal * : </label>
            <input name="name_animal"  placeholder="Nom de l'animal" ><br />
          </div>
          <div class="motif">
              <label for="name"> Motif * : </label>
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
                'hour_meet' => $_POST['hour_meet'],
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
