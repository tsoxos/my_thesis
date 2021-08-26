<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>Home</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href = "{{asset('assets/css/bootstrap.css')}}" rel="stylesheet" />
        <link href = "{{asset('assets/css/fivos.css')}}" rel="stylesheet" />
        <link href = "{{asset('assets/css/all.css')}}" rel="stylesheet" />
    </head>
        @include('incl.header')
                <div class="container mt-5">
                    <h1 class="text-center">{{$questionnaire->title}}</h1>
                </div>
                <div class="container mt-5">
                    <div id="chart_all">
                        @if(!$data_men)
                            <h5>Δεν υπάρχουν ακόμα δεδομένα</h5>
                        @endif
                    </div>
                </div>
                <div class="container mt-5">
                    <div id="chart_men">
                        @if(!$data_men)
                            <h5>Δεν υπάρχουν ακόμα δεδομένα για το φύλο Άνδρες.</h5>
                        @endif
                    </div>
                </div>
                <div class="container mt-5">
                    <div id="chart_women">
                    @if(!$data_women)
                        <h5>Δεν υπάρχουν ακόμα δεδομένα για το φύλο Γυναίκες.</h5>
                    @endif
                    </div>
                </div>
                <div class="container mt-5">
                    <div id="chart_other">
                        @if(!$data_other)
                        <h5>Δεν υπάρχουν ακόμα δεδομένα για το φύλο Άλλο.</h5>
                        @endif
                    </div>
                </div>
            </div>
            @include('incl.footer')
        </div>
    </body>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="{{asset('assets/js/bootstrap.bundle.js')}}"></script>
    <script src="https://www.gstatic.com/charts/loader.js"></script>
    
    <script>
        google.charts.load('current', {packages: ['corechart', 'bar']});
        @if($data_all)
            google.charts.setOnLoadCallback(drawAllData);

            function drawAllData() {
                var data = google.visualization.arrayToDataTable([
                    ['', 'Σωστό', 'Λάθος'],
                    @foreach($data_all as $key => $d)
                        ['{{$d["question"]}}', {{$d["true"]}}, {{$d["false"]}}],
                    @endforeach
                ]);
                var materialOptions = {
                    chartArea: {
                        top: 55,
                        height: '40%' 
                    },
                    title: '{{$questionnaire->title}} (Ολα)',
                    vAxis: {
                        gridlines: 8 ,
                    },
                    hAxis: {
                        format:"#",
                    },
                };
                var materialChart = new google.visualization.ColumnChart(document.getElementById('chart_all'));
                materialChart.draw(data, materialOptions);
            }
        @endif

        @if($data_men)
            google.charts.setOnLoadCallback(drawMenData);

            function drawMenData() {
                var data = google.visualization.arrayToDataTable([
                    ['', 'Σωστό', 'Λάθος'],
                    @foreach($data_men as $key => $d)
                        ['{{$d["question"]}}', {{$d["true"]}}, {{$d["false"]}}],
                    @endforeach
                ]);
                var materialOptions = {
                    title: '{{$questionnaire->title}} (Άνδρες)',
                    chartArea: {
                        top: 55,
                        height: '40%' 
                    },
                    hAxis: {
                        format:"#",
                    },
                };
                var materialChart = new google.visualization.ColumnChart(document.getElementById('chart_men'));
                materialChart.draw(data, materialOptions);
            }
        @endif

        @if($data_women)
            google.charts.setOnLoadCallback(drawWomenData);

            function drawWomenData() {
                var data = google.visualization.arrayToDataTable([
                    ['', 'Σωστό', 'Λάθος'],
                    @foreach($data_women as $key => $d)
                        ['{{$d["question"]}}', {{$d["true"]}}, {{$d["false"]}}],
                    @endforeach
                ]);
                var materialOptions = {
                    title: '{{$questionnaire->title}} (Γυναίκες)',
                    chartArea: {
                        top: 55,
                        height: '40%' 
                    },
                    hAxis: {
                        format:"#",
                    },
                };
                var materialChart = new google.visualization.ColumnChart(document.getElementById('chart_women'));
                materialChart.draw(data, materialOptions);
            }
        @endif

        @if($data_other)
            google.charts.setOnLoadCallback(drawOtherData);

            function drawOtherData() {
                var data = google.visualization.arrayToDataTable([
                    ['', 'Σωστό', 'Λάθος'],
                    @foreach($data_women as $key => $d)
                        ['{{$d["question"]}}', {{$d["true"]}}, {{$d["false"]}}],
                    @endforeach
                ]);
                var materialOptions = {
                    title: '{{$questionnaire->title}} (Άλλο)',
                    chartArea: {
                        top: 55,
                        height: '40%' 
                    },
                    hAxis: {
                        format:"#",
                    },
                };
                var materialChart = new google.visualization.ColumnChart(document.getElementById('chart_other'));
                materialChart.draw(data, materialOptions);
            }
        @endif

        $(window).resize(function(){
            @if($data_all)
                google.charts.setOnLoadCallback(drawAllData);
            @endif
            @if($data_men)
                google.charts.setOnLoadCallback(drawMenData);
            @endif
            @if($data_women)
                google.charts.setOnLoadCallback(drawWomenData);
            @endif
        });
    </script>
</html>