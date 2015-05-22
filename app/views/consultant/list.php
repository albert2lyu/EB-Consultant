<?php echo View::make('header'); ?>

<div class="page-header">
	<h2 class="text-center">私募投顾列表</h2>
</div>
<!--<a href="<?=url('signup')?>" class="btn btn-primary btn-block">登记新投顾</a>
<hr>-->
<table class="table table-striped">
	<?php foreach($consultants as $consultant){ ?>
	<tr role="presentation">
		<td>
			<h4><?=$consultant->name?> <span class="small"><?=$consultant->type?></span></h4>
		</td>
		<td>
			<a href="<?=url('view-consultant/' . $consultant->id)?>" class="btn btn-default"><span class="glyphicon glyphicon-edit"></span> 资料</a>
		</td>
		<td>
			<a href="<?=url()?>/view-client?consultant_id=<?=$consultant->id?>" class="btn btn-default"><span class="glyphicon glyphicon-usd"></span> 产品</a>
		</td>
	</tr>
	<?php } ?>
</table>

<?php echo View::make('footer'); ?>