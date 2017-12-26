<?php 
global $wpdb;
$table_name = $wpdb->prefix . 'code_academie';
$result = $wpdb->get_results( "SELECT * FROM $table_name"); ?><table>
    <tr>
        <td>Nom</td>
        <td>Prénom</td>
        <td>Mail</td>
        <td>Tel</td>
        <td>Date</td>
        <td>Animal</td>
        <td>Nom de l'animal</td>
        <td>Message</td>
        <td>Motif</td>
        <td>Status</td>
        <td>Heure</td>
    </tr>
<?php
foreach ($result as $datas){?>
    <tr>
        <td><?= $datas->name ?></td>
        <td><?= $datas->first_name ?></td>
        <td><?= $datas->mail ?></td>
        <td><?= $datas->tel ?></td>
        <td><?= $datas->date ?></td>
        <td><?= $datas->animal ?></td>
        <td><?= $datas->name_animal ?></td>
        <td><?= $datas->message ?></td>
        <td><?= $datas->motif ?></td>
        <td><?= $datas->status ?></td>
        <td><?= $datas->hour_meet ?></td> 
    </tr>
<?php } ?>
    
</table>