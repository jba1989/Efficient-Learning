@extends('mooc.layouts.main')

@section('head-extension')
    <script>
        function deleteFavorite(classId) {            
            $.ajax({
                type: "delete",
                datatype: "json",
                url: "/api/user/delete",                
                data: {"_token": "{{ csrf_token() }}", "classId": classId},
                success: function(response){
                    $("#" + classId).remove();
                }
            });
        };
    </script>
@endsection

@section('content')
    <div class="container">
        <div class="row mt-5">
            <div class="col-12 col-lg-5 rounded bg-light">
                
                    <h3 class="mt-3 text-info text-center">{{ __('dictionary.Account') }}</h3>
                    <form>
                        <fieldset disabled>
                            <div class="form-group row">
                                <label for="Name" class="col-3 col-form-label text-center">{{ __('auth.Name') }}</label>
                                <div class="col-8">
                                <input type="text" readonly class="form-control" id="Name" value="{{ Auth::user()->name }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="Email" class="col-3 col-form-label text-center">{{ __('auth.Email') }}</label>
                                <div class="col-8">
                                <input type="text" readonly class="form-control" id="Email" value="{{ Auth::user()->email }}">
                                </div>
                            </div>
                        </fieldset>
                    </form>                    
                
            </div>

            <div class="col-12 py-3 col-lg-1 py-lg-0"></div>

            <div class="col-12 col-lg-6 rounded bg-light">           
                <h3 class="mt-3 text-info text-center">{{ __('dictionary.Favorite') }}</h3>
                <div class="list-group my-3">
                @isset ($favorites)
                    @foreach ($favorites as $favorite)
                        <div class="btn-group mb-4" role="group" id="{{ $favorite['classId'] }}">
                            <button type="button" class="btn bg-white col-11 text-dark">{{ $favorite['className'] }}</button>
                            <button type="button" class="btn btn-danger col-1" onclick="deleteFavorite('{{ $favorite["classId"] }}')">&times;</button>
                        </div>
                    @endforeach
                @endisset
                </div>
                
            </div>            
        </div>
    </div>
@endsection