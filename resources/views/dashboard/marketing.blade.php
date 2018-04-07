@extends('layouts.dashboard')

@section('content')

<div class="container dashboard col-sm-10 col-xs-12 offset-sm-2">
    <nav class="nav nav-pills">
    <a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#case-modal" href="#"><i class="fas fa-plus"></i> <i class="fas fa-briefcase"></i> Add case</a>
    <a class="nav-item nav-link btn btn-info"  data-toggle="modal" data-target="#user-modal" href="#"><i class="fas fa-briefcase"></i> My Cases</a>
  </nav>  
  
  	@include('dashboard.includes.alerts')
  <div class="col-md-6">
   <div class="panel panel-default">
      <div class="panel-heading" style="overflow:hidden;">
        <h1 class="pull-left mt-4 ml-3 mb-2">
          <span class="glyphicon glyphicon-envelope" style="padding-right:10px;top:4px;"></span >Reports
        </h1>
        <!--<button type="button" class="btn btn-warning pull-right">Compose Mail</button>-->
     </div>
     <div class="panel-body">
        <canvas id="myChart" width="400" height="400"></canvas>
<script>
var ctx = document.getElementById("myChart").getContext('2d');
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
        datasets: [{
            label: '# of Votes',
            data: [12, 19, 3, 5, 2, 3],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
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
  </div>   
  </div>

</div>

@endsection