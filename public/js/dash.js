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