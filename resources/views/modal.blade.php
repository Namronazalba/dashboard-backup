<style>
    .apexcharts-menu-icon {
        display: none;
    }
</style>
<div class="card2" id="fixed-offline-card" onclick="window.changeRoute('{{ route('modal_fixed_table') }}')">
    <div class="card-top">
        <img src="{{asset('img/offline-vehicles.svg')}}" alt="">
        <div class="card-label">    
            <span>Total</span>        
            <span class="total">{{ $fixed }}</span>
        </div>
    </div>
    <span><b>Fixed Offline</b></span>
</div>
<div class="card2" id="standby-card" onclick="window.changeRoute('{{ route('modal_standby_table') }}')">
    <div class="card-top">
        <div id="chart1"></div>
        <div class="card-label">
            <span>Total</span>
            <span class="total">{{ $standby }}</span>
        </div>
    </div>
    <span><b>Standby</b></span>
</div>
<div class="card2" id="breakdown-card" onclick="window.changeRoute('{{ route('modal_breakdown_table') }}')">
    <div class="card-top">
        <div id="chart2"></div>
        <div class="card-label">
            <span>Total</span>
            <span class="total">{{ $breakdown }}</span>
        </div>
    </div>
    <span><b>Breakdown</b></span>
</div>
<div class="card2" id="decommissioned-card" onclick="window.changeRoute('{{ route('modal_decommissioned_table') }}')">
    <div class="card-top">
        <div id="chart3"></div>
        <div class="card-label">
            <span>Total</span>
            <span class="total">{{ $decommissioned }}</span>
        </div>
    </div>
    <span><b>Decommissioned</b></span>
</div>

<script>
    var data = {{ $data }}
    var standby = {{ $standby }};
    var breakdown = {{ $breakdown }};
    var decommissioned = {{ $decommissioned }};
    // Define the options object once
    var options = {
        series: [50],
        chart: {
            height: 150,
            type: 'radialBar',
            toolbar: {
                show: true
            }
        },
        plotOptions: {
            radialBar: {
                startAngle: -135,
                endAngle: 225,
                hollow: {
                    margin: 0,
                    size: '35%',
                    background: '#fff',
                    image: undefined,
                    imageOffsetX: 0,
                    imageOffsetY: 0,
                    position: 'front',
                    dropShadow: {
                        enabled: true,
                        top: 3,
                        left: 0,
                        blur: 4,
                        opacity: 0.24
                    }
                },
                track: {
                    background: '#fff',
                    strokeWidth: '80%',
                    margin: 0, 
                    dropShadow: {
                        enabled: true,
                        top: 0,
                        left: 0,
                        blur: 4,
                        opacity: 0.35
                    }
                },

                dataLabels: {
                    show: true,
                    name: {
                        offsetY: 10,
                        show: false,
                        color: '#888',
                        fontSize: '10px'
                    },
                    value: {
                        formatter: function(val) {
                            return parseInt(val) + '%';
                        },
                        color: '#111',
                        fontSize: '20px',
                        show: true,
                    }
                }
            }
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'dark',
                type: 'horizontal',
                shadeIntensity: 0.5,
                gradientToColors: ['#ABE5A1'],
                inverseColors: true,
                opacityFrom: 1,
                opacityTo: 1,
                stops: [0, 100]
            }
        },
        stroke: {
            lineCap: 'round'
        },
        labels: ['Percent'],
    };

    // Create and render a chart with the given ID and new value
    function createAndRenderChart(chartId, newValue) {
        var chart = new ApexCharts(document.querySelector(chartId), options);
        chart.render();
        chart.updateSeries([newValue]);
    }

    // Calculate the percentage values
    var newValueChart1 = isNaN(standby / data) ? 0 : (standby / data) * 100;
    var newValueChart2 = isNaN(breakdown / data) ? 0 : (breakdown / data) * 100;
    var newValueChart3 = isNaN(decommissioned / data) ? 0 : (decommissioned / data) * 100;

    // Create and render each chart with its new value
    createAndRenderChart("#chart1", newValueChart1);
    createAndRenderChart("#chart2", newValueChart2);
    createAndRenderChart("#chart3", newValueChart3);
</script>
