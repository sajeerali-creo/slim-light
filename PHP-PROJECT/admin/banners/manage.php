<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<?php 
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * from banners where id = '{$_GET['id']}' ");
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
		<h5 class="card-title"><?php echo isset($id) ? "Manage": "Create" ?> Banner</h5>
		</div>
		<div class="card-body">
			<form id="banner">
				<div class="row" class="details">
					<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
					<div class="col-sm-6">
						<div class="form-group">
							<label for="" class="control-label">Banner Title</label>
							<input name="heading" type="text" class="form-control" value="<?php echo isset($heading) ? $heading : '' ?>">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6">
						<div class="form-group">
							<label for="" class="control-label">Banner Sub Title</label>
							<textarea name="sub_heading" cols="30" rows="2" class="form-control"><?php echo isset($sub_heading) ? $sub_heading : '' ?></textarea>
						</div>
					</div>
				</div>
				<div class="row" class="details">
					<div class="col-sm-6">
						<div class="form-group">
							<label for="" class="control-label">Buton Text</label>
							<input name="button_text" type="text" class="form-control" value="<?php echo isset($button_text) ? $button_text : '' ?>">
						</div>
					</div>
				</div>
				<div class="row" class="details">
					<div class="col-sm-6">
						<div class="form-group">
							<label for="" class="control-label">Buton URL</label>
							<input name="button_url" type="url" class="form-control" value="<?php echo isset($button_url) ? $button_url : '' ?>">
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="" class="control-label">Banner Image</label>
					<div class="custom-file">
						<input type="hidden" name="old_file" value="<?php echo isset($file_path) ? $file_path :'' ?>">
		              <input type="file" class="custom-file-input rounded-circle" id="customFile" name="img" onchange="displayImg(this,$(this))">
		              <label class="custom-file-label" for="customFile">Choose file</label>
		            </div>
				</div>
				<div class="form-group d-flex justify-content-center">
					<img src="<?php echo validate_image(isset($file_path) ? $file_path :'') ?>" alt="" id="cimg" class="img-fluid img-thumbnail">
				</div>
			</form>
		</div>
		<div class="card-footer">
			<button class="btn btn-primary btn-sm" form="banner"><?php echo isset($_GET['id']) ? "Update": "Save" ?></button>
			<a class="btn btn-primary btn-sm" href="./?page=banners">Cancel</a>
		</div>
	</div>
</div>
<style>
	img#cimg{
		height: 15vh;
		width: 15vh;
		object-fit: cover;
		border-radius: 100% 100%;
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
		$('#banner').submit(function(e){
			e.preventDefault();
			start_loader();
			$.ajax({
				url:_base_url_+"classes/Content.php?f=banner",
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
							location.href=_base_url_+"?page=banners";
						}else{
							alert_toast(resp.err_msg,'error')
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