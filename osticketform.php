<?php
include("functions/medoo_querys.php");
// include("functions/config.php");
// initialize session
session_start();
include("functions/validar_captcha.php");
include("functions/authenticate.php");
include("functions/curl.php");
// include("functions/funciones.php");
 
// check to see if user is logging out
if(isset($_GET['out'])) {
	// destroy session
	session_unset();
	$_SESSION = array();
	unset($_SESSION['usuario'],$_SESSION['access']);
	session_destroy();
}

$user_login = filter_input(INPUT_POST, 'userLogin');
$user_login = preg_replace('([^A-Za-z0-9. ])', '', $user_login);

$user_password = filter_input(INPUT_POST, 'userPassword');
$user_password = preg_replace('([^A-Za-z0-9. ])', '', $user_password);

// check to see if login form has been submitted
if(isset($user_login)){
	// run information through authenticator
	if(! authenticate($user_login, $user_password)){
        $error = 1;
    }
}

if (!$_SESSION) {
    header("Location: login.php?error=1");
}

$lista_tipo_ticket = getListItemsByListId(3); //busca items correspondiente a tipo de Ticket
$lista_nivel_1 = getListItemsByListId(7); //busca items correspondiente al nivel 1
$lista_academicos = getTopicAcademicos(); //obtiene lista Departamento correspondiente al formulario Académicos (estudiantes / docentes)
$lista_biblioteca = getListItemsByListId(6);

// output error to user
//if(isset($error)) echo "Login failed: Incorrect user name, password, or rights<br />";

?>

