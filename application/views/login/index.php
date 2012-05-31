<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<!-- Jquerys -->
<script type="text/javascript" src="<?php echo base_url();?>js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery_numberformat.js"></script>

<script src="<?php echo base_url();?>jqgrid/js/i18n/grid.locale-es.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>jqgrid/js/jquery.jqGrid.min.js" type="text/javascript"></script>

<!-- Hojas de estilo -->
<link type="text/css" href="<?php echo base_url();?>css/ui-lightness/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
<link type="text/css" rel="stylesheet"  href="<?php echo base_url();?>css/styles_login.css" />
<link type="text/css" rel="stylesheet"  href="<?php echo base_url();?>css/ui/demos.css" />
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url();?>jqgrid/css/ui.jqgrid.css" />

<!-- Metas -->
<meta http-equiv="Expires" content="0"> 
<meta http-equiv="Last-Modified" content="0">
<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- Titulo de la pagina -->
<title><?php if(isset($titulo)){echo $titulo;}?></title>


<script type="text/javascript">
  function str_replace(busca, repla, orig)
  {
    str   = new String(orig);

    rExp  = "/"+busca+"/g";
    rExp  = eval(rExp);
    newS  = String(repla);

    str = new String(str.replace(rExp, newS));

    return str;
  }

  function msg(mensaje){
    $("#mensajes").html(mensaje);
    $("#mensajes_0").fadeIn();
  }


  $(function($){
    $.datepicker.regional["es"] = {
    closeText: "Cerrar",
      prevText: "&#x3c;Ant",
      nextText: "Sig&#x3e;",
      currentText: "Hoy",
      monthNames: ["Enero","Febrero","Marzo","Abril","Mayo","Junio",
      "Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],
      monthNamesShort: ["Ene","Feb","Mar","Abr","May","Jun",
      "Jul","Ago","Sep","Oct","Nov","Dic"],
      dayNames: ["Domingo","Lunes","Martes","Mi&eacute;rcoles","Jueves","Viernes","S&aacute;bado"],
      dayNamesShort: ["Dom","Lun","Mar","Mi&eacute;","Juv","Vie","S&aacute;b"],
      dayNamesMin: ["Do","Lu","Ma","Mi","Ju","Vi","S&aacute;"],
      weekHeader: "Sm",
      dateFormat: "yy-mm-dd",
      firstDay: 1,
      isRTL: false,
      showMonthAfterYear: true,
      changeYear: true,
      yearSuffix: ""};
    $.datepicker.setDefaults($.datepicker.regional["es"]);
  });    
    $(document).ready(function() {
       
       $("#fecha_entrega").datepicker({ changeMonth: true });
       $("#fecha_nacimiento").datepicker({ changeMonth: true });
       
    });

  function oculta(id)
  {
    $(id).fadeOut();
  } 
</script>
</head>

<body class="front">  
    <div class="login">
    <table class="login-table"  border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="corner-left-top"></td>
        <td class="estira-top"></td>
        <td  class="corner-right-top"></td>
      </tr>
      <tr>
        <td  class="estira-left"></td>
        <td class="bg-login">
          <img class="logo" src="<?php echo base_url(); ?>images/logo_easy.png" width="505" height="235"><br />
          <form name="form" id="form"  enctype="multipart/form-data" method="POST" action="<?php echo base_url().'inicio/validar_usuario';?>" >
            <div id="respuesta"><p><?php if(isset($ErrorDatos)){echo $ErrorDatos;}?></p></div>
            <table class="login-form" border="0">
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td>usuario:</td>
                    <td><input name="usuario" type="text" /></td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td>password:</td>
                    <td><input name="password" type="password" /></td>
                    <td> <input type="image" id="button" name="button" value="Enviar" src="<?php echo base_url(); ?>images/entrar-btn.png" width="123" height="35" alt="Submit"/>
                      </td>
                  </tr>
                </table>
          </form>
        </td>
        <td  class="estira-right"></td>
      </tr>
      <tr>
        <td  class="corner-left-bottom"></td>
        <td class="estira-bottom"></td>
        <td  class="corner-right-bottom"></td>
      </tr>
    </table>
    <div class="desarrollo"><a href="www.asf.com.mx"><img src="<?php echo base_url(); ?>images/desarrollado.png" width="90" height="25" /></a></div>
    </div>
    
</body>
</html>
