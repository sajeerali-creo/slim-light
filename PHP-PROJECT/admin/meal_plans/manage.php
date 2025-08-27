<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<?php 
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * from `meal_plans` where id = '{$_GET['id']}' ");
	foreach($qry->fetch_array() as $k => $v){
		if(!is_numeric($k)){
			$$k = $v;
		}
	}
}
?>
<div class="col-lg-12">
	<div class="card card-outline card-primary">
		<div class="card-header">
			<h5 class="card-title"><?php echo isset($id) ? "Manage": "Create" ?> Meal Plan</h5>
		</div>
		<div class="card-body">
			<form id="meal_plan">
				<div class="row" class="details">
				    <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
					<!-- <input type="hidden" name="deleted_gallery_names" value=""> -->
					<!-- <input type="hidden" name="existing_gallery" value='<?php echo isset($gallery_images) ? $gallery_images : "" ?>'>  -->
					<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
					<div class="col-sm-6">
						<div class="form-group">
							<label for="title" class="control-label">Title</label>
							<!-- <textarea name="title" cols="30" rows="2" class="form-control"><?php echo isset($title) ? $title : '' ?></textarea> -->
							<input name="title" type="text" class="form-control" value="<?php echo isset($title) ? $title : '' ?>">
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label for="sub_title" class="control-label">Sub Title</label>
							<input name="sub_title" type="text" class="form-control" value="<?php echo isset($sub_title) ? $sub_title : '' ?>">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<div class="form-group">
							<label for="" class="control-label">Description</label>
				             <textarea name="description" id="" cols="30" rows="10" class="form-control summernote"><?php echo (isset($description)) ? html_entity_decode(($description)) : '' ?></textarea>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-2">
						<div class="form-group">
							<label for="" class="control-label">Image</label>
							<div class="custom-file">
							<input type="hidden" name="old_file" value="<?php echo isset($file_path) ? $file_path :'' ?>">
							<input type="file" class="custom-file-input rounded-circle" id="customFile" name="img" onchange="displayImg(this,$(this))">
							<label class="custom-file-label" for="customFile">Choose file</label>
							</div>
						</div>
						<div class="form-group d-flex justify-content-center">
							<img src="<?php echo validate_image(isset($file_path) ? $file_path :'') ?>" alt="" id="cimg" class="img-fluid img-thumbnail">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<hr>
						<label class="control-label">Upload Gallery Images</label>
						<input type="file" name="gallery_imgs[]" multiple class="form-control" id="gallery-input">
						<input type="hidden" name="deleted_gallery_names" id="deleted_gallery_names">
						<div class="row mt-3" id="gallery-wrapper">
							<?php 
							$gallery_list = [];
							if (isset($id)) {
								$gallery_dir = base_app . "uploads/meal_gallery/";
								$gallery_url = base_url . "uploads/meal_gallery/";

								$q = $conn->query("SELECT gallery_images FROM meal_plans WHERE id = {$id}");
								if ($q && $q->num_rows > 0) {
									$gallery_images = $q->fetch_assoc()['gallery_images'];
									$gallery_list = explode(',', $gallery_images);

									foreach ($gallery_list as $file) {
										if (empty($file)) continue;
										?>
										<div class="col-md-2 mb-2 position-relative preview-img-item" id="gallery-img-<?php echo $file ?>">
											<img src="<?php echo $gallery_url . $file ?>" class="img-thumbnail" style="height:150px;">
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
				<!-- Second Gallary -->
				<div class="row">
					<div class="col-md-12">
						<label class="control-label">What’s in your bag</label>
						<input type="file" name="gallery_2_imgs[]" id="gallery-2-input" multiple class="form-control">
						<input type="hidden" name="deleted_gallery_2_names" id="deleted_gallery_2_names">
						<div class="row mt-3" id="gallery-2-container">
							<?php 
							$gallery_2 = [];
							$gallery_dir = base_app . "uploads/meal_gallery/";
							$gallery_url = base_url . "uploads/meal_gallery/";
							if (isset($id)) {
								$res = $conn->query("SELECT gallery_2_images FROM meal_plans WHERE id = {$id}");
								if ($res && $res->num_rows > 0) {
									$data = $res->fetch_assoc();
									$gallery_2 = array_filter(explode(',', $data['gallery_2_images']));
									foreach ($gallery_2 as $item) {
										[$img, $title] = array_pad(explode('::', $item, 2), 2, '');
										?>
										<div class="col-md-3 gallery-img-box" data-filename="<?= $img ?>">
											<img src="<?= $gallery_url . $img ?>" class="img-thumbnail mb-1" style="height:130px;">
											<input type="text" class="form-control form-control-sm mb-1" name="gallery_2_titles_existing[<?= $img ?>]" placeholder="Title" value="<?= htmlspecialchars($title) ?>">
											<button type="button" class="btn btn-sm btn-danger" onclick="deleteExistingGallery2('<?= $img ?>')">Delete</button>
										</div>
										<?php
									}
								}
							}
							?>
						</div>
						</div>
			</div>
			</form>
		</div>
		<div class="card-footer">
			<button class="btn btn-primary btn-sm" form="meal_plan"><?php echo isset($_GET['id']) ? "Update": "Save" ?></button>
			<a class="btn btn-primary btn-sm" href="./?page=meal_plans">Cancel</a>
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
<!-- JavaScript: Preview New Uploads and Remove -->
<script>
	
function displayImg(input, _this) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            $('#cimg').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
        _this.siblings('.custom-file-label').html(input.files[0].name);
    }
}


function deleteExistingGallery2(img) {
    if (confirm("Remove this image?")) {
        const container = document.querySelector(`.gallery-img-box[data-filename="${img}"]`);
        container.remove();

        const hiddenInput = document.getElementById("deleted_gallery_2_names");
        let names = hiddenInput.value ? hiddenInput.value.split(',') : [];
        if (!names.includes(img)) names.push(img);
        hiddenInput.value = names.join(',');
    }
}

document.getElementById('gallery-2-input').addEventListener('change', function(e) {
    const previewContainer = document.getElementById('gallery-2-container');
    
    for (let i = 0; i < this.files.length; i++) {
        const file = this.files[i];
        const reader = new FileReader();

        reader.onload = function (e) {
            const box = document.createElement('div');
            box.classList.add('col-md-3', 'gallery-img-box', 'mb-3');

            box.innerHTML = `
                <img src="${e.target.result}" class="img-thumbnail mb-1" style="height:130px;">
                <input type="text" name="gallery_2_titles[]" class="form-control form-control-sm mb-1" placeholder="Title">
                <button type="button" class="btn btn-sm btn-danger remove-gallery-img">Delete</button>
            `;

            // Attach delete button handler
            box.querySelector('.remove-gallery-img').addEventListener('click', function() {
                box.remove(); // Remove the entire preview box
            });

            previewContainer.appendChild(box);
        };

        reader.readAsDataURL(file);
    }
});

</script>
<script>

// let selectedGallery_2Files = [];
// $('#gallery_2-input').on('change', function (e) {
// 	const newFiles = Array.from(e.target.files);

// 	newFiles.forEach((file, index) => {
// 		if (!file.type.startsWith('image/')) return;

// 		const uniqueId = Date.now() + '_' + Math.floor(Math.random() * 1000);
// 		selectedGallery_2Files.push({ id: uniqueId, file });

// 		const reader = new FileReader();
// 		reader.onload = function (event) {
// 			const html = `
// 				<div class="col-md-2 mb-2 position-relative preview-img-item" id="preview-img-${uniqueId}">
// 					<img src="${event.target.result}" class="img-thumbnail" style="height:150px;">
// 					<button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0" onclick="removePreviewImage_2('${uniqueId}')">&times;</button>
// 				</div>
// 			`;
// 			$('#gallery_2-wrapper').append(html); // ✅ Append to same container
// 		};
// 		reader.readAsDataURL(file);
// 	});

// 	this.value = ''; // allow reselect
// });

// function removePreviewImage_2(id) {
// 	selectedGallery_2Files = selectedGallery_2Files.filter(f => f.id !== id);
// 	$('#preview-img-' + id).remove();
// }

// function delete_gallery_2_image(name) {
// 	if (confirm("Are you sure you want to delete this image?")) {
// 		let deletedInput = $('[name="deleted_gallery_2_names"]');
// 		let currentVal = deletedInput.val();
// 		let newVal = currentVal ? currentVal.split(',') : [];

// 		if (!newVal.includes(name)) newVal.push(name);
// 		deletedInput.val(newVal.join(','));

// 		$('#' + $.escapeSelector('gallery_2-img-' + name)).remove(); // now remove properly
// 	}
// }

// On form submit, only send selected files
// $('form').on('submit', function () {
// 	const dt = new DataTransfer();
// 	selectedGallery_2Files.forEach(obj => dt.items.add(obj.file));
// 	document.getElementById('gallery_2-input').files = dt.files;
// });

</script>
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
						<img src="${event.target.result}" class="img-thumbnail" style="height:150px;">
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
		$('.select')
		$('#meal_plan').submit(function(e){
			e.preventDefault();
			start_loader();
			$.ajax({
				url:_base_url_+"classes/Content.php?f=meal_plan",
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
							location.href=_base_url_+"?page=meal_plans";
						}else{
							alert_toast("An error occured",'error')
							console.log(resp);
						}
						end_loader();
					}
				}
			})
		})
		$('.summernote').summernote({
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
	})
</script>