<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pedidos_bodega_prod_term_model extends CI_Model {

public function get_pedido_bodega_producto($sidx, $sord, $start, $limite)
	{
		$query = $this->db->query("SELECT
										pedido_bodega_producto_terminado.id_pedido,
										pedido_bodega_producto_terminado.fecha_pedido,
										pedido_bodega_producto_terminado.fecha_entrega,
										oficina.nombre_oficina,
										clientes.nombre_empresa,
										pedido_bodega_producto_terminado.activo
										FROM
										pedido_bodega_producto_terminado ,
										oficina,
										clientes
										WHERE
										pedido_bodega_producto_terminado.oficina_pedido = oficina.id_oficina AND
										clientes.id_clientes = pedido_bodega_producto_terminado.cliente
										ORDER BY $sidx $sord
										LIMIT $start, $limite;"
								);
		return ($query->num_rows()> 0)? $query->result() : NULL;
	}

	public function guardar_pedido()
   {
		   		$data = array (
		   		'fecha_pedido'=>date("Y-m-d"),
			   	'fecha_entrega'=>$this->input->post('fecha_entrega'),
				'cliente'=>$this->input->post('clientes'),
				'oficina_pedido'=>$this->input->post('oficina_pedido'),
				'id_usuario'=>$this->session->userdata('id'),
				'id_sucursal'=>$this->session->userdata('oficina')

			);
   		$this->db->insert('pedido_bodega_producto_terminado', $data);
		return $this->db->affected_rows();
	}

public function guardar()
   {
   		$data = array (
					   	'catalogo_producto'=>$this->input->post('catalogo_producto'),
						'cantidad'=>$this->input->post('cantidad'),
						'observaciones'=>$this->input->post('observaciones_bodega_pedido'),
						'id_pedido'=>$this->input->post('id_pedido'),
        				'id_usuario'=>$this->session->userdata('id'),
        				'id_sucursal'=>$this->session->userdata('oficina'),
        				'id_bodega_hacer'=>$this->input->post('oficina_pedido_hacer'),
        				'fecha_entrega'=>$this->input->post('fecha_entrega_pedido')
						);
   		$this->db->insert('cantidad_pedido_producto', $data);
		return $this->db->affected_rows();
   }

   public function get_id($id)
    {
        $query = $this->db->query("SELECT
        								pedido_bodega_producto_terminado.fecha_entrega,
										pedido_bodega_producto_terminado.oficina_pedido,
										pedido_bodega_producto_terminado.cliente
										FROM
										pedido_bodega_producto_terminado
										WHERE
										pedido_bodega_producto_terminado.id_pedido = $id ");
        $fila = $query->row();
          return $fila;
    }

   public function editar($id)
   {
	   	$data = array (
			   	'fecha_entrega'=>$this->input->post('fecha_entrega'),
				'cliente'=>$this->input->post('clientes'),
				'oficina_pedido'=>$this->input->post('oficina_pedido'),
				'id_usuario'=>$this->session->userdata('id'),
				'id_sucursal'=>$this->session->userdata('oficina')
						);

	$this->db->where('id_pedido', $id);
	$this->db->update('pedido_bodega_producto_terminado',$data);
   }

      public function eliminar_producto($id)
   {
	    $data = array('id_cantidad_pedido' => $id);
				$this->db->delete('cantidad_pedido_producto',$data);
				return $this->db->affected_rows();
   }
	/////////////////////cerrar pedido /////////////////////
	public function cerrar($id)
	{
	    $data = array('activo' => 0);
				$this->db->where('id_pedido', $id);
				$this->db->update('pedido_bodega_producto_terminado', $data);
				return $this->db->affected_rows();
	}
}

/* End of file pedidos_bodega_prod_term_model.php */
/* Location: ./application/controllers/pedidos_bodega_prod_term_model.php */