<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Catalogo_producto extends CI_Controller {

    public function __construct()
    {

       parent::__construct();

       $this->load->model('catalogo_producto_model','producto');
       $this->load->model('resistencia_mprima_model','resistencia');
              $this->load->model("clientes_model","clientes_");
       //$this->load->model('archivo_model','archivo');

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
                //si no son numericas que cierre la session actual
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
        $data['clientes']=$this->clientes_->get_clientes_all();
        $data['resistencia']=$this->resistencia->get_resistencia_mprima_all();
        $data['vista']='catalogo_producto/index';
        $data['titulo']='Catalogo de Productos';
        $data['error']='';
        $this->load->view('principal', $data);
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

     $consul = $this->db->query('SELECT
                                        *
                                        FROM
                                        catalogo_producto
                                        WHERE
                                        catalogo_producto.activo = "1"');
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
        if ($start < 0){

          $start = 0;
         $data[]=0;
        }else{
        $resultado_catalogo =$this->producto->get_cat_productos($sidx, $sord, $start, $limite);
        // Se agregan los datos de la respuesta del servidor
        $data->page = $page;
        $data->total = $total_pages;
        $data->records = $count;
        $i=0;
if ($this->permisos->permisos(8,2)==1) {

                foreach($resultado_catalogo as $row) {
                   $data->rows[$i]['id']=$row->id_catalogo;
                   ///todos lo permisos
                   if (($this->permisos->permisos(8,1)==1)&&($this->permisos->permisos(8,3)==1)){

                        $onclikedit="onclick=edit('".$row->id_catalogo."','1')";
                        $onclik="onclick=delet('".$row->id_catalogo."')";

                        if ($row->id_archivos!=0) {
                        $picture="onclick=picture_existe('".$row->id_archivos."','".$row->id_catalogo."','1')";
                        $acciones='<span style=" cursor:pointer" '.$onclikedit.'><img title="Editar" src="'.base_url().'img/edit.png" width="18" height="18" /></span>&nbsp;<span style=" cursor:pointer" '.$onclik.'><img src="'.base_url().'img/borrar.png" width="18" title="Eliminar" height="18" /></span><span style=" cursor:pointer" '.$picture.'><img title="Nueva imagen" src="'.base_url().'img/add_picture.png" width="18" height="18" /></span>';
                        }else{
                        $picture="onclick=picture('".$row->id_catalogo."')";
                        $acciones='<span style=" cursor:pointer" '.$onclikedit.'><img title="Editar" src="'.base_url().'img/edit.png" width="18" height="18" /></span>&nbsp;<span style=" cursor:pointer" '.$onclik.'><img src="'.base_url().'img/borrar.png" width="18" title="Eliminar" height="18" /></span><span style=" cursor:pointer" '.$picture.'><img title="Nueva imagen" src="'.base_url().'img/view_picture.png" width="18" height="18" /></span>';
                        }


                        // permisos solo para editar
                   }elseif (($this->permisos->permisos(8,1)==1)&&($this->permisos->permisos(8,3)==0)) {

                        $onclikedit="onclick=edit('".$row->id_catalogo."','1')";
                        //$onclik="onclick=delet('".$row->id_catalogo."')";
                        if ($row->id_archivos!=0) {
                        $picture="onclick=picture_existe('".$row->id_archivos."','".$row->id_catalogo."','1')";
                        $acciones='<span style=" cursor:pointer" '.$onclikedit.'><img title="Editar" src="'.base_url().'img/edit.png" width="18" height="18" /></span><span style=" cursor:pointer" '.$picture.'><img title="Nueva imagen" src="'.base_url().'img/add_picture.png" width="18" height="18" /></span>';
                        }else{
                        $picture="onclick=picture('".$row->id_catalogo."')";
                        $acciones='<span style=" cursor:pointer" '.$onclikedit.'><img title="Editar" src="'.base_url().'img/edit.png" width="18" height="18" /></span><span style=" cursor:pointer" '.$picture.'><img title="Nueva imagen" src="'.base_url().'img/view_picture.png" width="18" height="18" /></span>';
                        }

                        // permisos solo para eliminar
                   }elseif (($this->permisos->permisos(8,1)==0)&&($this->permisos->permisos(8,3)==1)) {

                        //$onclikedit="onclick=edit('".$row->id_catalogo."','1')";
                        $onclik="onclick=delet('".$row->id_catalogo."')";
                        if ($row->id_archivos!=0) {
                        $picture="onclick=picture_existe('".$row->id_archivos."','".$row->id_catalogo."','1')";
                        $acciones='<span style=" cursor:pointer" '.$onclik.'><img src="'.base_url().'img/borrar.png" width="18" title="Eliminar" height="18" /></span><span style=" cursor:pointer" '.$picture.'><img title="Nueva imagen" src="'.base_url().'img/add_picture.png" width="18" height="18" /></span>';
                        }else{
                        $picture="onclick=picture('".$row->id_catalogo."')";
                        $acciones='<span style=" cursor:pointer" '.$onclik.'><img src="'.base_url().'img/borrar.png" width="18" title="Eliminar" height="18" /></span><span style=" cursor:pointer" '.$picture.'><img title="Nueva imagen" src="'.base_url().'img/view_picture.png" width="18" height="18" /></span>';
                        }

// sin permisos
                   }elseif (($this->permisos->permisos(8,1)==0)&&($this->permisos->permisos(8,3)==0)) {

                        //$onclikedit="onclick=edit('".$row->id_catalogo."','1')";
                        //$onclik="onclick=delet('".$row->id_catalogo."')";
                        $acciones='';

                   }
                   $data->rows[$i]['cell']=array($acciones,
                               $row->nombre_empresa,
                               $row->nombre,
                               $row->largo,
                               $row->ancho,
                               $row->alto,
                               $row->resistencia,
                               $row->corrugado,
                               $row->score,
                               $row->descripcion,
                               $row->nombre_producto
                               );
                   $i++;
                }
        }
    }
        // La respuesta se regresa como json
        echo json_encode($data);
    }
public function paginacionID($id_row)
{
        $page = $_POST['page'];  // Almacena el numero de pagina actual
        $limite = $_POST['rows']; // Almacena el numero de filas que se van a mostrar por pagina
        $sidx = $_POST['sidx'];  // Almacena el indice por el cual se hará la ordenación de los datos
        $sord = $_POST['sord'];  // Almacena el modo de ordenación

        if(!$sidx) $sidx =1;

        // Se crea la conexión a la base de datos
        // $conexion = new mysqli("servidor","usuario","password","basededatos");
        // Se hace una consulta para saber cuantos registros se van a mostrar

     $consul = $this->db->query("SELECT
                                        *
                                        FROM
                                        catalogo_producto
                                        WHERE
                                        catalogo_producto.activo = 1 AND catalogo_producto.id_productoFinal = ".$id_row." ");
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
        if ($start < 0){

          $start = 0;
         $data[]=0;
        }else{
        $resultado_catalogo =$this->producto->get_cat_productosID($sidx, $sord, $start, $limite,$id_row);
        // Se agregan los datos de la respuesta del servidor
        $data->page = $page;
        $data->total = $total_pages;
        $data->records = $count;
        $i=0;
if ($this->permisos->permisos(8,2)==1) {

                  foreach($resultado_catalogo as $row) {
                   $data->rows[$i]['id']=$row->id_catalogo;
                   ///todos lo permisos
                   if (($this->permisos->permisos(8,1)==1)&&($this->permisos->permisos(8,3)==1)){

                        $onclikedit="onclick=edit('".$row->id_catalogo."','1')";
                        $onclik="onclick=delet('".$row->id_catalogo."','1')";

                        if ($row->id_archivos!=0) {
                        $picture="onclick=picture_existe('".$row->id_archivos."','".$row->id_catalogo."','1')";
                        $acciones='<span style=" cursor:pointer" '.$onclikedit.'><img title="Editar" src="'.base_url().'img/edit.png" width="18" height="18" /></span>&nbsp;<span style=" cursor:pointer" '.$onclik.'><img src="'.base_url().'img/borrar.png" width="18" title="Eliminar" height="18" /></span><span style=" cursor:pointer" '.$picture.'><img title="Nueva imagen" src="'.base_url().'img/add_picture.png" width="18" height="18" /></span>';
                        }else{
                        $picture="onclick=picture('".$row->id_catalogo."')";
                        $acciones='<span style=" cursor:pointer" '.$onclikedit.'><img title="Editar" src="'.base_url().'img/edit.png" width="18" height="18" /></span>&nbsp;<span style=" cursor:pointer" '.$onclik.'><img src="'.base_url().'img/borrar.png" width="18" title="Eliminar" height="18" /></span><span style=" cursor:pointer" '.$picture.'><img title="Nueva imagen" src="'.base_url().'img/view_picture.png" width="18" height="18" /></span>';
                        }


                        // permisos solo para editar
                   }elseif (($this->permisos->permisos(8,1)==1)&&($this->permisos->permisos(8,3)==0)) {

                        $onclikedit="onclick=edit('".$row->id_catalogo."','1')";
                        //$onclik="onclick=delet('".$row->id_catalogo."','1')";
                        if ($row->id_archivos!=0) {
                        $picture="onclick=picture_existe('".$row->id_archivos."','".$row->id_catalogo."','1')";
                        $acciones='<span style=" cursor:pointer" '.$onclikedit.'><img title="Editar" src="'.base_url().'img/edit.png" width="18" height="18" /></span><span style=" cursor:pointer" '.$picture.'><img title="Nueva imagen" src="'.base_url().'img/add_picture.png" width="18" height="18" /></span>';
                        }else{
                        $picture="onclick=picture('".$row->id_catalogo."')";
                        $acciones='<span style=" cursor:pointer" '.$onclikedit.'><img title="Editar" src="'.base_url().'img/edit.png" width="18" height="18" /></span><span style=" cursor:pointer" '.$picture.'><img title="Nueva imagen" src="'.base_url().'img/view_picture.png" width="18" height="18" /></span>';
                        }

                        // permisos solo para eliminar
                   }elseif (($this->permisos->permisos(8,1)==0)&&($this->permisos->permisos(8,3)==1)) {

                        //$onclikedit="onclick=edit('".$row->id_catalogo."','1')";
                        $onclik="onclick=delet('".$row->id_catalogo."','1')";
                        if ($row->id_archivos!=0) {
                        $picture="onclick=picture_existe('".$row->id_archivos."','".$row->id_catalogo."','1')";
                        $acciones='<span style=" cursor:pointer" '.$onclik.'><img src="'.base_url().'img/borrar.png" width="18" title="Eliminar" height="18" /></span><span style=" cursor:pointer" '.$picture.'><img title="Nueva imagen" src="'.base_url().'img/add_picture.png" width="18" height="18" /></span>';
                        }else{
                        $picture="onclick=picture('".$row->id_catalogo."')";
                        $acciones='<span style=" cursor:pointer" '.$onclik.'><img src="'.base_url().'img/borrar.png" width="18" title="Eliminar" height="18" /></span><span style=" cursor:pointer" '.$picture.'><img title="Nueva imagen" src="'.base_url().'img/view_picture.png" width="18" height="18" /></span>';
                        }

// sin permisos
                   }elseif (($this->permisos->permisos(8,1)==0)&&($this->permisos->permisos(8,3)==0)) {

                        //$onclikedit="onclick=edit('".$row->id_catalogo."','1')";
                        //$onclik="onclick=delet('".$row->id_catalogo."','1')";
                        $acciones='';

                   }
                   $data->rows[$i]['cell']=array($acciones,
                               // $row->nombre_empresa,
                               $row->nombre,
                               $row->largo,
                               $row->ancho,
                               $row->alto,
                               $row->resistencia,
                               $row->corrugado,
                               $row->score,
                               $row->descripcion
                               );
                   $i++;
                }
        }
    }
        // La respuesta se regresa como json
        echo json_encode($data);
    }
 public function get_imagen($id)
    {
        $row=$this->producto->get_imagen($id);
                    echo   $row->id_file.'~'.
                           $row->nombre_archivo;

    }
  ///extraer la imagen
 public function get($id)
    {
        $row=$this->producto->get_id($id);
                    echo   $row->id_cliente.'~'.
                           $row->nombre.'~'.
                           $row->largo.'~'.
                           $row->ancho.'~'.
                           $row->alto.'~'.
                           $row->resistencia.'~'.
                           $row->corrugado.'~'.
                           $row->score.'~'.
                           $row->descripcion.'~'.
                           $row->id_productoFinal;
    }
// funcion para guardar formulario
public function guardar()
    {
        $save=$this->producto->guardar();
        echo $save;
    }

public function eliminar($id)
    {
        $delete=$this->producto->eliminar($id);
        if($delete > 0)
        {
            echo 1;
        }
        else
        {
            echo 0;
        }
    }
public function eliminar_imagen($id,$id_catalogo)
{
    $delete=$this->producto->eliminar_imagen($id,$id_catalogo);
    if($delete > 0)
    {
        echo 1;
    }
    else
    {
        echo 0;
    }
}
public function editar_producto($id)
    {
        $editar=$this->producto->editar($id);
        echo 1;
    }

function do_uploadProductoFinal()
{

$extencion=$this->obtenerExtensionFichero($_FILES['userfileCatalogoFinal']['name']);
if ($extencion=='jpg') {
$data = array (
'nombre'=>$this->input->post('nombre_archivoCatalogoFinal'),
'descripcion'=>$this->input->post('descripcion_archivoCatalogoFinal')
 );
$id_catalogo=$this->input->post('id_catCatalogoFinal');
$this->db->insert('archivo', $data);
$insert_id = $this->db->insert_id();

$path = $_SERVER['DOCUMENT_ROOT'].'/easy/uploads/';
    $tamano_archivo = $_FILES['userfileCatalogoFinal']['size'];
    $nombre_archivo = $_FILES['userfileCatalogoFinal']['name'];
    $tipo_archivo = $_FILES['userfileCatalogoFinal']['type'];
  if (!strpos($tipo_archivo, "jpeg")){
    $this->session->set_flashdata('message', array('4'));
     redirect(base_url().'catalogo_producto?m=2&submain=8','refresh');
  }else{
    $numero_archivo=$insert_id.".jpg";
    move_uploaded_file($_FILES['userfileCatalogoFinal']['tmp_name'], $path.$numero_archivo);
     $data = array (
      'nombre_archivo'=>$numero_archivo
      );
    $this->db->where('id_file', $insert_id);
    $this->db->update('archivo',$data);
$catalogo = array (
'id_archivos'=>$insert_id
 );
    $this->db->where('id_catalogo', $id_catalogo);
    $this->db->update('producto_final',$catalogo);

    $this->session->set_flashdata('message', array('3'));
    redirect(base_url().'catalogo_producto?m=2&submain=8','refresh');
  }
}else{
    $this->session->set_flashdata('message', array('4'));
     redirect(base_url().'catalogo_producto?m=2&submain=8','refresh');
}
 }


function obtenerExtensionFichero($str)
{

        return end(explode(".", $str));

}


function do_upload()
{

$extencion=$this->obtenerExtensionFichero($_FILES['userfile']['name']);
if ($extencion=='jpg') {


$data = array (
'nombre'=>$this->input->post('nombre_archivo'),
'descripcion'=>$this->input->post('descripcion_archivo')
 );
$id_catalogo=$this->input->post('id_cat');
$this->db->insert('archivo', $data);
$insert_id = $this->db->insert_id();

$path = $_SERVER['DOCUMENT_ROOT'].'/easy/uploads/';
    $tamano_archivo = $_FILES['userfile']['size'];
    $nombre_archivo = $_FILES['userfile']['name'];
    $tipo_archivo = $_FILES['userfile']['type'];
  if (!strpos($tipo_archivo, "jpeg")){
    $this->session->set_flashdata('message', array('4'));
     redirect(base_url().'catalogo_producto?m=2&submain=8','refresh');
  }else{
    $numero_archivo=$insert_id.".jpg";
    move_uploaded_file($_FILES['userfile']['tmp_name'], $path.$numero_archivo);
     $data = array (
      'nombre_archivo'=>$numero_archivo
      );
    $this->db->where('id_file', $insert_id);
    $this->db->update('archivo',$data);
$catalogo = array (
'id_archivos'=>$insert_id
 );
    $this->db->where('id_catalogo', $id_catalogo);
    $this->db->update('catalogo_producto',$catalogo);

    $this->session->set_flashdata('message', array('3'));
    redirect(base_url().'catalogo_producto?m=2&submain=8','refresh');
  }
}else{
    $this->session->set_flashdata('message', array('4'));
     redirect(base_url().'catalogo_producto?m=2&submain=8','refresh');
}
 }


 ////////////////////////////paginacion de productos requeriada para formulario productos
    public function paginacion_productos()
    {


        $page = $_POST['page'];  // Almacena el numero de pagina actual
        $limite = $_POST['rows']; // Almacena el numero de filas que se van a mostrar por pagina
        $sidx = $_POST['sidx'];  // Almacena el indice por el cual se hará la ordenación de los datos
        $sord = $_POST['sord'];  // Almacena el modo de ordenación

        if(!$sidx) $sidx =1;

        // Se crea la conexión a la base de datos
        // $conexion = new mysqli("servidor","usuario","password","basededatos");
        // Se hace una consulta para saber cuantos registros se van a mostrar

     $consul = $this->db->query('SELECT * from catalogo_producto WHERE activo= "1"');
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
        $resultado_catalogo =$this->producto->get_cat_productos($sidx, $sord, $start, $limite);
        // Se agregan los datos de la respuesta del servidor
        $data->page = $page;
        $data->total = $total_pages;
        $data->records = $count;
        $i=0;
        foreach($resultado_catalogo as $row) {
           $data->rows[$i]['id']=$row->id_catalogo;
           $onclik="onclick=agregar('".$row->id_catalogo."')";
           $acciones='<span style=" cursor:pointer" '.$onclik.'><img src="'.base_url().'img/add_producto.ico" width="18" title="Agregar" height="18" /></span>';
           $data->rows[$i]['cell']=array($acciones,
                $row->nombre_empresa,
                $row->nombre,
                $row->largo,
                $row->ancho,
                $row->alto,
                $row->resistencia,
                $row->corrugado,
                $row->score,
                $row->descripcion
                );
           $i++;
        }
        // La respuesta se regresa como json
        echo json_encode($data);
    }
     ////////////////////////////paginacion de productos requeriada para formulario productos
    public function paginacion_productos_stock()
    {


        $page = $_POST['page'];  // Almacena el numero de pagina actual
        $limite = $_POST['rows']; // Almacena el numero de filas que se van a mostrar por pagina
        $sidx = $_POST['sidx'];  // Almacena el indice por el cual se hará la ordenación de los datos
        $sord = $_POST['sord'];  // Almacena el modo de ordenación

        if(!$sidx) $sidx =1;

        // Se crea la conexión a la base de datos
        // $conexion = new mysqli("servidor","usuario","password","basededatos");
        // Se hace una consulta para saber cuantos registros se van a mostrar

     $consul = $this->db->query('SELECT * from catalogo_producto WHERE activo= "1"');
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
        $resultado_catalogo =$this->producto->get_cat_productos($sidx, $sord, $start, $limite);
        // Se agregan los datos de la respuesta del servidor
        $data->page = $page;
        $data->total = $total_pages;
        $data->records = $count;
        $i=0;
        foreach($resultado_catalogo as $row) {
           $data->rows[$i]['id']=$row->id_catalogo;
           $onclik="onclick=select_producto1('".$row->id_catalogo."')";
           $acciones='<span style=" cursor:pointer" '.$onclik.'><img src="'.base_url().'img/add_producto.ico" width="18" title="Agregar" height="18" /></span>';
           $data->rows[$i]['cell']=array($acciones,
                $row->nombre_empresa,
                $row->nombre,
                $row->largo,
                $row->ancho,
                $row->alto,
                $row->resistencia,
                $row->corrugado,
                $row->score,
                $row->descripcion,
                $row->nombre_producto

                );
           $i++;
        }
        // La respuesta se regresa como json
        echo json_encode($data);
    }
    ///////////////buqueda por palabra catalogo
    //
public function buscandoStock()
{

$filters = $_POST['filters'];

        $where = "";
        if (isset($filters)) {
            $filters = json_decode($filters);
            $where = " where catalogo_producto.activo = 1 AND ";
            $whereArray = array();
            $rules = $filters->rules;

            foreach($rules as $rule) {

                if ($rule->field =='nombre_empresa') {

                  $whereArray[] = ". catalogo_producto.id_cliente=clientes.id_clientes AND clientes.nombre_empresa like '%".$rule->data."%'";

                }elseif ($rule->field=='resistencia') {

                if (($rule->data=='SG')||($rule->data=='sg')) {
                   $whereArray[] = "resistencia_mprima.resistencia LIKE '%".$rule->data."%'";
                    }else{
                   $whereArray[] = "resistencia_mprima.resistencia=".$rule->data." ";
                    }
                }else{

                $whereArray[] = $rule->field." like '%".$rule->data."%'";
                }
            }
            if (count($whereArray)>0) {

                $where .= join(" and ", $whereArray);
            } else {
                $where = " where catalogo_producto.activo = 1 ";
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
 $consul = $this->db->query("SELECT
                                        *
                                        FROM
                                        catalogo_producto,
                                        clientes,
                                        resistencia_mprima
                                         ".$where);
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
        if ($start < 0){

          $start = 0;
         $data[]=0;
        }else{
        $resultado_catalogo =$this->producto->get_cat_productos_search($where, $sidx, $sord, $start, $limite);
        // Se agregan los datos de la respuesta del servidor
        $data->page = $page;
        $data->total = $total_pages;
        $data->records = $count;
        $i=0;

              foreach($resultado_catalogo as $row) {
           $data->rows[$i]['id']=$row->id_catalogo;
           $onclik="onclick=select_producto1('".$row->id_catalogo."')";
           $acciones='<span style=" cursor:pointer" '.$onclik.'><img src="'.base_url().'img/add_producto.ico" width="18" title="Agregar" height="18" /></span>';
           $data->rows[$i]['cell']=array($acciones,
                $row->nombre_empresa,
                $row->nombre,
                $row->largo,
                $row->ancho,
                $row->alto,
                $row->resistencia,
                $row->corrugado,
                $row->score,
                $row->descripcion
                );
           $i++;
        }
    }
        // La respuesta se regresa como json
        echo json_encode($data);
}


public function buscando()
{

$filters = $_POST['filters'];

        $where = "";
        if (isset($filters)) {
            $filters = json_decode($filters);
            $where = " where ";
            $whereArray = array();
            $rules = $filters->rules;

            foreach($rules as $rule) {

                if ($rule->field =='nombre_empresa') {

                  $whereArray[] = "clientes.nombre_empresa like '%".$rule->data."%' AND catalogo_producto.activo = 1 AND resistencia_mprima.id_resistencia_mprima=catalogo_producto.resistencia AND catalogo_producto.id_cliente=clientes.id_clientes";

                }elseif ($rule->field=='resistencia') {

                if (($rule->data=='SG')||($rule->data=='sg')) {
                   $whereArray[] = "resistencia_mprima.resistencia LIKE '%".$rule->data."%' AND catalogo_producto.activo = 1 AND resistencia_mprima.id_resistencia_mprima=catalogo_producto.resistencia AND catalogo_producto.id_cliente=clientes.id_clientes";
                    }else{
                   $whereArray[] = "resistencia_mprima.resistencia=".$rule->data." AND catalogo_producto.activo = 1 AND resistencia_mprima.id_resistencia_mprima=catalogo_producto.resistencia AND catalogo_producto.id_cliente=clientes.id_clientes";
                    }
                }else{

                $whereArray[] = $rule->field." like '%".$rule->data."%'";
                }
            }
            if (count($whereArray)>0) {

                $where .= join(" and ", $whereArray);
            } else {
                $where = " where catalogo_producto.activo = 1 AND catalogo_producto.activo = 1 AND resistencia_mprima.id_resistencia_mprima=catalogo_producto.resistencia AND catalogo_producto.id_cliente=clientes.id_clientes";
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
 $consul = $this->db->query("SELECT
                                        *
                                        FROM
                                        catalogo_producto,
                                        clientes,
                                        resistencia_mprima
                                         ".$where);
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
        if ($start < 0){

          $start = 0;
         $data[]=0;
        }else{
        $resultado_catalogo =$this->producto->get_cat_productos_search($where, $sidx, $sord, $start, $limite);
        // Se agregan los datos de la respuesta del servidor
        $data->page = $page;
        $data->total = $total_pages;
        $data->records = $count;
        $i=0;
if ($this->permisos->permisos(4,2)==1) {

                foreach($resultado_catalogo as $row) {
                   $data->rows[$i]['id']=$row->id_catalogo;
                   ///todos lo permisos
                   if (($this->permisos->permisos(4,1)==1)&&($this->permisos->permisos(4,3)==1)){

                        $onclikedit="onclick=edit('".$row->id_catalogo."')";
                        $onclik="onclick=delet('".$row->id_catalogo."')";

                        if ($row->id_archivos!=0) {
                        $picture="onclick=picture_existe('".$row->id_archivos."','".$row->id_catalogo."')";
                        $acciones='<span style=" cursor:pointer" '.$onclikedit.'><img title="Editar" src="'.base_url().'img/edit.png" width="18" height="18" /></span>&nbsp;<span style=" cursor:pointer" '.$onclik.'><img src="'.base_url().'img/borrar.png" width="18" title="Eliminar" height="18" /></span><span style=" cursor:pointer" '.$picture.'><img title="Nueva imagen" src="'.base_url().'img/add_picture.png" width="18" height="18" /></span>';
                        }else{
                        $picture="onclick=picture('".$row->id_catalogo."')";
                        $acciones='<span style=" cursor:pointer" '.$onclikedit.'><img title="Editar" src="'.base_url().'img/edit.png" width="18" height="18" /></span>&nbsp;<span style=" cursor:pointer" '.$onclik.'><img src="'.base_url().'img/borrar.png" width="18" title="Eliminar" height="18" /></span><span style=" cursor:pointer" '.$picture.'><img title="Nueva imagen" src="'.base_url().'img/view_picture.png" width="18" height="18" /></span>';
                        }


                        // permisos solo para editar
                   }elseif (($this->permisos->permisos(4,1)==1)&&($this->permisos->permisos(4,3)==0)) {

                        $onclikedit="onclick=edit('".$row->id_catalogo."')";
                        //$onclik="onclick=delet('".$row->id_catalogo."')";
                        if ($row->id_archivos!=0) {
                        $picture="onclick=picture_existe('".$row->id_archivos."','".$row->id_catalogo."')";
                        $acciones='<span style=" cursor:pointer" '.$onclikedit.'><img title="Editar" src="'.base_url().'img/edit.png" width="18" height="18" /></span><span style=" cursor:pointer" '.$picture.'><img title="Nueva imagen" src="'.base_url().'img/add_picture.png" width="18" height="18" /></span>';
                        }else{
                        $picture="onclick=picture('".$row->id_catalogo."')";
                        $acciones='<span style=" cursor:pointer" '.$onclikedit.'><img title="Editar" src="'.base_url().'img/edit.png" width="18" height="18" /></span><span style=" cursor:pointer" '.$picture.'><img title="Nueva imagen" src="'.base_url().'img/view_picture.png" width="18" height="18" /></span>';
                        }

                        // permisos solo para eliminar
                   }elseif (($this->permisos->permisos(4,1)==0)&&($this->permisos->permisos(4,3)==1)) {

                        //$onclikedit="onclick=edit('".$row->id_catalogo."')";
                        $onclik="onclick=delet('".$row->id_catalogo."')";
                        if ($row->id_archivos!=0) {
                        $picture="onclick=picture_existe('".$row->id_archivos."','".$row->id_catalogo."')";
                        $acciones='<span style=" cursor:pointer" '.$onclik.'><img src="'.base_url().'img/borrar.png" width="18" title="Eliminar" height="18" /></span><span style=" cursor:pointer" '.$picture.'><img title="Nueva imagen" src="'.base_url().'img/add_picture.png" width="18" height="18" /></span>';
                        }else{
                        $picture="onclick=picture('".$row->id_catalogo."')";
                        $acciones='<span style=" cursor:pointer" '.$onclik.'><img src="'.base_url().'img/borrar.png" width="18" title="Eliminar" height="18" /></span><span style=" cursor:pointer" '.$picture.'><img title="Nueva imagen" src="'.base_url().'img/view_picture.png" width="18" height="18" /></span>';
                        }

// sin permisos
                   }elseif (($this->permisos->permisos(4,1)==0)&&($this->permisos->permisos(4,3)==0)) {

                        //$onclikedit="onclick=edit('".$row->id_catalogo."')";
                        //$onclik="onclick=delet('".$row->id_catalogo."')";
                        $acciones='';

                   }
                   $data->rows[$i]['cell']=array($acciones,
                               $row->nombre_empresa,
                               $row->nombre,
                               $row->largo,
                               $row->ancho,
                               $row->alto,
                               $row->resistencia,
                               $row->corrugado,
                               $row->score,
                               $row->descripcion);
                   $i++;
                }
        }
    }
        // La respuesta se regresa como json
        echo json_encode($data);
}


public function buscando_stock()
{

$filters = $_POST['filters'];

        $where = "";
        if (isset($filters)) {
            $filters = json_decode($filters);
            $where = " where catalogo_producto.activo = 1 AND ";
            $whereArray = array();
            $rules = $filters->rules;

            foreach($rules as $rule) {

                if ($rule->field =='nombre_empresa') {

                  $whereArray[] = ". catalogo_producto.id_cliente=clientes.id_clientes AND clientes.nombre_empresa like '%".$rule->data."%'";

                }elseif ($rule->field=='resistencia') {

                if (($rule->data=='SG')||($rule->data=='sg')) {
                   $whereArray[] = "resistencia_mprima.resistencia LIKE '%".$rule->data."%'";
                    }else{
                   $whereArray[] = "resistencia_mprima.resistencia=".$rule->data." ";
                    }
                }else{

                $whereArray[] = $rule->field." like '%".$rule->data."%'";
                }
            }
            if (count($whereArray)>0) {

                $where .= join(" and ", $whereArray);
            } else {
                $where = " where catalogo_producto.activo = 1 ";
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
 $consul = $this->db->query("SELECT
                                        *
                                        FROM
                                        catalogo_producto,
                                        clientes,
                                        resistencia_mprima
                                         ".$where);
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
        if ($start < 0){

          $start = 0;
         $data[]=0;
        }else{
        $resultado_catalogo =$this->producto->get_cat_productos_search($where, $sidx, $sord, $start, $limite);
        // Se agregan los datos de la respuesta del servidor
        $data->page = $page;
        $data->total = $total_pages;
        $data->records = $count;
        $i=0;
if ($this->permisos->permisos(4,2)==1) {

                foreach($resultado_catalogo as $row) {
                   $data->rows[$i]['id']=$row->id_catalogo;
                   ///todos lo permisos
                   if (($this->permisos->permisos(4,1)==1)&&($this->permisos->permisos(4,3)==1)){

                        $onclikedit="onclick=edit('".$row->id_catalogo."')";
                        $onclik="onclick=delet('".$row->id_catalogo."')";

                        if ($row->id_archivos!=0) {
                        $picture="onclick=picture_existe('".$row->id_archivos."','".$row->id_catalogo."')";
                        $acciones='<span style=" cursor:pointer" '.$onclikedit.'><img title="Editar" src="'.base_url().'img/edit.png" width="18" height="18" /></span>&nbsp;<span style=" cursor:pointer" '.$onclik.'><img src="'.base_url().'img/borrar.png" width="18" title="Eliminar" height="18" /></span><span style=" cursor:pointer" '.$picture.'><img title="Nueva imagen" src="'.base_url().'img/add_picture.png" width="18" height="18" /></span>';
                        }else{
                        $picture="onclick=picture('".$row->id_catalogo."')";
                        $acciones='<span style=" cursor:pointer" '.$onclikedit.'><img title="Editar" src="'.base_url().'img/edit.png" width="18" height="18" /></span>&nbsp;<span style=" cursor:pointer" '.$onclik.'><img src="'.base_url().'img/borrar.png" width="18" title="Eliminar" height="18" /></span><span style=" cursor:pointer" '.$picture.'><img title="Nueva imagen" src="'.base_url().'img/view_picture.png" width="18" height="18" /></span>';
                        }


                        // permisos solo para editar
                   }elseif (($this->permisos->permisos(4,1)==1)&&($this->permisos->permisos(4,3)==0)) {

                        $onclikedit="onclick=edit('".$row->id_catalogo."')";
                        //$onclik="onclick=delet('".$row->id_catalogo."')";
                        if ($row->id_archivos!=0) {
                        $picture="onclick=picture_existe('".$row->id_archivos."','".$row->id_catalogo."')";
                        $acciones='<span style=" cursor:pointer" '.$onclikedit.'><img title="Editar" src="'.base_url().'img/edit.png" width="18" height="18" /></span><span style=" cursor:pointer" '.$picture.'><img title="Nueva imagen" src="'.base_url().'img/add_picture.png" width="18" height="18" /></span>';
                        }else{
                        $picture="onclick=picture('".$row->id_catalogo."')";
                        $acciones='<span style=" cursor:pointer" '.$onclikedit.'><img title="Editar" src="'.base_url().'img/edit.png" width="18" height="18" /></span><span style=" cursor:pointer" '.$picture.'><img title="Nueva imagen" src="'.base_url().'img/view_picture.png" width="18" height="18" /></span>';
                        }

                        // permisos solo para eliminar
                   }elseif (($this->permisos->permisos(4,1)==0)&&($this->permisos->permisos(4,3)==1)) {

                        //$onclikedit="onclick=edit('".$row->id_catalogo."')";
                        $onclik="onclick=delet('".$row->id_catalogo."')";
                        if ($row->id_archivos!=0) {
                        $picture="onclick=picture_existe('".$row->id_archivos."','".$row->id_catalogo."')";
                        $acciones='<span style=" cursor:pointer" '.$onclik.'><img src="'.base_url().'img/borrar.png" width="18" title="Eliminar" height="18" /></span><span style=" cursor:pointer" '.$picture.'><img title="Nueva imagen" src="'.base_url().'img/add_picture.png" width="18" height="18" /></span>';
                        }else{
                        $picture="onclick=picture('".$row->id_catalogo."')";
                        $acciones='<span style=" cursor:pointer" '.$onclik.'><img src="'.base_url().'img/borrar.png" width="18" title="Eliminar" height="18" /></span><span style=" cursor:pointer" '.$picture.'><img title="Nueva imagen" src="'.base_url().'img/view_picture.png" width="18" height="18" /></span>';
                        }

// sin permisos
                   }elseif (($this->permisos->permisos(4,1)==0)&&($this->permisos->permisos(4,3)==0)) {

                        //$onclikedit="onclick=edit('".$row->id_catalogo."')";
                        //$onclik="onclick=delet('".$row->id_catalogo."')";
                        $acciones='';

                   }
                   $data->rows[$i]['cell']=array($acciones,
                               $row->nombre_empresa,
                               $row->nombre,
                               $row->largo,
                               $row->ancho,
                               $row->alto,
                               $row->resistencia,
                               $row->corrugado,
                               $row->score,
                               $row->descripcion);
                   $i++;
                }
        }
    }
        // La respuesta se regresa como json
        echo json_encode($data);
}
// paginacion para mostrar los productos del pedido
public function paginacionComponentesPedido($id_row)
{
        $page = $_POST['page'];  // Almacena el numero de pagina actual
        $limite = $_POST['rows']; // Almacena el numero de filas que se van a mostrar por pagina
        $sidx = $_POST['sidx'];  // Almacena el indice por el cual se hará la ordenación de los datos
        $sord = $_POST['sord'];  // Almacena el modo de ordenación

        if(!$sidx) $sidx =1;

        // Se crea la conexión a la base de datos
        // $conexion = new mysqli("servidor","usuario","password","basededatos");
        // Se hace una consulta para saber cuantos registros se van a mostrar

     $consul = $this->db->query("SELECT
                                        *
                                        FROM
                                        catalogo_producto
                                        WHERE
                                        catalogo_producto.activo = 1 AND catalogo_producto.id_productoFinal = ".$id_row." ");
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
        if ($start < 0){

          $start = 0;
         $data[]=0;
        }else{
        $resultado_catalogo =$this->producto->get_cat_productosID($sidx, $sord, $start, $limite,$id_row);
        // Se agregan los datos de la respuesta del servidor
        $data->page = $page;
        $data->total = $total_pages;
        $data->records = $count;
        $i=0;
if ($this->permisos->permisos(8,2)==1) {

                  foreach($resultado_catalogo as $row) {
                   $data->rows[$i]['id']=$row->id_catalogo;
                   ///todos lo permisos
                   if (($this->permisos->permisos(8,1)==1)&&($this->permisos->permisos(8,3)==1)){

                        $onclikedit="onclick=edit('".$row->id_catalogo."','1')";
                        $onclik="onclick=delet('".$row->id_catalogo."','1')";

                        if ($row->id_archivos!=0) {
                        $picture="onclick=picture_existe('".$row->id_archivos."','".$row->id_catalogo."','1')";
                        $acciones='<span style=" cursor:pointer" '.$onclikedit.'><img title="Editar" src="'.base_url().'img/edit.png" width="18" height="18" /></span>&nbsp;<span style=" cursor:pointer" '.$onclik.'><img src="'.base_url().'img/borrar.png" width="18" title="Eliminar" height="18" /></span><span style=" cursor:pointer" '.$picture.'><img title="Nueva imagen" src="'.base_url().'img/add_picture.png" width="18" height="18" /></span>';
                        }else{
                        $picture="onclick=picture('".$row->id_catalogo."')";
                        $acciones='<span style=" cursor:pointer" '.$onclikedit.'><img title="Editar" src="'.base_url().'img/edit.png" width="18" height="18" /></span>&nbsp;<span style=" cursor:pointer" '.$onclik.'><img src="'.base_url().'img/borrar.png" width="18" title="Eliminar" height="18" /></span><span style=" cursor:pointer" '.$picture.'><img title="Nueva imagen" src="'.base_url().'img/view_picture.png" width="18" height="18" /></span>';
                        }


                        // permisos solo para editar
                   }elseif (($this->permisos->permisos(8,1)==1)&&($this->permisos->permisos(8,3)==0)) {

                        $onclikedit="onclick=edit('".$row->id_catalogo."','1')";
                        //$onclik="onclick=delet('".$row->id_catalogo."','1')";
                        if ($row->id_archivos!=0) {
                        $picture="onclick=picture_existe('".$row->id_archivos."','".$row->id_catalogo."','1')";
                        $acciones='<span style=" cursor:pointer" '.$onclikedit.'><img title="Editar" src="'.base_url().'img/edit.png" width="18" height="18" /></span><span style=" cursor:pointer" '.$picture.'><img title="Nueva imagen" src="'.base_url().'img/add_picture.png" width="18" height="18" /></span>';
                        }else{
                        $picture="onclick=picture('".$row->id_catalogo."')";
                        $acciones='<span style=" cursor:pointer" '.$onclikedit.'><img title="Editar" src="'.base_url().'img/edit.png" width="18" height="18" /></span><span style=" cursor:pointer" '.$picture.'><img title="Nueva imagen" src="'.base_url().'img/view_picture.png" width="18" height="18" /></span>';
                        }

                        // permisos solo para eliminar
                   }elseif (($this->permisos->permisos(8,1)==0)&&($this->permisos->permisos(8,3)==1)) {

                        //$onclikedit="onclick=edit('".$row->id_catalogo."','1')";
                        $onclik="onclick=delet('".$row->id_catalogo."','1')";
                        if ($row->id_archivos!=0) {
                        $picture="onclick=picture_existe('".$row->id_archivos."','".$row->id_catalogo."','1')";
                        $acciones='<span style=" cursor:pointer" '.$onclik.'><img src="'.base_url().'img/borrar.png" width="18" title="Eliminar" height="18" /></span><span style=" cursor:pointer" '.$picture.'><img title="Nueva imagen" src="'.base_url().'img/add_picture.png" width="18" height="18" /></span>';
                        }else{
                        $picture="onclick=picture('".$row->id_catalogo."')";
                        $acciones='<span style=" cursor:pointer" '.$onclik.'><img src="'.base_url().'img/borrar.png" width="18" title="Eliminar" height="18" /></span><span style=" cursor:pointer" '.$picture.'><img title="Nueva imagen" src="'.base_url().'img/view_picture.png" width="18" height="18" /></span>';
                        }

// sin permisos
                   }elseif (($this->permisos->permisos(8,1)==0)&&($this->permisos->permisos(8,3)==0)) {

                        //$onclikedit="onclick=edit('".$row->id_catalogo."','1')";
                        //$onclik="onclick=delet('".$row->id_catalogo."','1')";
                        $acciones='';

                   }
                   $data->rows[$i]['cell']=array(
                               // $row->nombre_empresa,
                               $row->nombre,
                               $row->largo,
                               $row->ancho,
                               $row->alto,
                               $row->resistencia,
                               $row->corrugado,
                               $row->score,
                               $row->descripcion
                               );
                   $i++;
                }
        }
    }
        // La respuesta se regresa como json
        echo json_encode($data);
    }

}

/* End of file catalogo_producto.php */
/* Location: ./application/controllers/catalogo_producto.php */