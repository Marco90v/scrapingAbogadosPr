<?php
    header("Content-Type: text/html;charset=utf-8");
    include('lib/simple_html_dom.php');
    $ruta = 'https://tribunalelectronico.ramajudicial.pr/dirabogados/Search.aspx?';
    $pais= 'p=c159ead5-0232-df11-867f-00155d000605';
    
    (!empty($_GET['i'])) ? $pagina = '&i='.$_GET['i'] : $pagina="&i=1";
    (!empty($_GET['c'])) ? $ciudad = '&c='.$_GET['c'] : $ciudad="0";

    $ruta.=$pais.$ciudad.$pagina;

    $html = file_get_html($ruta);
    $paginas=[];
    $listAbogados=[];
    $datos=[];

    function paginas($html){
        $p=[];
        $select = $html->find('select[name="ddlSearchTopPageIndex"]');
        $option = $select[0]->find('option');
        $contador=1;
        foreach ($option as $o) {
            $arr = explode("=",$o->value);
            $p[$contador]['valor']=end($arr);
            $p[$contador]['texto']= substr($o->plaintext,11); 
            $contador++;
        }
        return $p;
    }

    function lista_Abogados($html){
        $lA=[];
        $lista = $html->find('span[id="lblSearchResult"]');
        $table = $lista[0]->find('table');
        $td = $table[0]->find('td');
        foreach($td as $v){
            foreach($v->find('a') as $a){
                $arr = explode('=', $a->href);
                array_push($lA, end($arr));
            }
        }
        return $lA;
    }

    if(empty($_GET['i'])){
        $paginas=paginas($html);
        $listAbogados=lista_Abogados($html);
        array_push($datos,$paginas);
        array_push($datos,$listAbogados);
    }else{
        $listAbogados=lista_Abogados($html);
        array_push($datos,$listAbogados);
    }

    echo json_encode($datos);
        

?>