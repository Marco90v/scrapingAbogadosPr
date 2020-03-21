const app = angular.module('listaAbogados',[]);

app.filter('limpiar', ()=>{
    return (input)=>{
        if(input=='TODOS'){
            return '-------';
        }else{
            return input;
        }
    }
});

app.controller('datos',($scope,$http)=>{
    $scope.estado = 'Estados del Sistema';
    $scope.alertClass = 'alert alert-secondary';
    $scope.checkedCiudad=true;
    $scope.checkedPagina=true;
    $scope.listAbogados=[];
    $scope.ciudades=[];
    
    function All(){
        $scope.estado = 'Opteniendo lista de Ciudades';
        $scope.alertClass = 'alert alert-primary';
        $http.get('ciudades.php')
        .then((res)=>{
            $scope.ciudades=res.data[0];
            $scope.itemCiudad="0";
            $scope.checkedCiudad=false;
            $scope.estado = 'Lista de ciudades optenidas';
            $scope.alertClass = 'alert alert-success';
        },()=>{$scope.estado = 'Error en la recuperacion de Ciudades';$scope.alertClass = 'alert alert-danger';});
    }
    $scope.cambiarPagina = ()=>{
        $scope.estado = 'Cambio de pagina, Obteniendo lista de Abogados';
        $scope.alertClass = 'alert alert-primary';
        $scope.checkedPagina=true;
        $http.get('datos.php?c='+$scope.itemCiudad+'&i='+$scope.itemPagina)
        .then((res)=>{
            $scope.listAbogados=[];
            datosAbogados(res.data[0]);
        },()=>{$scope.estado = 'Error al cambiar de pagina y optener lista de Abogados';$scope.alertClass = 'alert alert-danger';});
    }
    $scope.cambiarCiudad = ()=>{
        $scope.estado = 'Opteniendo lista de Abogados';
        $scope.alertClass = 'alert alert-primary';
        $scope.checkedPagina=true;
        if($scope.itemCiudad != '0'){
            $http.get('datos.php?c='+$scope.itemCiudad)
            .then((res)=>{
                $scope.paginas=res.data[0];
                $scope.estado = 'Lista Recibida';
                $scope.alertClass = 'alert alert-success';
                $scope.itemPagina="1"
                $scope.listAbogados=[];
                datosAbogados(res.data[1]);
            },()=>{$scope.estado = 'Error en la recuperacion de Abogados';$scope.alertClass = 'alert alert-danger';});
        }
    }

    function datosAbogados(lista){
        let contador=0;
        let total = lista.length;
        $scope.estado = 'Opteniendo datos de Abogados '+contador+' de '+total;
        $scope.alertClass = 'alert alert-primary';
        lista.forEach(abogado => {
            $http.get('detallado.php?d='+abogado)
            .then((res)=>{
                $scope.alertClass = 'alert alert-warning';
                if(res.data.error){
                    // $scope.estado = res.data.error; 
                    // $scope.alertClass = 'alert alert-danger';
                    console.log(res.data.error);
                }
                else if(res.data.msj){
                    $scope.listAbogados.push('{"nombre":"Error en el Servidor","correo":"Error en el Servidor","telefono":"Error en el Servidor"}');
                    contador++;
                    $scope.estado = 'Opteniendo datos de Abogados '+contador+' de '+total;
                }else{
                    $scope.listAbogados.push(res.data);
                    contador++;
                    $scope.estado = 'Opteniendo datos de Abogados '+contador+' de '+total;
                }
                if(contador==total){
                    $scope.checkedPagina=false;
                    $scope.alertClass = 'alert alert-success';
                }
            },()=>{$scope.estado = "Error al obtener datos de Abogado";$scope.alertClass = 'alert alert-danger';});
        }); 
    }
    All();
});