@extends('matta.layouts.app', ['activeNav' => 'feeds'])

@section('title', 'Detail Feed')

@section('content')
<main class="main main-feeds" id="main">
  <div class="page-top">
    <nav class="breadcrumb" aria-label="Breadcrumb">
      <a href="{{ route('matta.dashboard') }}">Home</a>
      <svg class="icon icon-xs" viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
      <a href="{{ route('matta.feeds') }}">Feeds</a>
      <svg class="icon icon-xs" viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
      <span class="current" aria-current="page" data-crumb-current>Detail Feed</span>
    </nav>
  </div>
  <div class="art-sheet" data-page="feed-detail"><noscript>Aktifkan JavaScript untuk membaca feed.</noscript></div>
  <script type="application/json" id="feed-data">@json($feeds)</script>
</main>
@endsection
