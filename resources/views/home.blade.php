@extends('layouts.app')

@section('content')

    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Company Info') }}</div>

                    <div class="card-body">

                        <form method="POST" id="infoForm" action="{{ route('companyInfo') }}" aria-label="{{ __('Company Info') }}" novalidate>
                            @csrf
                            <div class="form-group row">
                                <label for="company_symbol" class="col-sm-4 col-form-label text-md-right">{{ __('Company Symbol') }}</label>

                                <div class="col-md-6">
                                    <input id="company_symbol" type="text" class="validate form-control {{ $errors->has('company_symbol') ? ' is-invalid' : '' }}" name="company_symbol" value="{{ old('company_symbol') }}">

                                    @if ($errors->has('company_symbol'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('company_symbol') }}</strong>
                                    </span>
                                    @endif
                                    <div class="text-danger messages"></div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="start_date" class="col-sm-4 col-form-label text-md-right">{{ __('Start Date') }}</label>

                                <div class="col-md-6">
                                    <input id="start_date" type="text" class="validate datepicker form-control {{ $errors->has('start_date') ? ' is-invalid' : '' }}" name="start_date" value="{{ old('start_date') }}">

                                    @if ($errors->has('start_date'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('start_date') }}</strong>
                                    </span>
                                    @endif
                                    <div class="text-danger messages"></div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="end_date" class="col-sm-4 col-form-label text-md-right">{{ __('End Date') }}</label>

                                <div class="col-md-6">
                                    <input id="end_date" type="text" class="validate datepicker form-control {{ $errors->has('end_date') ? ' is-invalid' : '' }}" name="end_date" value="{{ old('end_date') }}">

                                    @if ($errors->has('end_date'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('end_date') }}</strong>
                                    </span>
                                    @endif
                                    <div class="text-danger messages"></div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-sm-4 col-form-label text-md-right">{{ __('Email') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="validate form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}">

                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                    <div class="text-danger messages"></div>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Get Information') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if (session('rates'))
        <div class="row justify-content-center py-4">
            <div class="col-md-8">
                <div class="card">
                    <canvas id="myChart" width="200" height="200"></canvas>
                    <script>
                        var ctx = document.getElementById("myChart").getContext('2d');
                        var myChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: @json(session('chartData')['headers']),
                                datasets: [
                                    {
                                        label: 'Close',
                                        data: @json(session('chartData')['close']),
                                        borderWidth: 1
                                    },
                                    {
                                        label: 'Open',
                                        data: @json(session('chartData')['open']),
                                        borderWidth: 1,
                                        borderColor: [
                                            'rgba(255,99,132,1)',
                                            'rgba(54, 162, 235, 1)',
                                            'rgba(255, 206, 86, 1)',
                                            'rgba(75, 192, 192, 1)',
                                            'rgba(153, 102, 255, 1)',
                                            'rgba(255, 159, 64, 1)'
                                        ],
                                        backgroundColor: [
                                            'rgba(255, 99, 132, 0.2)',
                                            'rgba(54, 162, 235, 0.2)',
                                            'rgba(255, 206, 86, 0.2)',
                                            'rgba(75, 192, 192, 0.2)',
                                            'rgba(153, 102, 255, 0.2)',
                                            'rgba(255, 159, 64, 0.2)'
                                        ],
                                    }
                                ]
                            },
                            options: {
                                scales: {
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero:true
                                        }
                                    }]
                                }
                            }
                        });
                    </script>
                </div>
                <div class="card">

                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Open</th>
                            <th scope="col">High</th>
                            <th scope="col">Low</th>
                            <th scope="col">Close</th>
                            <th scope="col">Volume</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach(session('rates') as $rate)
                        <tr>
                            <td>{{ $rate[0] }}</td>
                            <td>{{ $rate[1] }}</td>
                            <td>{{ $rate[2] }}</td>
                            <td>{{ $rate[3] }}</td>
                            <td>{{ $rate[4] }}</td>
                            <td>{{ $rate[5] }}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>

    <script>
        $( function() {
            $( ".datepicker" ).datepicker();
            $( ".datepicker" ).datepicker("option", "dateFormat", "yy-mm-dd");
        } );
    </script>

    <script>
        (function() {
            validate.extend(validate.validators.datetime, {
                parse: function(value, options) {
                    return +moment.utc(value);
                },
                format: function(value, options) {
                    var format = options.dateOnly ? "YYYY-MM-DD" : "YYYY-MM-DD hh:mm:ss";
                    return moment.utc(value).format(format);
                }
            });

            var constraints = {
                _token: {
                    presence: false,
                },
                email: {
                    presence: true,
                    email: true
                },
                start_date: {
                    presence: true,
                    date: {
                        dateOnly: true,
                    }
                },
                end_date: {
                    presence: true,
                    date: {
                        dateOnly: true,
                    }
                },
                company_symbol: {
                    presence: true,
                    length: {
                        minimum: 1,
                        maximum: 10
                    }
                }
            };

            var form = document.querySelector("form#infoForm");
            form.addEventListener("submit", function(ev) {
                ev.preventDefault();
                handleFormSubmit(form);
            });

            var inputs = document.querySelectorAll('.validate')

            for (var i = 0; i < inputs.length; ++i) {
                inputs.item(i).addEventListener("change", function(ev) {
                    var errors = validate(form, constraints) || {};
                    showErrorsForInput(this, errors[this.name])
                });
            }

            function handleFormSubmit(form, input) {
                var errors = validate(form, constraints);
                showErrors(form, errors || {});
                if (!errors) {
                    form.submit();
                }
            }

            function showErrors(form, errors) {
                // We loop through all the inputs and show the errors for that input
                _.each(form.querySelectorAll(".validate"), function(input) {
                    showErrorsForInput(input, errors && errors[input.name]);
                });
            }

            function showErrorsForInput(input, errors) {
                var formGroup = closestParent(input.parentNode, "form-group")
                    , messages = formGroup.querySelector(".messages");
                resetFormGroup(formGroup);
                if (errors) {
                    formGroup.classList.add("has-error");
                    _.each(errors, function(error) {
                        addError(messages, error);
                    });
                } else {
                    formGroup.classList.add("has-success");
                }
            }

            function closestParent(child, className) {
                if (!child || child == document) {
                    return null;
                }
                if (child.classList.contains(className)) {
                    return child;
                } else {
                    return closestParent(child.parentNode, className);
                }
            }

            function resetFormGroup(formGroup) {
                formGroup.classList.remove("has-error");
                formGroup.classList.remove("has-success");
                _.each(formGroup.querySelectorAll(".help-block.error"), function(el) {
                    el.parentNode.removeChild(el);
                });
            }

            function addError(messages, error) {
                var block = document.createElement("p");
                block.classList.add("help-block");
                block.classList.add("error");
                block.innerText = error;
                messages.appendChild(block);
            }

        })();
    </script>
@endsection
