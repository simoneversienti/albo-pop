<?php 
$date=new DateTimeImmutable();
$pd=$date->sub(new DateInterval('P2M'));
$nd=$date->add(new DateInterval('P2Y'));
echo $pd->format('Y-m-d')."\n";
echo $nd->format('Y-m-d')."\n";
?>