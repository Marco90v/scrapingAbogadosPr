<?php
    // header("Content-Type: text/html; charset=UTF-8");
    include('lib/simple_html_dom.php');
    $ruta = 'https://tribunalelectronico.ramajudicial.pr/dirabogados/Search.aspx?d=';

    echo (!empty($_GET['d'])) ? datosDetallado($ruta.=$_GET['d']) : '{"error":"Error al Recibir Abogado"}';

    function datosDetallado($ruta){
        $datos=[];
        $validar = @file_get_contents($ruta);
        if(!$validar){return '{"msj":"Error al Recuperar datos del Abogado '.$_GET['d'].' "}';}

        $html = file_get_html($ruta);
        $lista = $html->find('span[id="lblSearchResultDetail"]');
        $table = $lista[0]->find('table');
        $tr = $table[0]->find('tr');
        foreach($tr as $t){
            $td = $t->find('td');
            if(strpos($td[0]->plaintext,'ombre')){
                $nombreArr = explode(',', $td[1]->plaintext);
                $datos["nombre"]= $nombreArr[1].' '.$nombreArr[0];
            }
            if(strpos($td[0]->plaintext,'mail')){
                 $datos["correo"]= $td[1]->plaintext;
            }
            if(strpos($td[0]->plaintext,'fono')){
                $datos["telefono"]=$td[1]->plaintext;
            }
        }
        $datos = json_encode($datos);
        $datos = str_replace("&#193;","Á",$datos);
        $datos = str_replace("&#201;","É",$datos);
        $datos = str_replace("&#205;","Í",$datos);
        $datos = str_replace("&#211;","Ó",$datos);
        $datos = str_replace("&#218;","Ú",$datos);
        $datos = str_replace("&#209;","Ñ",$datos);
        return $datos;
    }

?>