<!DOCTYPE html>
<html lang="en">
<?php include('includes/header.php'); ?>
<body>
        
    <?php include('includes/menu.php'); ?>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.3.0/dropzone.js"></script> -->
    <!-- include the style -->
    <div class="container">


    	<div class="col-md-12">    
            <h1 class="text-left">Abrir un Nuevo Ticket</h1>
            <p>Favor llenar el siguiente formulario para crear un nuevo ticket</p>

            <form id="ticketForm" method="post" action="procesa_data.php" enctype="multipart/form-data" class="form-horizontal">
                <!-- <input type="hidden" name="__CSRFToken__" value="5a5f09dbfad9b430d2567ba0d645c454e6c67c38">   -->
                <!-- <input type="hidden" name="a" value="open"> -->
                <div class="form-header" style="margin-bottom:0.5em">
                    <h3>Información de contacto</h3>
                    <div><p>Los campos marcados con * son obligatorios</p></div>
                    <hr>
                </div>

                <div class="form-group">
                    <div class="row">
                        
                        <label class="control-label col-sm-2 text-end" for="telefono_alt">
                            <span class="required">Teléfono (opcional)
                            </span>

                            <br>
                        </label>
                        <div class="col-sm-10">
                            <input class="form-control" id="telefono_alt" size="40" placeholder="" name="telefono_alt" value="">
                        </div>

                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        
                        <label class="control-label col-sm-2 text-end" for="correo_alt">
                            <span class="required">Correo alternativo (opcional)
                            </span>

                            <br>
                        </label>
                        <div class="col-sm-10">
                            <input class="form-control" id="correo_alt" size="40" placeholder="" name="correo_alt" value="">
                        </div>

                    </div>
                </div>

                <hr>


                <div class="form-group">
                    <div class="row">
                        
                        <label class="control-label col-sm-2 text-end"> Tipo*&nbsp;</label>
                        <div class="col-sm-10">
                        
                            <select class="form-control" id="tipoId" name="tipoId">
                                <option value="" selected="selected">— Seleccione el tipo de caso —</option>

                                <?php if (count((int) $lista_tipo_ticket) > 0): ?>
                                    <?php foreach ($lista_tipo_ticket as $key => $tipo_ticket): ?>
                                        <option value="<?php echo $tipo_ticket['extra'] ?>"><?php echo $tipo_ticket['value'] ?></option>
                                    <?php endforeach ?>
                                <?php endif ?>


                            </select>
                            <font class="error"></font>
                        </div>

                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        
                        <label class="control-label col-sm-2 text-end"> Área*&nbsp;</label>
                        <div class="col-sm-10">
                        
                            <select class="form-control" id="Nivel1" name="Nivel1">
                                <option value="" selected="selected">— Seleccione una categoría —</option>
                                <?php if (count((int) $lista_nivel_1) > 0): ?>
                                    <?php foreach ($lista_nivel_1 as $key => $item): ?>
                                        <option value="<?php echo $item['extra'] ?>"><?php echo $item['extra'] ?> - <?php echo $item['value'] ?></option>
                                    <?php endforeach ?>
                                    <!-- <option value="999">— Otros —</option> -->
                                <?php endif ?>
                            </select>
                            <font class="error"></font>
                        </div>

                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label class="control-label col-sm-2 text-end"> Categoría*&nbsp;</label>
                        <div class="col-sm-10">
                        
                            <select class="form-control" id="Nivel2" name="Nivel2" disabled>
                                <option value="" selected="selected">— Seleccione una categoría —</option>
                            </select>
                            <font class="error"></font>
                        </div>
                        
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label class="control-label col-sm-2 text-end"> Subcategoría*&nbsp;</label>
                        <div class="col-sm-10">
                        
                            <select class="form-control" id="Nivel3" name="Nivel3" disabled>
                                <option value="" selected="selected">— Seleccione una categoría —</option>
                            </select>
                            <font class="error"></font>
                        </div>
                        
                    </div>

                </div>


                <hr>


                <div class="form-group">
                    <div class="row">
                        
                        <label class="control-label col-sm-2 text-end"> Departamento*&nbsp;</label>
                        <div class="col-sm-10">
                        
                            <select class="form-control" id="topicId" name="topicId">
                                <option value="" selected="selected">— Seleccione el área de ayuda relacionada con su caso —</option>
                                
                                <?php if ($lista_academicos): ?>
                                    <?php foreach ($lista_academicos as $topic_id => $value): ?>
                                        <option value="<?php echo $topic_id; ?>"><?php echo $value['topic']; ?></option>
                                    <?php endforeach ?>
                                <?php endif ?>

                            </select>
                            <font class="error"></font>
                        </div>
                    </div>
                </div>

                <div id="groupBiblioteca" class="form-group" hidden>
                    <div class="row">
                        <label class="control-label col-sm-2 text-end"> Biblioteca*&nbsp;</label>
                        <div class="col-sm-10">
                        
                            <select class="form-control" id="bibliotecaId" name="bibliotecaId">
                                <option value="" selected="selected">— Seleccione biblioteca —</option>
                                
                                <?php if ($lista_biblioteca): ?>
                                    <?php foreach ($lista_biblioteca as $topic_id => $value): ?>
                                        <option value="<?php echo $value['extra'] ?>"><?php echo $value['value'] ?></option>
                                    <?php endforeach ?>
                                <?php endif ?>

                            </select>
                            <font class="error"></font>
                        </div>
                        
                    </div>
                </div>


                <div class="form-group">
                    <div class="row">
                        <label class="control-label col-sm-2 text-end"> Prioridad *&nbsp;</label>
                        <div class="col-sm-10">
                        
                            <select class="form-control" id="prioridad" name="prioridad">
                                <option value="baja">Baja</option>
                                <option value="media" selected="selected">Media</option>
                                <option value="alta">Alta</option>
                            </select>
                            <font class="error"></font>
                        </div>
                        
                    </div>
                </div>

                <hr>

                <div class="form-group">
                    <div class="row">
                        
                        <label class="control-label col-sm-2 text-end" for="asunto">
                            <span class="required">Asunto
                                <span class="error">*</span>
                            </span>

                            <br>
                        </label>
                        <div class="col-sm-10">
                            <input class="form-control" id="asunto" size="40" placeholder="" name="asunto" value="">
                        </div>

                    </div>
                </div>
                <div class="form-group">

                    <div class="row">
                        <label class="control-label col-sm-2 text-end" for="detalle">
                            <span class="required">Detalle
                                <!-- <span class="error">*</span> -->
                            </span>

                            <br>
                        </label>
                        <div class="col-sm-10">
                            <textarea id="detalle" name="detalle" rows="5" cols="50" class="form-control"></textarea>
                        </div>
                        
                    </div>

                </div>

                <div class="form-group">

                    <div class="row">
                        <label class="control-label col-sm-2 text-end" for="adjuntar">
                            <span class="">Adjuntar<span class="error"></span>
                            </span>
                            <br>
                        </label>
                        <div class="col-sm-10">
                            <!-- <input type="file" name="adjuntar[]" multiple id="adjuntar"> -->
                             <!-- <input id="archivos" name="file" type="file" multiple /> -->
                             <div class="dropzone" id="archivos">
                                <div class="dz-message needsclick">    
                                    Soltar archivos aquí o elegirlos
                                    <p>
                                        Cantidad máxima de archivos: <strong>3</strong>
                                        <br>
                                        Peso máximo por archivo: <strong>5 MB</strong>
                                    </p>
                                    

                                </div>
                                <div class="dropzone-previews"></div>
                                <!-- <input type="" name=""> -->
                            </div>
                        </div>
                        
                    </div>

                    <!-- <input type="file" name="adjuntar[]" id="adjuntar" hidden multiple> -->
                </div>
                <hr>
                <div class="form-group">

                    <div class="row">
                        <label class="control-label col-sm-2 text-end" for="modulo">
                            <span class="required">Módulo
                                <!-- <span class="error">*</span> -->
                            </span>
                            <br>
                        </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" id="modulo" size="16" placeholder="Ejemplo: TCI201" name="modulo" value="" onkeyup="javascript:this.value=this.value.toUpperCase();">
                        </div>
                        
                    </div>

                </div>
                <div class="form-group">

                    <div class="row">
                        <label class="control-label col-sm-2 text-end" for="seccion">
                            <span class="required">Sección
                                <!-- <span class="error">*</span> -->
                            </span>
                            <br>
                        </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" id="seccion" size="16" placeholder="Ejemplo: 9001" name="seccion" value="">
                        </div>
                        
                    </div>

                    <input type="hidden" id="velocidadDescarga" name="velocidad_descarga" value="">
                </div>
                
                <div id="dynamic-form"></div>


                <table></table>
                <table>
                    <tbody>
                    </tbody>
                </table>
                <hr>
                <p class="buttons" style="text-align:center;">
                    <input id="button" class="btn btn-success" type="submit" value="Crear Ticket">
                    <input class="btn btn-warning" type="reset" name="reset" value="Restablecer">
                    <input class="btn btn-danger" type="button" name="cancel" value="Cancelar" 
                        onclick="javascript:
                        // $('.richtext').each(function() {
                        //     var redactor = $(this).data('redactor');
                        //     if (redactor &amp;&amp; redactor.opts.draftDelete)
                        //         redactor.plugin.draft.deleteDraft();
                        // });
                        window.location.href='/index.php';
                    ">
                </p>

            </form>

        <br><br>

        </div>
    </div>

 <?php include('includes/footer.php'); ?>


    <script src="dist/dropzone/dropzone.js"></script>
    <!-- include the script -->
    <script src="dist/alertifyjs/alertify.min.js"></script>
    <script type="text/javascript">
        // alertify.error('Error message');
        // $("#success-alert").hide();
        // $("#danger-alert").hide();

        Dropzone.autoDiscover = false;
        // $(document).ready(function () {
            let files = [];
            var myDropzone = new Dropzone("#archivos", {
                url: "procesa_data.php",
                addRemoveLinks: true,
                dictRemoveFile: 'Remover',
                autoProcessQueue: false,
                uploadMultiple: true,
                parallelUploads: 3,
                maxFiles: 3,
                maxFilesize : 5,
                // dictFileTooBig: 1,
                acceptedFiles: 'image/*,application/pdf,.psd,.xlsx,.csv,.docx,.txt',
                forceFallback: false,
                // clickable: false,
                previewsContainer: '.dropzone-previews',
                init: function () {
                    dzClosure = this;
                    this.on("addedfile", function (file) {
                        // console.log(file);
                        var reader = new FileReader();
                        reader.onload = function (event) {
                            // event.target.result contains base64 encoded image
                            var base64String = event.target.result;
                            var fileName = file.name;
                            files.push({[fileName]: base64String});
                        };
                        reader.readAsDataURL(file);
                    });

                    this.on("sending", function(file, xhr, formData) {
                        // Will send the filesize along with the file as POST data.
                        // console.log(file.dataURL);
                        var asunto = $('#asunto').val();
                        var modulo = $('#modulo').val();
                        var seccion = $('#seccion').val();
                        var topicId = $('#topicId').val();

                        var tipoId = $('#tipoId').val();
                        var nivel1 = $('#Nivel1').val();
                        var nivel2 = $('#Nivel2').val();
                        var nivel3 = $('#Nivel3').val();
                        var bibliotecaId = $('#bibliotecaId').val();
                        var prioridad = $('#prioridad').val();
                        var detalle = $('#detalle').val();
                        var sedeId = $('#sedeId').val();
                        var carrera = $('#carrera').val();
                        var modalidad = $('#modalidadId').val();
                        var escuela = $('#escuelaId').val();
                        var velocidad_descarga = $('#velocidadDescarga').val();
                        var telefono_alt = $('#telefono_alt').val();
                        var correo_alt = $('#correo_alt').val();



                        // rePattern_modulo = /^[a-z]{3}[0-9]{3}$/i; // abc123

                        // var valida_modulo = rePattern_modulo.test(modulo);

                        // if (valida_modulo == false) {
                        //      alertify.error('Debe agregar un módulo válido (EJ:ABC123).');
                        //     return false;
                        // }

                        // rePattern_seccion = /[0-9]{1}$/i; // abc123
                        // var valida_seccion = rePattern_seccion.test(seccion);
           
                        // if (valida_seccion == false) {
                        //      alertify.error('Debe agregar una sección válida (EJ:1234).');
                        //     return false;
                        // }
                        // if (seccion.length < 1 || seccion.length > 4) {
                        //      alertify.error('Debe agregar una sección válida de 1 a 4 digitos.');
                        //     return false;
                        // }


                        var validated_asunto =  validateField(asunto, 'Debe agregar un asunto.');
                        // var validated_modulo = validateField(modulo, 'Debe agregar un módulo.');
                        // var validated_seccion = validateField(seccion, 'Debe agregar una sección.');
                        var validated_topicId = validateField(topicId, 'Debe seleccionar un departamento.');

                        var validated_tipoId = validateField(tipoId, 'Debe seleccionar un tipo.');
                        var validated_nivel1 = validateField(nivel1, 'Debe seleccionar un nivel.');
                        var validated_nivel2 = validateField(nivel2, 'Debe seleccionar un nivel 2.');
                        var validated_nivel3 = validateField(nivel3, 'Debe seleccionar un nivel 3.');

                        if (topicId == 22) {
                            var validated_groupBiblioteca = validateField(bibliotecaId, 'Debe seleccionar una biblioteca.');
                        }
                        
                        // var validated_groupBiblioteca = validateField(groupBiblioteca, 'Debe seleccionar una biblioteca.');
                        var validated_prioridad = validateField(prioridad, 'Debe seleccionar una prioridad.');
                        var validated_detalle = validateField(detalle, 'Debe escribir una descripción para el ticket.');
                        // var validated_sedeId = validateField(sedeId, 'Debe seleccionar una sede.');
                        // var validated_carrera = validateField(carrera, 'Debe seleccionar una carrera.');

                        if (
                            validated_asunto == false || 
                            // validated_modulo == false || 
                            // validated_seccion == false || 
                            validated_topicId == false ||

                            validated_tipoId == false ||
                            validated_nivel1 == false ||
                            validated_nivel2 == false ||
                            validated_nivel3 == false ||
                            // validated_groupBiblioteca == false ||
                            validated_prioridad == false ||

                            validated_detalle == false 
                            // validated_sedeId == false 
                            // validated_carrera == false ||

                        ) {
                            return false;
                        }

                        formData.append("files", JSON.stringify(files));
                        formData.append("dataUrl", file.dataURL);
                        formData.append("detalle", $('#detalle').val());
                        formData.append("asunto", asunto);
                        formData.append("modulo", modulo);
                        formData.append("seccion", seccion);
                        formData.append("topicId", topicId);

                        formData.append("tipoId", tipoId);
                        formData.append("Nivel1", nivel1);
                        formData.append("Nivel2", nivel2);
                        formData.append("Nivel3", nivel3);
                        formData.append("bibliotecaId", bibliotecaId);
                        formData.append("prioridad", prioridad);
                        formData.append("detalle", detalle);
                        formData.append("velocidad_descarga", velocidad_descarga);
                        formData.append("telefono_alt", telefono_alt);
                        formData.append("correo_alt", correo_alt);


                        // formData.append("sedeId", sedeId);
                        // formData.append("carrera", carrera);

                    });
                    this.on("queuecomplete", function (file) {
                        // alert("All files have uploaded ");
                        window.location.href = "ticket_creado.php";
                    });
                },
                removedfile: function (file) {
                    var fileName = file.name;
                    files = files.filter(function (item, i) {
                        return !item[fileName]
                    })
                    // console.log(files);
                    file.previewElement.remove();
                },
                params: function() {

                }

            });
            
            $("#button").click(function (e) {
                e.preventDefault();

                

                var asunto = $('#asunto').val();
                var modulo = $('#modulo').val();
                var seccion = $('#seccion').val();
                var topicId = $('#topicId').val();

                var tipoId = $('#tipoId').val();
                var nivel1 = $('#Nivel1').val();
                var nivel2 = $('#Nivel2').val();
                var nivel3 = $('#Nivel3').val();
                var bibliotecaId = $('#bibliotecaId').val();
                var prioridad = $('#prioridad').val();
                var detalle = $('#detalle').val();
                var sedeId = $('#sedeId').val();
                var carrera = $('#carrera').val();
                var modalidad = $('#modalidadId').val();
                var escuela = $('#escuelaId').val();

                var telefono_alt = $('#telefono_alt').val();
                var correo_alt = $('#correo_alt').val();

                // rePattern_modulo = /^[a-z]{3}[0-9]{3}$/i; // abc123

                // var valida_modulo = rePattern_modulo.test(modulo);

                // if (valida_modulo == false) {
                //      alertify.error('Debe agregar un módulo válido (EJ:ABC123).');
                //     return false;
                // }

                // rePattern_seccion = /[0-9]{1}$/i; // abc123
                // var valida_seccion = rePattern_seccion.test(seccion);
   
                // if (valida_seccion == false) {
                //      alertify.error('Debe agregar una sección válida (EJ:1234).');
                //     return false;
                // }
                // if (seccion.length < 1 || seccion.length > 4) {
                //      alertify.error('Debe agregar una sección válida de 1 a 4 digitos.');
                //     return false;
                // }

                var validated_asunto =  validateField(asunto, 'Debe agregar un asunto.');
                // var validated_modulo = validateField(modulo, 'Debe agregar un módulo válido (EJ:ABC123).');
                // var validated_seccion = validateField(seccion, 'Debe agregar una sección.');
                var validated_topicId = validateField(topicId, 'Debe seleccionar un departamento.');

                var validated_tipoId = validateField(tipoId, 'Debe seleccionar un tipo.');
                var validated_nivel1 = validateField(nivel1, 'Debe seleccionar un nivel.');
                var validated_nivel2 = validateField(nivel2, 'Debe seleccionar un nivel 2.');
                var validated_nivel3 = validateField(nivel3, 'Debe seleccionar un nivel 3.');

                if (topicId == 22) {
                    var validated_groupBiblioteca = validateField(bibliotecaId, 'Debe seleccionar una biblioteca.');
                }
                
                // var validated_groupBiblioteca = validateField(groupBiblioteca, 'Debe seleccionar una biblioteca.');
                var validated_prioridad = validateField(prioridad, 'Debe seleccionar una prioridad.');
                var validated_detalle = validateField(detalle, 'Debe escribir una descripción para el ticket.');
                // var validated_sedeId = validateField(sedeId, 'Debe seleccionar una sede.');
                // var validated_carrera = validateField(carrera, 'Debe seleccionar una carrera.');

                if (
                    validated_asunto == false || 
                    // validated_modulo == false || 
                    // validated_seccion == false || 
                    validated_topicId == false ||

                    validated_tipoId == false ||
                    validated_nivel1 == false ||
                    validated_nivel2 == false ||
                    validated_nivel3 == false ||
                    // validated_groupBiblioteca == false ||
                    validated_prioridad == false ||

                    validated_detalle == false
                    // validated_sedeId == false 
                    // validated_carrera == false ||

                ) {
                    return false;
                }

                $(this).attr('disabled', 'disabled');
                alertify.success('Un momento por favor, estamos generando su ticket.');

                URL = $('#ticketForm').attr('action');

                formData = $('#ticketForm').serialize();
                var preview_files = $('.dropzone-previews').html();
                // console.log(preview_files.length);
                if (preview_files.length > 0) {
                    myDropzone.processQueue();
                } else {
                    $.ajax({
                        type: 'POST',
                        url: URL,
                        data : formData,
                        success: function(resp) {
                            // alert(resp);
                            // console.log(JSON.stringify(resp));
                            window.location.href = "ticket_creado.php";
                        }
                    });
                }
            });

            function validateField(value, message) {
                if (value.length == 0) {
                     alertify.error(message);
                    return false;
                    // successAlert('Debe agregar un asunto.');
                } else {
                    return true;
                }
            }


            $("#topicId").change(function (e) {
                topic_id = $(this).val();
                if (topic_id == 22) {
                    $('#groupBiblioteca').show();
                } else {
                    $('#groupBiblioteca').hide();
                }
            });


            $("#Nivel1").change(function (e) {
                var nivel_id = $(this).val();
            	$.ajax({
                    type: 'POST',
                    url: 'functions/buscar.php',
                    data : {nivel_id : nivel_id, tipo_nivel : 'nivel_1'},
                    dataType: 'json',
                    success: function(resp) {
                        
                        $('#Nivel2').removeAttr('disabled');

                        var html = '<option value="" selected="selected">— Seleccione una categoría —</option>';
                        if (resp) {
                            for (index in resp) {

                                html += '<option value="' + resp[index].extra + '">' + resp[index].extra + '- ' + resp[index].value + '</option>';
                            }
                        }
                        html += '<option value="999">— Otros —</option>';
                        $('#Nivel2').html(html);
                        
                    }
                });

                $('#Nivel3').html('<option value="" selected="selected">— Seleccione una categoría —</option>');
                $('#Nivel3').prop('disabled', 'disabled');
            });

            $("#Nivel2").change(function (e) {
                var nivel_id = $(this).val();
                $.ajax({
                    type: 'POST',
                    url: 'functions/buscar.php',
                    data : {nivel_id : nivel_id, tipo_nivel : 'nivel_2'},
                    dataType: 'json',
                    success: function(resp) {

                        $('#Nivel3').removeAttr('disabled');

                        var html = '<option value="" selected="selected">— Seleccione una categoría —</option>';

                         if (resp) {
                            for (index in resp) {
                                console.log(resp);
                                if (resp[index].extra !== 'undefined') {
                                    html += '<option value="' + resp[index].extra + '">' + resp[index].extra + '- ' + resp[index].value + '</option>';
                                }
                            }
                        }
                        html += '<option value="999">— Otros —</option>';
                        $('#Nivel3').html(html);
                        
                    }
                });
            });


            var ImagenTesting = "https://www.aiepvirtual.cl/bbcswebdav/institution/AIEP/test_velocidad_descarga.jpg?falso=" + Math.random();
            var tiempo_inicio, tiempo_fin;
                
            // Tamaño del archivo en bytes
            var DescargaTamano = 2707459;
            var DescargaImagenRuta = new Image();

            DescargaImagenRuta.onload = function () {
                tiempo_fin = new Date().getTime();
                VelocidadDespliegue();
            };
            tiempo_inicio = new Date().getTime();
            DescargaImagenRuta.src = ImagenTesting;


            function VelocidadDespliegue() {
                var TiempoDuracion = (tiempo_fin - tiempo_inicio) / 1000;
                var BitsDescargados = DescargaTamano * 8;
                
                // Convierte un número en una cadena usando toFixed (2) redondeando a 2 
                var bps = (BitsDescargados / TiempoDuracion).toFixed(2);
                var VelocidadaEnKbps = (bps / 1024).toFixed(2);
                var VelocidadaEnMbps = (VelocidadaEnKbps / 1024).toFixed(2);
                $('#velocidadDescarga').val(VelocidadaEnMbps);
                console.log(VelocidadaEnMbps);
                var url = "https://dte.aiep.cl/test/index.php?VelocidadaEnMbps=" + VelocidadaEnMbps;
                // var url = "https://dte.aiep.cl/test/module_home.php?VelocidadaEnMbps=" + VelocidadaEnMbps + "&u=" + usuario + "&r=" + rol + "&n=" + nombre + "&p=" + rut;
                // window.location.href = url;
            }

            function callSpinner() {
                const spinner = '<img id="Spinner" src="img/spinner.gif">';
                $('#ButtonPanel').html(spinner);
            }

    </script>

</body>
</html>