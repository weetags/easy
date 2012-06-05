<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* 
*/
class Empleados extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model("empleados_model", "empleados");
        $this->load->model("puestos_model","puestos");
        $this->load->model("estados_model","estados");
        $this->load->model("oficina_model","oficina");

	}

	public function index()
	{
		
        $data['puestos']=$this->puestos->get_puestos_all();
        $data['oficinas']=$this->oficina->get_oficinas_all();
        $data['estados']=$this->estados->get_estados_all();
        $data['vista']='empleados/index';
		$data['titulo']='empleados';
        $data['vistaa']="menu_izquierda";
        $data['vistab']="menu";
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

     $consul = $this->db->query('SELECT * from obrero WHERE activo= "1"');
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
        $resultado_catalogo =$this->empleados->get_empleado($sidx, $sord, $start, $limite);
        // Se agregan los datos de la respuesta del servidor
        $data->page = $page;
        $data->total = $total_pages;
        $data->records = $count;
        $i=0;
        foreach($resultado_catalogo as $row) {
           $data->rows[$i]['id']=$row->id_obrero;
           $onclik="onclick=delet('".$row->id_obrero."')";
    	   $onclikedit="onclick=edit('".$row->id_obrero."')";
     	   $acciones='<span style=" cursor:pointer" '.$onclikedit.'><img title="Editar" src="'.base_url().'img/edit.png" width="18" height="18" /></span>&nbsp;<span style=" cursor:pointer" '.$onclik.'><img src="'.base_url().'img/borrar.png" width="18" title="Eliminar" height="18" /></span>';
           $data->rows[$i]['cell']=array($acciones,
                                    strtoupper($row->nombre_obrero),
                                    strtoupper($row->a_paterno),
                                    strtoupper($row->a_materno),
                                    strtoupper($row->fecha_nacimiento),
                                    strtoupper($row->estado_civil),
                                    strtoupper($row->sexo),
                                    strtoupper($row->dsc_estado),
                                    strtoupper($row->ciudad),
                                    strtoupper($row->colonia),
                                    strtoupper($row->direccion),
                                    strtoupper($row->celular),
                                    strtoupper($row->telefono_casa),
                                    strtoupper($row->nombre),/*puesto*/
                                    strtoupper($row->nombre_oficina),
                                    
                                    
                                    );

                                    
           $i++;
        }
    	// La respuesta se regresa como json
        echo json_encode($data);
    }

    public function get($id)
    {
        $row=$this->empleados->get_id($id);
        echo strtoupper($row->nombre_obrero).'~'.
             strtoupper($row->a_paterno).'~'.
             strtoupper($row->a_materno).'~'.
             strtoupper($row->fecha_nacimiento).'~'.
             strtoupper($row->direccion).'~'.
             strtoupper($row->celular).'~'.
             strtoupper($row->telefono_casa).'~'.
             strtoupper($row->puestos_id_tipo_puesto).'~'.
             strtoupper($row->oficina_id_oficina).'~'.
             strtoupper($row->estado_civil).'~'.
             strtoupper($row->sexo).'~'.
             strtoupper($row->estado_id_estado).'~'.
             strtoupper($row->colonia).'~'.
             strtoupper($row->ciudad);
    }

    public function editar_empleado($id)
    {
        $editar=$this->empleados->editar($id);
        echo 1;
    }

    public function guardar()
    {
        $save=$this->empleados->guardar();
        echo $save;
    }

    public function eliminar($id)
    {
        $delete=$this->empleados->eliminar($id);
        if($delete > 0)
        {
            echo 1;
        }
        else
        {
            echo 0;
        }
    }
		
}