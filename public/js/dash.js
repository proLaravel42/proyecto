
var base_url = 'http://localhost/proyecto/public/';


function showView(id,clases){
  console.debug(id+'    '+ clases);
  $('.'+clases).hide();
  $('#'+id).show();
}

function addClassActive(id) {
	console.debug('este es el id '+id);
    $('.nav-sidebar > li').removeClass('active');
    $('.nav-sidebar > li#' + id).addClass('active');
}
function searchTask(){

console.debug('Hola llegaste a buscar');

var search = $('#search').val();
//http://midominio.com/searchTask?search=mi busqueda
var URL = base_url+'searchTask';
var DATA = 'search='+search;

$.ajax({
        type: "GET",
        url: URL,
        data:DATA,
        success: function(data) {
           
            console.log(data);
           /* var model = $('#tasks');
            model.empty();
            for (var i in data.tasks) {
                var item = data.tasks[i];
                if (item.fecha_respuesta == d.format('Y\\-m\\-d 00\\:00\\:00')) {
                    var semaforo = "<th class='center'><button type='button' class='btn btn-danger' style='border-radius:45%;'></button></th>";
                } else {
                    var semaforo = "<th class='center'><button type='button' class='btn btn-success' style='border-radius:45%;'></button></th>";
                };
                model.append("<tr><th class='center'>" + item.folio + "</th>" +
                    "<th class='center'>" + item.oficio_referencia + "</th>" +
                    "<th class='center'>" + item.asunto + "</th>" +
                    "<th class='center'>" + item.first_name + "</th>" +
                    //"<th class='center shortDateFormat'>" + item.fecha_respuesta + "</th>" +
                    //"<th class='center'>" + item.estatus +
                    semaforo +
                    "<th class='center'><button type='button' class='btn btn-info' onclick='getTaskDetailsByIdOperative(" + item.id + ")'>Ver Detalles</button></th>"+
                    "<th class='center'><button type='button' class='btn btn-warning' onclick='showRejectTask(" + item.id + ")'>Rechazar</button></th>");
            }*/
        },

        error: function(xhr, ajaxOptions, thrownError) {
           console.debug('Error en tu peticion');
    });
}
}
