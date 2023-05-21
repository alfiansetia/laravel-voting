@extends('layouts.template')

@push('csslib')
<link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
<link rel="stylesheet" href="{{ asset('library/owl.carousel/dist/assets/owl.carousel.min.css') }}">
<link rel="stylesheet" href="{{ asset('library/owl.carousel/dist/assets/owl.theme.default.min.css') }}">

@endpush

@push('css')
@endpush

@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-lg-7">
            <div class="card card-danger">
                <div class="card-header">
                    <h4>
                        Total Calon : {{ count($calon) }}
                    </h4>
                </div>
                <div class="card-body">
                    <div class="owl-carousel owl-theme slider pt-0" id="slider2" data-ride="carousel">
                        @foreach($calon as $c)
                        <div>
                            <center>
                                <img alt="{{ $c->calon->name }}" src="{{ url('/images/calon/' . ($c->calon->image ?? ($c->calon->gender . '.jpg'))) }}" style="width: 70%; height: 70%;">
                            </center>
                            <div class="slider-caption">
                                <div class="slider-title">
                                    <h3>{{ $c->calon->name }} [{{ $c->calon->partai }}]</h3>
                                </div>
                                <div class="slider-description">
                                    <h3>Suara : <span class="badge badge-success suara_calon{{ $c->calon_id }}" id="suara_calon{{ $c->calon_id }}">0</span></h3>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
        <div class="col-lg-5">
            <div class="card card-danger">
                <div class="card-header">
                    <h4>Event : {{ $event->name }} </h4>
                </div>
                <div class="card-body">
                    <div class="form-group row mb-1">
                        <table class="table table-sm ml-2 mr-2 mb-0 mt-0 pt-0">
                            <tbody>
                                <tr>
                                    <td>Date</td>
                                    <td>:</td>
                                    <td>{{ $event->date }}</td>
                                </tr>
                                <tr>
                                    <td>Expired</td>
                                    <td>:</td>
                                    <td>{{ $event->expired }}</td>
                                </tr>
                                <tr>
                                    <td>Desc</td>
                                    <td>:</td>
                                    <td>{{ $event->desc ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Suara Masuk</td>
                                    <td>:</td>
                                    <td><span id="suara_masuk">-</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card card-primary">
                <div class="card-header">
                    <h4>Statistic <span id="span_event"></span></h4>
                    <div class="card-header-action">
                        <div class="btn-group">
                            <button type="button" id="btn_pie" class="btn btn-primary">Pie</button>
                            <button type="button" id="btn_donut" class="btn btn-primary">Donut</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="myChart4"></canvas>
                    <div class="text-center">
                        <div class="d-inline" id="pie"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('modal')
@endpush


@push('jslib')
<script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>
<script src="{{ asset('library/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
<script src="{{ asset('library/chart.js/dist/Chart.min.js') }}"></script>
<script src="{{ asset('library/owl.carousel/dist/owl.carousel.min.js') }}"></script>
@endpush

@push('js')
<script>
    $(document).ready(function() {
        getStatistic(id)
        setInterval(() => {
            getStatistic(id)
        }, 10000);

        $("#slider2").owlCarousel({
            items: 1,
            nav: true,
            navText: ['<i class="fas fa-chevron-left"></i>', '<i class="fas fa-chevron-right"></i>'],
            autoplay: true,
            autoplayTimeout: 3000,
            loop: true,
        });
    });

    var id = "{{ $event->id }}"
    var total = parseInt("{{ count($calon) }}") + 1
    var colors = getColors(total)

    var ctx = document.getElementById("myChart4").getContext('2d');
    var option = {
        responsive: true,
        legend: {
            position: 'bottom',
        },
    }
    var data = {
        datasets: [{
            data: [],
            backgroundColor: [],
            label: ''
        }],
        labels: [],
    }

    var myChart = new Chart(ctx, {
        type: 'pie',
        data: data,
        options: option
    });

    $('#btn_pie').click(function() {
        pie()
        getStatistic(id)
    })

    $('#btn_donut').click(function() {
        donut()
        getStatistic(id)
    })

    function donut() {
        myChart.destroy();
        myChart = new Chart(ctx, {
            type: 'doughnut',
            data: data,
            options: option
        });
        myChart.update();
    }

    function pie() {
        myChart.destroy();
        myChart = new Chart(ctx, {
            type: 'pie',
            data: data,
            options: option
        });
        myChart.update();
    }

    function createPie(data) {
        clearePie()
        for (let i = 0; i < data.detail.length; i++) {
            $('.suara_calon' + data.detail[i].calon_id).text(data.detail[i].total)
            myChart.data.datasets[0].data.push(data.detail[i].total ?? 0)
            myChart.data.labels.push(data.detail[i].calon.name)
        }
        myChart.data.datasets[0].data.push(data.status.invalid ?? 0)
        myChart.data.labels.push('invalid')
        myChart.data.datasets[0].backgroundColor = colors
        myChart.update();
    }

    function clearePie() {
        myChart.data.datasets[0].data = []
        myChart.data.labels = []
        myChart.data.datasets[0].backgroundColor = []
        myChart.update();
    }

    function getColors(value) {
        let color = []
        for (let i = 0; i < value; i++) {
            color.push(dynamicColors())
        }
        return color
    }

    function dynamicColors() {
        var letters = '0123456789ABCDEF'.split('');
        var color = '#';
        for (var i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    function getStatistic(event_id) {
        $.get("{{ route('statistic.event')}}?event=" + event_id).done(function(response) {
            let total = 0;
            let valid = response.data.status.valid
            let invalid = response.data.status.invalid
            total = parseInt(valid) + parseInt(invalid)
            $('#suara_masuk').text(hrg(total))
            createPie(response.data)
        }).fail(function(xhr) {
            if (xhr.status == 403) {
                swal(
                    'Failed!',
                    xhr.responseJSON.message,
                    'error'
                )
            } else {
                swal(
                    'Failed!',
                    'Server Error',
                    'error'
                )
            }
        })

    }
</script>
@endpush