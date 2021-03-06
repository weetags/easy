<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Historial_reutilizable_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

public function guardar_select()
{
		$cantidad=$this->input->post('cantidad');
		$id_reutilizable=$this->input->post('id_reutilizable');
		$id_producto_post=$this->input->post('id_producto');
	$material=$this->db->query("SELECT
									cat_mprima_reutilizable.nombre,
									cat_mprima_reutilizable.largo,
									cat_mprima_reutilizable.ancho,
									cat_mprima_reutilizable.resistencia_mprima_id_resistencia_mprima,
									cat_mprima_reutilizable.tipo,
									cat_mprima_reutilizable.tipo_m,
									cat_mprima_reutilizable.restan,
									cat_mprima_reutilizable.cantidad
									FROM
									cat_mprima_reutilizable
									WHERE
									cat_mprima_reutilizable.id_cat_mprima=$id_reutilizable
									AND cat_mprima_reutilizable.id_sucursal= ".$this->session->userdata('oficina')." "
									);
	$resultado=$material->row();
	if (count($resultado)>0) {

		$cantidad_sistema=$resultado->cantidad;
		if ($cantidad>$cantidad_sistema) {
			return 4;
		}elseif (count($resultado)>0) {


		$data=array (
			'nombre'=>$resultado->nombre,
			'corrugado'=>$resultado->tipo_m,
	        'largo'=>$resultado->largo,
	        'ancho'=>$resultado->ancho,
	        'resistencia'=>$resultado->resistencia_mprima_id_resistencia_mprima,
	        'cantidad'=>$cantidad,
	        'fecha_uso'=>date('y-m-d'),
	        'id_usuario'=>$this->session->userdata('id'),
	        'id_sucursal'=>$this->session->userdata('oficina'),
	        'id_producto_uso_material'=>$id_producto_post,

			);

        $this->db->insert('historial_reutilizable', $data);
         $numero_rows1=$this->db->affected_rows();
        if ($numero_rows1>0) {
				$cantidad_db=$resultado->cantidad;
				$result=$cantidad_db-$cantidad;
				$data2= array ('cantidad'=>$result);
           	 	$this->db->where('id_cat_mprima',$id_reutilizable);
        	    $this->db->update('cat_mprima_reutilizable',$data2);
        	    return 1;
		}else{
			return 2;
		}
	}

	}else{
		return 0;
	}

}



}

/* End of file historial_reutilizable_model.php */
/* Location: ./application/models/historial_reutilizable_model.php */
