@extends('dashboard')

@section('additional_include')
<link href="{{ URL::asset('css/Post_Style.css') }}" rel="stylesheet">
<link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.9/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.9/js/jquery.dataTables.js"></script>
@endsection

@section('script')
  @include('post._postsJS')
@endsection

@section('app_content')
<div class="container-fluid">
<div class="col-md-10">
  <h3>Search a page</h3>
  <form class="form-horizontal" method="post" action="/post/search">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="form-group">
      <label class="col-md-2 control-label">Page ID</label>
      <div class="col-md-10">
        <input type="text" id="txtID" name="PageID" class="form-control" placeholder="page id..."/>
      </div>
    </div><!--/form-group-->
    <div class="form-group">
      <label class="col-md-2 control-label">Result</label>
      <div class="col-md-10">
        <input type="number" class="form-control" name="Qty" id="result-qty" min="1" max="99" value="1">
      </div>
    </div><!--/form-group-->
    <div class="form-group">
      <label class="col-md-2 control-label">Date</label>
      <div class="col-md-10">
        <div class="form-group row">
          <div class="col-md-5">
            <input type="text" id="datepicker-from" name="Since" class="form-control" placeholder="from"/>
          </div>
          <div class="col-md-5">
            <input type="text" id="datepicker-until" name="Until" class="form-control" placeholder="until"/>
          </div>
        </div>
      </div>
    </div><!--/form-group-->
    <div class="form-group">
      <div class="col-md-12" align="center">
        <input type="button" class="btn btn-info" id="btnSearch" value="Search"></input>
      </div>
    </div><!--/form-group-->
  </form><!--/form-horizontal-->
</div><!--/col-md-10-->
</div><!--/container-fluid-->
<hr/>
<div class="container-fluid">
  <div id="page-result"></div>
</div>

@endsection
