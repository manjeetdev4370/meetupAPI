@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-13">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <head>
                        <title>Custom filter/Search with Laravel Datatables Example</title>
                        <meta name="csrf-token" content="{{ csrf_token() }}">
                        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
                        <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
                        <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
                        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
                        <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
                        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
                        <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
                    </head>
                    <div class="table-responsive">
                    
                    <div class="form-group">
                        <form id="" class="form-horizontal no-margin" method="get">
                            <div class="row">
                                <div class="col-sm-4">
                                    {!! csrf_field() !!}
                                    <input name="search_name" id="search_name" type="text" class="form-control" placeholder="Name" value="{{Input::get('search_name')}}" aria-describedby="basic-addon1">
                                </div>
                                <div class="col-sm-4">
                                    <input name="search_locality" id="search_locality" type="text" class="form-control" placeholder="Locality" value="{{Input::get('search_locality')}}" aria-describedby="basic-addon1">
                                </div>

                                <div class="col-sm-2">
                                  <button type="submit" class="btn btn-primary">Search
                                  </button>
                                  <button type="button" class="btn btn-primary" onclick="window.location.href='/admin';" style="margin:-66px 0px 0px 90px;">Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    {{ $userDetails->links() }}

                    <table class="table table-responsive table-striped table-bordered table-hover no-margin">
                      <thead>
                        <tr>
                         <th style="width:10%">ID</th>
                          <th style="width:10%">Name</th>
                          <th style="width:20%" class="hidden-xs">Date Of Birth</th>
                          <th style="width:10%" class="hidden-xs">Age</th>
                          <th style="width:10%" class="hidden-xs">Profession</th>
                          <th style="width:10%" class="hidden-xs">Locality</th>
                          <th style="width:20%" class="hidden-xs">No Of Guest</th>
                          <th style="width:10%" class="hidden-xs">Address</th>
                          <th style="width:10%" class="hidden-xs">Created At</th>
                          <th style="width:20%" class="hidden-xs">Updated At</th>
                        </tr>
                      </thead>
                      <tbody>

                            @if (count($userDetails) > 0)
                                @foreach ($userDetails->toArray()['data'] as $user)
                                
                                <tr data-entry-id="{{ $user['id'] }}">
                                    <td field-key='id'>{{ $user['id'] }}</td>
                                    <td field-key='name'>{{ $user['name'] }}</td>
                                    <td field-key='age'>{{ $user['age'] }}</td>
                                    <td field-key='dob'>{{ $user['dob'] }}</td>
                                    <td field-key='profession'>{{ $user['profession'] }}</td>
                                    <td field-key='locality'>{{ $user['locality'] }}</td>
                                    <td field-key='noofguest'>{{ $user['noofguest'] }}</td>
                                    <td field-key='address'>{{ $user['address'] }}</td>
                                    <td field-key='created_at'>{{ $user['created_at'] }}</td>
                                    <td field-key='updated_at'>{{ $user['updated_at'] }}</td>
                                    
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="10">"No Data In Table"</td>
                                </tr>
                                @endif
                      </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
  $(function () {
   
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: "{{ route('home') }}",
          data: function (d) {
                d.email = $('.searchEmail').val(),
                d.search = $('input[type="search"]').val()
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
   
    $(".searchEmail").keyup(function(){
        table.draw();
    });
  
  });
</script>

@endsection
