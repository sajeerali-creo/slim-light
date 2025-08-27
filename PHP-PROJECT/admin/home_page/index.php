<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<?php 
//if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * from `admin_home`");
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
			<!-- <h5 class="card-title">Home Contents</h5> -->
		</div>
		<div class="card-body">
			<form id="home_c">
				<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
				<div class="form-group">
					<!-- <input type="hidden" name="file" value="home"> -->
					<label for="" class="control-label">Who We Are</label>
		             <textarea name="who_we_are" id="" cols="30" rows="10" class="form-control summernote"><?php echo (isset($who_we_are)) ? html_entity_decode(($who_we_are)) : '' ?></textarea>
				</div>
				<div class="form-group">
					<!-- <input type="hidden" name="file" value="home"> -->
					<label for="" class="control-label">How It Works?</label>
		             <textarea name="how_it_work" id="" cols="30" rows="10" class="form-control summernote"><?php echo (isset($how_it_work)) ? html_entity_decode(($how_it_work)) : '' ?></textarea>
				</div>
				<div class="form-group">
					<!-- <input type="hidden" name="file" value="home"> -->
					<label for="" class="control-label">Why BLite?</label>
		             <textarea name="why_bLite" id="" cols="30" rows="10" class="form-control summernote"><?php echo (isset($why_bLite)) ? html_entity_decode(($why_bLite)) : '' ?></textarea>
				</div>
				<div class="form-group">
					<!-- <input type="hidden" name="file" value="home"> -->
					<label for="" class="control-label">Exciting News?</label>
		             <textarea name="exciting_news" id="" cols="30" rows="10" class="form-control summernote"><?php echo (isset($exciting_news)) ? html_entity_decode(($exciting_news)) : '' ?></textarea>
				</div>
			</form>
		</div>
		<div class="card-footer">
			<button class="btn btn-primary btn-sm" form="home_c">Update </button>
		</div>
	</div>
</div>

<script>

	$(document).ready(function(){
		$('#home_c').submit(function(e){
			e.preventDefault();
			start_loader();
			$.ajax({
				url:_base_url_+"classes/Content.php?f=admin_home",
				method:"POST",
				data:$(this).serialize(),
				error: err=>{
					alert_toast("An error occured",'error')
					console.log(err);
				},
				success:function(resp){
					if(resp != undefined){
						resp = JSON.parse(resp)
						if(resp.status == 'success'){
							location.reload()
						}else{
							alert_toast("An error occured",'error')
							console.log(resp);
							end_loader();
						}
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
		            [ 'view', [ 'undo', 'redo', 'fullscreen', 'codeview', 'help' ] ]
		        ]
		    })
	})
	
</script>