<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<style>
	.banner-img{
		width: 75px;
		object-fit:contain;
	}
</style>
<div class="col-lg-12">
	<div class="card card-outline card-primary">
		<div class="card-header">
			<div class="card-tools">
				<a class="btn btn-primary new_coupon_code" href="javascript:void(0)"><i class="fa fa-plus"></i>&nbsp;Add New</a>
			</div>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table tabe-hover table-bordered table-compact" id="list">
					<colgroup>
						<col width="10%">
						<col width="20%">
						<col width="25%">
						<col width="25%">
						<!-- <col width="15%"> -->
					</colgroup>
					<thead>
						<tr>
							<th class="text-center">#</th>
							<th>Coupon Name</th>
							<th>Coupon Amount(%)</th>
							<th>User Type</th>
							<!-- <th>Meal Plan</th> -->
							<th>Expiry Date</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$i = 1;
						$qry = $conn->query("SELECT * FROM `coupon_codes` order by name asc ");
						while($row= $qry->fetch_assoc()):
						?>
						<tr>
							<th class="text-center"><?php echo $i++ ?></th>
							<td>
								<b class=""><?php echo $row['name'] ?></b>
							</td>
							<td><b class=""><?php echo ucwords($row['amount_per']) ?></b></td>
							<td><b class=""><?php echo ucwords($row['user_type']) ?></b></td>
							<!-- <td><b class=""><?php echo ucwords($row['meal_plan']) ?></b></td> -->
							<td><b class=""><?php echo date('d/m/Y', strtotime($row['expiry_date'])) ?></b></td>
							<td class="text-center">
								<div class="btn-group">
									<a href="javascript:void(0)" data-id='<?php echo $row['id'] ?>' class="btn btn-primary btn-flat btn-sm manage_coupon_code">
									<i class="fas fa-edit"></i>
									</a>
									<button type="button" class="btn btn-danger btn-sm btn-flat delete_coupon_code" data-id="<?php echo $row['id'] ?>">
									<i class="fas fa-trash"></i>
									</button>
							</div>
							</td>
						</tr>	
					<?php endwhile; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<script>

	$(document).ready(function(){
		$('.new_coupon_code').click(function(){
			location.href = _base_url_+"?page=coupon_codes/manage";
		})
		$('.manage_coupon_code').click(function(){
			location.href = _base_url_+"?page=coupon_codes/manage&id="+$(this).attr('data-id')
		})
		$('.delete_coupon_code').click(function(){
		_conf("Are you sure to delete this Coupon Code detail?","delete_coupon_code",[$(this).attr('data-id')])
		})
		$('#list').dataTable()
	})
	function delete_coupon_code($id){
		start_loader()
		$.ajax({
			url:_base_url_+'classes/Content.php?f=coupon_code_delete',
			method:'POST',
			data:{id:$id},
			dataType:'json',
			success:function(resp){
				if(resp.status == 'success'){
					location.reload()
				}else{
					alert_toast(resp.err_msg,'error')
				}
				end_loader();
			}
		})
	}
</script>