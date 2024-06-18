@extends('layouts.app', ['pageSlug' => 'dashboard'])

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-chart">
                <div class="card-header ">
                    <div class="row">
                        <div class="col-sm-6 text-left">
                            <h5 class="card-category">Users Data</h5>
                            <h2 class="card-title">Performance ({{ $userCounts }})</h2>
                        </div>
                        <div class="col-sm-6">
                            <div class="btn-group btn-group-toggle float-right" data-toggle="buttons">
                                <label class="btn btn-sm btn-primary btn-simple active" id="0">
                                    <input type="radio" name="options" checked>
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">Month</span>
                                    <span class="d-block d-sm-none">
                                        <i class="tim-icons icon-single-02"></i>
                                    </span>
                                </label>
                                <label class="btn btn-sm btn-primary btn-simple" id="1">
                                    <input type="radio" class="d-none d-sm-none" name="options">
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">Week</span>
                                    <span class="d-block d-sm-none">
                                        <i class="tim-icons icon-gift-2"></i>
                                    </span>
                                </label>
                                <label class="btn btn-sm btn-primary btn-simple" id="2">
                                    <input type="radio" class="d-none" name="options">
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">Day</span>
                                    <span class="d-block d-sm-none">
                                        <i class="tim-icons icon-tap-02"></i>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="chartBig1"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card card-chart">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-6 text-left">
                            <h5 class="card-category">Post Data</h5>
                            <h2 class="card-title">Performance ({{ $postCounts }})</h2>
                        </div>
                        <div class="col-sm-6">
                            <div class="btn-group btn-group-toggle float-right" data-toggle="buttons">
                                <label class="btn btn-sm btn-primary btn-simple active" id="post_0">
                                    <input type="radio" name="options" checked>
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">Month</span>
                                    <span class="d-block d-sm-none">
                                        <i class="tim-icons icon-single-02"></i>
                                    </span>
                                </label>
                                <label class="btn btn-sm btn-primary btn-simple" id="post_1">
                                    <input type="radio" class="d-none d-sm-none" name="options">
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">Week</span>
                                    <span class="d-block d-sm-none">
                                        <i class="tim-icons icon-gift-2"></i>
                                    </span>
                                </label>
                                <label class="btn btn-sm btn-primary btn-simple" id="post_2">
                                    <input type="radio" class="d-none" name="options">
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">Day</span>
                                    <span class="d-block d-sm-none">
                                        <i class="tim-icons icon-tap-02"></i>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="chartBig2"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('black') }}/js/plugins/chartjs.min.js"></script>
    <script>
        $(document).ready(function() {

            gradientChartOptionsConfigurationWithTooltipPurple = {
                maintainAspectRatio: false,
                legend: {
                    display: false
                },

                tooltips: {
                    backgroundColor: '#f5f5f5',
                    titleFontColor: '#333',
                    bodyFontColor: '#666',
                    bodySpacing: 4,
                    xPadding: 12,
                    mode: "nearest",
                    intersect: 0,
                    position: "nearest"
                },
                responsive: true,
                scales: {
                    yAxes: [{
                        barPercentage: 1.6,
                        gridLines: {
                            drawBorder: false,
                            color: 'rgba(29,140,248,0.0)',
                            zeroLineColor: "transparent",
                        },
                        ticks: {
                            suggestedMin: 60,
                            suggestedMax: 125,
                            padding: 20,
                            fontColor: "#9a9a9a"
                        }
                    }],

                    xAxes: [{
                        barPercentage: 1.6,
                        gridLines: {
                            drawBorder: false,
                            color: 'rgba(225,78,202,0.1)',
                            zeroLineColor: "transparent",
                        },
                        ticks: {
                            padding: 20,
                            fontColor: "#9a9a9a"
                        }
                    }]
                }
            };

            var ctx = document.getElementById("chartBig1").getContext('2d');

            var gradientStroke = ctx.createLinearGradient(0, 230, 0, 50);

            gradientStroke.addColorStop(1, 'rgba(72,72,176,0.1)');
            gradientStroke.addColorStop(0.4, 'rgba(72,72,176,0.0)');
            gradientStroke.addColorStop(0, 'rgba(119,52,169,0)'); //purple colors

            var config = {
                type: 'line',
                data: {
                    labels: @json($userDataMonth['labels']),
                    datasets: [{
                        label: "Users",
                        fill: true,
                        backgroundColor: gradientStroke,
                        borderColor: '#d346b1',
                        borderWidth: 2,
                        borderDash: [],
                        borderDashOffset: 0.0,
                        pointBackgroundColor: '#d346b1',
                        pointBorderColor: 'rgba(255,255,255,0)',
                        pointHoverBackgroundColor: '#d346b1',
                        pointBorderWidth: 20,
                        pointHoverRadius: 4,
                        pointHoverBorderWidth: 15,
                        pointRadius: 4,
                        data: @json($userDataMonth['data']),
                    }]
                },
                options: gradientChartOptionsConfigurationWithTooltipPurple
            };
            var myChartData = new Chart(ctx, config);

            $("#0").click(function() {
                var newData = @json($userDataMonth['data']);
                var newLabels = @json($userDataMonth['labels']);

                updateChartData(myChartData, newData, newLabels);
            });

            $("#1").click(function() {
                var newData = @json($userDataWeek['data']);
                var newLabels = @json($userDataWeek['labels']);

                updateChartData(myChartData, newData, newLabels);
            });

            $("#2").click(function() {
                var newData = @json($userDataDay['data']);
                var newLabels = @json($userDataDay['labels']);

                updateChartData(myChartData, newData, newLabels);
            });

            var ctx2 = document.getElementById("chartBig2").getContext('2d');
            var gradientStroke2 = ctx2.createLinearGradient(0, 230, 0, 50);

            gradientStroke2.addColorStop(1, 'rgba(72,72,176,0.1)');
            gradientStroke2.addColorStop(0.4, 'rgba(72,72,176,0.0)');
            gradientStroke2.addColorStop(0, 'rgba(119,52,169,0)'); //purple colors

            var config2 = {
                type: 'line',
                data: {
                    labels: @json($chartPostDataMonth['labels']),
                    datasets: [{
                        label: "Posts",
                        fill: true,
                        backgroundColor: gradientStroke,
                        borderColor: '#d346b1',
                        borderWidth: 2,
                        borderDash: [],
                        borderDashOffset: 0.0,
                        pointBackgroundColor: '#d346b1',
                        pointBorderColor: 'rgba(255,255,255,0)',
                        pointHoverBackgroundColor: '#d346b1',
                        pointBorderWidth: 20,
                        pointHoverRadius: 4,
                        pointHoverBorderWidth: 15,
                        pointRadius: 4,
                        data: @json($chartPostDataMonth['data']),
                    }]
                },
                options: gradientChartOptionsConfigurationWithTooltipPurple
            };

            var myChartData2 = new Chart(ctx2, config2);

            $("#post_0").click(function() {
                var newData = @json($chartPostDataMonth['data']);
                var newLabels = @json($chartPostDataMonth['labels']);

                updateChartData(myChartData2, newData, newLabels);
            });

            $("#post_1").click(function() {
                var newData = @json($chartPostDataWeek['data']);
                var newLabels = @json($chartPostDataWeek['labels']);

                updateChartData(myChartData2, newData, newLabels);
            });

            $("#post_2").click(function() {
                var newData = @json($chartPostDataDay['data']);
                var newLabels = @json($chartPostDataDay['labels']);

                updateChartData(myChartData2, newData, newLabels);
            });

            function updateChartData(chart, newData, newLabels) {
                chart.data.datasets.forEach((dataset) => {
                    dataset.data = newData;
                });
                chart.data.labels = newLabels;

                // This approach forces Chart.js to reevaluate the tooltip and animation state
                chart.update({
                    duration: 0,
                    easing: 'easeOutBounce',
                    lazy: false,
                });
            };
        });
    </script>
@endpush
