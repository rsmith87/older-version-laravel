@extends('layouts.dashboard')

@section('content')

<div class="container dashboard home col-sm-10 col-xs-12 offset-sm-2" style="margin-top:15px;">
  
  	@include('dashboard.includes.alerts')
  
  
  <div class="col-md-6">
   <div class="panel panel-info">
      <div class="panel-heading" style="overflow:hidden;">
        <h2 style="margin-top:0;margin-bottom:0;" class="pull-left">
          <i class="fas fa-envelope"></i>Messages
        </h2>
        <button type="button" class="btn btn-primary pull-right"><i class="fas fa-envelope-open"></i></button>
     </div>
     <div class="panel-body">
        <table class="table table-striped table-condensed table-hover">
          <thead>
            <tr>
              <td>From:</td>
              <td>Subject:</td>
            </tr>
          </thead>
          <tbody>
          <tr>
            <td>Billy Bob Jackson</td>
            <td>The Law Firm of John & John Bender</td>

          </tr>
          <tr>
            <td>one</td>
            <td>two</td>

          </tr>
          <tr>
            <td>one</td>
            <td>two</td>

          </tr>
          <tr>
            <td>one</td>
            <td>two</td>

          </tr>             
          </tbody>
        </table>      
     </div>
  </div>   
  </div>

  <div class="col-md-6">
   <div class="panel panel-success">
      <div class="panel-heading">
        <h2 style="margin-top:0;margin-bottom:0;">
          <i class="fas fa-users"></i>Clients
        </h2>
        <button type="button" class="btn btn-primary pull-right"><i class="fas fa-plus"></i></button>
     </div>
     <div class="panel-body">
        <table class="table table-striped table-condensed">
          <thead>
            <tr>
              <th>Client</th>
              <th>Quickactions</th>
            </tr>
          </thead>
          <tbody>
          <tr>
            <td>Jane Francis</td>
            <td><i class="fas fa-edit"></i> <i class="fas fa-trash-alt"></i> </td>

          </tr>
          <tr>
            <td>Jane Francis</td>
            <td><i class="fas fa-edit"></i> <i class="fas fa-trash-alt"></i> </td>

          </tr>
          <tr>
             <td>Jane Francis</td>
            <td><i class="fas fa-edit"></i> <i class="fas fa-trash-alt"></i> </td>


          </tr>
          <tr>
            <td>Jane Francis</td>
            <td><i class="fas fa-edit"></i> <i class="fas fa-trash-alt"></i> </td>

          </tr>             
          </tbody>
        </table>      
     </div>
  </div>   
  </div>
  
  <div class="col-md-6">
   <div class="panel panel-warning">
      <div class="panel-heading">
        <h2 style="margin-top:0;margin-bottom:0;">
          <i class="fas fa-tasks"></i>Tasks
        </h2>
        <button type="button" class="btn btn-primary pull-right"><i class="fas fa-plus"></i></button>
     </div>
     <div class="panel-body">
        <table class="table table-striped table-condensed dashboard-table-tasks">
          <tbody>
          <tr>
            <td>
            <div class="toggle-button toggle-button--aava">
                <input id="toggleButtonOne" type="checkbox">
                <label for="toggleButtonOne" data-on-text="Buy Milk" data-off-text="Buy Milk"></label>
                <div class="toggle-button__icon"></div>
            </div>
            <div class="toggle-button toggle-button--aava">
                <input id="toggleButtonTwo" type="checkbox">
                <label for="toggleButtonTwo" data-on-text="Get bug spray" data-off-text="Get bug spray"></label>
                <div class="toggle-button__icon"></div>
            </div>
            <div class="toggle-button toggle-button--aava">
                <input id="toggleButtonThree" type="checkbox">
                <label for="toggleButtonThree" data-on-text="Pick up children!" data-off-text="Pick up children!"></label>
                <div class="toggle-button__icon"></div>
            </div>
            <div class="toggle-button toggle-button--aava">
                <input id="toggleButtonFour" type="checkbox">
                <label for="toggleButtonFour" data-on-text="Bake the cake!" data-off-text="Bake the cake!"></label>
                <div class="toggle-button__icon"></div>
            </div></td>


          </tr>             
          </tbody>
        </table>      
     </div>
  </div>   
  </div>
  
  <div class="col-md-6">
   <div class="panel panel-info">
      <div class="panel-heading">
        <h2 style="margin-top:0;margin-bottom:0;">
          <i class="fas fa-cogs"></i>Settings
        </h2>
        <button type="button" class="btn btn-primary pull-right"><i class="fas fa-cogs"></i></button>
     </div>
     <div class="panel-body">
        <table class="table table-striped table-condensed">
          <thead>
            <tr>
              <td>From:</td>
              <td>Subject:</td>
            </tr>
          </thead>
          <tbody>
          <tr>
            <td>one</td>
            <td>two</td>

          </tr>
          <tr>
            <td>one</td>
            <td>two</td>

          </tr>
          <tr>
            <td>one</td>
            <td>two</td>

          </tr>
          <tr>
            <td>one</td>
            <td>two</td>

          </tr>             
          </tbody>
        </table>      
     </div>
  </div>   
  </div>

</div>

@endsection