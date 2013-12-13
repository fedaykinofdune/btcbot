<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;

$flags=", {
		        type: 'flags',
		        name: 'Flags on series',
		        data: [";
foreach ($buy as $btc)
{
	$flags.="{
					x: ".(strtotime($btc->dtm)*1000+4*60*60*1000).",
					title: 'B'
				}, ";
}
$flags.="],
		        onSeries: 'dataseries',
		        shape: 'squarepin'
		    }";
?>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script src="http://code.highcharts.com/stock/highstock.js"></script>

<div id="container" style="height: 500px; min-width: 500px"></div>
<script>
$(function() {
	
		
		// Create the chart
		$('#container').highcharts('StockChart', {
		    chart: {
		    },

		    rangeSelector: {
		        selected: 1
		    },

		    title: {
		        text: 'BTC to RUR exchange rate'
		    },

		    yAxis: {
		        title: {
		            text: 'Exchange rate'
		        }
		    },

			series: [{
		        name: 'buy',
		        data: <?php echo $data_buy; ?>,
				id: 'dataseries',
				tooltip: {
					valueDecimals: 4
				}
		    }, {
		        name: 'sell',
		        data: <?php echo $data_sell; ?>,
				id: 'dataseries',
				tooltip: {
					valueDecimals: 4
				}
		    }
		     <?php echo $flags; ?>]
		    
		});
	
});

</script>