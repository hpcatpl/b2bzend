 
    $().ready(function () {
    $("input[name=Hotel_name_city]").autocomplete({
        source: '/buyhotel/autosuggest',
        focus: function (event, ui) {
//                event.preventDefault();
            // $("#tags").val(ui.item.label);
        },
        select: function (event, ui) {
            event.preventDefault();
           // disableOverviewForm();
            $("#change-hotel").css("display", "block");
            $("input[name=Hotel_name_city]").val(ui.item.label);
            $("#hidden_selected_hotel_cityid").val(ui.item.CityId);
            $("#hidden_selected_hotel_id").val(ui.item.TBBCityId).trigger('change');

            //showBuyHotelOverviewTab();
        }
    })

});
$().ready(function () {
    $("input[name=Hotel_name_cityRem]").autocomplete({
        source: '/buyhotel/autosuggest',
        focus: function (event, ui) {
//                event.preventDefault();
            // $("#tags").val(ui.item.label);
        },
        select: function (event, ui) {
            event.preventDefault();
            disableOverviewForm();
            $("#change-hotel").css("display", "block");
            $("input[name=Hotel_name_cityRem]").val(ui.item.label);
            $("#hidden_selected_hotel_cityidRem").val(ui.item.CityId);
            $("#hidden_selected_hotel_idRem").val(ui.item.TBBCityId).trigger('change');

            //showBuyHotelOverviewTab();
        }
    })

});



function getSearchAccomoId(id) {
    
    var myArr = [];
    $.ajax({
        url: '/buyhotel/get-accomodation-detail',
        data : { id : id },
        type: 'POST',
        dataType: 'html',
        beforeSend: function () {
          //  $(".modalloader").show();
        },
        success: function (response) {
          $('#Overview').html(response);
            var selector = '.modal-content li';
            $(selector).removeClass('active');
            $(selector).eq([0]).addClass('active');
            $('.hideClass').removeClass('active').removeClass('in');
            $('#Overview').addClass('active').addClass('in');
            //$('#map').html(response);
            myArr = $.parseJSON(response);
              console.log(myArr);
               var resaminitHotel=getdataHotelAminityFromController($.trim(myArr['accomoTitle']['AccoAminitiesMask']));
               $('#amenitiesa').html(resaminitHotel);
              // $('#Overview').html(resaminitHotel);
               //console.log(myArr['vaTit']['RoomTitle']);
               $('#hotlname').empty().html(myArr['accomoTitle']['Title']);
               $('#hotladd').empty().html(myArr['accomoTitle']['Address']);
               var ratingCount=myArr['accomoTitle']['Rating'].substr(0,1);
               var ratingCounthalf=myArr['accomoTitle']['Rating'].substr(1,3);
               var htmRatingi='';
               var m=0;
               for(var d=0; d < ratingCount; d++){
                    htmRatingi +='<span class="fa fa-star text-danger"></span>'; 
                    m++;
                }
                if(ratingCounthalf==.50){
                    m++;
                     htmRatingi +='<span class="fa fa-star-half-full text-danger"></span>'; 
                }
                for(var h=m; h<5;h++){
                    htmRatingi +='<span class="fa fa-star-o text-danger"></span>'; 
                }
                $('#hotlrating').empty().html(htmRatingi);
                if(!myArr.accomoDetail[0]){
                $('#overviewul').empty();
                } else{
                $('#overviewul').html('<li>'+myArr['accomoDetail']['0']['Writeup']+'.</li><br>');
                $('#overviewul').append('<li>'+myArr['accomoDetail']['0']['Details']+'.</li><br>');
                }
                $('#map').empty().html(myArr['mapHotelRs']);
            //var imgcount=myArr['accImg'].length;
           
            var j=0;
            $('#grid').empty();
            var htmlImg=''
            for (var i=0; i<=2; i++){
              htmlImg+='<div class="col-md-4 col-sm-4 col-xs-12 m-col-md-4 picture-item" ><img src="'+myArr['accImg'][j]['Details']+'" alt="image" ></div>';
             $('#grid').append(htmlImg);
             j++;
            }
            var l=0;
            $('#photogrid').empty();
             for (var k=0; k<=myArr['accImg'].length; k++){
             if(!myArr['accImg'][l]){
                    
                }else{    
             var htmlImg2='<div class="col-md-3 col-sm-4 col-xs-12 m-col-md-3 picture-item" data-groups="["design","task"]" data-date-created="2013-05-19" data-title="Vestride"><div class="ls-hover-block ls-block-effect"><img src="'+myArr['accImg'][l]['Details']+'" alt="image" ><div class="ls-blind"></div><div class="ls-hover-info ls-four-column-icon"><div class="ls-icons-preview ls-gallery-image-view" data-rel="imageGallery"><a  href="javascript:void(0);"  data-src="'+myArr['accImg'][l]['Details']+'"><i class="fa fa-search"></i></a></div></div></div></div>';
             $('#photogrid').append(htmlImg2);
             l++;
            }
             }
         
            //$('#roomsRatediv1').empty();
//            for (var b=0; b<=myArr['vaTit'].length; b++){
//                if(!myArr['vaTit'][a]){
//                    
//                }else{
//                  
//                   var res=getdataFromController($.trim(myArr['accRooms'][a]['AminitiesMask']));
          
                    var f=0; var k=0; $('#roomrates').empty();
                    $.each(myArr['vaTit'] , function (index, value){
                 
                    var a=0;
                    var htmlImg3='<div class="col-md-12 margin-top-bot border-bottom"><h4 class="orange no-margin" id="roomTitles">'+index+'</h4>'
                           +'<div class="col-md-12 no-padding"><div class="col-sm-2 no-padding">'
                          +'<img src="'+myArr['accImg'][k]['Details']+'" style="max-width:100%;"></div><div class="col-sm-10" id="roomsRatediv1">';
               
               
                for(var c=0;c<value.length;c++){
                     
                        var res=getdataFromController($.trim(value[a]['AminitiesMask']));

                        var resAmity=res.rAmit.length; 
                        var htmlli='';    
                        for(var z=0;z<resAmity; z++){
                                       
                                 htmlli+=' <li><span>'+res.rAmit[z] +'</span></li>'
                                         
                                       }
                                      
             htmlImg3 +='<div class="alert alert-info col-md-12"><div class="col-sm-9 no-padding"><strong>'+value[a]['title']+'</strong><br />'
              +'<span class="graytxt">Cancellation charges applicable</span><br />'
              +'<a href="javascript:void(0);" class="small orange sdetail" id="sdetail_'+f+'" onclick="showdetail(this.id);">&raquo; Show Details</a><a href="javascript:void(0);" class="small orange hdetail"  id="hdetail_'+f+'" style="display:none;" onclick="hidedetail(this.id);">&raquo; Hide Details</a>'
              +'</div>'
              +'<div class="col-sm-3 no-padding text-center">'
              +'<h4 class="no-margin green"><span class="fa fa-rupee"></span>'+value[a]['cost']+'</h4>'
                                   +'<div class="clear">&nbsp;</div>'
                                   +'<button class=" btn btn-danger btn-danger" data-dismiss="modal">Book Now</button></div> <div class="col-md-12 whbg margin-top-bot no-padding policyid" id="policyid_'+f+'" style="display:none;">'
                                   +'<div class="col-md-6 margin-top-bot">'
                                   +'<strong>What"s included</strong>'
                                   +'<ul class="roomactivities">'+htmlli
                                   +'</ul></div><div class="col-md-6 margin-top-bot"><strong>Cancellation policy</strong><br />'
                                   +'<span class="graytxt"><strong>Note:</strong> If you cancel within 7 day(s) before checkin, you will incur 100.0% of your total stay.</span>'
                                   +'</div></div><div class="clear"></div></div>';
            a++; 
            f++;
         }
          
             
            $('#roomrates').append(htmlImg3); 
            var htmlclos='</div>';
            $('#roomrates').append(htmlclos);
            k++;
            }); 
            
        }
    });
}

