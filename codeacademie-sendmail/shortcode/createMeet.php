
<?php
global $post;
?>

<section id="codeacademie" class="shortcode">

      <form action=" <?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>" method="post">

        <div class="formulaire contact">
          <div class="center-element-contact">
            <p>

              <label for="name"><i class="fa fa-user" aria-hidden="true"></i> Vos Contacts (requis) : </label><br />

              <input name="name"  placeholder="Nom" required="required">
              <input name="first_name"  placeholder="Prenom" required="required"><br />
              <br />
            </p>
              <p class="mail">
                <label for="name"> E-mail (requis) : </label><br />
                <input name="mail" placeholder="*E-mail"><br />
              </p>

              <p class="tel">
                <label for="name"> Téléphone (requis) : </label><br />
                <input name="tel" placeholder="*Téléphone" required="required"><br/>
              </p>
            </div>








        </div>
        <div class="animal-contact contact">
            <div class="center-element-contact">
          <p class="date">

            <label for="name"><i class="fa fa-calendar" aria-hidden="true"></i> Jour souhaité : </label><br>
            <input type="date" required  name="date"><br>
          </p>

          <p class="animal">
            <input name="animal" placeholder="* Type d'animal"><br />
            <input name="name_animal"  placeholder="Nom de l'animal" ><br />
          </p>
          <p class="motif">
              <select name="motif" required>
                <option class="motiv"  disabled selected style="display: none;">* Motif</option>
                <option value="Vaccin">Vaccin</option>
                <option value="Medecine">Médecine</option>
                <option value="sterilisation">Chirurgie: stérilisation</option>
                <option value="detartrage">Chirurgie: détartrage</option>
                <option value="Autres">Autres</option>
              </select>
          </p>

          <div class="message">
            <textarea name="message" placeholder="Votre message (facultatif)" class="big"></textarea>
          </div>
          <div>
            <button type="submit" class="wpcf7-submit" name="submit">Envoyer</button>
          </div>
        </div>
      </div>
      </form>
  </section>

 <?php


 error_log("--------------------------LOADING-----------------------");
?>
