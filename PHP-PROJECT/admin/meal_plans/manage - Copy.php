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
					<div class="col-sm-6">
						<div class="form-group">
							<label for="title" class="control-label">Title</label>
							<!-- <textarea name="title" cols="30" rows="2" class="form-control"><?php echo isset($title) ? $title : '' ?></textarea> -->
							<input name="title" type="text" class="form-control" value="<?php echo isset($title) ? $title : '' ?>">
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
					<div class="col-md-12">
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
					<label class="control-label">Upload Additional Gallery Images</label>
					<input type="file" name="gallery_imgs[]" multiple class="form-control">
					<hr>
					<h6>Existing Gallery:</h6>
					<div class="row">
					  <?php 
					  if(isset($id)){
						$gallery = $conn->query("SELECT * FROM meal_plan_gallery WHERE meal_plan_id = $id");
						while($row = $gallery->fetch_assoc()):
					  ?>
						<div class="col-md-3 mb-2">
						  <img src="<?php echo validate_image($row['file_path']) ?>" class="img-thumbnail" style="height:150px;">
						  <br>
						  <a href="javascript:void(0)" onclick="delete_gallery_image(<?= $row['id'] ?>)" class="btn btn-sm btn-danger mt-1">Delete</a>
						</div>
					  <?php endwhile; } ?>
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
<script>
function displayImg(input,_this) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        reader.onload = function (e) {
	        	$('#cimg').attr('src', e.target.result);
	        }

	        reader.readAsDataURL(input.files[0]);
	    }
	}
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
	
	function delete_gallery_image(id){
		if(confirm("Delete this image?")){
			start_loader();
			$.ajax({
				url:_base_url_+"classes/Content.php?f=meal_plan_gallery_delete",
				method:"POST",
				data:{id:id},
				dataType:"json",
				error:err=>{
					console.log(err)
					//alert(err);
					alert_toast("An error occurred.",'error')
					end_loader();
				},
				success:function(resp){
					if(resp.status == 'success'){
						location.reload()
					}
				}
			})
		}
	}
</script>