<?php echo View::make('header'); ?>
<div class="page-header">
	<h2 class="text-center">净值报告</h2>
</div>
<ul>
	<li>成本：¥<?=$products[0]->getCost()?></li>
	<li>浮盈：<?=round(($products[0]->quotes()->dateDescending()->first()->cap - $products[0]->initial_cap) / $products[0]->initial_cap * 100, 2)?>%</li>
</ul>

<ul class="list-unstyled">
	<?php foreach($products as $product){ ?>
	<li>
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>日期</th>
					<th>净值</th>
					<th>市值</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($product->quotes()->dateDescending()->get() as $quote){ ?>
				<tr>
					<td><?=$quote->date->toDateString()?></td>
					<td><?=$quote->value?></td>
					<td>¥<?=$quote->cap?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</li>
	<?php } ?>
</ul>
<div id="chart"></div>
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
			 
			series: [{
				name: '本账户',
				data: <?=json_encode($chartData[$products[0]->id])?>,
				tooltip: {
					valueDecimals: 2
				}
			},{
				name: '沪深300指数',
				data: <?=json_encode($chartData['sh300'])?>,
				tooltip: {
					valueDecimals: 2
				}
			}],
		
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
