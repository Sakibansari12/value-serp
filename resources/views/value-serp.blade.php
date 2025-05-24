<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        

        @if(isset($error))
            <div class="alert alert-danger">
                {{ $error }}
            </div>
        @endif

        <form method="GET" action="{{ route('index') }}" class="mb-4">
            <div class="input-group">
                <input type="text" name="keyword" class="form-control" placeholder="Enter search keyword" value="{{ $keyword }}">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>

        
        @if(isset($data['organic_results']) && count($data['organic_results']) > 0)
            <form method="GET" action="{{ route('index') }}">
                <input type="hidden" name="keyword" value="{{ $keyword }}">
                <input type="hidden" name="page" value="{{ $currentPage }}">
                <input type="hidden" name="export" value="csv">
                <button type="submit" class="btn btn-success mb-3">Export CSV</button>
            </form>
        @endif

        
        <h2>Search Results</h2>
        @if(isset($data['organic_results']) && count($data['organic_results']) > 0)
            <div class="row">
                @foreach($data['organic_results'] as $result)
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <a href="{{ $result['link'] }}" target="_blank">{{ $result['title'] }}</a>
                                </h5>
                                <p class="card-text">{{ $result['snippet'] ?? 'No description available' }}</p>
                                <small class="text-muted">{{ $result['displayed_link'] ?? $result['link'] }}</small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p>No results found.</p>
        @endif

        
        @if(isset($data['pagination']['other_pages']))
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    
                    @if($currentPage > 1)
                        <li class="page-item">
                            <a class="page-link" href="{{ route('index', ['keyword' => $keyword, 'page' => $currentPage - 1]) }}">Previous</a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span class="page-link">Previous</span>
                        </li>
                    @endif

                   
                    @foreach($data['pagination']['other_pages'] as $page)
                        <li class="page-item {{ $currentPage == $page['page'] ? 'active' : '' }}">
                            <a class="page-link" href="{{ route('index', ['keyword' => $keyword, 'page' => $page['page']]) }}">{{ $page['page'] }}</a>
                        </li>
                    @endforeach

                    
                    @if(isset($data['pagination']['next']))
                        <li class="page-item">
                            <a class="page-link" href="{{ route('index', ['keyword' => $keyword, 'page' => $currentPage + 1]) }}">Next</a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span class="page-link">Next</span>
                        </li>
                    @endif
                </ul>
            </nav>
        @endif

        
        @if(isset($data['related_searches']) && count($data['related_searches']) > 0)
            <h3>Related Searches</h3>
            <ul class="list-group mb-4">
                @foreach($data['related_searches'] as $search)
                    <li class="list-group-item">
                        <a href="{{ route('index', ['keyword' => urlencode($search['query'])]) }}">{{ $search['query'] }}</a>
                    </li>
                @endforeach
            </ul>
        @endif

        @if(isset($data['related_questions']) && count($data['related_questions']) > 0)
            <h3>Related Questions</h3>
            <ul class="list-group">
                @foreach($data['related_questions'] as $question)
                    <li class="list-group-item">{{ $question['question'] }}</li>
                @endforeach
            </ul>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
