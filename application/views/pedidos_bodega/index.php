<?php $this->load->view('hed');?>
<script>
//////////////////////////////////////////// Alerta Pedido cerrado ///////////////////////////////////////////////////////////
function cerrado () {

   $( "#dialogo_cerrado" ).dialog({
      autoOpen: false,
      height: 'auto',
      width: 'auto',
      modal: true,
      buttons: {
      Cerrar:function()
        {
         $( "#dialogo_cerrado" ).dialog( "close" );
        }
      },
      close: function() {}
    });
        $( "#dialogo_cerrado" ).dialog( "open" );

}
//////////////////////////////////////////// Alerta producto - Pedido cerrado ////////////////////////////////////////////////

function pedido_cerrado () {

   $( "#dialogo_cerrado" ).dialog({
      autoOpen: false,
      height: 'auto',
      width: 'auto',
      modal: true,
      buttons: {

          Cerrar:function()
          {
        $( "#dialogo_cerrado" ).dialog( "close" );
          }
      },
      close: function() {}
    });
        $( "#dialogo_cerrado" ).dialog( "open" );

}

//////////////////////////////////////////// Espera id del pedido y confirmacion para cerrarlo ////////////////////
function cerrar_pedido(id,confirmacion)
{
if(confirmacion==true)
{
  $.ajax({
          async:true,cache: false,
          beforeSend:function(objeto){$('#loading').html('<img src="<?php echo base_url();?>img/ajax-loader.gif" width="28" height="28" />');},
          type:"POST",
          url:"<?php echo base_url();?>pedidos_bodega/cerrar_pedido/"+id,
          datatype:"html",
          success:function(data, textStatus){

              switch(data){
                case "0":
                  notify("Error al procesar los datos " ,500,5000,'error');
                break;
                case "1":
                  $( "#dialogo" ).dialog( "close" );
                  $("#tbl_p_prove").trigger("reloadGrid");
                  notify('Pedido cerrado satisfactoriamente',500,5000,'aviso');

              break;

              default:
              $( "#dialogo").dialog( "close" );

              break;

              }//switch
              },
              error:function(datos){
              notify("Error inesperado" ,500,5000,'error');
              }//Error
              });//Ajax
}

}
//////////////////////////////////////////// Funcion para cerrar Pedido a proveedores /////////////////////////////
function abierto (id) {
  $( "#dialogo" ).dialog({
      autoOpen: false,
      height: 'auto',
      width: 'auto',
      modal: true,
      buttons: {
          Aceptar: function() {
            var confirmacion=true;
           cerrar_pedido(id,confirmacion);
          },
          Cancelar:function()
          {
        $( "#dialogo" ).dialog( "close" );
          }
      },
      close: function() {}
    });
        $( "#dialogo" ).dialog( "open" );

}

////////////////////////////////////////////agregar producto///////////////////////////////////////////////////////
function add(id)
{

$( "#id_pedido").val(id);
$( "#dialog-procesos_producto" ).dialog({
      autoOpen: false,
      height: 'auto',
      width: 'auto',
      modal: true,
      buttons: {
          Aceptar: function() {
          $( "#dialog-procesos_producto" ).dialog( "close" );
          $("#tbl_p_prove").trigger("reloadGrid");
          }
      },
      close: function() {}
    });
        $( "#dialog-procesos_producto" ).dialog( "open" );
        $("#tbl_p_prove").trigger("reloadGrid");
}



/////////////////////////////////////////////////////////////////
function alta()
{

document.editar_pedido.reset();

$( "#dialog-procesos" ).dialog({
      autoOpen: false,
      height: 'auto',
      width: 'auto',
      modal: true,
      buttons: {
          Aceptar: function() {
            if (validarCamposForm1()==true) {
              guardar_pedido();
            }

          },
          Cancelar:function()
          {
        $( "#dialog-procesos" ).dialog( "close" );
          }
      },
      close: function() {}
    });
        $( "#dialog-procesos" ).dialog( "open" );
}

