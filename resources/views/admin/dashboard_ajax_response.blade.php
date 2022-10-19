
<div class="row row-cards-one">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <div class="card">
            <h5 class="card-header">{{ __('Total Sales in Last 30 Days') }}</h5>
            <div class="card-body">
                <div id="totalsalesChart"></div>
            </div>
        </div>
    </div>
</div>
<div class="row row-cards-one">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <div class="card">
            <h5 class="card-header">{{ __('Status wise Last 30 Days total order') }}</h5>
            <div class="card-body">
                <div id="statuswiseorder"></div>
            </div>
        </div>
    </div>
</div>
<div class="row row-cards-one">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <div class="card">
            <h5 class="card-header">{{ __('Total paid transaction amount in Last 30 Days') }}</h5>
            <div class="card-body">
                <div id="paidAmount"></div>
            </div>
        </div>
    </div>
</div>




<script>
    am4core.ready(function() {
        am4core.useTheme(am4themes_material);
        am4core.useTheme(am4themes_animated);
        let chart = am4core.create("totalsalesChart", am4charts.XYChart);
        chart.cursor = new am4charts.XYCursor();
        chart.cursor.lineX.disabled = true;
        chart.cursor.lineY.disabled = true;
        chart.scrollbarX = new am4core.Scrollbar();
        chart.data = {!! $totalsales !!};
        let dateAxis = chart.xAxes.push(new am4charts.DateAxis());
        dateAxis.renderer.grid.template.location = 0.5;
        dateAxis.dateFormatter.inputDateFormat = "yyyy-MM-dd";
        dateAxis.renderer.minGridDistance = 40;
        dateAxis.tooltipDateFormat = "MMM dd, yyyy";
        dateAxis.dateFormats.setKey("day", "dd");
        let valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
        let series = chart.series.push(new am4charts.LineSeries());
        series.tooltipText = "{date}\n[bold font-size: 17px]Value: {valueY}[/]";
        series.dataFields.valueY = "value";
        series.dataFields.dateX = "date";
        series.strokeDasharray = 3;
        series.strokeWidth = 2
        series.strokeOpacity = 0.3;
        series.strokeDasharray = "3,3"
        let bullet = series.bullets.push(new am4charts.CircleBullet());
        bullet.strokeWidth = 2;
        bullet.stroke = am4core.color("#fff");
        bullet.setStateOnChildren = true;
        bullet.propertyFields.fillOpacity = "opacity";
        bullet.propertyFields.strokeOpacity = "opacity";
        let hoverState = bullet.states.create("hover");
        hoverState.properties.scale = 1.7;
        function createTrendLine(data) {
            let trend = chart.series.push(new am4charts.LineSeries());
            trend.dataFields.valueY = "value";
            trend.dataFields.dateX = "date";
            trend.strokeWidth = 2
            trend.stroke = trend.fill = am4core.color("#c00");
            trend.data = data;
            let bullet1 = trend.bullets.push(new am4charts.CircleBullet());
            bullet1.tooltipText = "{date}\n[bold font-size: 17px]value: {valueY}[/]";
            bullet1.strokeWidth = 2;
            bullet1.stroke = am4core.color("#fff")
            bullet1.circle.fill = trend.stroke;
            let hoverState = bullet1.states.create("hover");
            hoverState.properties.scale = 1.7;
            return trend;
        }
    });
