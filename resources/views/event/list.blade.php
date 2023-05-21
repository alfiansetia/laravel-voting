@extends('layouts.template_onauth')

@section('content')
<div class="section-body">
    <div class="row">
        @foreach($event as $ev)
        <div class="col-12 col-sm-6 col-md-6 col-lg-3">
            <article class="article">
                <div class="article-header">
                    <div class="article-image" data-background="../assets/img/news/img08.jpg">
                    </div>
                    <div class="article-title">
                        <h2><a href="#">{{ $ev->name }}</a></h2>
                    </div>
                </div>
                <div class="article-details">
                    <p>
                        {{ date('Y-m-d', strtotime($ev->date)) }} - {{ date('Y-m-d', strtotime($ev->expired)) }}
                        <br>{{ $ev->desc ?? '-'}}
                    </p>
                    <div class="article-cta">
                        <a href="{{ route('statistic.index') }}?event={{ $ev->id }}" class="btn btn-primary">Statistic</a>
                    </div>
                </div>
            </article>
        </div>
        @endforeach
    </div>
</div>

@endsection