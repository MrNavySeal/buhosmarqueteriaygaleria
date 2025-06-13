<script>
    Highcharts.chart('pageChartViews', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Páginas más visitadas'
    },
    tooltip: {
        pointFormat: 'Porcentaje:<b> {point.percentage:.1f}%</b>'
    },
    accessibility: {
        point: {
            valueSuffix: '%'
        }
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: false
            },
            showInLegend: true
        }
    },
    series: [{
        name: "",
        colorByPoint: true,
        data: [
            <?php 
                foreach ($data as $det) {
                    $name = $det['name'];
                    $total = $det['total'];
                    echo "{name:'$total visitas, $name',y:$total},";
                }
            ?>
        ]
    }]
});
</script>