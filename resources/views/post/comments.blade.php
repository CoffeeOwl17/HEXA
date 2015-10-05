@extends('dashboard')

@section('additional_include')
<link href="{{ URL::asset('css/Comment_Style.css') }}" rel="stylesheet">
<link href="{{ URL::asset('css/Post_Style.css') }}" rel="stylesheet">
<script type="text/javascript" src="{{ URL::asset('js/Chart.js-master/Chart.js') }}"></script>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.9/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.9/js/jquery.dataTables.js"></script>
@endsection

@section('script')
	@include('post._commentJS');
@endsection

@section('app_content')
<div class="container-fluid statistical-result">
  <div id="loading"><i class='fa fa-cog fa-5x fa-spin'></i> Loading data, please wait...</div>
</div>
<div class="container">
  <hr/>
  <p><strong>Post ID</strong><br/>{!! $post['post_id'] !!}</p>
  <p><strong>Post</strong><br/>{!! $post['post_content'] !!}</p>
  <p><strong>Created time</strong><br/>{!! $post['post_time'] !!}</p>
  <a href="/update/{!! $post['post_id'] !!}" class="btn btn-default" value="Update Comments" data-toggle="tooltip" data-placement="top" title="Update and search for new comments made">Update Comments</a>
  <hr/>

  <table class="display" cellspacing="0" width="100%" id="comment_table">
    <thead>
      <tr>
        <th class="col1">#</th>
        <th class="col2">Name</th>
        <th class="col3">Comment</th>
        <th class="col4">Date</th>
        <th class="col5">Emotion</th>
      </tr>
    </thead>
    <tbody id="comment-list">
      <?php $count=1; ?>
      @foreach($comments as $comment)
      <tr>
        <td class="col1">{{ $count }}</td>
        <td class="col2">{!! $comment['commenter'] !!}</td>
        <td class="col3">{!! $comment['comment'] !!}</td>
        <td class="col4">{!! $comment['comment_datetime'] !!}</td>
        <td class="col5">{!! $sentiment[$count-1]['result'] !!}</td>
      </tr>
      <?php $count++ ?>
      @endforeach
    </tbody>
  </table>
</div>
@endsection
