<?php 

    function returnHealthCentres(){
        global $dbh;
        $stmt = $dbh->prepare('SELECT id_hc,name FROM healthCentre;');
        $stmt->execute();
        $healthCentresInfo_Arr = $stmt->fetchAll();
        return $healthCentresInfo_Arr;
    }



?>