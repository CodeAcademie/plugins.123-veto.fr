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
    $result = $wpdb->delete($table_name, array('id' => $id));
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
    $wpdb->update( 
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
            'status' => $status,
            'hour_meet' => $_POST['hours']
        ), 
        array( 'id' => $id ), 
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
if (!isset($_POST['modif_form'])){
    $result = $wpdb->get_results( "SELECT * FROM $table_name WHERE date > CURDATE()  ORDER BY date ASC"); 
    ?> 
       <h1>Les prochains rendez-vous : </h1> <?php 
    foreach ($result as $datas){ ?>
        <div class="box">
            <h1 class="<?php echo ($datas->hour_meet == null) ? 'red' : 'green' ?>">
                <?php echo $datas->name . " " . $datas->firstname; ?>
            </h1>
            <div>

                <p>Mail :
                    <?= $datas->mail; ?>
                </p>
                <p>Tel :
                    <?= $datas->tel; ?>
                </p>
                <p>Date souhaitée :
                    <?= $datas->date; ?>
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
            if ($datas->status == 0){
                echo "Non confirmé";
            } else {
                echo "Confirmé";
            }
            ?>
                </p>
                <p>Heure du rendez-vous :
                    <?php
            if ($datas->hour_meet == null){
                echo "Non Définis";
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
global $wpdb;
$table_name = $wpdb->prefix . 'code_academie';
$result = $wpdb->get_results( "SELECT * FROM $table_name WHERE id = $id ");
foreach ($result as $data){}
?>
        <h1>Modifier le rendez-vous</h1>
        <form action="" method="post">
            <label for="name"><h2>Nom</h2> </label>
            <input type="text" name="name" value="<?= $data->name ?>" required>
            <label for="first_name"><h2>Prénom</h2> </label>
            <input type="text" name="first_name" value="<?= $data->firstname ?>" required>
            <label for="mail"><h2>Adresse Email</h2> </label>
            <input type="email" name="mail" value="<?= $data->mail ?>" required>
            <label for="tel"><h2>Adresse Email</h2> </label>
            <input type="text" name="tel" value="<?= $data->tel ?>" required>
            <label for="date"> <h2>Date de rendez-vous souhaiter</h2> </label>
            <input type="date" name="date" value="<?= $data->date ?>" required>
            <label for="animal"><h2>Espece de l'animal</h2> </label>
            <input type="text" name="animal" value="<?= $data->animal ?>" required>
            <label for="name_animal"><h2>Nom de l'animal </h2></label>
            <input type="text" name="name_animal" value="<?= $data->name_animal ?>" required>
            <label for="message"><h2>Veuillez entrer ici la raison de votre demande de rendez-vous</h2></label>
            <textarea name="message" required><?= $data->message ?></textarea>
            <?php 
                if ($data->status == 0){
                    echo "<p>Confimer le RDV : <input type ='radio' name='validate' value='Oui'>Oui <input type ='radio' name='validate' value='Non' checked='checked'>Non</p>";
                } else {
                    echo "<p>Confimer le RDV : <input type ='radio' name='validate' value='Oui' checked='checked'>Oui <input type ='radio' name='validate' value='Non'>Non</p>";
                } 
                ?>
                <select name="motif">
                    <option class="motiv"  disabled selected style="display: none;">* Motif</option>
                    <option value="Vaccin">Vaccin</option>
                    <option value="Medecine">Médecine</option>
                    <option value="sterilisation">Chirurgie: stérilisation</option>
                    <option value="detartrage">Chirurgie: détartrage</option>
                    <option value="Autres">Autres</option>
              </select>
            <label for="hours"><h2>Heure du RDV</h2> </label>
            <input type="text" name="hours" value="<?php echo ($data->hour_meet == null) ? 'Non définis' : $data->hour_meet ?>" required>
            <input type="hidden" name="id_modif" value="<?php echo $data->id ?>">
            <button type="submit" name="modif_submit">Modifier rendez-vous</button>
        </form>
        <?php } ?>



</body>

</html>