function getdataFromController(amenity){
  var result;
          $.ajax({
        url: '/buyhotel/get-room-aminites-mask',
        data : { amenity : amenity },
        type: 'POST',
        async:false,
        dataType: 'html',
        beforeSend: function () {
          //  $(".modalloader").show();
        },
        success: function (response) {
         result=$.parseJSON(response);
         //amenity($.parseJSON(response));
          }
         
        });

  return result;
    
}
function getdataHotelAminityFromController(amenit){
  var result;
          $.ajax({
        url: '/buyhotel/get-hotl-amit-cate',
        data : { amenity : amenit },
        type: 'POST',
        async:false,
        dataType: 'html',
        beforeSend: function () {
          //  $(".modalloader").show();
        },
        success: function (response) {
            //alert(response);
//            console.log(response);
        result=response;
//         console.log(result)
          }
         
        });

  return result;
    
}
function showdetail(sdetailid){
    var id=sdetailid.substr(8, 1);
   
    		$("#hdetail_"+id).show();	
		$("#policyid_"+id).show();
		$("#sdetail_"+id).hide();			
	}
function hidedetail(hdetail){
    
    var id=hdetail.substr(8, 1);
   
    
                $("#sdetail_"+id).show();	
		$("#policyid_"+id).hide();
		$("#hdetail_"+id).hide();
	}
        
       

