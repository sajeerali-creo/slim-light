<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<?php
$qry = $conn->query("SELECT * from `about_us`");
	$row = $qry->fetch_array(MYSQLI_ASSOC);
	if (is_array($row) && !empty($row)) {
    foreach ($row as $k => $v) {
        if (!is_numeric($k)) {
            $$k = $v; // Dynamically create variables
        }
    }
	}
?>
<div class="col-lg-12">
	<div class="card card-outline card-primary">
		<div class="card-header">
			<h5 class="card-title">About</h5>
		</div>
		<div class="card-body">
			<form id="about_c">
				<div class="form-group">
					<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
					<label for="" class="control-label">Eat Light, Live Bright</label>
		             <textarea name="section_1_content" id="section_1_content" cols="30" rows="10" class="form-control summernote"><?php echo (isset($section_1_content)) ? html_entity_decode(($section_1_content)) : '' ?></textarea>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-12">		
						<label class="control-label">Meet Our Experts</label>				
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="team_section_title" class="control-label">Team Section Title</label>
									<input name="team_section_title" type="text" class="form-control" value="<?php echo isset($team_section_title) ? $team_section_title : '' ?>">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="team_section_sub_title" class="control-label">Team Section Sub Title</label>
									<input name="team_section_sub_title" type="text" class="form-control" value="<?php echo isset($team_section_sub_title) ? $team_section_sub_title : '' ?>">
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="team_section_description" class="control-label">Team Section Description</label>
							<textarea name="team_section_description" cols="30" rows="2" class="form-control"><?php echo isset($team_section_description) ? $team_section_description : '' ?></textarea>
						</div>
						
						<div class="row">
					<div class="col-md-12">
						<label class="control-label">Team Images</label>
						<input type="file" name="gallery_imgs[]" multiple class="form-control" id="gallery-input">
						<input type="hidden" name="deleted_gallery_names" id="deleted_gallery_names">
						<div class="row mt-3" id="gallery-wrapper">
							<?php 
							$gallery_list = [];
							if (isset($id)) {
								$gallery_dir = base_app . "uploads/team_gallary/";
								$gallery_url = base_url . "uploads/team_gallary/";

								$q = $conn->query("SELECT gallery_images FROM about_us WHERE id = {$id}");
								if ($q && $q->num_rows > 0) {
									$gallery_images = $q->fetch_assoc()['gallery_images'];
									$gallery_list = explode(',', $gallery_images);

									foreach ($gallery_list as $file) {
										if (empty($file)) continue;
										?>
										<div class="col-md-2 mb-2 position-relative preview-img-item" id="gallery-img-<?php echo $file ?>">
											<img src="<?php echo $gallery_url . $file ?>" class="img-thumbnail" style="height:150px;width:160px">
											<button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0" onclick="delete_gallery_image('<?php echo $file ?>')">&times;</button>
										</div>
										<?php
									}
								}
							}
							?>
						</div>
					</div>
				</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<hr>
						<div class="form-group">
							<input type="hidden" name="file" value="about">
							<label for="" class="control-label">Why Choose BLite</label>
							<textarea name="section_2_content" id="section_2_content" cols="30" rows="10" class="form-control summernote"><?php echo (isset($section_2_content)) ? html_entity_decode(($section_2_content)) : '' ?></textarea>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<hr>
						<div class="form-group">
							<input type="hidden" name="file" value="about">
							<label for="" class="control-label">Our menu</label>
							<textarea name="section_3_content" id="section_3_content" cols="30" rows="10" class="form-control summernote"><?php echo (isset($section_3_content)) ? html_entity_decode(($section_3_content)) : '' ?></textarea>
						</div>
					</div>
				</div>
			</form>
		</div>
		<div class="card-footer">
			<button class="btn btn-primary btn-sm" form="about_c">Update </button>
		</div>
	</div>
</div>

<style>
	img#cimg{
		height: 30vh;
		width: 100%;
		object-fit:scale-down;
		object-position:center center;
	}
</style>
<script>
	let selectedGalleryFiles = [];
	$('#gallery-input').on('change', function (e) {
		const newFiles = Array.from(e.target.files);

		newFiles.forEach((file, index) => {
			if (!file.type.startsWith('image/')) return;

			const uniqueId = Date.now() + '_' + Math.floor(Math.random() * 1000);
			selectedGalleryFiles.push({ id: uniqueId, file });

			const reader = new FileReader();
			reader.onload = function (event) {
				const html = `
					<div class="col-md-2 mb-2 position-relative preview-img-item" id="preview-img-${uniqueId}">
						<img src="${event.target.result}" class="img-thumbnail" style="height:150px;width:160px">
						<button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0" onclick="removePreviewImage('${uniqueId}')">&times;</button>
					</div>
				`;
				$('#gallery-wrapper').append(html); // ✅ Append to same container
			};
			reader.readAsDataURL(file);
		});

		this.value = ''; // allow reselect
	});

	function removePreviewImage(id) {
		selectedGalleryFiles = selectedGalleryFiles.filter(f => f.id !== id);
		$('#preview-img-' + id).remove();
	}

	function delete_gallery_image(name) {
		if (confirm("Are you sure you want to delete this image?")) {
			let deletedInput = $('[name="deleted_gallery_names"]');
			let currentVal = deletedInput.val();
			let newVal = currentVal ? currentVal.split(',') : [];

			if (!newVal.includes(name)) newVal.push(name);
			deletedInput.val(newVal.join(','));

			$('#' + $.escapeSelector('gallery-img-' + name)).remove(); // now remove properly
		}
	}

	// On form submit, only send selected files
	$('form').on('submit', function () {
		const dt = new DataTransfer();
		selectedGalleryFiles.forEach(obj => dt.items.add(obj.file));
		document.getElementById('gallery-input').files = dt.files;
	});

