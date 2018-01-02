<!DOCTYPE html>
<html>

<head>
    <title>Prise de rendez-vous</title>
    <meta charset="UTF-8">
</head>

<body>
    <style>
        .box {
            display: inline-block;
            margin: 1%;
            font-size: 1.5vh;
        }
        .box p {
            font-size: 1.5vh;
        }

        .red {
            margin-bottom:2rem;
            color: red;
        }

        .green {
            margin-bottom:2rem;
            color: green;
        }

    </style>


    <?php

global $wpdb;
$table_name = $wpdb->prefix . 'code_academie';
if (isset($_POST['delete'])){
    $id = $_POST['id_delete'];
    Meet::delete($id);

}
if(isset($_POST['modif_submit'])){
    $id = $_POST['id_modif'];
    if (isset($_POST['validate'])){
        if ($_POST['validate'] == "Oui"){
            $status = 1;
        } else {
            $status = 0;
        }
    }
    $meet = new Meet($_POST);
    $meet->save();


}

$motifs = Meet::MOTIFS;
$statuses = Meet::STATUSES;

if (!isset($_POST['modif_form'])){
    // $result = $wpdb->get_results( "SELECT * FROM $table_name WHERE date >= CURDATE()  ORDER BY timestamp DESC");
    $filter = ["date >= CURDATE()"];
    $order = ["date ASC"];
    $result = Meet::findFilter($filter, $order);

    ?>
       <h1>Les prochains rendez-vous : </h1> <?php
    foreach ($result as $datas){ ?>
        <div class="box status-<?=$datas->status?>">
            <h2>
                <?php echo $datas->name . " " . $datas->firstname; ?>
                <br /><small> <?php
                echo DateTime::createFromFormat('Y-m-d', $datas->date)->format("d/m/Y");
                ?>  </small>
            </h2>
            <div>

                <p>Mail :
                    <?= $datas->mail; ?>
                </p>
                <p>Tel :
                    <?= $datas->tel; ?>
                </p>

                <p>Type de l'animal :
                    <?= $datas->animal; ?>
                </p>
                <p>Nom de l'animal :
                    <?= $datas->name_animal; ?>
                </p>
                <p>Motif de la prise de rendez-vous :
                    <?= $datas->message; ?>
                </p>
                <p>Status du rendez vous :
                    <?php
                    echo $statuses[$datas->status];
            ?>
                </p>
                <p>Heure du rendez-vous :
                    <?php
            if ($datas->hour_meet == null){
                echo "Non Défini";
            } else {
                echo $datas->hour_meet;
            }
            ?>
                </p>
            </div>
            <form action="" method="post">
                <input type="hidden" name="id" value="<?= $datas->id ?>">
                <button type="text" name="modif_form">Modifier</button>
            </form>
            <form action="" method="post">
                <input type="hidden" name="id_delete" value="<?= $datas->id ?>">
                <button type="text" name="delete">supprimer</button>
            </form>
        </div>
        <?php }

} else {
$id = $_POST['id'];
$result = Meet::findById($id);


foreach ($result as $data){}
?>
  <h1>Modifier le rendez-vous</h1>
    <section id="codeacademie">
        <form action="" method="post">
          <div class="section">
            <input type="hidden" name="id" value="<?= $data->id ?>">
          <div class="">

            <label for="name">Nom * :</label>
            <input type="text" name="name" value="<?= $data->name ?>" required>
          </div>
          <div class="">

            <label for="first_name">Prénom * :</label>
            <input type="text" name="first_name" value="<?= $data->firstname ?>" required>
          </div>
          <div class="">

            <label for="mail">Adresse Email * : </label>
            <input type="email" name="mail" value="<?= $data->mail ?>" required>
          </div>
          <div class="">

            <label for="tel">Téléphone :</label>
            <input type="text" name="tel" value="<?= $data->tel ?>">
          </div>
          <div class="">

            <label for="date"> Date de rendez-vous souhaité * : </label>
            <input type="date" name="date" value="<?= $data->date ?>" required>
          </div>
          <div class="">

            <label for="animal">Espèce de l'animal :</label>
            <input type="text" name="animal" value="<?= $data->animal ?>">
          </div>
          <div class="">

            <label for="name_animal">Nom de l'animal  :</label>
            <input type="text" name="name_animal" value="<?= $data->name_animal ?>">
          </div>
          <div class="">

            <label for="message">Commentaire  :</label>
            <textarea name="message"><?= $data->message ?></textarea>
          </div>
            <div class="">

                <select name="motif" required>
                  <option class="motiv"  disabled selected style="display: none;">* Motif</option>
                  <?php
                    foreach($motifs as $key => $val){
                      echo "<option value='$key' ". ( $data->motif == $key ? 'selected="selected"' : '' ) .">$val</option>";

                    }
                   ?>
              </select>
            </div>
            <div class="">

            <label for="hours">Heure du RDV </label>
            <input type="time" name="hour_meet" value="<?php echo ($data->hour_meet == null) ? '00:00' : $data->hour_meet ?>" required>
          </div>
            <div class="">
              <label for="validate">Statut : </label>
                <select name="status" required>
              <?php
                  foreach ($statuses as $key => $value) {
                    echo "<option value='$key' ".( $data->status == $key ? "selected='selected'" : "" ).">$value</option>";
                  }
                  ?>
                </select>
            </div>
            <input type="hidden" name="id_modif" value="<?php echo $data->id ?>">
            <button type="submit" name="modif_submit">Modifier rendez-vous</button>
          </div>
        </form>
      </section>

        <?php } ?>



</body>

</html>
