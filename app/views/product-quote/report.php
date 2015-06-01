<?php echo View::make('header'); ?>
<div class="page-header">
	<h2 class="text-center">净值报告 <small><?=isset($product) ? $product->name : ''?></small></h2>
</div>
<?php if(!isset($product)){ ?>
<div class="alert alert-warning">
	你还没有登记成为客户。请使用私募投顾发送给您的链接进入一次系统，之后您可以从平台“净值查询”按钮查询。
</div>
<?php } ?>

<div class="row">
	<div class="col-sm-4 col-xs-12">
		<?php if(isset($product)){ ?>
		<?php if($weixin->account !== 'news'){ ?>
		<ul>
			<li>累计成本：¥<?=$product->getCost()?>（仅供参考）</li>
			<li>浮动盈亏：<?=round(($quotes->last()->value - 1) * 100, 2)?>%</li>
		</ul>
		<?php } ?>

		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>日期</th>
					<th>单位净值</th>
					<?php if($product->type === '结构化'){ ?><th>劣后净值</th><?php } ?>
					<?php if($weixin->account !== 'news' && in_array($product->type, array('单账户', '伞型'))){ ?><th>市值</th><?php } ?>
					<?php if($weixin->account === 'consultant'){ ?>
					<th>操作</th>
					<?php } ?>
				</tr>
			</thead>
			<tbody>
				<?php foreach($quotes->sortByDesc('date') as $quote){ ?>
				<tr>
					<td><?=$quote->date->toDateString()?></td>
					<td><?=$quote->value?></td>
					<?php if($product->type === '结构化'){ ?><td><?=$quote->value_inferior?></td><?php } ?>
					<?php if($weixin->account !== 'news' && in_array($product->type, array('单账户', '伞型'))){ ?><td>¥<?=$quote->cap?></td><?php } ?>
					<?php if($weixin->account === 'consultant'){ ?>
					<td><a href="<?=url('product/' . $product->id . '/quote/' . $quote->id . '/edit')?>" class="btn btn-xs btn-info">修改</a></td>
					<?php } ?>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		<?php } ?>
	</div>
	<div class="col-sm-8 col-xs-12">
		<div id="chart"></div>
	</div>
</div>
<script type="text/javascript" src="<?=url()?>/packages/highstock-release/highstock.js"></script>
<script type="text/javascript">
	jQuery(function($){
		
		Highcharts.setOptions({
			lang: {
				months: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
				shortMonths: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
				weekdays: ['星期日', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六']
			}
		});
		
		$('#chart').highcharts('StockChart', {
			 
			series: [
				<?php if(isset($product)){ ?>
				{
					name: '<?=$product->name?> 单位净值',
					data: <?=@json_encode($chart_data[$product->id])?>,
					tooltip: {
						valueDecimals: 2
					}
				},
				<?php } ?>
				<?php if(isset($product) && $product->type === '结构化' && $quotes[0]->value_inferior){ ?>
				{
					name: '<?=$product->name?> 劣后净值',
					data: <?=@json_encode($chart_data[$product->id . '_inferior'])?>,
					tooltip: {
						valueDecimals: 2
					},
					color: '#F66'
				},
				<?php } ?>
				{
					name: '沪深300指数',
					data: <?=json_encode($chart_data['sh300'])?>,
					tooltip: {
						valueDecimals: 2
					},
					color: '#AAA'
				}
			],
		
			rangeSelector: {
				enabled: false
			},

			yAxis: {
				labels: {
					formatter: function () {
						return (this.value > 0 ? ' + ' : '') + this.value + '%';
					}
				},
				plotLines: [{
					value: 0,
					width: 2,
					color: 'silver'
				}]
			},
			
			xAxis: {
				labels: {
					formatter: function () {
						return Highcharts.dateFormat('%m.%d', this.value);
					}
				}
			},

			plotOptions: {
				series: {
					compare: 'percent'
				}
			},
			navigator: {
				enabled: false
			},
			scrollbar: {
				enabled: false
			},
			credits: {
				enabled: false
			}
		});
	});
</script>
<?php echo View::make('footer'); ?>