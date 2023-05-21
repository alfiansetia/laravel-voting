@extends('layouts.template')

@push('csslib')
<link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@push('css')
@endpush

@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-lg-7">
            <div class="card card-danger">
                <div class="card-body">
                    <div class="form-group row mb-1">
                        <label for="select_event" class="col-sm-3 col-form-label">Event :</label>
                        <div class="col-sm-9">
                            <select id="select_event" class="form-control">
                                <option value="">Select Event</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card card-danger">
                <div class="card-header">
                    <h4>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="all" name="stock" class="custom-control-input" value="all" checked>
                            <label class="custom-control-label" for="all">all</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="available" name="stock" class="custom-control-input" value="available">
                            <label class="custom-control-label" for="available">available</label>
                        </div>
                    </h4>
                    <div class="card-header-action">
                        <div class="btn-group">
                            <button type="button" id="add_to_cart" class="btn btn-primary">Menu</button>
                            <button type="button" id="list_table" class="btn btn-primary">Table</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group row mb-1">
                        <label for="type" class="col-sm-3 col-form-label">Status :</label>
                        <div class="col-sm-9">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="valid" name="status" class="custom-control-input" value="valid" checked>
                                <label class="custom-control-label" for="valid">valid</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="invalid" name="status" class="custom-control-input" value="invalid">
                                <label class="custom-control-label" for="invalid">invalid</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label for="select_calon" class="col-sm-3 col-form-label">Calon :</label>
                        <div class="col-sm-9">
                            <select id="select_calon" class="form-control">
                                <option value="">Select Calon</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row mb-1 mt-3">
                        <label class="col-sm-3 col-form-label"></label>
                        <div class="col-sm-9">
                            <button class="btn btn-primary" id="save">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header">
                    <h4>Event : <span id="span_event"></span></h4>
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
@endpush

@push('js')
<script>
    $(document).ready(function() {
        $('#select_event').selectric({
            disableOnMobile: false,
            nativeOnMobile: false,
            onChange: function(data) {
                clearEvent();
                let id = $('#select_event').val()
                if (id != '') {
                    getEventCalon(id)
                    getStatistic(id)
                }
            },
        })
        $('#select_calon').selectric({
            disableOnMobile: false,
            nativeOnMobile: false,
        })
        getEvent()

        $('input[type=radio][name=status]').change(function() {
            if ($(this).val() == 'valid') {
                $("#select_calon").prop('disabled', false);

            } else {
                $("#select_calon").val('').change();
                $("#select_calon").prop('disabled', true);
            }
            $('#select_calon').selectric('refresh');
        });

        $('#save').click(function() {
            let event = $('#select_event').val()
            let status = $('input[type=radio][name=status]:checked').val()
            let calon = $('#select_calon').val()
            if (event == '') {
                return $('#select_event').focus()
            }
            if (status == 'valid' && calon == '') {
                return $('#select_calon').focus()
            }
            addData({
                'event': event,
                'status': status,
                'calon': calon,
            })
        })

    });

    var ctx = document.getElementById("myChart4").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'pie',
        data: {
            datasets: [{
                data: [],
                backgroundColor: [],
                label: 'Dataset 1'
            }],
            labels: [],
        },
        options: {
            responsive: true,
            legend: {
                position: 'bottom',
            },
        }
    });

    function createPie(data) {
        clearePie()
        for (let i = 0; i < data.detail.length; i++) {
            myChart.data.datasets[0].data.push(data.detail[i].total ?? 0)
            myChart.data.labels.push(data.detail[i].calon.name)
            myChart.data.datasets[0].backgroundColor.push(dynamicColors());
        }
        myChart.data.datasets[0].data.push(data.status.invalid ?? 0)
        myChart.data.labels.push('invalid')
        myChart.data.datasets[0].backgroundColor.push(dynamicColors());
        myChart.update();
    }


    function clearePie() {
        myChart.data.datasets[0].data = []
        myChart.data.labels = []
        myChart.data.datasets[0].backgroundColor = []
        myChart.update();
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

    function addData(data) {
        swal({
            title: 'Add Vote?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then(function(result) {
            if (result) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'POST',
                    url: "{{ route('vote.store') }}",
                    data: data,
                    beforeSend: function() {
                        block();
                        $('button').prop('disabled', true);
                    },
                    success: function(res) {
                        unblock();
                        $('button').prop('disabled', false);
                        if (res.status == true) {
                            $('#select_calon').val('').change()
                            $('#select_calon').selectric('refresh')
                            getStatistic(data.event)
                            swal(
                                'Success!',
                                res.message,
                                'success'
                            )
                        } else {
                            swal(
                                'Failed!',
                                res.message,
                                'error'
                            )
                        }
                    },
                    error: function(xhr, status, error) {
                        unblock();
                        $('button').prop('disabled', false);
                        er = xhr.responseJSON.errors
                        if (xhr.status == 500) {
                            swal(
                                'Failed!',
                                'Server Error',
                                'error'
                            )
                        } else {
                            swal(
                                'Failed!',
                                xhr.responseJSON.message,
                                'error'
                            )
                        }
                    }
                });
            }
        })
    }

    function getEvent() {
        $.get("{{ route('event.index') }}").done(function(response) {
            for (let i = 0; i < response.data.length; i++) {
                $('#select_event').append(`<option value="${response.data[i].id}">${response.data[i].name+' ['+moment(response.data[i].date).format('YYYY-MM-DD')+']'}</option>`)
            }
            $('#select_event').selectric('refresh');
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

    function clearEvent() {
        $('#select_calon').empty().change()
        $('#select_calon').append(`<option value="">Select Calon</option>`)
        $('#select_calon').selectric('refresh');
        $('#span_event').text('')
        clearePie()
    }

    function getEventCalon(event_id) {
        let url = "{{ route('event.edit', ':id') }}";
        url = url.replace(':id', event_id);
        $.get(url).done(function(response) {
            $('#span_event').text(response.data.name + ' [' + moment(response.data.date).format('YYYY-MM-DD') + ']')
            for (let i = 0; i < response.data.dtevent.length; i++) {
                $('#select_calon').append(`<option value="${response.data.dtevent[i].calon.id}">${response.data.dtevent[i].calon.name+' ['+ response.data.dtevent[i].calon.partai+']'}</option>`)
            }
            $('#select_calon').selectric('refresh');
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