function edit(id)
{
document.editar_pedido.reset();
$.ajax({
            async:true,cache: false,
            beforeSend:function(objeto){$('#loading').html('<img src="<?php echo base_url();?>img/ajax-loader.gif" width="28" height="28" />');},
            type:"GET",
            url:"<?php echo base_url();?>pedidos_bodega/get/"+id+"/"+Math.random()*10919939116744,
            datatype:"html",
            success:function(data, textStatus){
            dato= data.split('~');
            $("#fecha_entrega").val(dato[0]);
            $("#oficina_pedido").val(dato[1]);
            $("#oficina").val(dato[2]);
            },
        error:function(datos){
        notify("Error al procesar los datos " ,500,5000,'error');
          return false;
        }//Error
        });//Ajax


$( "#dialog-procesos" ).dialog({
      autoOpen: false,
      height: 'auto',
      width: 'auto',
      modal: true,
      buttons: {
          Aceptar: function() {
            if (validarCamposForm1()==true) {
          editar(id);
           }
            },
          Cancelar:function()
          {
              $( "#dialog-procesos" ).dialog( "close" );
          }
      },
      close: function() {}
    });
        $( "#dialog-procesos" ).dialog( "open" );

}
//Funcion editar empleado

function editar(id)
{

$.ajax({
          async:true,cache: false,
          beforeSend:function(objeto){$('#loading').html('<img src="<?php echo base_url();?>img/ajax-loader.gif" width="28" height="28" />');},
           type:"POST",
            url:"<?php echo base_url();?>pedidos_bodega/editar_pedido/"+id,
          data:{"fecha_entrega":$("#fecha_entrega").val(),
                  "oficina_pedido":$("#oficina_pedido").val(),
                  "oficina":$("#oficina").val()},

                     datatype:"html",
                      success:function(data, textStatus){

                          switch(data){
                              case "0":
                                  notify("Error al procesar los datos " ,500,5000,'error');
                              break;

                              case "1":
                                  $("#tbl_p_prove").trigger("reloadGrid");
                                  notify('El registro se guardado correctamente',500,5000,'aviso');
                                  $( "#dialog-procesos" ).dialog( "close" );
                              break;

                              default:
                                  $( "#dialog-procesos" ).dialog( "close" );
                                  var error='Error'+data;
                                 notify(error ,500,5000,'error');
                              break;
                              }//switch
                             },
                        error:function(datos){
                             notify("Error inesperado" ,500,5000,'error');
                             }//Error
                         });//Ajax


}



function guardar_pedido()
{
$.ajax({
          async:true,cache: false,
          beforeSend:function(objeto){$('#loading').html('<img src="<?php echo base_url();?>img/ajax-loader.gif" width="28" height="28" />');},
           type:"POST",
            url:"<?php echo base_url();?>pedidos_bodega/guardar_pedido?da="+Math.random()*2312,
          data:{"fecha_entrega":$("#fecha_entrega").val(),
                            "oficina_pedido":$("#oficina_pedido").val(),
                  "oficina":$("#oficina").val()},

                     datatype:"html",
                      success:function(data, textStatus){

                      switch(data){
                               case "0":
                                  notify("Error al procesar los datos " ,500,5000,'error');
                                break;

                                case "1":
                                   $("#tbl_p_prove").trigger("reloadGrid");
                                    notify('El registro se guardado correctamente',500,5000,'aviso');
                                   $( "#dialog-procesos" ).dialog( "close" );
                                break;

                                default:
                                   $( "#dialog-procesos" ).dialog( "close" );
                                  var error='Error'+data;
                                 notify(error ,500,5000,'error');
                                break;

                              }//switch
                             },
                        error:function(datos){
                             notify("Error inesperado" ,500,5000,'error');
                             }//Error
                         });//Ajax

}

