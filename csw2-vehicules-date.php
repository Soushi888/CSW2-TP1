<?php
// Incomplet

$sql = "SELECT * FROM $wpdb->prefix" . "vehicules";
$vehicules = $wpdb->get_results($sql);

$today = new DateTime();


foreach ($vehicules as $vehicule) :
    $date_enregistrement = new DateTime($vehicule->vehicule_date_enregistrement);
    $expiration = $date_enregistrement->add((new DateInterval('P15D')));
    $diff = new DateTime($today->getTimestamp() - $expiration->getTimestamp());
?>
    <pre><?= "Date enregistrement" . var_dump($date_enregistrement->format("Y-d-M")); ?></pre>
    <pre><?= var_dump($today->format("Y-d-M")); ?></pre>
    <pre><?= "Date expiration" . var_dump($expiration->format("Y-d-M")); ?></pre>
    <pre><?=  $diff->format("Y-d-M") ?></pre>
    
    <hr>
<?php
endforeach;