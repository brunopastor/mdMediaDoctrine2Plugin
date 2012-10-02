/**
 * Alerts a message to the user via an xmlhttpresponse json
 * @param object response xmlhttpresponse
 * @param string status response status 
 * @return string the response status passed as parameter
 */
function alertUser(response, status){
  $('#sf_media_browser_user_message').hide().html('<p class="'+response.status+'">'+response.message+'</p>').show('slow');
  return status;
};  


/**
 * Sort a file
 */
$(document).ready(function(){
  mdMediaDoctrine.getInstance().media_sortable();
});

/**
 * Confirm on delete
 */
$(document).ready(function(){
  mdMediaDoctrine.getInstance().media_delete();
});

/**
 * Change Avatar
 */
$(document).ready(function(){
  mdMediaDoctrine.getInstance().media_avatar();
});