</script>
<script>
    am4core.ready(function() {
        am4core.useTheme(am4themes_animated);
        let transactionChart = am4core.create("paidAmount", am4charts.XYChart);
        transactionChart.paddingRight = 20;
        transactionChart.data = {!! $transactionAmount !!};
        let dateAxis = transactionChart.xAxes.push(new am4charts.DateAxis());
        dateAxis.renderer.grid.template.location = 0;
        dateAxis.renderer.axisFills.template.disabled = true;
        dateAxis.renderer.ticks.template.disabled = true;
        let valueAxis = transactionChart.yAxes.push(new am4charts.ValueAxis());
        valueAxis.tooltip.disabled = true;
        valueAxis.renderer.minWidth = 35;
        valueAxis.renderer.axisFills.template.disabled = true;
        valueAxis.renderer.ticks.template.disabled = true;
        let series = transactionChart.series.push(new am4charts.LineSeries());
        series.dataFields.dateX = "date";
        series.dataFields.valueY = "value";
        series.strokeWidth = 2;
        series.tooltipText = "Payment Date: {dateX},\n[bold font-size: 17px]Pay Amount: {valueY}[/]";
        series.propertyFields.stroke = "color";
        transactionChart.cursor = new am4charts.XYCursor();
        let scrollbarX = new am4core.Scrollbar();
        transactionChart.scrollbarX = scrollbarX;
        dateAxis.start = 0.6;
        dateAxis.keepSelection = true;
    });
</script>
<script>
    am4core.ready(function() {
        am4core.useTheme(am4themes_animated);
        let chart = am4core.create("statuswiseorder", am4charts.XYChart);
        chart.colors.step = 2;
        chart.data = {!! $statuswiseorder !!};
        let dateAxis = chart.xAxes.push(new am4charts.DateAxis());
        dateAxis.renderer.minGridDistance = 50;
        function createAxisAndSeries(field, name, opposite, bullet) {
            var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
            if(chart.yAxes.indexOf(valueAxis) != 0){
                valueAxis.syncWithAxis = chart.yAxes.getIndex(0);
            }
            var series = chart.series.push(new am4charts.LineSeries());
            series.dataFields.valueY = field;
            series.dataFields.dateX = "date";
            series.strokeWidth = 2;
            series.yAxis = valueAxis;
            series.name = name;
            series.tooltipText = "{name}: [bold]{valueY}[/]";
            series.tensionX = 0.8;
            series.showOnInit = true;
            var interfaceColors = new am4core.InterfaceColorSet();

            switch(bullet) {
                case "triangle":
                    var bullet = series.bullets.push(new am4charts.Bullet());
                    bullet.width = 12;
                    bullet.height = 12;
                    bullet.horizontalCenter = "middle";
                    bullet.verticalCenter = "middle";

                    var triangle = bullet.createChild(am4core.Triangle);
                    triangle.stroke = interfaceColors.getFor("background");
                    triangle.strokeWidth = 2;
                    triangle.direction = "top";
                    triangle.width = 12;
                    triangle.height = 12;
                    break;
                case "rectangle":
                    var bullet = series.bullets.push(new am4charts.Bullet());
                    bullet.width = 10;
                    bullet.height = 10;
                    bullet.horizontalCenter = "middle";
                    bullet.verticalCenter = "middle";

                    var rectangle = bullet.createChild(am4core.Rectangle);
                    rectangle.stroke = interfaceColors.getFor("background");
                    rectangle.strokeWidth = 2;
                    rectangle.width = 10;
                    rectangle.height = 10;
                    break;
                default:
                    var bullet = series.bullets.push(new am4charts.CircleBullet());
                    bullet.circle.stroke = interfaceColors.getFor("background");
                    bullet.circle.strokeWidth = 2;
                    break;
            }
            valueAxis.renderer.line.strokeOpacity = 1;
            valueAxis.renderer.line.strokeWidth = 2;
            valueAxis.renderer.line.stroke = series.stroke;
            valueAxis.renderer.labels.template.fill = series.stroke;
            valueAxis.renderer.opposite = opposite;
        }

        createAxisAndSeries("pending", "Pending", false, "circle");
        createAxisAndSeries("processing", "Processing", true, "triangle");
        createAxisAndSeries("completed", "Completed", true, "rectangle");
        createAxisAndSeries("declined", "Declined", true, "rectangle");

        chart.legend = new am4charts.Legend();
        chart.cursor = new am4charts.XYCursor();
    });
</script>