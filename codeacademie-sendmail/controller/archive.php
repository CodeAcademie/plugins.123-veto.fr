<?php

$result = Meet::findAll();

?><table>
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
        <td><?= $datas->firstName ?></td>
        <td><?= $datas->mail ?></td>
        <td><?= $datas->tel ?></td>
        <td><?= $datas->date ?></td>
        <td><?= $datas->animal ?></td>
        <td><?= $datas->name_animal ?></td>
        <td><?= $datas->message ?></td>
        <td><?= Meet::MOTIFS[$datas->motif] ?></td>
        <td><?= Meet::STATUSES[$datas->status] ?></td>
        <td><?= $datas->hour_meet ?></td>
    </tr>
<?php } ?>

</table>