function eliminar_producto(id) {
  msg="Este producto se eliminara. ¿Estás seguro?";
  confirmacion_producto_(id,msg);
}
function eliminar_producto_(id)
{

  $.ajax({
                      async:true,cache: false,
                      beforeSend:function(objeto){$('#loading').html('<img src="<?php echo base_url();?>img/ajax-loader.gif" width="28" height="28" />');},
                      type:"POST",
                      url:"<?php echo base_url();?>pedidos_bodega/eliminar_producto/"+id,
                      datatype:"html",
                      success:function(data, textStatus){

                             switch(data){
                               case "0":
                                  notify("Error al procesar los datos " ,500,5000,'error');
                                break;
                               case "1":
                                    $( "#dialog-procesos" ).dialog( "close" );

                                 $("#tbl_p_prove").trigger("reloadGrid");
                                 notify('El registro se elimino correctamente',500,5000,'aviso');
                                break;

                                   default:
                                   $( "#dialog-procesos" ).dialog( "close" );
                                 break;

                              }//switch
                             },
                        error:function(datos){
                              notify("Error inesperado" ,500,5000,'error');
                             }//Error
                         });//Ajax
}
function eliminar_pedido(id) {
  msg="Este pedido se eliminara. ¿Estás seguro?";
  confirmacion_eliminar(id,msg);
}
///////////////////////////////////////eliminar pedido con sus productos ////////////////////////////////////////
function eliminar_pedido_(id)
{
  $.ajax({
                      async:true,cache: false,
                      beforeSend:function(objeto){$('#loading').html('<img src="<?php echo base_url();?>img/ajax-loader.gif" width="28" height="28" />');},
                      type:"POST",
                      url:"<?php echo base_url();?>pedidos_bodega/eliminar_pedido/"+id,
                      datatype:"html",
                      success:function(data, textStatus){

                             switch(data){
                               case "0":
                                  notify("Error al procesar los datos " ,500,5000,'error');
                                break;
                               case "1":
                               $("#tbl_p_prove").jqGrid("GridUnload");
                                    $( "#dialog-procesos" ).dialog( "close" );
                                 notify('El registro se elimino correctamente',500,5000,'aviso');
                                  setTimeout("cargar()",1000);
                                break;

                                   default:
                                   $( "#dialog-procesos" ).dialog( "close" );
                                 break;

                              }//switch
                             },
                        error:function(datos){
                              notify("Error inesperado" ,500,5000,'error');
                             }//Error
                         });//Ajax
}

////////////////////////////////////////////////////////////////////////
  $(document).ready(function(){
  $("#tbl_p_prove").jqGrid({
    url:'<?php echo base_url();?>pedidos_bodega/paginacion',
    datatype: "json",
    mtype: 'POST',

                        colNames:['Acciones',
                                    'PROVEEDOR',
                                    // 'ID PEDIDO',
                                    // 'FECHA DE PEDIDO',
                                    'FECHA DE ENTREGA'
                                    
                                    // 'LUGAR DE ENVIO'
                                    ],
                        colModel:[{name:'acciones', index:'acciones', width:120, resizable:true, align:"center", search:false},
                                {name:'nombre_oficina', index:'nombre_oficina', width:170,resizable:true, sortable:true,search:false,editable:true},
                                  // {name:'id_pedido', index:'id_pedido', width:30,resizable:true, sortable:true,search:false,editable:true},
                                  // {name:'fecha_pedido', index:'fecha_pedido', width:30,resizable:true, sortable:true,search:false,editable:true},
                                  {name:'fecha_entrega', index:'fecha_entrega', width:200,resizable:true, sortable:true,search:false,editable:true}
                                  
                                  // {name:'nombre_oficina', index:'nombre_oficina', width:90,resizable:true, sortable:true,search:false,editable:true}
                                ],
    pager: jQuery('#paginacion'),
    rownumbers:true,
  rowNum:15,
    rowList:[10,20,30],
    imgpath: '<?php echo base_url();?>img/editar.jpg',
    mtype: "POST",
    sortname: 'id_pedido',
    viewrecords: true,
    sortorder: "desc",
  editable: true,
    caption: 'Pedidos a Bodega Materia Prima',
    multiselect: false,
    height:'auto',
    loadtext: 'Cargando',
  width:'100%',
  subGrid: true,
    //searchurl:'<?php echo base_url();?>empresas/buscando',
    height:"auto",
   subGridRowExpanded: function(subgrid_id, row_id) {
   var subgrid_table_id, pager_id; subgrid_table_id = subgrid_id+"_t"; pager_id = "p_"+subgrid_table_id;

   $("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' alt='subtabla' class='scroll'></table><div id='"+pager_id+"' class='scroll'></div>");

   $("#"+subgrid_table_id).jqGrid({
   //url:"subgrid.php?q=2&id="+row_id,
   url:"<?php echo base_url();?>pedidos_bodega/subpaginacion/"+row_id,
   datatype: "json",
   mtype: 'POST',
   colNames: ['ACCI&Oacute;N','NOMBRE','CLAVE','CANTIDAD'],
   colModel: [
             {name:"acciones",index:"acciones",width:56,align:"center"},
             // {name:"No",index:"No",width:30,align:"center"},
             {name:"descripcion",index:"descripcion",width:180,search: false,align:"center"},
             {name:"nombre",index:"nombre",width:180,align:"left",search: false},
             {name:"cantidad",index:"cantidad",width:56,align:"left",search: false}
              ],
   // rows:10,
   // rowNum:10,
   // rowList:[10,15],
   pager: pager_id,
   sortname: 'id_cantidad_pedido',
   height:'auto',
   sortorder: "asc" });

   $("#"+subgrid_table_id).jqGrid('navGrid',"#"+pager_id,{edit:false,add:false,del:false,search:false}) }
        }).navGrid("#paginacion", { edit: false, add: false, search: false, del: false, refresh:true });
        $("#tbl_p_prove").jqGrid('filterToolbar', { stringResult: true, searchOnEnter: false }) ;
      $("#tbl_p_prove").jqGrid('navGrid','#paginacion',{add:false,edit:false,del:false,search:false});
   });
