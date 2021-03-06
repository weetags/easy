<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Pedidos_nave extends CI_Controller {
 function __construct()
    {

        parent::__construct();
        $this->load->model("pedidos_bodega_model", "pedidos");
        //$this->load->model("proveedores_model","proveedores");

            if(!$this->redux_auth->logged_in()){//verificar si el el usuario ha iniciado sesion
                redirect(base_url().'inicio/logout');
            //echo 'denegado';
            }
 //inicializamos las variables MENU Y SIBMENU, por si no se enviaran desde la url
        $menu=0;
        $submenu=0;
        //verificamos si se enviaron las variables GET->m "(menu)" GET->submain"(submenu)"
        if (isset($_GET['m'])||isset($_GET['submain'])) {
            //si se enviaorn las variables GET condicionamos que sean solo numericas
            if (!is_numeric($_GET['m']) || !is_numeric($_GET['submain'])) {
                //si no son njumericas que cierre la session actual
                 redirect(base_url().'inicio/logout');
            }else{
                //en caso de que si fueran numericas agregamos la variables GET a las variables previamente creadas.
                $menu=$_GET['m'];
                $submenu=$_GET['submain'];
                //validamos el menu y submenu
                $this->permisos->permisosURL($menu,$submenu);
               }
        }

    }
	public function index()
	{
        $data['vista']='pedidos_nave/index';
        $data['titulo']='Pedidos por enviar';
        $this->load->view('principal',$data);
	}
	/////////////////////////////////////listar los pedidos por nave
     public function paginacionLista()
    {
        $oficina=$this->session->userdata('oficina');
        $page = $_POST['page'];  // Almacena el numero de pagina actual
        $limite = $_POST['rows']; // Almacena el numero de filas que se van a mostrar por pagina
        $sidx = $_POST['sidx'];  // Almacena el indice por el cual se hará la ordenación de los datos
        $sord = $_POST['sord'];  // Almacena el modo de ordenación

        if(!$sidx) $sidx =1;

        // Se crea la conexión a la base de datos
        // $conexion = new mysqli("servidor","usuario","password","basededatos");
        // Se hace una consulta para saber cuantos registros se van a mostrar

     $consul = $this->db->query("SELECT * from pedido_bodega WHERE pedido_bodega.oficina_pedido =  ".$this->session->userdata('oficina')." AND pedido_bodega.enviado=0");
     $count = $consul->num_rows();
        //En base al numero de registros se obtiene el numero de paginas
        if( $count >0 ) {
        $total_pages = ceil($count/$limite);
        } else {
        $total_pages = 0;
        }
        if ($page > $total_pages)
            $page=$total_pages;

        //Almacena numero de registro donde se va a empezar a recuperar los registros para la pagina
        $start = $limite*$page - $limite;
        //Consulta que devuelve los registros de una sola pagina
        //if ($start < 0) $start = 0;
        if ($start < 0){
          $start = 0;
         $data();
        }else{
        $resultado_ =$this->pedidos->get_pedido_bodegaNave($sidx, $sord, $start, $limite);
        // Se agregan los datos de la respuesta del servidor
        $data->page = $page;
        $data->total = $total_pages;
        $data->records = $count;
        $i=0;
    if ($this->permisos->permisos(24,2)==1) {
            foreach($resultado_ as $row) {
               $data->rows[$i]['id']=$row->id_pedido;
     if (($this->permisos->permisos(24,1)==1)&&($this->permisos->permisos(24,3)==1)){

         $onclik="onclick=enviado('".$row->id_pedido."')";
         $acciones='<span style=" cursor:pointer" '.$onclik.'><img title="Verificar" src="'.base_url().'img/alert-icon.png" width="18" height="18" /></span>';


     }else{
        $acciones='';
     }
           $data->rows[$i]['cell']=array($acciones,
                                    strtoupper($row->id_pedido),
                                    strtoupper($row->fecha_pedido),
                                    strtoupper($row->fecha_entrega),
                                    strtoupper($row->oficina_pedido),
                                    strtoupper($row->oficina_envio));
           $i++;
        }
    }
}
        // La respuesta se regresa como json
        echo json_encode($data);
    }

    ////////subpaginacion de materia prima por pedido de cada nave
    public function subpaginacionLista($id)
{


    $page = $_POST['page'];  // Almacena el numero de pagina actual
    $limit = $_POST['rows']; // Almacena el numero de filas que se van a mostrar por pagina
    $sidx = $_POST['sidx'];  // Almacena el indice por el cual se hará la ordenación de los datos
    $sord = $_POST['sord'];  // Almacena el modo de ordenación

    if(!$sidx) $sidx =1;

$verificacion = $this->db->query("SELECT
                                        pedido_bodega.activo
                                        FROM
                                        pedido_bodega
                                        WHERE
                                        pedido_bodega.id_pedido = '$id'"
                                );
 $consul = $this->db->query("SELECT
                                    cantidad_pedido_bodega.id_cantidad_pedido,
                                    cat_mprima.nombre,
                                    cat_mprima.ancho,
                                    cat_mprima.largo,
                                    cantidad_pedido_bodega.cantidad
                                    FROM
                                    cantidad_pedido_bodega ,
                                    pedido_bodega ,
                                    cat_mprima
                                    WHERE
                                    cantidad_pedido_bodega.id_pedido = '$id' AND
                                    cantidad_pedido_bodega.catalogo_producto = cat_mprima.id_cat_mprima
                                    GROUP BY
                                    cantidad_pedido_bodega.id_cantidad_pedido
                                    ORDER BY
                                    cat_mprima.nombre ASC
                        ");

if($consul->num_rows()==0)
{
echo 0;
    exit();
}

 $count = $consul->num_rows();
    //En base al numero de registros se obtiene el numero de paginas
    if( $count >0 ) {
    $total_pages = ceil($count/$limit);
    } else {
    $total_pages = 0;
    }
    if ($page > $total_pages)
        $page=$total_pages;

    //Almacena numero de registro donde se va a empezar a recuperar los registros para la pagina
    $start = $limit*$page - $limit;
    //Consulta que devuelve los registros de una sola pagina
    if ($start < 0) $start = 0;
    $consulta = "SELECT
                        cantidad_pedido_bodega.id_cantidad_pedido,
                        cat_mprima.nombre,
                        cat_mprima.ancho,
                        cat_mprima.largo,
                        cantidad_pedido_bodega.cantidad
                        FROM
                        cantidad_pedido_bodega ,
                        pedido_bodega ,
                        cat_mprima
                        WHERE
                        cantidad_pedido_bodega.id_pedido = '$id' AND
                        cantidad_pedido_bodega.catalogo_producto = cat_mprima.id_cat_mprima
                        GROUP BY cantidad_pedido_bodega.id_cantidad_pedido
                        ORDER BY $sidx $sord LIMIT $start , $limit;";
    $result1 = $this->db->query($consulta);

    // Se agregan los datos de la respuesta del servidor
    $data->page = $page;
    $data->total = $total_pages;
    $data->records = $count;
    $i=0;
$con = $verificacion->row();
$valor = $con->activo;

if ($valor == 1) {
    $N=1;
    foreach($result1->result() as $row) {

      $data->rows[$i]['id']=$row->id_cantidad_pedido;
        $onclik="onclick=eliminar_producto('".$row->id_cantidad_pedido."')";
        $acciones='';
        $data->rows[$i]['cell']=array($acciones,
        ($N),
        strtoupper($row->nombre),
        strtoupper($row->largo),
        strtoupper($row->ancho),
        strtoupper($row->cantidad));
        $i++;
        $N++;
    }
    }elseif ($valor == 0) {
 $N=1;
    foreach($result1->result() as $row) {

      $data->rows[$i]['id']=$row->id_cantidad_pedido;
        $onclik="onclick=pedido_cerrado('".$row->id_cantidad_pedido."')";
        $acciones='';
        $data->rows[$i]['cell']=array($acciones,
        ($N),
        strtoupper($row->nombre),
        strtoupper($row->ancho),
        strtoupper($row->largo),
        strtoupper($row->cantidad));
        $i++;
        $N++;
    }

    }


    // La respuesta se regresa como json
    echo json_encode($data);
}



}

/* End of file pedidos_nave.php */
/* Location: ./application/controllers/pedidos_nave.php */
