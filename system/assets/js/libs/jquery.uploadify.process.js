/*function process_uploaded_image(file_paths, entry_id, field_id) {
	log(file_paths);
} // end process_uploaded_image()*/


function process_uploaded_image(file_path, file_name, entry_id, field_id) {
	$.ajax({
		 type: 		"post"
		,url: 		admin_url+"content/uploader_image"
		,data: 		{
			'file_path': file_path,
			'file_name': file_name,
			'entry_id':   entry_id,
			'field_id':   field_id
		}
		,dataType: 	"html"
		,success: 	function(data) {
			// Refresh view
			$("#image-uploader-"+field_id+" .preview").html(data);
		}
		,error: 	function(data) {
			alert("Error!");
		}
	});
} // end process_uploaded_image




/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function process_uploaded_images(items, entry_id, field_id)
{
	log(items);
	$.ajax({
		 type: 		"post"
		,url: 		admin_url+"content/uploader_gallery"
		,data: 		{
			'items': items,
		}
		,dataType: 	"html"
		,success: 	function(data) {
			// Refresh view
			$("#gallery-uploader-"+field_id+" .preview").html(data);
		}
		,error: 	function(data) {
			alert("Error!");
		}
	});
} // end process_uploaded_images()












