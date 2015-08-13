@extends('dashboard')

@section('additional_include')
<link href="{{ URL::asset('css/History_Style.css') }}" rel="stylesheet">
<link href="{{ URL::asset('css/scrollable_table.css') }}" rel="stylesheet">
@endsection

@section('app_content')
<div class="container-fluid">
	<h3>Total post registered: <span class="label label-default">{!! $total !!}</span></h3>
	<hr/>
	<table class='table table-hover header-fixed' id="post-table">
	    <thead>
	    	<tr>
	        	<th class="col-md-1">#</th>
	        	<th class="col-md-3">ID</th>
	        	<th class="col-md-4">Content</th>
	        	<th class="col-md-2">Created Time</th>
	        	<th class="col-md-2">Page</th>
	      	</tr>
	    </thead>
	    <tbody id="post-list">
	    <?php $count=1; ?>
		@foreach($post as $data)
			<tr>
		        <td class='col-md-1'>{{ $count }}</td>
		        <td class='col-md-3'><a href="post/{!! $data['page_id'] !!}/{!! $data['post_id'] !!}">{!! $data['post_id'] !!}</a></td>
		        <td class='col-md-4'>{!! $data['content'] !!}</td>
		        <td class='col-md-2'>{!! $data['created_time'] !!}</td>
		        <td class='col-md-2'>{!! $data['page_name'] !!}</td>
	      	</tr>
	    	<?php $count++ ?>
		@endforeach
		</tbody>
	</table>
</div>
@endsection