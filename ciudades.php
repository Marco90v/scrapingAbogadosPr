<?php
    header('Content-Type: text/html; charset=utf-8');
    include('lib/simple_html_dom.php');
    $ruta = 'https://tribunalelectronico.ramajudicial.pr/dirabogados/search.aspx';
    $html = file_get_html($ruta);

    // $selectPais = $html->find('select[name="ddlCountry"]');
    // $optionPais = $selectPais[0]->find('option');
    // $paises=[];
    // $pa=0;
    // foreach ($optionPais as $oPais) {
    //     $paises[$pa]["valor"]=$oPais->value;
    //     $paises[$pa]["texto"]=$oPais->plaintext;
    //     $pa++;
    // }

    $selectC = $html->find('select[name="ddlCity"]');
    $optionC = $selectC[0]->find('option');
    $ciudades=[];
    $c=0;
    foreach($optionC as $oC){
        $ciudades[$c]["valor"]=$oC->value;
        $ciudades[$c]["texto"]=$oC->plaintext;
        $c++;
    }

    // $selectP = $html->find('select[name="ddlDirectoryTopPageIndex"]');
    // $optionP = $selectP[0]->find('option');
    // $paginas=[];
    // $p=0;
    // foreach ($optionP as $oP) {
    //     $paginas[$p]["valor"]=$p+1;//$oP->value;
    //     $paginas[$p]["texto"]= substr($oP->plaintext,11,strlen($oP->plaintext));
    //     $p++;
    // }
    $datos=[$ciudades];
    $datos = json_encode($datos);
    $datos = str_replace("&#193;","Á",$datos);
    $datos = str_replace("&#201;","É",$datos);
    $datos = str_replace("&#205;","Í",$datos);
    $datos = str_replace("&#211;","Ó",$datos);
    $datos = str_replace("&#218;","Ú",$datos);
    $datos = str_replace("&#209;","Ñ",$datos);
    echo $datos;
?>