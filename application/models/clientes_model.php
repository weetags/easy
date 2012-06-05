<?php 
/**
* 
*/
class Clientes_model extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
	}

	public function get_clientes($sidx, $sord, $start, $limite)
	{
		$query = $this->db->query("SELECT
										clientes.id_clientes,
										clientes.nombre_empresa,
										clientes.nombre_contacto,
										clientes.tipo_persona,
										clientes.rfc,
										estados.dsc_estado,
										clientes.cp,
										clientes.direccion,
										clientes.ciudad,
										clientes.lada,
										clientes.num_telefono,
										clientes.ext,
										clientes.fax,
										clientes.email,
										clientes.comentario,
										clientes.fecha_ingreso
										FROM
										clientes ,
										estados
										WHERE
										clientes.activo = 1 AND
										clientes.estado_id_estado = estados.id_estado
									ORDER BY $sidx $sord 
									LIMIT $start, $limite;"
									);
		return ($query->num_rows()> 0)? $query->result() : NULL;
	}

    public function get_id($id)
    {
        $query = $this->db->query("SELECT 	clientes.nombre_empresa,
											clientes.nombre_contacto,
											clientes.tipo_persona,
											clientes.rfc,
											clientes.estado_id_estado,
											clientes.cp,
											clientes.direccion,
											clientes.ciudad,
											clientes.lada,
											clientes.num_telefono,
											clientes.ext,
											clientes.fax,
											clientes.email,
											clientes.comentario
											FROM
											clientes
											WHERE
											clientes.activo = 1 AND
											clientes.id_clientes = $id
											GROUP BY
											clientes.id_clientes
											ORDER BY
											clientes.nombre_empresa ASC");
				        $fila = $query->row();
				          return $fila;    
	}

   public function editar($id)
   {
	   	$data = array (
		'nombre_empresa'=>$this->input->post('nombre_empresa'),
		'nombre_contacto'=>$this->input->post('nombre_contacto'),
		'tipo_persona'=>$this->input->post('tipo_persona'),
		'rfc'=>$this->input->post('rfc'),
		'estado_id_estado'=>$this->input->post('estado_id_estado'),
		'cp'=>$this->input->post('cp'),
		'direccion'=>$this->input->post('direccion'),
		'ciudad'=>$this->input->post('ciudad'),
		'lada'=>$this->input->post('lada'),
		'num_telefono'=>$this->input->post('num_telefono'),
		'ext'=>$this->input->post('ext'),
		'fax'=>$this->input->post('fax'),
		'email'=>$this->input->post('email'),
		'comentario'=>$this->input->post('comentario')
	);

	$this->db->where('id_clientes', $id);
	$this->db->update('clientes',$data);
   }

   public function guardar()
   {
		   			$data = array (
		   				'nombre_empresa'=>$this->input->post('nombre_empresa'),
					   	'nombre_contacto'=>$this->input->post('nombre_contacto'),
						'tipo_persona'=>$this->input->post('tipo_persona'),
						'rfc'=>$this->input->post('rfc'),
						'estado_id_estado'=>$this->input->post('estado_id_estado'),
						'cp'=>$this->input->post('cp'),
						'direccion'=>$this->input->post('direccion'),
						'ciudad'=>$this->input->post('ciudad'),
						'lada'=>$this->input->post('lada'),
						'num_telefono'=>$this->input->post('num_telefono'),
						'ext'=>$this->input->post('ext'),
						'fax'=>$this->input->post('fax'),
						'email'=>$this->input->post('email'),
						'comentario'=>$this->input->post('comentario'),
						'fecha_ingreso'=>date('y-m-d'),
						'activo'=>1
						);
   		$this->db->insert('clientes', $data);
		return $this->db->affected_rows(); 
   }

   public function eliminar($id)
   {
	    $data = array('activo' => 0);
				$this->db->where('id_clientes', $id);
				$this->db->update('clientes', $data);
				return $this->db->affected_rows();
   }

   
}
?>