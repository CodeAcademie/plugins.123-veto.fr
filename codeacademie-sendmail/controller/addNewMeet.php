


<section id="codeacademie">

      <form action="" method="post">
        <h1>Prise de rendez-vous</h1>

        <div class="section">
          <div class="user">
            <label for="name"> Nom * : </label>
            <input name="name"  placeholder="Nom" required="required"><br />
          </div>
          <div class="user">
            <label for="name"> Prénom * : </label>
            <input name="first_name"  placeholder="Prenom" required="required"><br />
          </div>
          <div class="mail">
            <label for="name"> E-mail * : </label>
            <input name="mail" required placeholder="E-mail"><br />
          </div>

          <div class="tel">
            <label for="name"> Téléphone : </label>
            <input name="tel" placeholder="Téléphone"><br/>
          </div>



          <div class="date">
            <label for="name"> Date * : </label>
            <input type="date" required placeholder="*Date du rendez-vous : " name="date"><br>
          </div>
          <div class="">
            <label for="hours">Heure du RDV </label>
            <input type="time" name="hour_meet" value="10:00" required>
          </div>
          <div class="animal">
            <label for="name"> Type Animal : </label>
            <input name="animal" placeholder=" Type d'animal"><br />
          </div>


          <div class="aniname">
              <label for="name"> Nom de l'animal : </label>
            <input name="name_animal"  placeholder="Nom de l'animal" ><br />
          </div>
          <div class="motif">
              <label for="name"> Motif * : </label>
              <select name="motif" required>
                <option class="motiv"  disabled selected style="display: none;">* Motif</option>
                <?php
                  foreach(Meet::MOTIFS as $key => $val){
                    echo "<option value='$key'>$val</option>";

                  }
                 ?>
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
    if (isset($_POST['submit'])){
        $meet = new Meet($_POST);
        $meet->save();
    }
?>
