@extends('mooc.layouts.main')

@section('script-extension')
    @parent
                @isset ($school)
                // 選中的學校選單增加active特效
                $("a.school").each(function() {
                    if ($(this).attr("name") == "{{ $school }}") {
                        $(this).addClass("active");
                    }
                });
                @endisset

                @isset ($type)
                // 選中的分類選單增加active特效
                $("a.type").each(function() {                
                    if ($(this).text() == "{{ $type }}") {
                        $(this).addClass("active");
                        $(this).addClass("font-weight-bold");
                    }
                });
                @endisset
@endsection

@section('content')
    <div class="container my-5">

    <!-- 學校選單 -->
        <nav class="nav nav-pills flex-column flex-lg-row nav-justified my-5">
            <a class="nav-item nav-link btn btn-lg btn-outline-info mr-lg-2 mb-2 school" href="{{ route('class') }}?school=ntu" name="ntu">{{ __('dictionary.NTU') }}</a>
            <a class="nav-item nav-link btn btn-lg btn-outline-secondary mr-lg-2 mb-2 school disabled" href="{{ route('class') }}?school=nthu" name="nthu">{{ __('dictionary.NTHU') }}</a>
            <a class="nav-item nav-link btn btn-lg btn-outline-info mr-lg-2 mb-2 school" href="{{ route('class') }}?school=nctu" name="nctu">{{ __('dictionary.NCTU') }}</a>
            <a class="nav-item nav-link btn btn-lg btn-outline-secondary mb-2 school disabled" href="#">{{ __('dictionary.Opening soon') }}</a>
        </nav>

    <!-- 分類選單 -->
        <ul class="nav nav-tabs">
            @isset($search)
            <li class="nav-item">
                <a class="nav-link text-dark type" href="{{ route('class') }}?search={{ $search }}">搜尋結果</a>
            @else</li>
            <li class="nav-item">
                <a class="nav-link text-dark type" href="{{ route('class') }}?school={{ $school }}&type=熱門課程">熱門課程</a>
            </li>
                @foreach ($classTypes as $classType)
                    <li class="nav-item">
                        <a class="nav-link text-dark type" href="{{ route('class') }}?school={{ $school }}&type={{ $classType }}">{{ $classType }}</a>
                    </li>
                @endforeach
            @endisset            
        </ul>

    <!-- 課程表 -->
        <div class="my-5 table-responsive">
            <table class="table table-striped">                
                <thead class="thead-dark">
                <tr>                    
                    <th class="text-center text-nowrap w-50">{{ __('dictionary.ClassName') }}:</th>
                    <th class="text-center text-nowrap">{{ __('dictionary.Like') }}:</th>
                    <th class="text-center text-nowrap">{{ __('dictionary.School') }}:</th>
                    <th class="text-center text-nowrap w-25">{{ __('dictionary.Teacher') }}:</th>
                    <th class="text-center text-nowrap">{{ __('dictionary.ClassType') }}:</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($classes as $class)
                    <tr>
                        <td class="pl-4"><a href="{{ route('class') }}?class={{ $class->classId }}" style="display:block;">{{ $class->className }}</a></td>
                    
                        @if ($class->likeCount != '')
                            <td class="text-center">{{ substr_count($class->likeCount, ',') + 1 }}</td>
                        @else
                            <td class="text-center">0</td>
                        @endif
                                            
                        <td class="text-center">{{ $class->school }}</td>
                        <td class="text-center">{{ $class->teacher }}</td>
                        <td class="text-center">{{ $class->classType }}</td>                    
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    <!-- 課程頁數 -->
        <div class="w-100">
            <div class="mx-auto">
                @if (isset($school) || isset($type))
                    {{ $classes->appends(['school' => $school, 'type' => $type])->links() }}
                @else
                    {{ $classes->links() }}
                @endif
            </div>
        </div>

    </div>
@endsection