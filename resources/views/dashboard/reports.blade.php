@extends('layouts.dashboard')

@section('content')

<div class="container dashboard col-sm-10 col-xs-12 offset-sm-2">
    <nav class="nav nav-pills">
    <a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#case-modal" href="#"><i class="fas fa-user"></i> Clients by month</a>
    <a class="nav-item nav-link btn btn-info"  data-toggle="modal" data-target="#user-modal" href="#"><i class="fas fa-briefcase"></i> Cases by month</a>
    <a class="nav-item nav-link btn btn-info"  data-toggle="modal" data-target="#user-modal" href="#"><i class="fas fa-dollar-sign"></i> Payments by month</a> 
    <a class="nav-item nav-link btn btn-info"  data-toggle="modal" data-target="#user-modal" href="#"><i class="fas fa-clock"></i> Hours worked by week</a>        
  </nav>  
  

   <div class="panel panel-default">
      <div class="panel-heading" style="overflow:hidden;">
        <h1 class="pull-left ml-3 mt-4 mb-2">
          <i class="fas fa-chart-line"></i> New Clients
        </h1>
      	@include('dashboard.includes.alerts')
     </div>
     <div class="panel-body">
        <div class="col-sm-6 col-12" style="float:left">
          <canvas id="myChart" width="400" height="400"></canvas>
       </div>
       <div class="col-sm-6 col-12" style="float:left">
         <form>
           <input type="text" class="form-control datepicker" name="date_start" />
           <inpur
           <input type="radio" name="month" value="" class="form-control"> Month 
         </form>
         	<table class="table table-responsive table-resposive table-striped table-{{ $table_color }} table-{{ $table_size }}">
					<thead> 
						<tr>           
								<th scope="col">ID</th>
								<th scope="col">Name</th>
						</tr> 
					</thead> 
					<tbody> 

					</tbody> 
				</table>
       </div>
     </div>
  </div>   
  </div>


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
@endsection