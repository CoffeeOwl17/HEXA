@extends('dashboard')

@section('additional_include')
<link href="{{ URL::asset('css/Comment_Style.css') }}" rel="stylesheet">
<link href="{{ URL::asset('css/Post_Style.css') }}" rel="stylesheet">
<link href="{{ URL::asset('css/scrollable_table.css') }}" rel="stylesheet">
<script type="text/javascript" src="{{ URL::asset('js/Chart.js-master/Chart.js') }}"></script>
@endsection

@section('script')
	@include('post._commentJS');
@endsection

@section('app_content')
<div class="container-fluid statistical-result">
  <div id="loading"><i class='fa fa-cog fa-5x fa-spin'></i> Loading data, please wait...</div>
</div>
<div class="container-fluid">
  <hr/>
  <p><strong>Post ID</strong><br/>{!! $post['post_id'] !!}</p>
  <p><strong>Post</strong><br/>{!! $post['post_content'] !!}</p>
  <p><strong>Created time</strong><br/>{!! $post['post_time'] !!}</p>
  <hr/>

  <table class='table table-hover header-fixed' id="comment-table">
    <thead>
      <tr>
        <th class="col-md-1">#</th>
        <th class="col-md-3">Name</th>
        <th class="col-md-6">Comment</th>
        <th class="col-md-2">Emotion</th>
      </tr>
    </thead>
    <tbody id="comment-list">
      <?php $count=1; ?>
      @foreach($comments as $comment)
      <tr>
        <td class='col-md-1'>{{ $count }}</td>
        <td class='col-md-3'>{!! $comment['commenter'] !!}</td>
        <td class='col-md-6'>{!! $comment['comment'] !!}</td>
        <td class='col-md-2'>{!! $sentiment[$count-1]['result'] !!}</td>
      </tr>
      <?php $count++ ?>
      @endforeach
    </tbody>
  </table>
</div>
@endsection
