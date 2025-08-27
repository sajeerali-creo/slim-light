<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<?php 
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * from coupon_codes where id = '{$_GET['id']}' ");
	foreach($qry->fetch_array() as $k => $v){
		if(!is_numeric($k)){
			$$k = $v;
		}
	}
}
?>
<style>
	#cimg{
		max-width: 50%;
		object-fit: contain;
	}
</style>
<div class="col-lg-12">
	<div class="card card-outline card-primary">
		<div class="card-header">
			<h5 class="card-title"><?php echo isset($id) ? "Manage" : "Create" ?> Coupon Codes</h5>
		</div>
		<div class="card-body">
			<form id="coupon_code">
				<div class="row" class="details">
					<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
					<div class="col-sm-6">
						<div class="form-group">
							<label for="" class="control-label">Coupon Name</label>
							<input name="name" type="text" class="form-control" value="<?php echo isset($name) ? $name : '' ?>">
						</div>
					</div>
					
				</div>
				<div class="row">
					<div class="col-sm-12">
						<!-- <div class="form-group">
							<label for="" class="control-label">User Type</label>
				            <input type="radio" value="new" class="form-control" name="user_type" <?= isset($user_type) && $user_type == "new" ? 'checked' : '' ?>> New User
							<input type="radio" value="exist" class="form-control" name="user_type" <?= isset($user_type) && $user_type == "exist" ? 'checked' : '' ?>> Existing User
						</div> -->
						<div class="form-group usertyperd">
						<label class="control-label d-block">User Type</label>

						<div class="form-check form-check-inline">
							<input class="form-check-input" type="radio" name="user_type" id="newUser" value="new"
							<?= isset($user_type) && $user_type == "new" ? 'checked' : '' ?>>
							<label class="form-check-label" for="newUser">New User</label>
						</div>

						<div class="form-check form-check-inline">
							<input class="form-check-input" type="radio" name="user_type" id="existUser" value="exist"
							<?= isset($user_type) && $user_type == "exist" ? 'checked' : '' ?>>
							<label class="form-check-label" for="existUser">Existing User</label>
						</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6">
						<div class="form-group">
							<?php 
							$qry = $conn->query("SELECT * FROM `meal_plans`");
							?>
							<label for="" class="control-label">Meal Plan</label>
							<select name="meal_plan" id="meal_plan" class="form-control">
								<option value="0">All</option>
								<?php while($row= $qry->fetch_assoc()): ?>
								<option value="<?php echo $row['id']; ?>" <?= isset($meal_plan) && $meal_plan == $row['id'] ? 'selected' : ''; ?>><?php echo $row['title']; ?></option>
								<?php endwhile; ?>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6">
						<div class="form-group">
							<label for="" class="control-label">Coupon Value(%)</label>
							<input name="amount_per" type="text" class="form-control" value="<?php echo isset($amount_per) ? $amount_per : '' ?>">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6">
						<div class="form-group">
							<label for="" class="control-label">Expiry Date</label>
							<input name="expiry_date" type="text" id="coupondatepicker" class="form-control" value="<?php echo isset($expiry_date) ? $expiry_date : '' ?>">
						</div>
					</div>
				</div>
			</form>
		</div>
		<div class="card-footer">
			<button class="btn btn-primary btn-sm" form="coupon_code"><?php echo isset($_GET['id']) ? "Update": "Save" ?></button>
			<a class="btn btn-primary btn-sm" href="./?page=coupon_codes">Cancel</a>
		</div>
	</div>
</div>
 <script>
    $(function() {
      $("#coupondatepicker").datepicker({
        dateFormat: "yy-mm-dd", // Optional format
        minDate: 0,              // Disable past dates
        changeMonth: true,
        changeYear: true
      });
    });
  </script>
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
		$('#coupon_code').submit(function(e){
			e.preventDefault();
			start_loader();
			$.ajax({
				url:_base_url_+"classes/Content.php?f=coupon_code",
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
							location.href=_base_url_+"?page=coupon_codes";
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
<style>
	.usertyperd .form-check-inline .form-check-input {
		position: static;
		margin-top: 0;
		height: 21px;
		width: 30px;
		margin-left: 0;
	}
</style>