function validateForm() {
    var TodayDate = new Date();
    var d = TodayDate.getDay();
    var m = TodayDate.getMonth()+1;
    var y = TodayDate.getFullYear();
    var nData = '0'+m+'/0'+d+'/'+y;


    var dest = document.forms["hoteSEarch"]["Hotel_name_city"].value;
//   var nationality = document.forms["hoteSEarch"]["selectNationality"].value;
    var chekOutD = document.forms["hoteSEarch"]["chekOutDate"].value;
    var nights = document.forms["hoteSEarch"]["nights"].value;
    var chekInD = document.forms["hoteSEarch"]["chekInDate"].value;
   
    var rating = document.forms["hoteSEarch"]["selectStarRating"].value;
    var preferedHotel = document.forms["hoteSEarch"]["preferedHotel"].value;
    var selectRoom = document.forms["hoteSEarch"]["selectRoom"].value;
    if(selectRoom!="0"){
      var Adults1 = document.getElementById('selectnoOfAdults1').value;
    }
    
//   var startDate = $('#chekInDate').val();
//    var endDate =$('#chekOutDate').val();

//if (startDate < endDate){
//    alert('startDate='+startDate+'endDate='+endDate);
// var mes="Check Out should be greater than Check In";
//        $('#error4').html(mes);
//      
//    }else{
//        var mes='';
//        $('#error4').html(mes);
//    }

    if (dest == null || dest == "") {
        var mes = "Please Select Destination";
        alert(mes);
//        $('#error_Hotel_name_city').html(mes);
        $("#Hotel_name_city").focus();
           return false;

    } 

//    if (nationality == null || nationality == "") {
//        var mes="Please Enter Destination");
//        return false;
//    }
//return true;
    if (chekInD == "") {
        var mes = "Please Select Check In Date";
        alert(mes);
//        $('#errorchekInDate').html(mes);
        $("#chekInDate").focus();
           return false;

    } 
    if( nData==chekInD){
         var mes = "Please select valid date";
        alert(mes);
//        $('#errornights').html(mes);
        $("#chekInDate").focus();
           return false;

    }
    if (nights == null || nights == "" || nights < 0 || nights == 0) {

        var mes = "Please Select Nights";
        alert(mes);
//        $('#errornights').html(mes);
        $("#nights").focus();
           return false;

    } 
    if (chekOutD == "") {
        var mes = "Please Select Check Out Date";
        alert(mes);
//        $('#errorchekOutD').html(mes);
        $("#chekOutDate").focus();
           return false;

    } 

    if (rating == null || rating == "0") {
        var mes = "Please Select Star Rating";
        alert(mes);
//        $('#errorselectStarRating').html(mes);
        $("#selectStarRating").focus();
           return false;
    } 
//    if (preferedHotel == null || preferedHotel == "") {
//        //var mes = "Please Select Preferred Hotel";
//       // $('#error7').html(mes);
//
//    } else {
//        var mes = '';
//        $('#error7').html(mes);
//    }

    if (selectRoom == null || selectRoom == "0") {
        var mes = " Please Select Rooms";
        alert(mes);
//        $('#errorselectRoom').html(mes);
        $("#select-room").focus();
           return false;

    } 
    
       if ((selectRoom != null || selectRoom != "0")&&(Adults1 == null || Adults1 == "0")) {
        var mes = " Please Select no of adults";
        alert(mes);
//        $('#errorselectRoom').html(mes);
        $("#selectnoOfAdults1").focus();
           return false;

    }

//    if (mes != '') {
//        return false;
//    } else {
        submitForm();
//    }
}
function validateFormRem() {

    var dest = document.forms["searchHotelRem"]["Hotel_name_cityRem"].value;
//   var nationality = document.forms["hoteSEarch"]["selectNationality"].value;
    var chekOutD = document.forms["searchHotelRem"]["chekOutDateRem"].value;
    var nights = document.forms["searchHotelRem"]["nightsRem"].value;
    var chekInD = document.forms["searchHotelRem"]["chekInDateRem"].value;
    var rating = document.forms["searchHotelRem"]["selectStarRatingRem"].value;
    var preferedHotel = document.forms["searchHotelRem"]["preferedHotelRem"].value;
    var selectRoom = document.forms["searchHotelRem"]["selectRoomRem"].value;
    
//   var startDate = $('#chekInDate').val();
//    var endDate =$('#chekOutDate').val();

//if (startDate < endDate){
//    alert('startDate='+startDate+'endDate='+endDate);
// var mes="Check Out should be greater than Check In";
//        $('#error4').html(mes);
//      
//    }else{
//        var mes='';
//        $('#error4').html(mes);
//    }

    if (dest == null || dest == "") {
        var mes = "Please Select Destination";
        $('#error1').html(mes);

    } else {
        var mes = '';
        $('#error1').html(mes);
    }

//    if (nationality == null || nationality == "") {
//        var mes="Please Enter Destination");
//        return false;
//    }

    if (chekInD == "") {

        var mes = "Please Select Check In Date";

        $('#error2').html(mes);

    } else {
        var mes = '';
        $('#error2').html(mes);
    }

    if (nights == null || nights == "" || nights < 0 || nights == 0) {

        var mes = "Please Select Nights";
        $('#error3').html(mes);

    } else {
        var mes = '';
        $('#error3').html(mes);
    }
    if (chekOutD == "") {
        var mes = "Please Select Check Out Date";
        $('#error4').html(mes);

    } else {
        var mes = '';
        $('#error4').html(mes);
    }

    if (rating == null || rating == "0") {
        var mes = "Please Select Star Rating";
        $('#error6').html(mes);

    } else {
        var mes = '';
        $('#error6').html(mes);
    }
    if (preferedHotel == null || preferedHotel == "") {
        //var mes = "Please Select Preferred Hotel";
       // $('#error7').html(mes);

    } else {
        var mes = '';
        $('#error7').html(mes);
    }

    if (selectRoom == null || selectRoom == "0") {
        var mes = " Please Select Rooms";
        $('#error8').html(mes);

    } else {
        var mes = '';
        $('#error8').html(mes);
    }

    if (mes != '') {
        return false;
    } else {
        submitFormRem();
    }
}
function submitFormRem() {

    var cityId = $("#hidden_selected_hotel_idRem").val();
 //      var chekInDate=$("#chekInDate").val();
//      var chekOutDate=$("#chekOutDate").val();
//      var nights=$("#nights").val();     
//      var selectStarRating=$("#selectStarRating").val();
//      var preferedHotel=$("#preferedHotel").val();
//      var selectRoom=$("#select-room").val();
//      var nationality=$("#selectNationality").val();

    var data = $('#searchHotelRem').serialize();
    //   $.each(data,function(key,input){
    //   formData.append(input.name,input.value);
    //   });
    $.ajax({
        url: '/buyhotel/send-data-for-search-modify',
        data: data,
        type: 'POST',
        dataType: 'html',
        beforeSend: function () {
            $(".modalloader").show();
        },
        success: function (response) {

//                    $("#hidden_selected_hotel_id").val(response.intLastInsertId);
//                    alert(response.intLastInsertId);
//                    showHotelAddRoomsTab();
            $(".modalloader").hide();
            //$('#hotelRes .inner').replaceWith(response);
            $('#modifysearch').show();
            $('#filderdiv').show();
            $('#label1').removeClass('green').addClass('orange');
            $('#label1 span').removeClass('ls-green-btn').addClass('btn-warning');
            $('#label2').removeClass('graytxt').addClass('green');
            $('#label2 span').removeClass('btn-default').addClass('ls-green-btn');

//                else {
//                    alert('<?php ///echo TECHNICAL_ERROR_MSG; ?>');
//                }
        }
    });


}
function submitForm() {

    var cityId = $("#hidden_selected_hotel_id").val();
 //      var chekInDate=$("#chekInDate").val();
//      var chekOutDate=$("#chekOutDate").val();
//      var nights=$("#nights").val();     
//      var selectStarRating=$("#selectStarRating").val();
//      var preferedHotel=$("#preferedHotel").val();
//      var selectRoom=$("#select-room").val();
//      var nationality=$("#selectNationality").val();

    var data = $('#hoteSEarch').serialize();
    //   $.each(data,function(key,input){
//        formData.append(input.name,input.value);
//    });
    $.ajax({
        url: '/buyhotel/send-data-for-searchs',
        data: data,
        type: 'POST',
        dataType: 'html',
        beforeSend: function () {
            $(".modalloader").show();
        },
        success: function (response) {
            console.log(response);
            $(".modalloader").hide();
            //$('#hotelRes').empty().html(response);
//            $('#modifysearch').show();
//            $('#filderdiv').show();
//            $('#label1').removeClass('green').addClass('orange');
//            $('#label1 span').removeClass('ls-green-btn').addClass('btn-warning');
//            $('#label2').removeClass('graytxt').addClass('green');
//            $('#label2 span').removeClass('btn-default').addClass('ls-green-btn');

//                else {
//                    alert('<?php ///echo TECHNICAL_ERROR_MSG; ?>');
//                }
window.location.replace("/buyhotel/send-data-for-search");

        }
    });


}

