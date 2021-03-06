<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Stock_lista extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
        $this->load->model("historial_linea_model","historial");
		$this->load->model("stock_lista_model", "stock");


            if(!$this->redux_auth->logged_in()){//verificar si el el usuario ha iniciado sesion
                redirect(base_url().'inicio');
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
        $data['vista']='stock/index';
		$data['titulo']='Stock';
		$this->load->view('principal',$data);
	}

	public function paginacion()
	{
		$page = $_POST['page'];  // Almacena el numero de pagina actual
        $limite = $_POST['rows']; // Almacena el numero de filas que se van a mostrar por pagina
        $sidx = $_POST['sidx'];  // Almacena el indice por el cual se hará la ordenación de los datos
        $sord = $_POST['sord'];  // Almacena el modo de ordenación

        if(!$sidx) $sidx =1;

        // Se crea la conexión a la base de datos
        // $conexion = new mysqli("servidor","usuario","password","basededatos");
        // Se hace una consulta para saber cuantos registros se van a mostrar

     $consul = $this->db->query('SELECT * from stock_linea');
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
        if ($start < 0) $start = 0;
        $resultado_ =$this->stock->get_stock_lista($sidx, $sord, $start, $limite);
        // Se agregan los datos de la respuesta del servidor
        $data->page = $page;
        $data->total = $total_pages;
        $data->records = $count;
        $i=0;
        foreach($resultado_ as $row) {

           $data->rows[$i]['id']=$row->id_stock_linea;

        if ($row->cantidad>0) {

                $onclickUsar="onclick=usarLinea('".$row->id_stock_linea."')";
                $acciones='<span style=" cursor:pointer" '.$onclickUsar.'><img src="'.base_url().'img/usar.png" width="18" title="usar" height="18" /></span>';

        }else{
                $acciones='';
        }
           $data->rows[$i]['cell']=array(
                                    $row->id_stock_linea,
                                    $acciones,
                                    strtoupper($row->nombre),
                                    strtoupper($row->descripcion),
                                    strtoupper($row->largo),
                                    strtoupper($row->ancho),
                                    strtoupper($row->corrugado),
                                    strtoupper($row->resistencia),
                                    strtoupper($row->cantidad)
                                    );
           $i++;
        }
    	// La respuesta se regresa como json
        echo json_encode($data);
    }
    public function add_stock($id)
        {
        $save=$this->stock->add_stock($id);
        echo $save;
        }
 public function add_stock_bodega($id)
    {
    $save=$this->stock->add_stock_bodega($id);
    echo $save;
    }
    ////////////////////////////////////evniar a stock el embarque que se selecciono//////////////////
   public function add_stock_reutilizable($id)
    {
    $save=$this->stock->add_stock_reutilizable($id);
    echo $save;
    }

    //////******//////////////////////////////////paginacion de stock reutilizable////////////////////////
    public function paginacion_reutilizable()
    {
        $page = $_POST['page'];  // Almacena el numero de pagina actual
        $limite = $_POST['rows']; // Almacena el numero de filas que se van a mostrar por pagina
        $sidx = $_POST['sidx'];  // Almacena el indice por el cual se hará la ordenación de los datos
        $sord = $_POST['sord'];  // Almacena el modo de ordenación

        if(!$sidx) $sidx =1;

        // Se crea la conexión a la base de datos
        // $conexion = new mysqli("servidor","usuario","password","basededatos");
        // Se hace una consulta para saber cuantos registros se van a mostrar

     $consul = $this->db->query('SELECT * from stock_reutilizable');
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
        if ($start < 0) $start = 0;
        $resultado_ =$this->stock->get_stock_lista_reutilizable($sidx, $sord, $start, $limite);
        // Se agregan los datos de la respuesta del servidor
        $data->page = $page;
        $data->total = $total_pages;
        $data->records = $count;
        $i=0;
        foreach($resultado_ as $row) {

           $data->rows[$i]['id']=$row->id_stock_reutilizable;
           $data->rows[$i]['cell']=array(
                                    strtoupper($row->proveedor),
                                    strtoupper($row->cantidad),
                                    strtoupper($row->fecha_ingreso)
                                    );
           $i++;
        }
        // La respuesta se regresa como json
        echo json_encode($data);
    }
     public function guardar_select()
    {
        $save=$this->historial->guardar_select();
        if ($save==1) {
            echo '1' ;
        }elseif ($save==2) {
            echo '2';
        }elseif ($save==3) {
            echo '3';
        }elseif ($save==4) {
            echo '4';
        }
    }
public function buscando()
{
$oficina=$this->session->userdata('oficina');
$filters = $_POST['filters'];

        $where = "";
        if (isset($filters)) {
            $filters = json_decode($filters);
            $where = " where id_sucursal=".$oficina." AND ";
            $whereArray = array();
            $rules = $filters->rules;

            foreach($rules as $rule) {

                $whereArray[] = $rule->field." like '%".$rule->data."%'";

            }
            if (count($whereArray)>0) {

                $where .= join(" and ", $whereArray);
            } else {
                $where = " where id_sucursal=".$oficina." ";
            }
        }

 $page = $_POST['page'];  // Almacena el numero de pagina actual
    $limite = $_POST['rows']; // Almacena el numero de filas que se van a mostrar por pagina
    $sidx = $_POST['sidx'];  // Almacena el indice por el cual se hará la ordenación de los datos
    $sord = $_POST['sord'];  // Almacena el modo de ordenación

    if(!$sidx) $sidx =1;

    // Se crea la conexión a la base de datos
//    $conexion = new mysqli("servidor","usuario","password","basededatos");
    // Se hace una consulta para saber cuantos registros se van a mostrar
 $consul = $this->db->query('SELECT * FROM stock_linea '.$where);
 $count = $consul->num_rows();
    if($consul->num_rows()==0)
{
echo json_encode('null');

exit();
}
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
        if ($start < 0) $start = 0;
        $resultado_ =$this->stock->get_stock_lista_search($where,$sidx, $sord, $start, $limite);
        // Se agregan los datos de la respuesta del servidor
        $data->page = $page;
        $data->total = $total_pages;
        $data->records = $count;
        $i=0;
        foreach($resultado_ as $row) {

           $data->rows[$i]['id']=$row->id_stock_linea;

        if ($row->cantidad>0) {

                $onclickUsar="onclick=usarLinea('".$row->id_stock_linea."')";
                $acciones='<span style=" cursor:pointer" '.$onclickUsar.'><img src="'.base_url().'img/usar.png" width="18" title="usar" height="18" /></span>';

        }else{
                $acciones='';
        }
           $data->rows[$i]['cell']=array(
                                    $row->id_stock_linea,
                                    $acciones,
                                    strtoupper($row->nombre),
                                    strtoupper($row->descripcion),
                                    strtoupper($row->largo),
                                    strtoupper($row->ancho),
                                    strtoupper($row->corrugado),
                                    strtoupper($row->resistencia),
                                    strtoupper($row->cantidad)
                                    );
           $i++;
        }
        // La respuesta se regresa como json
        echo json_encode($data);
}
}