</script>
<script>

	$(document).ready(function(){
		// $('#about_c').submit(function(e){
		// 	e.preventDefault();
		// 	start_loader();
		// 	$.ajax({
		// 		url:_base_url_+"classes/Content.php?f=about_us",
		// 		method:"POST",
		// 		data:$(this).serialize(),
		// 		error: err=>{
		// 			alert_toast("An error occured",'error')
		// 			console.log(err);
		// 		},
		// 		success:function(resp){
		// 			if(resp != undefined){
		// 				resp = JSON.parse(resp)
		// 				if(resp.status == 'success'){
		// 					location.reload()
		// 				}else{
		// 					alert_toast("An error occured",'error')
		// 					console.log(resp);
		// 					end_loader();
		// 				}
		// 			}
		// 		}
		// 	})
		// })

		$('#about_c').submit(function(e){
			e.preventDefault();
			start_loader();
			$.ajax({
				url:_base_url_+"classes/Content.php?f=about_us",
				data: new FormData($(this)[0]),
				cache: false,
				contentType: false,
				processData: false,
				method: 'POST',
				type: 'POST',
				dataType: 'json',
				error: err=>{
					alert_toast("An error occured",'error')
					console.log(err);
					end_loader();
				},
				success:function(resp){
					if(resp != undefined){
						if(resp.status == 'success'){
							location.reload()
						}else{
							alert_toast("An error occured",'error')
							console.log(resp);
						}
						end_loader();
					}
				}
			})
		})

	})



$(document).ready(function() {
		$('#section_1_content').summernote({
		        height: 200,
		        toolbar: [
		            [ 'style', [ 'style' ] ],
		            [ 'font', [ 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear'] ],
		            [ 'fontname', [ 'fontname' ] ],
		            [ 'fontsize', [ 'fontsize' ] ],
		            [ 'color', [ 'color' ] ],
		            [ 'para', [ 'ol', 'ul', 'paragraph', 'height' ] ],
		            [ 'table', [ 'table' ] ],
		            [ 'view', [ 'link','undo', 'redo', 'fullscreen', 'codeview', 'help' ] ]
		        ]
		    })

			$('#section_2_content').summernote({
		        height: 200,
		        toolbar: [
		            [ 'style', [ 'style' ] ],
		            [ 'font', [ 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear'] ],
		            [ 'fontname', [ 'fontname' ] ],
		            [ 'fontsize', [ 'fontsize' ] ],
		            [ 'color', [ 'color' ] ],
		            [ 'para', [ 'ol', 'ul', 'paragraph', 'height' ] ],
		            [ 'table', [ 'table' ] ],
		            [ 'view', [ 'link','undo', 'redo', 'fullscreen', 'codeview', 'help' ] ]
		        ]
		    })

			$('#section_3_content').summernote({
		        height: 200,
		        toolbar: [
		            [ 'style', [ 'style' ] ],
		            [ 'font', [ 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear'] ],
		            [ 'fontname', [ 'fontname' ] ],
		            [ 'fontsize', [ 'fontsize' ] ],
		            [ 'color', [ 'color' ] ],
		            [ 'para', [ 'ol', 'ul', 'paragraph', 'height' ] ],
		            [ 'table', [ 'table' ] ],
		            [ 'view', [ 'link','undo', 'redo', 'fullscreen', 'codeview', 'help' ] ]
		        ]
		    })
    // Function to handle image upload
    function sendFile(file, editor) {
        var data = new FormData();
        data.append("file", file);  // Append the image file

        $.ajax({
            url: 'ajax/upload_image.php',  // Your PHP script URL for image upload
            type: 'POST',
            data: data,
            contentType: false,
            processData: false,
            success: function(response) {
                console.log("Server Response:", response);  // Log the server response
                if (response.url) {
                    var imageUrl = response.url;  // Assuming response contains the image URL
                    $(editor).summernote('insertImage', imageUrl);  // Insert image into Summernote editor
                } else {
                    alert(response.error || 'Failed to upload image.');
                }
            },
            error: function() {
                alert('Error while uploading the image.');
            }
        });
    }
});
	
</script>