function disableOverviewForm() {
   // $("#Hotel_name_city").attr("readonly", true);
}

    

function recurse(data) {
    var htmlRetStr = "<ul style='display:block;' >";
    for (var key in data) {
        htmlRetStr += ("<li style='display:block;'>" + key + ': &quot;' + data[key].label + '&quot;</li>');
    }
    htmlRetStr += '</ul >';
    return(htmlRetStr);
}
function getSelectChild(id) {

    $("#" + id).each(function () {
        var temp = '';

        var noOfChild = ($(this).val());



        if (id == 'select-child1') {
            $(temp).empty();
            temp = "#chidlediv" + 1;
        }
        if (id == 'select-child2') {
            $(temp).empty();
            temp = "#chidlediv" + 2;
        }
        if (id == 'select-child3') {
            $(temp).empty();
            temp = "#chidlediv" + 3;
        }
        if (id == 'select-child4') {
            $(temp).empty();
            temp = "#chidlediv" + 4;
        }


        var j = 1;
        $(temp).empty();
        for (var i = 0; i < noOfChild; i++) {
            var htmlChild = '<div class="col-md-4 col-sm-4">'
                    + '<div class="form-group form-group1">'
                    + '<label>Age of Child ' + j + ' </label>  '
                    + '<select id="select-childAge' + j + '" name="select-childAge' + j + '" class="demo-default selectchildAge" placeholder="Select">'
                    + '<option value="0">1</option>'
                    + '<option value="1">2</option>'
                    + '<option value="2">3</option>'
                    + '<option value="3">4</option>'
                    + '<option value="4">5</option>'
                    + '<option value="5">6</option>'
                    + '<option value="6">7</option>'
                    + '<option value="8">8</option>'
                    + '<option value="9">9</option>'
                    + '<option value="10">10</option>'
                    + '<option value="11">11</option>'
                    + '<option value="12">12</option>'
                    + '</select>  '
                    + '</div>'
                    + '</div>';

            $(temp).css("display", "block");
            $(temp).append(htmlChild);

            j++;
        }

    });
}
function getSelectChildRem(id) {

    $("#" + id).each(function () {
        var temp = '';

        var noOfChild = ($(this).val());



        if (id == 'select-childRem1') {
            $(temp).empty();
            temp = "#chidledivRem" + 1;
        }
        if (id == 'select-childRem2') {
            $(temp).empty();
            temp = "#chidledivRem" + 2;
        }
        if (id == 'select-childRem3') {
            $(temp).empty();
            temp = "#chidledivRem" + 3;
        }
        if (id == 'select-childRem4') {
            $(temp).empty();
            temp = "#chidledivRem" + 4;
        }


        var j = 1;
        $(temp).empty();
        for (var i = 0; i < noOfChild; i++) {
            var htmlChildRem = '<div class="col-md-4 col-sm-4">'
                    + '<div class="form-group form-group1">'
                    + '<label>Age of Child ' + j + ' </label>  '
                    + '<select id="select-childAgeRem' + j + '" name="select-childAgeRem' + j + '" class="demo-default selectchildAgeRem" placeholder="Select">'
                    + '<option value="0">1</option>'
                    + '<option value="1">2</option>'
                    + '<option value="2">3</option>'
                    + '<option value="3">4</option>'
                    + '<option value="4">5</option>'
                    + '<option value="5">6</option>'
                    + '<option value="6">7</option>'
                    + '<option value="8">8</option>'
                    + '<option value="9">9</option>'
                    + '<option value="10">10</option>'
                    + '<option value="11">11</option>'
                    + '<option value="12">12</option>'
                    + '</select>  '
                    + '</div>'
                    + '</div>';

            $(temp).css("display", "block");
            $(temp).append(htmlChildRem);

            j++;
        }

    });
}
$(document).ready(function () {
    $('#selectRoombtn').click(function (e) {
    var tab = e.target.hash;
    $('li > a[href="' + tab + '"]').tab("show");
});

    $(".loader").fadeOut("slow");
    $('.selectchild').change(function () {

        var htmlChild1 = '<div class="col-md-4 col-sm-4">'
                + '<div class="form-group form-group1">'
                + '<label>Child 2</label>'
                + '<select id="select-country" class="demo-default select-country" placeholder="Select">'
                + '<option value="1">1</option>'
                + '<option value="2">2</option>'
                + '<option value="3">3</option>'
                + '<option value="4">4</option>'
                + '<option value="5">5</option>'
                + '<option value="6">6</option>'
                + '<option value="7">7</option>'
                + '<option value="8">8</option>'
                + '<option value="9">9</option>'
                + '<option value="10">10</option>'
                + '<option value="11">11</option>'
                + '<option value="12">12</option>'
                + '</select>'
                + '</div>'
                + '</div>'
                + '<div class="col-md-4 col-sm-4">'
                + '<div class="form-group form-group1">'
                + '<label>Child 3</label>'
                + '<select id="select-country" class="demo-default select-country" placeholder="Select">'
                + '<option value="1">1</option>'
                + '<option value="2">2</option>'
                + '<option value="3">3</option>'
                + '<option value="4">4</option>'
                + '<option value="5">5</option>'
                + '<option value="6">6</option>'
                + '<option value="7">7</option>'
                + '<option value="8">8</option>'
                + '<option value="9">9</option>'
                + '<option value="10">10</option>'
                + '<option value="11">11</option>'
                + '<option value="12">12</option>'
                + '</select>'
                + '</div>'
                + '</div>';
    });
    $('.selectchildRem').change(function () {

        var htmlChild1 = '<div class="col-md-4 col-sm-4">'
                + '<div class="form-group form-group1">'
                + '<label>Child 2</label>'
                + '<select id="select-country" class="demo-default select-country" placeholder="Select">'
                + '<option value="1">1</option>'
                + '<option value="2">2</option>'
                + '<option value="3">3</option>'
                + '<option value="4">4</option>'
                + '<option value="5">5</option>'
                + '<option value="6">6</option>'
                + '<option value="7">7</option>'
                + '<option value="8">8</option>'
                + '<option value="9">9</option>'
                + '<option value="10">10</option>'
                + '<option value="11">11</option>'
                + '<option value="12">12</option>'
                + '</select>'
                + '</div>'
                + '</div>'
                + '<div class="col-md-4 col-sm-4">'
                + '<div class="form-group form-group1">'
                + '<label>Child 3</label>'
                + '<select id="select-country" class="demo-default select-country" placeholder="Select">'
                + '<option value="1">1</option>'
                + '<option value="2">2</option>'
                + '<option value="3">3</option>'
                + '<option value="4">4</option>'
                + '<option value="5">5</option>'
                + '<option value="6">6</option>'
                + '<option value="7">7</option>'
                + '<option value="8">8</option>'
                + '<option value="9">9</option>'
                + '<option value="10">10</option>'
                + '<option value="11">11</option>'
                + '<option value="12">12</option>'
                + '</select>'
                + '</div>'
                + '</div>';
    });
    $('#select-room').change(function () {
        $('#starratingdiv').empty();
        var rooms = $('#select-room').val();

        var j = 1;
        for (var i = 0; i < rooms; i++) {

            var html = '<div class="col-md-12" id="room_details">'
                    + '<div class="col-md-2 col-sm-2">'
                    + '<div class="form-group form-group1"><label style="line-height: 34px;"><strong>Room ' + j + '</strong></label></div>'
                    + '</div>'
                    + '<div class="col-md-2 col-sm-2">'
                    + '<div class="form-group form-group1">'
                    + '<label>Adults <span class="graytxt">(18+)</span></label>'
                    + '<select id="selectnoOfAdults' + j + '" name="noOfAdults' + j + '" class="demo-default select-country" placeholder="Select">'
                    + '<option value="0">0</option>'
                    + '<option value="1">1</option>'
                    + '<option value="2">2</option>'
                    + '<option value="3">3</option>'
                    + '<option value="4">4</option>'
                    + '</select>   '
                    + '</div></div>'
                    + '<div class="col-md-2 col-sm-2">'
                    + '<div class="form-group form-group1">'
                    + '<label>Children <span class="graytxt">(0-12 )</span></label>'
                    + '<select id="select-child' + j + '" name="select-noOfChild' + j + '" class="demo-default selectchild" onChange="getSelectChild(this.id);" placeholder="Select">'
                    + '<option value="0">0</option>'
                    + '<option value="1">1</option>'
                    + '<option value="2">2</option>'
                    + '<option value="3">3</option>'
                    + '</select>           </div>    </div>'
                    + '<div class="col-md-6 childcls col-sm-6 no-padding" id="chidlediv' + j + '">'

                    + '</div>'
                    + '</div>';
            // $('#starratingdiv').append('');
//               room_details_old
//               $( "#room_details_old" ).replaceWith(html);
            $('#starratingdiv').append(html);
            j++;
        }
    });

 $('#select-roomRem').change(function () {
    
        $('#starratingdivRem').empty();
        var rooms = $('#select-roomRem').val();

        var j = 1;
        for (var i = 0; i < rooms; i++) {

            var htmlrem = '<div class="col-md-12" id="room_detailsRem">'
                    + '<div class="col-md-2 col-sm-2">'
                    + '<div class="form-group form-group1"><label style="line-height: 34px;"><strong>RoomRem ' + j + '</strong></label></div>'
                    + '</div>'
                    + '<div class="col-md-2 col-sm-2">'
                    + '<div class="form-group form-group1">'
                    + '<label>Adults <span class="graytxt">(18+)</span></label>'
                    + '<select id="select-country" name="noOfAdultsRem' + j + '" class="demo-default select-country" placeholder="Select">'
                    + '<option value="0">0</option>'
                    + '<option value="1">1</option>'
                    + '<option value="2">2</option>'
                    + '<option value="3">3</option>'
                    + '<option value="4">4</option>'
                    + '</select>   '
                    + '</div></div>'
                    + '<div class="col-md-2 col-sm-2">'
                    + '<div class="form-group form-group1">'
                    + '<label>Children <span class="graytxt">(0-12 )</span></label>'
                    + '<select id="select-childRem' + j + '" name="select-noOfChildRem' + j + '" class="demo-default selectchildRem" onChange="getSelectChildRem(this.id);" placeholder="Select">'
                    + '<option value="0">0</option>'
                    + '<option value="1">1</option>'
                    + '<option value="2">2</option>'
                    + '<option value="3">3</option>'
                    + '</select>           </div>    </div>'
                    + '<div class="col-md-6 childcls col-sm-6 no-padding" id="chidledivRem' + j + '">'
                    + '</div>'
                    + '</div>';
            // $('#starratingdiv').append('');
//               room_details_old
//               $( "#room_details_old" ).replaceWith(html);
            $('#starratingdivRem').append(htmlrem);
            j++;
        }
    });

});

