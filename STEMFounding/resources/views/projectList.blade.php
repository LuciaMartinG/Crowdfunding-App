@extends('layout')

@section('title', 'Project List')

@section('content')

<div class="container">
        @if (Auth::user()->role == 'admin')
            <div class="mb-4">
            <a href="/projects/pending/" class="btn btn-danger btn-sm mb-3 w-auto">Pending Projects</a>
            </div>
        @endif

    <div class="row">
        @foreach($projectList as $project)
            @if(in_array($project->state, ['active', 'inactive']))
                <a href="project/detail/{{ $project->id }}" class="card col-12 col-md-6 col-lg-3">
                    <!-- <img src="{{ $project->image_url }}" alt=""> -->
                    <h2>{{ $project->title }}</h2>
                    <h3>{{ $project->description }}</h3>
                </a>
            @endif
        @endforeach
    </div>

    @if( ! $projectList->onFirstPage() )
        <a href="{{$projectList->previousPageUrl()}}" class="btn btn-primary">Previous page</a>
    @endif

    @if( ! $projectList->onLastPage() )
        <a href="{{$projectList->nextPageUrl()}}" class="btn btn-primary">Next page</a>
    @endif

</div>


@endsection