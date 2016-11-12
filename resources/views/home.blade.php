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
                <form role="form" method="POST" action="{{ url('/home') }}" enctype="multipart/form-data">
                    <div class="col-xs-3">
                        <label for="notes">My Notes:</label>
                        <textarea name ="noteBody" rows ="20" class ="form-control"> put something $}}</textarea>
                    </div>

                    <div class="col-xs-3">
                        <label for="website">My WebSites:</label>
                        <input class="form-control" name="website" id="website">
                        <!-- foreach($website as $url)
                            if not empty
                                <input class="form-control" name="website" id="website" value="}}" onclick="window.open(this);">
                            end if
                         endforeach-->
                        <input class="form-control" name="website" id="website">
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
                        <label for="tbd">My Tdb:</label>
                        <textarea name ="tbdBody" rows ="20" class ="form-control"> put something $}}</textarea>
                    </div>

                    <div class = "text-center">
                        <button type ="submit" class=" btn btn-primary">Save </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
@endsection