$(function () {
    $("#chekInDate").datepicker({
        minDate: 0});

});
$(function () {
    $("#chekOutDate").datepicker({
        minDate: +1});

});
function setdateforcheckout() {
$("#chekOutDate").datepicker({
minDate: 0,
onSelect: function(date){
var date2 = $('#chekInDate').datepicker('getDate');
date2.setDate(date2.getDate()+181);
$('#chekOutDate').datepicker('setDate', date2);
}
});

}



function getTotalDays() {
   
    if ($("#chekInDate").val() == '') {
        $("#chekOutDate").val('');
        return false;
    } else {
        var date1 = new Date($("#chekInDate").val());
        var date2 = new Date($("#chekOutDate").val());
        var diffDays = parseInt((date2 - date1) / (1000 * 60 * 60 * 24));
        $("#nights").val(parseInt(diffDays) );
    }
}
function updateNoOfDays(days) {
    var todate = $("#chekInDate").val();
    var d = new Date(todate);
    d.setDate(d.getDate() + parseInt(days));
    var date = d.getDate();
    var month = d.getMonth() + 1;
    var year = d.getFullYear();
    if (month < 10)
        month = "0" + month;
    if (date < 10)
        date = "0" + date;
    var newFormatedDate = month + '/' + date + '/' + year;
    $("#chekOutDate").val(newFormatedDate);
}
$(function () {
    $("#chekInDateRem").datepicker({
        minDate: 0});

});
$(function () {
    $("#chekOutDateRem").datepicker({
        minDate: 0});
});
function getTotalDaysRem() {
    if ($("#chekInDateRem").val() == '') {
        $("#chekOutDateRem").val('');
        return false;
    } else {
        var date1 = new Date($("#chekInDateRem").val());
        var date2 = new Date($("#chekOutDateRem").val());
        var diffDays = parseInt((date2 - date1) / (1000 * 60 * 60 * 24));
        $("#nightsRem").val(parseInt(diffDays) + 1);
    }
}
function updateNoOfDaysRem(days) {
    var todate = $("#chekInDateRem").val();
    var d = new Date(todate);
    d.setDate(d.getDate() + parseInt(days));
    var date = d.getDate();
    var month = d.getMonth() + 1;
    var year = d.getFullYear();
    if (month < 10)
        month = "0" + month;
    if (date < 10)
        date = "0" + date;
    var newFormatedDate = month + '/' + date + '/' + year;
    $("#chekOutDateRem").val(newFormatedDate);
}
function bookNow(){
    //window.location.replace("/buyhotel/hotel-guest-detail");
}

