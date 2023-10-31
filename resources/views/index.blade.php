@extends('main')
@section('title', 'dashboard')
@section('content')

    <span>Dashboard</span>

    <div class="date-container">
        <div id="reportrange"
            style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 250px">
            <i class="fa fa-calendar"></i>&nbsp;
            <span></span> <i class="fa fa-caret-down"></i>
        </div>
    </div>
    <div class="card-container">
        <div class="card">
            <div class="card-label"s>
                <span>186</span>
                <span>Online Vehicles</span>
            </div>
        </div>
        <div class="card">
            <div class="card-label">
                <span>313</span>
                <span>Offline Vehicles</span>
            </div>
        </div>

        <div id="troubleshoot-card"></div>

        <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
            aria-hidden="true" id="myModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Modal Header</h4>
                    </div>
                    <div class="modal-body" id="load-modal-content"></div>
                    <div class="date-container">
                        <div class="search-container">
                            <div class="search-icon-container">
                                <i class="fa fa-search search-icon"></i>
                            </div>
                            <input class="search-input" type="text" placeholder="Search..." id="search">
                        </div>
                        <div class="calendar-picker" id="reportrange2">
                            <i class="fa fa-calendar"></i>&nbsp;
                            <span></span> <i class="fa fa-caret-down"></i>
                        </div>
                    </div>
                    <div class="table-div" id="load-modal-table"> </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            var defaultStartDate = moment().subtract(29, 'days');
            var defaultEndDate = moment();
            var sdate, edate;

            loadDatas = [{
                    dateRangeId: $('#reportrange'),
                    route: '{{ route('load_card') }}',
                    content: $('#troubleshoot-card')
                },
                {
                    dateRangeId: $('#reportrange2'),
                    route: '{{ route('load_modal_content') }}',
                    content: $('#load-modal-content')
                },
                {
                    dateRangeId: $('#reportrange2'),
                    route: '{{ route('modal_fixed_table') }}',
                    content: $('#load-modal-table')
                }
            ];


            loadDatas.forEach((data) => {
                dateRange(defaultStartDate, defaultEndDate, data.dateRangeId, data.route, data.content);
            });

            window.changeRoute = function(route) {
                var startDate = sdate ? sdate : defaultStartDate;
                var endDate = edate ? edate : defaultEndDate;

                dateRange(startDate, endDate, loadDatas[2].dateRangeId, route, loadDatas[2]
                    .content);
                localStorage.setItem('selectedRoute', route);
            };

            function ajaxRequest(start, end, dateRangeId, route, content) {
                var startDate = start.format('YYYY-MM-DD');
                var endDate = end.format('YYYY-MM-DD');
                $.ajax({
                    url: route,
                    type: 'GET',
                    data: {
                        start: startDate,
                        end: endDate
                    },
                    success: function(data) {
                        dateRangeId.find('span').html(start.format('MMM-DD-YYYY') + ' - ' + end.format(
                            'MMM-DD-YYYY'));
                        content.html(data);
                    }
                });
            }

            function dateRange(startDate, endDate, dateRangeId, route, content) {
                ajaxRequest(startDate, endDate, dateRangeId, route, content);
                dateRangeId.daterangepicker({
                    startDate: startDate,
                    endDate: endDate,
                    opens: 'left',
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                            'month').endOf('month')]
                    }
                }, function(startDate, endDate) {
                    ajaxRequest(startDate, endDate, dateRangeId, route, content);
                    updateDateRange(startDate, endDate);
                });
            }

            function updateDateRange(startDate, endDate) {
                $('#reportrange2').find('span').html(startDate.format('MMM-DD-YYYY') + ' - ' + endDate.format(
                    'MMM-DD-YYYY'));

                sdate = startDate;
                edate = endDate;

                var selectedRoute = localStorage.getItem('selectedRoute');
                var route1;

                if (selectedRoute) {
                    route1 = selectedRoute;
                } else {
                    route1 = loadDatas[2].route;
                }

                dateRange(startDate, endDate, loadDatas[1].dateRangeId, loadDatas[1].route, loadDatas[1].content);
                dateRange(startDate, endDate, loadDatas[2].dateRangeId, route1, loadDatas[2].content);
            }
        });
    </script>
@endsection