///////////////////dialogo de confirmacion////////////////////////////////////
  function confirmacion_eliminar (id,msg) {
$('#dialog-confirm').html('<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>'+msg+'</p>');

    $( "#dialog-confirm" ).dialog({
      resizable: false,
      height: 'auto',
      width: 'auto',
      modal: true,
      buttons: {
        "Eliminar": function() {
          $( this ).dialog( "close" );
          eliminar_pedido_(id);
        },
        Cancel: function() {
          $( this ).dialog( "close" );
        }
      }
    });
    }
function confirmacion_producto_ (id,msg) {
$('#dialog-confirm').html('<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>'+msg+'</p>');

    $( "#dialog-confirm" ).dialog({
      resizable: false,
      height: 'auto',
      width: 'auto',
      modal: true,
      buttons: {
        "Eliminar": function() {
          $( this ).dialog( "close" );
          eliminar_producto_(id);
        },
        Cancel: function() {
          $( this ).dialog( "close" );
        }
      }
    });
    }
//////////////////////////////////////////////////////////////////////////////
function validarCamposForm1 () {
  fecha_entrega=$('#fecha_entrega').val();
  proveedor_id_proveedor=$('#oficina_pedido').val();
  oficina=$('#oficina').val();
  if (validarVacio(fecha_entrega)==false) {
      notify('* El campo <strong>FECHA DE ENTREGA</strong> no puede estar vacio!!!',500,5000,'error');
    $("#fecha_entrega").focus();
    return false;
  }else if (validarCombo(proveedor_id_proveedor)==false) {
      notify('* Debe seleccionar almenos una opcion de la lista <strong>PROVEEDORES</strong>',500,5000,'error');
    $("#oficina_pedido").focus();
    return false;
  }else if (validarCombo(oficina)==false) {
      notify('* Debe seleccionar almenos una opcion de la lista <strong>OFICINAS</strong>',500,5000,'error');
    $("#oficina").focus();
    return false;
  }else{
    return true;
  }
}
</script>
<div id="dialog-confirm" title="Confirmacion" style="display: none;">
</div>
<table >
<tr>
<td>
  <?php
if (!isset($_GET['submain'])) {
}  elseif (($this->permisos->permisos_submenus($_GET['m'],$_GET['submain'],0)==1)&&($this->permisos->permisos($_GET['submain'],2)==1)) {?>

  <div onclick="alta()" id="alta"><img src="<?php '.base_url().' ?>img/nuevo.ico" title="Nuevo Registro"></div>
<?php  }?>
</td>
<td></td>
<td >&nbsp;</td>
</tr>
</table>
        <table id="tbl_p_prove"></table>
        <div id="paginacion"> </div>
        <div style="display:none" id="dialog-procesos" title="Pedidos">
        <?php
        $this->load->view('pedidos_bodega/formulario');?>
        </div>
<!-- formulario de nuevo producto -->
        <div style="display:none" id="dialog-procesos_producto" title="Pedidos">
        <?php
        $this->load->view('pedidos_bodega/formulario_producto');?>
        </div>
        <!-- Funcion dialogo -->
        <div style="display:none;" id="dialogo" >
          <div class="ui-widget">
            <div class="ui-state-error ui-corner-all" style="padding: 0 .7em;">
              <p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
              <strong>Precaución:</strong> Esta seguro de cerrar el pedido?</p>
            </div>
          </div>
        </div>
        <!-- Pedido cerrado -->
        <div style="display:none;" id="dialogo_cerrado" >
          <div class="ui-widget">
            <div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em;">
              <p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
              <strong>Alerta:</strong> El Pedido ya esta cerrado!!!</p>
            </div>
          </div>
        </div>

