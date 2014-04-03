var __DEFAULT_ALBUM_ID = null;
var __lock = false;
mdMediaDoctrine = function(options){
  this._initialize();

}

mdMediaDoctrine.instance = null;
mdMediaDoctrine.getInstance = function (){
  if(mdMediaDoctrine.instance == null)
    mdMediaDoctrine.instance = new mdMediaDoctrine();
  return mdMediaDoctrine.instance;
}

mdMediaDoctrine.prototype = {
  _initialize: function(){
        
  },
  
  media_avatar: function(){
    $( "#droppable" ).droppable({
      drop: function( event, ui ) {
        __lock = true;
        event.preventDefault();        
        
        var data = $(ui.draggable).attr('id').split('_');

        var self = $(this);
        $.ajax({
          type: "POST",
          data: {
            md_asset_file_id : data[1]
          },
          url: self.attr('action'),
          dataType: "json",
          success: function(json){
            self.find('img').attr('src', json.options.response);
          }, 
          complete: function(){
            __lock = false;
          }
        });
      }
    });
  },

  media_delete: function(){
    $('#sf_media_browser_list li a.delete').each(function(){
      $(this).click(function(){
        delete_msg = 'Seguro que quieres borrar este archivo ?';
        var self = $(this);
      
        if( window.confirm(delete_msg) ){
          $.ajax({
            type: "POST",
            url: $(this).attr('href'),
            dataType: "json",
            success: function(data){
              // ELIMINAR IMAGEN DEL DOM
              self.parents('.file').fadeOut();
            
              // ACTUALIZAR AVATAR SI SE ELIMINO
              if(data.options.is_avatar){
                $('#droppable').find('img').attr('src', $('#droppable').find('img').attr('avatar'));
              }
            }
          });
        }
      
        return false;
      });
    });    
  },
  
  media_sortable: function(){
    $( "#sf_media_browser_list" ).sortable({
      stop: function(event, ui) {
        if(__lock) return false;
        
        var arr = new Array();
        $('li.file').each(function(index, item){
          arr.push($(item).attr('id'));
        });

        var data = arr.join(',');

        $.ajax({
          url: '/backend_dev.php/sf_media_doctrine/file_sort',
          data: {
            'priorities' : data
          },
          type: 'post',
          dataType: 'json',
          success: function(json){

          }
        });              
      }
    });
    $( "#sf_media_browser_list" ).disableSelection();
  },
  
  /**
     * @param obj_id - Objeto duenio del contenido
     * @param obj_name  - Objeto duenio del contenido
     */
  updateAssetList: function(obj_id, obj_name, album_id)
  {
    var self = this;
    params = {
        'object_class': obj_name, 
        'object_id': obj_id, 
      };
    if(album_id !== undefined){
      params['md_asset_album_id'] = album_id;
    }

    $.ajax({
      url: '/backend.php/sf_media_doctrine/asset_update',
      data: params,
      dataType: 'json',
      type: 'post',
      success: function(json){
        if(json.response == 'OK'){
          $('#sf_media_files').html(json.options.response);
          mdMediaDoctrine.getInstance().media_sortable();
          mdMediaDoctrine.getInstance().media_delete();
          mdMediaDoctrine.getInstance().media_avatar();          
        }
      }
    });
  },

  setDefaultAlbumId: function(value){
    __DEFAULT_ALBUM_ID = value;
  },

  getDefaultAlbumId: function(){
    return __DEFAULT_ALBUM_ID;
  },

  resetDefaultAlbumId: function(){
    __DEFAULT_ALBUM_ID = null;
  }

}

// CALLBACKS FOR swfupload widget handler
function afterUpload(__MD_OBJECT_ID, __MD_OBJECT_CLASS, __MD_ALBUM_SELECTED_ID){
  mdMediaDoctrine.getInstance().updateAssetList(__MD_OBJECT_ID, __MD_OBJECT_CLASS, __MD_ALBUM_SELECTED_ID)  
}
