@extends('layouts.app')

@php
    use App\User;
    use Illuminate\Support\Facades\Auth;
    use App\Http\Requests;

        $user_id  =  Auth::user()->id;

        session_start();
        session_regenerate_id();
        if ((time() - $_SESSION["timeout"]) > (20 * 60)) {
            Auth::logout(User::find($user_id));
            unset($_SESSION["timer"]);
        }
@endphp
@section('content')
    <div class="container">
        <div class="row">
            <div class="text-center">
                <div class="panel panel-default">
                    <div class="panel-heading">My Notes</div>
                </div>
            </div>
            <div class="col-xs-12">
                <!--{{Auth::user()->id}}-->
                <form role="form" method="POST" action="{{ url('/home') }}" enctype="multipart/form-data">

                    <!-- Maks added this and isn't sure what it does but it stopped the
                         "TokenMismatchException in VerifyCsrfToken.php" -->
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="col-xs-3">
                        <label for="notes">My Notes:</label>
                        <textarea name ="texts" rows ="20" class ="form-control">{{$texts}}</textarea>
                    </div>

                    <div class="col-xs-3">
                        <div class="form-group">
                            <label for="website">WebSites:</label>
                            @foreach($links as $link)
                                @if(!empty($link))
                                    <a href="{{"http://" . $link}}" target="_blank"><input class="form-control" name="website[]" id="website" placeholder="add website" value="{{$link}}"></a>
                                @endif
                            @endforeach

                            <input class="form-control" name="website[]" id="website" placeholder="add website" >

                        </div>


                    </div>

                    <div class="col-xs-3">
                        <label for="images">Images:</label>
                        <input class="form-control" type="file" name="myImage" id="images">
                        <table>
                                @foreach($images as $key => $image)
                                <tr>
                                    <td>
                                        <input name="imageNames[]" id="imageNames" type="hidden" value="{{$image}}"/>
                                        <img width="100px" src="{{ asset('uploads/' . $image) }}" />
                                    </td>
                                    <td>
                                         <input type="checkbox" name="delete[]" value="{{ $key }}"> delete
                                    </td>
                                </tr><br/>
                                @endforeach
                        </table>
                    </div>

                    <div class="col-xs-3">
                        <label for="tba">TBD</label>
                        <textarea name ="tbas" rows ="20" class ="form-control">{{$tbas}}</textarea>
                    </div>
                    <div class = "text-center">
                        <button type ="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
@endsection
