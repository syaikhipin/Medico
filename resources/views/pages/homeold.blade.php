<style>
    .linktitle{
        overflow: hidden;
        color: #fff;

    }
</style>
<section class="slider">
    <div class="container" style="position: relative;min-height: 700px">
        @if(Session::has('message'))
            {!! Session::get('message') !!}
        @endif
        <div id="center-block" class="heading-block text-center">
            <h2 style="color:#ffffff">Find and Book</h2>

            <form method="get" action="{{URL::to('results')}}" class="form-inline ">
                <div class="col-lg-12" style="margin-bottom:60px ">
                    <div class="form-group">
                        <select id="type" class="form-control" name="type" title="Please Select" data-width="250px"
                                data-live-search="false">
                            <option value="Clinic">Clinic</option>
                            <option value="Doctor">Doctor</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input id="loc" name="loc" class="form-control" type="text" placeholder="Location"
                               autocomplete="off">
                    </div>
                    <div class="form-group">
                        <input id="q" name="q" class="form-control ui-autocomplete-input" placeholder="Keyword"
                               type="text" autocomplete="off"/>
                    </div>
                    <input id="scope" type="hidden" name="scope">
                    <datalist id="suggestion">
                    </datalist>

                    <div class="form-group">
                        <button type="submit" class="btn btn-md btn-success center"><i
                                    class="icon-search icon-white"></i> Search Now
                        </button>
                    </div>
                </div>
            </form>
            <div class="col-lg-8 col-lg-offset-2 col-md-12" style="background-color: rgba(255,255,255,0.4);" >

                <ul class="nav nav-tabs directlink" >
                    <li class="active"><a href="#clinics" data-toggle="tab">  Clinics </a></li>
                    <li><a href="#doctors" data-toggle="tab">  Doctors </a></li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane active m-t" id="clinics">
                        <div class="row">
                            @foreach($specialities as $speciality)
                            <div class="col-md-3 col-sm-3 col-xs-3">
                                <a  href="{{ url('results?scope=speciality&q='.$speciality->Speciality.'&type=Clinic&loc=') }}"> <img src="{{ asset('sximo/images/icons/'.$speciality->display_name.'.png') }}" class="img" height="40px"></a>
                                <p class="text-center linktitle">{{$speciality->display_name}}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="tab-pane m-t" id="doctors">
                        <div class="row">
                            @foreach($expertizes as $expertise)
                            <div class="col-md-3 col-sm-3 col-xs-3">
                                <a  href="{{ url('results?scope=Dermatologist&type=Doctor&q='.$expertise->Expertize.'&loc=') }}"> <img src="{{ asset('sximo/images/icons/doctor.png') }}" class="img" height="40px"></a>
                                <p class="text-center linktitle">{{$expertise->display_name}}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    </div>

</section>

<section class="text-promo"
         style="margin-bottom:0px !important;padding: 100px 0px;background-color:#ffffff; color:#FF6607;"
         class="section parallax">
    <div class="container text-center">
        <div class="heading-block center nobottomborder nobottommargin">
            <h2>"Everything is designed, but some things are designed well."</h2>
        </div>
    </div>
</section>
<script src="{{url('sximo/js/plugins/select2/select2.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script>
    $(document).ready(function () {
        var geocoder = new google.maps.Geocoder();;

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(successFunction, errorFunction);
        }
//Get the latitude and the longitude;
        function successFunction(position) {
            var lat = position.coords.latitude;
            var lng = position.coords.longitude;
            codeLatLng(lat, lng)
        }

        function errorFunction(){
            alert("Geocoder failed");
        }

        function codeLatLng(lat, lng) {

            var latlng = new google.maps.LatLng(lat, lng);
            geocoder.geocode({'latLng': latlng}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {

                    if (results[1]) {
                        //find country name
                        console.log(results[2]);
                        for (var i=0; i<results[0].address_components.length; i++) {
                            for (var b=0;b<results[0].address_components[i].types.length;b++) {

                                //there are different types that might hold a city admin_area_lvl_1 usually does in come cases looking for sublocality type will be more appropriate
                                if (results[0].address_components[i].types[b] == "administrative_area_level_2") {
                                    //this is the object you are looking for
                                    city= results[0].address_components[i];
                                    break;
                                }
                            }
                        }
                        //city data
                        //alert(city.long_name);
                        $.get("VerifyLocation?city="+city.long_name, function(data, status){
                            if(status=="success"){
                                $('#loc').val(data);
                            }
                        });

                    }
                }
            });
        }

        var location = $('#loc').val();
        var type = $('#type').val();
        $('#type').change(function () {
            type = $(this).val();

        });


        $("#q").on('input', function () {
//
            var location = $("#loc").val();
            var val = this.value;
//            console.log(encodeURI("/search/autocomplete?type="+ type + '&term=' + val + '&location=' + location ));

            $("#q").autocomplete({
                minLength: 2,
                source: encodeURI("/search/autocomplete?type=" + type + '&term=' + val + '&location=' + location),
                focus: function (event, ui) {
                    $("#q").val(ui.item.value);
                    return false;
                },
                select: function (event, ui) {
                    $("#q").val(ui.item.value);
                    $("#scope").val(ui.item.type);
                    return false;
                }
            })
                    .autocomplete("instance")._renderItem = function (ul, item) {


                return $("<li>")
                        .append("<span class='value left'>" + item.value + "</span> <span class='keyword label-inverse' style='float: right !important;'>" + item.type + "</span></a>")
                        .appendTo(ul);
            };
        });

        $("#loc").on('input', function () {
            var value = this.value;
            $("#loc").autocomplete({
                minLength: 1,
                source: encodeURI("/search/location?type=" + type),
                focus: function (event, ui) {
                    $("#loc").val(ui.item.value);
                    return false;
                },
                select: function (event, ui) {
                    $("#loc").val(ui.item.value);
                    return false;
                }
            })
                    .autocomplete("instance")._renderItem = function (ul, item) {
                return $("<li>")
                        .append("<span>" + item.value + "</span>")
                        .appendTo(ul);
            };
        });


//                $.ajax({
//                    url    : "/search/autocomplete/" + type + "/"+ $(this).val() ,
//                    method : "GET",
//                    success: function (result) {
//                        var dataList = document.getElementById('suggestion');
//                        dataList.innerHTML="";
//                        jQuery.each(result, function (i, data) {
//                            var option = document.createElement('li');
//                            // Set the value using the item in the JSON array.
//                            option.value = data.value;
//                            // option.label= data.type;
//                            option.innerHTML = data.value;
//                            option.setAttribute('data-value',data.type);
//                            // Add the <option> element to the <datalist>.
//                            dataList.appendChild(option);
//
//                        });
//
//
//                    }
//                });

        //   var val = this.value;
//                $("#q").autocomplete({
//
//                    source: encodeURI("/search/autocomplete?type="+ document.getElementById('type').value + '&term=' + val),
//                    select : function (suggestion) {
//                        console.log('You selected: ' + suggestion.value + ', ' + suggestion.data);
//                    }
//                });
////                    source:encodeURI("/search/autocomplete?type="+ document.getElementById('type').value + '&term=' + val),
////                    minLength: 2,
////                    select: function(event, ui) {
////                        console.log(ui.item.type);
////                    },
//
//                //});

//            else {
//                console.log();
//
//            }


        $("#q").bind('change', function () {

            var v = this.value;
            var xyz = $('#suggestion option').filter(function () {
                return this.value == v;
            }).data('value');
            if (xyz != 'undefined') {
                $('#scope').val(xyz);
            }


        });


    });


</script>



