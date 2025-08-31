<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<?php 
	$qry = $conn->query("SELECT * from contacts ");
	while($row = $qry->fetch_assoc()){
		$meta[$row['meta_field']] = $row['meta_value'];
	}
?>
<div class="col-lg-12">
	<div class="card card-outline card-primary">
		<div class="card-header">
			<!-- <h5 class="card-title">General Details</h5> -->
		</div>
		<div class="card-body">
			<form id="contact">
				<div class="row">
					<div class="col-sm-6">
						<div class="form-group">
							<label for="" class="control-label">Contact #</label>
							<div class="input-group">
			                    <div class="input-group-prepend">
			                      <span class="input-group-text"><i class="fa fa-phone"></i></span>
			                    </div>
			                    <input type="text" name="mobile" class="form-control" value="<?php echo isset($meta['mobile']) ? $meta['mobile'] : '' ?>">
		                	</div>
						</div>

						<div class="form-group">
							<label for="" class="control-label">Email</label>
							<div class="input-group">
			                    <div class="input-group-prepend">
			                      <span class="input-group-text"><i class="fa fa-envelope"></i></span>
			                    </div>
			                    <input type="text" class="form-control" name="email" value="<?php echo isset($meta['email']) ? $meta['email'] : '' ?>">
		                	</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6">
						<div class="form-group">
							<label for="" class="control-label">Location 1</label>
				            <input type="text" class="form-control" placeholder="Location Title" name="location_title1" value="<?php echo isset($meta['location_title1']) ? $meta['location_title1'] : '' ?>">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6">
						<div class="form-group">
				             <textarea name="address1" id="" cols="30" rows="10" class="form-control " placeholder="address"><?php echo (isset($meta['address1'])) ? ($meta['address1']) : '' ?></textarea>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6">
						<div class="form-group">
				             <textarea name="iframe1" id="" cols="30" rows="10" class="form-control " placeholder="Map Iframe"><?php echo (isset($meta['iframe1'])) ? ($meta['iframe1']) : '' ?></textarea>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6">
						<div class="form-group">
							<label for="" class="control-label">Location 2</label>
				            <input type="text" class="form-control" placeholder="Location Title" name="location_title2" value="<?php echo isset($meta['location_title2']) ? $meta['location_title2'] : '' ?>">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6">
						<div class="form-group">
				             <textarea name="address2" id="" cols="30" rows="10" class="form-control " placeholder="address"><?php echo (isset($meta['address2'])) ? ($meta['address2']) : '' ?></textarea>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6">
						<div class="form-group">
				             <textarea name="iframe2" id="" cols="30" rows="10" class="form-control " placeholder="Map Iframe"><?php echo (isset($meta['iframe2'])) ? ($meta['iframe2']) : '' ?></textarea>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6">
						<div class="form-group">
							<label for="" class="control-label">Location 3</label>
				            <input type="text" placeholder="Location Title" class="form-control" name="location_title3" value="<?php echo isset($meta['location_title3']) ? $meta['location_title3'] : '' ?>">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6">
						<div class="form-group">
				             <textarea name="address3" id="" cols="30" rows="10" class="form-control " placeholder="address" placeholder="Map Iframe"><?php echo (isset($meta['address3'])) ? ($meta['address3']) : '' ?></textarea>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6">
						<div class="form-group">
				             <textarea name="iframe3" id="" cols="30" rows="10" class="form-control " placeholder="Map Iframe"><?php echo (isset($meta['iframe3'])) ? ($meta['iframe3']) : '' ?></textarea>
						</div>
					</div>
				</div>
			</form>
		</div>
		<div class="card-footer">
			<button class="btn btn-primary btn-sm" form="contact">Save</button>
		</div>
	</div>
</div>

<script>

	$(document).ready(function(){
		$('.select')
		$('#contact').submit(function(e){
			e.preventDefault();
			start_loader();
			$.ajax({
				url:_base_url_+"classes/Content.php?f=general_details",
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
							location.href=_base_url_+"?page=general_details";
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