function payFullTotalAmount(){
    //window.location.replace("/buyhotel/net-banking-hotel");
}
function getSearchAccomoIdFromAPI(resultIndex, hotelCode,userip,tokenid,traceid){
   
   var tokenId=$('#tokenId').val();
   var noOfRooms=$('#noOfRooms').val();
   
    $.ajax({
        url: '/buyhotel/get-hotel-detail-api?resultIndex='+resultIndex+'&hotelCode='+hotelCode+'&userip='+userip+'&tokenid='+tokenid+'&traceid='+traceid,
        //data: { resultIndex :resultIndex },
        type: 'POST',
        async:false,
        dataType: 'html',
        beforeSend: function () {
            $(".modalloader").show();
        },
        success: function (response) {
            console.log(response);
            var apiRDetailArr=$.parseJSON(response);
           if(apiRDetailArr['hotelInfo']['HotelInfoResult']['HotelDetails']){
               $('#hotlname').empty().html(apiRDetailArr['hotelInfo']['HotelInfoResult']['HotelDetails']['HotelName']); 
            }else{
               var errorMessage=apiRDetailArr['hotelRoomD']['GetHotelRoomResult']['Error']['ErrorMessage'];
               $('#hotlname').empty().html(errorMessage);
            }
            var rooms=apiRDetailArr['hotelRoomD']['GetHotelRoomResult']['HotelRoomsDetails'].length;
            var aminitHtml='<ul class="roomactivities">';
            $('#overviewul').empty();
            $('#amenitiesa').empty();
              aminitHtml+='<li><span>'+apiRDetailArr['hotelRoomD']['GetHotelRoomResult']['HotelRoomsDetails'][0]['Amenities']+'</li>'
              aminitHtml+='</ul>';
                $('#amenitiesa').html(aminitHtml);
                $(".modalloader").hide();
                $('#overviewul').empty();
                var hotel = 'Hotel '+apiRDetailArr['hotelInfo']['HotelInfoResult']['HotelDetails']['HotelName']+ ' ' + apiRDetailArr['hotelInfo']['HotelInfoResult']['HotelDetails']['Address'];
                var mapHotel1 = hotel.replace(" ", "+");
                var mapHotel = hotel.replace(",", "+");
            $('#map').html( '<iframe src="http://maps.google.com/maps?q='+mapHotel+'&loc:'+apiRDetailArr['hotelInfo']['HotelInfoResult']['HotelDetails']['HotelFacilities']['Latitude']+'+' + apiRDetailArr['hotelInfo']['HotelInfoResult']['HotelDetails']['HotelFacilities']['Longitude']+'&z=9&output=embed" width="600" height="450"></iframe>');
            
            if(!apiRDetailArr['hotelInfo']['HotelInfoResult']['HotelDetails']['Description']){
             $('#overviewul').empty();
            } else{
            $('#overviewul').html(''+apiRDetailArr['hotelInfo']['HotelInfoResult']['HotelDetails']['Description'].replace("HotelDescription:",'')+'.<br>');
//            $('#overviewul').append('<li>'+myArr['accomoDetail']['0']['Details']+'.</li><br>');
            }
          
            
            
            
            
            $('#hotladd').empty().html(apiRDetailArr['hotelInfo']['HotelInfoResult']['HotelDetails']['Address']);
               var ratingCount=apiRDetailArr['hotelInfo']['HotelInfoResult']['HotelDetails']['StarRating'];
               var ratingCounthalf=apiRDetailArr['hotelInfo']['HotelInfoResult']['HotelDetails']['StarRating'];
               var htmRatingi='';
               var m=0;
               for(var d=0; d < ratingCount; d++){
                    htmRatingi +='<span class="fa fa-star text-danger"></span>'; 
                    m++;
                }
                if(ratingCounthalf==.50){
                    m++;
                     htmRatingi +='<span class="fa fa-star-half-full text-danger"></span>'; 
                }
                for(var h=m; h<5;h++){
                    htmRatingi +='<span class="fa fa-star-o text-danger"></span>'; 
                }
                $('#hotlrating').empty().html(htmRatingi);

               
            $('#grid').empty();
            var htmlImg='';  var j=0;
            for (var i=0; i<=2; i++){
             
              htmlImg+='<div class="col-md-4 col-sm-4 col-xs-12 m-col-md-4 " ><img src="'+apiRDetailArr['hotelInfo']['HotelInfoResult']['HotelDetails']['Images'][j]+'" alt="image" width="180" height="140"></div>';
            
             j++;
            }
             $('#grid').append(htmlImg);
            var l=0;
            $('#photogrid').empty();
             for (var k=0; k<=apiRDetailArr['hotelInfo']['HotelInfoResult']['HotelDetails']['Images'].length; k++){
             if(!apiRDetailArr['hotelInfo']['HotelInfoResult']['HotelDetails']['Images'][l]){
                    
                }else{  
                    if(l<=7){
             var htmlImg2='<div class="col-md-3 col-sm-4 col-xs-12 m-col-md-3 "><img src="'+apiRDetailArr['hotelInfo']['HotelInfoResult']['HotelDetails']['Images'][l]+'" alt="image"  width="180" height="140" style="margin-top:4%"></div>';
             $('#photogrid').append(htmlImg2);
             
                }
         l++;
            }
             }


                   $('#roomrates').empty();
                  var resRooms=apiRDetailArr['hotelRoomD']['GetHotelRoomResult']['HotelRoomsDetails'].length; 
                 var f=0;
                
                //$.each(apiRDetailArr['hotelRoomD']['GetHotelRoomResult']['HotelRoomsDetails'] , function (index, value){
                //alert(apiRDetailArr['hotelRoomD']['GetHotelRoomResult']['HotelRoomsDetails'][f]['RoomTypeName']);
               
                var htmlImg3='<div class="col-md-12 margin-top-bot border-bottom"><h4 class="orange" id="roomTitles">'+apiRDetailArr['hotelRoomD']['GetHotelRoomResult']['HotelRoomsDetails'][f]['RoomTypeName']+'</h4>'
                           +'<div class="col-md-12 no-padding"><div class="col-sm-2 no-padding">'
                          +'<img src="'+apiRDetailArr['hotelInfo']['HotelInfoResult']['HotelDetails']['Images'][f]+'" width="100%" ></div><div class="col-sm-10" id="roomsRatediv1">';
               
               
               // for(var c=0;c<value.length;c++){
                     
                      //  var res=getdataFromController($.trim(value[a]['Amenities']));

                        var resAmity=apiRDetailArr['hotelRoomD']['GetHotelRoomResult']['HotelRoomsDetails'][f]['Amenities'].length; 
                       
                        var htmlli='';    
                        for(var z=0;z<resAmity; z++){
                                      
                                 htmlli+=' <li><span>'+apiRDetailArr['hotelRoomD']['GetHotelRoomResult']['HotelRoomsDetails'][f]['Amenities'][z] +'</span></li>'
                                         
                            }
                                 var c=0    ;  
              // for(var c=0; c<=resRooms;c++){    
              $('#rate').html(apiRDetailArr['hotelRoomD']['GetHotelRoomResult']['HotelRoomsDetails'][0]['Price']['RoomPrice']);
               $.each(apiRDetailArr['hotelRoomD']['GetHotelRoomResult']['HotelRoomsDetails'] , function (index, value){
               // $('#rate').html(value['Price']['RoomPrice']);
             htmlImg3 +='<div class="alert alert-info col-md-12"><div class="col-sm-9 no-padding"><strong>'+value['RoomTypeName']+'</strong><br />'
              +'<span class="graytxt">'+value['CancellationPolicy']+'</span><br />'
              +'<a href="javascript:void(0);" class="small orange sdetail" id="sdetail_'+f+'" onclick="showdetail(this.id);">&raquo; Show Details</a><a href="javascript:void(0);" class="small orange hdetail"  id="hdetail_'+f+'" style="display:none;" onclick="hidedetail(this.id);">&raquo; Hide Details</a>'
              +'</div>'
              +'<div class="col-sm-3 no-padding text-center">'
              +'<h4 class="no-margin green"><span class="fa fa-rupee"></span>'+value['Price']['RoomPrice']+'</h4>'
                                   +'<div class="clear">&nbsp;</div>'
                                   +'<button class="btn1 btn btn-danger btn-danger"  data-dismiss="modal" id="'+value['RoomIndex']+'">Book Now</button></div> <div class="col-md-12 whbg margin-top-bot no-padding policyid" id="policyid_'+f+'" style="display:none;">'
                                   +'<div class="col-md-6 margin-top-bot">'
                                   +'<strong>What"s included</strong>'
                                   +'<ul class="roomactivities">'+htmlli
                                   +'</ul></div><div class="col-md-6 margin-top-bot"><strong>Cancellation policy</strong><br />'
                                   +'<span class="graytxt"><strong>Note:</strong> If you cancel within 7 day(s) before checkin, you will incur 100.0% of your total stay.</span>'
                                   +'</div></div><div class="clear"></div></div>';
                          

            
            c++; f++;
         });
          
             
          $('#roomrates').append(htmlImg3); 
           var htmlclos='</div>';
            $('#roomrates').append(htmlclos);
           
  $('.btn1').click(function(){
         var roomindexID = $(this).attr("id");
         var blkRoomRes = bookHotel(apiRDetailArr,roomindexID,tokenId,resultIndex,noOfRooms);
       // $('#testDivId').html(blkRoomRes);
       
        window.location.replace("/buyhotel/hotel-guest-detail");
        //console.log(blkRoomRes);
         
       
    });

}
    });
    
    }
    function bookHotel(roomDetail,roomindexID,tokenId,resultIndex,noOfRooms){
    // console.log(roomDetail);   
        var resultBookRoom;
        $.ajax({
        url: '/buyhotel/block-room-details?tokenId='+tokenId+"&resultIndex="+resultIndex+"&noOfRooms="+noOfRooms+'&roomindexID='+roomindexID,
        data : { roomDetail : roomDetail },
        type: 'POST',
        async:false,
        dataType: 'html',
        beforeSend: function () {
          //  $(".modalloader").show();
        },
        success: function (response) {
           resultBookRoom=response;
          }
         
        });
        return resultBookRoom;
    }
    
     $(document).ready(function(){
    $('#reviewandAgree').on('ifChecked ifUnchecked', function(event) {
       
        if (event.type == 'ifChecked') {
            
            $('#reviewandAgree').val('checked');
            $('#confRev').hide();
        } else {
            $('#confRev').show();
             $('#reviewandAgree').val('unchecked');
        }
    });
      
//     $('#reviewandAgree').on('ifUnchecked', function (event) {
//                   var checkVal = 0;
//                   $('#confRev').show();
//      });
//      $('#reviewandAgree').on('ifChecked', function (event) {
//          var checkVal = 1;
//          bookRoom();
//      });
      
  });
  
  
function bookRoom(){     
var reviewandAgree = $('#reviewandAgree').val();
//alert(reviewandAgree);
if(reviewandAgree == 'unchecked'){
    $('#confRev').show();
} else if(reviewandAgree != 'unchecked'){ 
       $.ajax({
        url: '/buyhotel/book-room',
       // data : { roomDetail : roomDetail },
        type: 'POST',
        // async:false,
        dataType: 'json',
        beforeSend: function () {
          //  $(".modalloader").show();
        },
        success: function (response) {
            console.log(response);
            //alert(response);
            //$('#responseREs').html(response);
             
           }
         
        });
      window.location.replace("/buyhotel/make-payment");
    }
    }
 
 