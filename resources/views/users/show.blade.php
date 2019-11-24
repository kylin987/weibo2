@extends('layouts.default')
@section('title', $user->name)

@section('content')
<div class="row">
  <div class="offset-md-2 col-md-8">
    <section class="user_info">
      @include('shared._user_info', ['user' => $user])
    </section>

    @if (Auth::check())
      @include('users._follow_form')
    @endif

    <section class="stats mt-2">
      @include('shared._stats', ['user' => $user])
    </section>
    <hr>

    <section class="status">
      @if ($weibos->count() > 0)
        <ul class="list-unstyled">
          @foreach ($weibos as $weibo)
            @include('weibos._weibo')
          @endforeach
        </ul>
        <div class="mt-5">
          {!! $weibos->render() !!}
        </div>
      @else
        <p>没有数据！</p>
      @endif
    </section>
  </div>
</div>
@stop
