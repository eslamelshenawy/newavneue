<div class="chart-container">
    <canvas id="canvas" style="height: 100px"></canvas>
</div>
<script>
    var lineChartData = {
        labels: [
            @foreach($data as $k => $v)
                '{{ $k }}',
            @endforeach
        ],
        datasets: [{
            fillColor: "rgba(220,220,220,0)",
            strokeColor: "#caa42d",
            pointColor: "#caa42d",
            data: [
                @foreach($data as $k => $v)
                {{ $v['total'] }},
                @endforeach
                ]
        },{
            fillColor: "rgba(220,220,220,0)",
            strokeColor: "#3c8dbc",
            pointColor: "#3c8dbc",
            data: [
                @foreach($data as $k => $v)
                {{ $v['commission'] }},
                @endforeach
                ]
        }]

    }

    Chart.defaults.global.animationSteps = 50;
    Chart.defaults.global.tooltipYPadding = 16;
    Chart.defaults.global.tooltipCornerRadius = 0;
    Chart.defaults.global.tooltipTitleFontStyle = "normal";
    Chart.defaults.global.tooltipFillColor = "rgba(0,160,0,0.8)";
    Chart.defaults.global.animationEasing = "easeOutBounce";
    Chart.defaults.global.responsive = true;
    Chart.defaults.global.scaleLineColor = "black";
    Chart.defaults.global.scaleFontSize = 16;

    var ctx = document.getElementById("canvas").getContext("2d");
    var LineChartDemo = new Chart(ctx).Line(lineChartData, {
        pointDotRadius: 10,
        bezierCurve: false,
        scaleShowVerticalLines: false,
        scaleGridLineColor: "black"
    });
</script>
