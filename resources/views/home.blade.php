@extends('layouts.app')

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

                        <textarea name ="texts" rows ="20" class ="form-control">
                            @foreach ($texts as $text)
                                {{$text->textBody}}
                            @endforeach
                        </textarea>
                    </div>

                    <div class="col-xs-3">
                        <div class="form-group">
                            <label for="website">WebSites:</label>

                            @foreach($links as $link)
                                @if(!empty($link))
                                    <a href="{{$link}}" target="_blank"><input class="form-control" name="website[]" id="website" placeholder="add website" value="{{$link}}"></a>
                                @endif
                            @endforeach

                            <input class="form-control" name="website[]" id="website" placeholder="add website" >

                        </div>


                    </div>

                    <div class="col-xs-3">
                        <label for="images">Images:</label>
                        <input class="form-control" type="file" name="myImage" id="images">
                        <table>
                            <tr>
                                <!-- //foreach($myImage as imgKey => img)
                                         <text-align: center">
                                         <img height="150" width="150" src="????? $img }}"><br>
                                         <input type="checkbox" name="delete" value="imgKey}}"> delete
                                         </td>
                                     //endforeach -->
                            </tr>
                        </table>
                    </div>

                    <div class="col-xs-3">
                        <label for="tba">TBD</label>
                        <textarea name ="tbas" rows ="20" class ="form-control">
                            @foreach ($tbas as $tba)
                                {{$tba->tbaBody}}
                            @endforeach
                        </textarea>
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
