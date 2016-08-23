<?php 
/**
 * Class Hotel
 *
 * @name		Buy Hotel
 * @author		Harpreet
 * @version 	1.0
 * @copyright 	Catabatic India Pvt Ltd
 * Handle Hotel Related function
 *
 */
class BuyhotelController extends Zend_Controller_Action {
    private $intLoggedinUserId = '';
    private $intLoggedinUserGroupSysId = '';
    private $intLoggedinUserAgencySysId = '';
    private $intLoggedinUserTrxCurrency = '';
    private $InfoSourceSysId = '';
    
    public $baseUrl = '';
    public $url = 'http://api.tektravels.com/SharedServices/SharedData.svc/rest/Authenticate';
    public $urlHotel = 'http://api.tektravels.com/BookingEngineService_Hotel/hotelservice.svc/rest/GetHotelResult/';
    public $userIp = '180.151.8.18';
    public $user = "tripshows";
    public $pass = "tripshows@123";
    public $clientID = 'apiintegration';
    public function init() {
        $request = Zend_Controller_Front::getInstance()->getRequest();
        $this->baseUrl = $request->getScheme() . '://' . $request->getHttpHost();
        $this->view->headScript()->appendFile($this->baseUrl . '/public/assets/js/jquery-ui.js');
        $this->view->headScript()->appendFile($this->baseUrl . '/public/assets/js/owl.carousel.min.js');
        $this->view->headScript()->appendFile($this->baseUrl . '/public/assets/js/owl.carousel.js');
        $this->view->headLink()->appendStylesheet($this->baseUrl . '/public/assets/css/autosuggest-jquery-ui.css');
        $this->view->headLink()->appendStylesheet($this->baseUrl . '/public/assets/css/plugins/owl.carousel.css');
//        $this->intLoggedinUserId = 1;
//        $this->intLoggedinUserGroupSysId = 1;
//        $this->intLoggedinUserAgencySysId = 1;
        $this->view->headScript()->appendFile($this->baseUrl . '/public/assets/js/jquery-ui.js');
//        $this->view->headScript()->appendFile($this->baseUrl . '/public/assets/js/owl.carousel.min.js');
//        $this->view->headScript()->appendFile($this->baseUrl . '/public/assets/js/owl.carousel.js');
//        $this->view->headLink()->appendStylesheet($this->baseUrl . '/public/assets/css/autosuggest-jquery-ui.css');
//        $this->view->headLink()->appendStylesheet($this->baseUrl . '/public/assets/css/plugins/owl.carousel.css');

        
        $sessionLogin_user = new Zend_Session_Namespace('sessionLogin_user');
        
        $this->intLoggedinUserId            = $sessionLogin_user->intLoggedinUserId;
        $this->intLoggedinUserGroupSysId    = $sessionLogin_user->intLoggedinUserGroupSysId;
        $this->intLoggedinUserAgencySysId   = $sessionLogin_user->intLoggedinUserAgencySysId;
        $this->intLoggedinUserTrxCurrency   = $sessionLogin_user->intLoggedinUserTrxCurrency;
         
        if(!empty($this->intLoggedinUserAgencySysId)) {
            $this->InfoSourceSysId = '2'; /* Information Source is Agent */
        }
        
        
        
        if(empty($this->intLoggedinUserId)) {
            $this->_redirect('/login/');
        }
        
        
    }
    public function indexAction() {
       
//     //   $this->_helper->layout->disableLayout();
//        $this->view->headScript()->appendFile($this->baseUrl . '/public/js/buyhotel/buyhotel.js');
//        $this->view->headLink()->appendStylesheet($this->baseUrl . '/public/assets/css/plugins/hotel.css');
//        $this->view->headLink()->appendStylesheet($this->baseUrl . '/public/assets/css/plugins/shuffle.css');
        $this->view->country = $this->getCountry();
        // $this->view->roomAminity = $this->getRoomAminitesMask();
        //$this->view->hoteaminity=$this->getHotlAmitCate();
        $objHotel = new Travel_Model_TblHotel();
                $arrResponse = $objHotel->getHotelAminityAutoSuggest();
                $this->view->AminityArr = $arrResponse;
               
    }
 
     public function hotelReviewBookingAction() {

         
         
    }
    public function netBankingHotelAction() {

         
         
    }
    public function getCountry() {
        $objCountry = new Travel_Model_TblCountry();
        return $country = $objCountry->getCountryList();
    }
    public function autosuggestAction() {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
//       print_r($this->getRequest()->getParam("term"));
//       exit;
        try{
            $arrResponse = array();
            if ($this->getRequest()->getParam("term")) {
                $term = $this->getRequest()->getParam("term");
                $objHotel = new Travel_Model_TblHotel();
                $arrResponse = $objHotel->getBuyHotelAutoSuggest($term);
                // print_r($arrResponse);die;
            }
            echo json_encode($arrResponse);
            exit;
            }
        catch( Exception $e) {
                $response = array('success' => false, 'msg' => $e->getMessage() );
                echo json_encode($response);
                exit;
            }
    }
        public function autosuggestAminityAction() {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
//       print_r($this->getRequest()->getParam("term"));
//       exit;
        try{
            $arrResponse = array();
            if ($this->getRequest()->getParam("term")) {
                $term = $this->getRequest()->getParam("term");
                $objHotel = new Travel_Model_TblHotel();
                $arrResponse = $objHotel->getHotelAminityAutoSuggest($term);
                // print_r($arrResponse);die;
            }
            echo json_encode($arrResponse);
            exit;
            }
        catch( Exception $e) {
                $response = array('success' => false, 'msg' => $e->getMessage() );
                echo json_encode($response);
                exit;
            }
    }
    public function getRoomAminitesMaskAction() {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
        try{
            $aminity = $this->getRequest()->getParam('amenity');
            if ($this->getRequest()->isXmlHttpRequest()) {
            $objRoomAminities = new Travel_Model_TblAmenities();
            $roomAminity = $objRoomAminities->getHotlRoomAmenities();
            $aminiti = str_split($aminity);
            unset($aminiti[0]);
            $aminitiesArray = array_values($aminiti);
            $count = min(count($roomAminity), count($aminitiesArray));
            $amitnity = array_combine($roomAminity, $aminitiesArray);
            //$selAminity = array();
            $i=0;
            foreach ($amitnity as $key => $value) {
                if ($value == 1) {
                    $selAminity[] = array($key);
                }
                $i++; 

                }
                $res=array('rAmit'=>$selAminity);
                echo json_encode($res);
            }
        }
        catch( Exception $e) 
        {
            $response = array('success' => false, 'msg' => $e->getMessage() );
            echo json_encode($response);
            exit;
         }
    }
    public function getAminitiesMask($aminiteStr) { /*     * ***Aminities with masterAminities**** */
        try{
            $objHotelAminities = new Travel_Model_TblAmenities();
            $aminityTble = $objHotelAminities->getAccomodationAmenitiesList();
            $aminitiesArra = str_split($aminiteStr);
            unset($aminitiesArra[0]);
            $aminitiesArray = array_values($aminitiesArra);
            foreach ($aminityTble as $val) {
                $title[] = trim($val['Title']);
            }
            $count = min(count($title), count($aminitiesArray));
            $aminitiesArray1 = array_combine(array_slice($title, 0, $count), array_slice($aminitiesArray, 0, $count));
            $imgArr = array();
//            if (trim($aminitiesArray1['room service']) == '1') {
//                $image = 'incIcon7';
//                array_push($imgArr, $image);
//            }
//            if (trim($aminitiesArray1['free Wi-Fi in all rooms']) == '1') {
//                $image = 'incIcon1';
//                array_push($imgArr, $image);
//            }
//            if (trim($aminitiesArray1['bar']) == '1') {
//                $image = 'incIcon2';
//                array_push($imgArr, $image);
//            }
//            if (trim($aminitiesArray1['restaurant']) == '1') {
//                $image = 'incIcon5';
//                array_push($imgArr, $image);
//            }
//            if (trim($aminitiesArray1['Air conditioning']) == '1') {
//                $image = 'incIcon3';
//                array_push($imgArr, $image);
//            }
//            if (trim($aminitiesArray1['business center']) == '1') {
//                $image = 'incIcon8';
//                array_push($imgArr, $image);
//            }
//            if (trim($aminitiesArray1['coffee shop']) == '1') {
//                $image = 'incIcon6';
//                array_push($imgArr, $image);
//            }
//    //        if($aminitiesArray1['Full board with drinks']=='1'){
//    //        $image='public/tinymce/skins/lightgray/img/drink.gif';
//    //        }
//            if (trim($aminitiesArray1['Gym']) == '1') {
//                $image = 'incIcon10';
//                array_push($imgArr, $image);
//            }
//            if (trim($aminitiesArray1['Internet access']) == '1') {
//                $image = 'incIcon11';
//                array_push($imgArr, $image);
//            }
//            if (trim($aminitiesArray1['Indoor pool']) == '1') {
//                $image = 'incIcon9';
//                array_push($imgArr, $image);
//            }
//            if (trim($aminitiesArray1['24-hour reception']) == '1') {
//                $image = 'incIcon4';
//                array_push($imgArr, $image);
//            }
            return $imgArr;
        }  
        catch( Exception $e) 
        {
            $response = array('success' => false, 'msg' => $e->getMessage() );
            echo json_encode($response);
            exit;
        }
    }
    public function getHotlAmitCateAction(){
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
        try{
            if ($this->_request->isXmlHttpRequest()) {
            $amenityId = $this->getRequest()->getParam('amenity');
            //$this->view->AccomSysId = base64_decode($AccomSysId) ;
            $objAminity = new Travel_Model_TblAmenities();
            $hotelAminity=$objAminity->getHotelAminitiesWithCategory();
            $aminitiesArra = str_split($amenityId);
            unset($aminitiesArra[0]);
            $i=1;
            $category = array();
            foreach ($hotelAminity as $amit){
                $title[]=$amit['Title'];
                if($aminitiesArra[$i]==1){
                    $amity=trim($amit['Category']);
                    $amtTit=trim($amit['Title']);

                $category[$amity][]['title']=$amtTit;

                }
                $i++;
            }
            $res=array('HotelAmit'=>$category);
           // echo json_encode(utf8_encode($category));
         //echo json_encode(toArray($category));
           //print_r($category);
            $htmlAminityHotel='';

           foreach($category as $key=> $valC){
              // echo $valC[$key];
                    $htmlAminityHotel.='<strong class="col-md-12 no-padding">'.$key.'</strong>
                                    <ul class="roomactivities">';
                                    $i=0;

                                    foreach($valC as $keys){

                                        if($i<10 ){
                                            if(!empty($keys['title'])){
                                       $htmlAminityHotel.='<li><span>'.$keys['title'].'</span></li>';
                                            }
                                        }
                                     $i++;
                                    }

                               $htmlAminityHotel.='</ul>';
           }


          echo $htmlAminityHotel;
            }
        }
        catch( Exception $e)
        {
            $response = array('success' => false, 'msg' => $e->getMessage() );
            echo json_encode($response);
            exit;
         }
    }
    public function getImageAccomodationDB($accomoID) {
        $objImgAccomo = new Travel_Model_TblAccomoImg();
        $img = $objImgAccomo->getAccomoImage($accomoID);
        foreach ($img as $val) {
            $imgDetail = $val['Details'];
        }
        return $imgDetail;
    }
    public function getAccomodationDetailAction() {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
        try{
            if ($this->_request->isXmlHttpRequest()) {
                $AccomSysId = $this->getRequest()->getParam('id');
                //$this->view->AccomSysId = base64_decode($AccomSysId) ;
            }
            $objAccomo = new Travel_Model_TblHotel();
            $objAccImg = new Travel_Model_TblAccomoImg();
            $objRomms  = new Travel_Model_TblAccomoRooms();

            $accomoDetail = $objAccomo->getAccomoWherID($AccomSysId);

            $accomoTitle = $objAccomo->getAccomodation($AccomSysId);
            $accImg = $objAccImg->getAccomoImage($AccomSysId);
            $accRooms = $objRomms->getAccomoRoomDetail($AccomSysId);
    //        $this->getHotlAmitCate();
    //        $aminities=$this->getHotlAmitCate();
            $vaTit=array();
            foreach ($accRooms as $valRTitle){ 

                $vaTit[$valRTitle['RoomTitle']][]=array('title'=>$valRTitle['Title'],'cost'=>$valRTitle['DOccupCost'],'AminitiesMask'=>$valRTitle['AminitiesMask']);

            }
            $accomoMap = $objRomms->getAccomoMap($AccomSysId);
           // $roomAminite = $this->getRoomAminitesMask($aminity);
            foreach ($accomoMap as $map) {
                $hotel = 'Hotel ' . $map['Title'] . ' ' . trim($map['Address']);
                $mapHotel = str_replace(" ", "+", $hotel);
                $mapRes = '<iframe src="http://maps.google.com/maps?q=' . $mapHotel . '&loc:' . trim($map['GeoLat']) . '+' . trim($map['GeoLong']) . '&z=9&output=embed" width="600" height="450"></iframe>';
                $mapHotelRes = str_replace("++", "+", $mapRes);
                $mapHotelRs = str_replace(",+,+", "+", $mapHotelRes);
            }
            $res = array('accomoDetail' => $accomoDetail, 'accImg' => $accImg, 'accomoTitle' => $accomoTitle, 'accRooms' => $accRooms, 'mapHotelRs' => $mapHotelRs,'vaTit'=>$vaTit);
          echo json_encode($res);
    }
   
    catch( Exception $e) 
        {
            $response = array('success' => false, 'msg' => $e->getMessage() );
            echo json_encode($response);
            exit;
         }
    }
//     public function sendDataForSearchModifyAction() {
//        /* Disable Layout */
//        $this->_helper->layout->disableLayout();
//        try{
//        if ($this->_request->isXmlHttpRequest()) {
//            $dataSearch = $this->getRequest()->getPost();
//           
//            $objHotel = new Travel_Model_TblHotel();
//            //            $con = " AND TB_IC_Accomdation.CitySysId='" . $dataSearch['hidden_selected_hotel_cityidRem'] . "'"
////                    . "  AND TB_IC_Accomdation.Rating <>'" . $dataSearch['selectStarRatingRem'] . "' "
////                    . " AND TB_MP_Inventory_Accom.FromDate<='" . date('Y-m-d', strtotime($dataSearch['chekInDateRem'])) . ' 00:00:00.000' . "' "
////                    . " AND TB_MP_Inventory_Accom.ToDate>='" . date('Y-m-d', strtotime($dataSearch['chekOutDateRem'])) . ' 00:00:00.000' . "'";
//            $con = " WHERE TB_MP_Inventory_Accom.FromDate<='" . date('Y-m-d', strtotime($dataSearch['chekInDateRem'])) . ' 00:00:00.000' . "' "
//                    ." AND TB_MP_Inventory_Accom.ToDate>='" . date('Y-m-d', strtotime($dataSearch['chekOutDateRem'])) . ' 00:00:00.000' . "' AND TB_MP_Inventory_Accom.DOccupCost!='0.00' GROUP BY TB_MP_Inventory_Accom.XRefAccoSysId)"
//                    ." AND t1.IsActive = '1' AND t1.CitySysId='" . $dataSearch['hidden_selected_hotel_cityidRem'] . "' "
//                    ." AND t1.Rating <>'" . $dataSearch['selectStarRatingRem'] . "'";
//
//            $getdata = $objHotel->getAccomodationHotelList($con);
//           
//            //*************Call API**********///////////////
//            $userIp = $_SERVER['REMOTE_ADDR'];
//            $objApi = new Travel_Model_ApiIntegration();
//            $tokenId = $objApi->apiAuthentication($this->clientID, $this->url, $this->user, $this->pass, $this->userIp);
//            $urlHotel = 'http://api.tektravels.com/BookingEngineService_Hotel/hotelservice.svc/rest/GetHotelResult/';
//            
//            if (isset($dataSearch['select-childAgeRem1'])) {
//                $childAge = (int) $dataSearch['select-childAgeRem1'];
//            } else {
//                $childAge = null;
//            }
//            $datah = array(
//                "CheckInDate" => date('d/m/Y', strtotime($dataSearch['chekInDateRem'])),
//                "NoOfNights" => (int) $dataSearch['nightsRem'],
//                "CountryCode" => "IN",
//                "CityId" => $dataSearch['hidden_selected_hotel_idRem'],
//                "ResultCount" => null,
//                "PreferredCurrency" => "INR",
//                "GuestNationality" => "IN",
//                "NoOfRooms" => (int) $dataSearch['selectRoomRem'],
//                "RoomGuests" => array(array("NoOfAdults" => (int) $dataSearch['noOfAdultsRem1'], "NoOfChild" => (int) $dataSearch['select-noOfChildRem1'], "ChildAge" => $childAge)),
//                'PreferredHotel' => '',
//                'MaxRating' => 5,
//                'MinRating' => 0,
//                'ReviewScore' => null,
//                'IsNearBySearchAllowed' => 'false',
//                'EndUserIp' => $userIp,
//                'TokenId' => $tokenId
//            );
//           // $apiRes = '';
//          // $apiRes = $objApi->apiHotelSearch($urlHotel, $userIp, $datah);
//           
//            $error='';
//            if(!$apiRes=''){
//           
//            }else{
//                 if($apiRes['HotelSearchResult']['Error']['ErrorCode']!=0){
//            $error.=$apiRes['HotelSearchResult']['Error']['ErrorMessage'];
//            $error.=' From API';
//            }
//            }
//
//         
//
//            //*********end of calling API*******/
//            
//            $html1 = $error.'<br/><div class="col-md-9">'
//                    . '<ul class="nav nav-tabs icon-tab">
//        <li class="active"><a class="bg-warning" href="#allairlines" data-toggle="tab"> <span class="fa fa-plane"></span> All Hotels</a></li>
//        <li class=""><a class="bg-warning" href="#tbdeals" data-toggle="tab"> <span class="fa fa-plane"></span> TB Deals</a></li>
//        <button style="display:none;" class="btn ls-green-btn btn-round btn-sm pull-right sendmail" data-toggle="modal" data-target="#myModa1Email"><i class="fa fa-envelope-o"></i> Send Email</button>
//    </ul>
//    <div class="col-md-12 no-padding">
//<div class="tab-content">  
//<div id="allairlines" class="tab-pane fade in active no-padding">';
//            $i = 0;
//            $htmlDb = '';
//            foreach ($getdata as $val) {
//                  $ratingCount=substr($val['Rating'],0,1);
//            $ratingCounthalf=substr($val['Rating'],1,3);
//            
//            $htmRatingdb='';
//            $m=0;
//            for($d=0; $d < $ratingCount; $d++){
//                
//                $htmRatingdb .='<span class="fa fa-star text-danger"></span>'; 
//                $m++;
//            }
//            if($ratingCounthalf==.50){
//                $m++;
//                 $htmRatingdb .='<span class="fa fa-star-half-full text-danger"></span>'; 
//            }
//            for( $h=$m; $h<5;$h++){
//                $htmRatingdb .='<span class="fa fa-star-o text-danger"></span>'; 
//            }
//                $htmlDb.= '<div class="col-md-12 no-padding gallery1-outer">   
//            <div class="gallery-sec ">
//        	<div class="col-sm-3 ">
//			<div class="gallery1 owl-carousel owl-theme owl-loaded">
//			<div class="owl-stage-outer"><div class="owl-stage" style="width: 1424px; transform: translate3d(-356px, 0px, 0px); transition: all 0s ease 0s;"><div class="owl-item cloned" style="width: 178px; margin-right: 0px;"><a class="item" href="#"> 
//                <span>View all 28 Photos</span>
//					<figure><img title="" alt=" " src="' . $this->getImageAccomodationDB($val['AccomSysId']) . '"></figure>	
//				</a></div><div class="owl-item cloned" style="width: 178px; margin-right: 0px;"><a class="item" href="#"> 
//                <span>View all 28 Photos</span>
//					<figure><img title="" alt=" " src="' . $this->getImageAccomodationDB($val['AccomSysId']) . '"></figure>	
//				</a></div><div class="owl-item active" style="width: 178px; margin-right: 0px;"><a class="item" href="#"> 
//                <span>View all 28 Photos</span>
//					<figure><img title="" alt=" " src="' . $this->getImageAccomodationDB($val['AccomSysId']) . '"></figure>	
//                                        
//				</a></div><div class="owl-item" style="width: 178px; margin-right: 0px;"><a class="item" href="#"> 
//                <span>View all 28 Photos</span>
//					<figure><img title="" alt=" " src="assets/images/hotel2.jpg"></figure>	
//				</a></div><div class="owl-item" style="width: 178px; margin-right: 0px;"><a class="item" href="#"> 
//                <span>View all 28 Photos</span>
//					<figure><img title="" alt=" " src="assets/images/hotel2.jpg"></figure>	
//				</a></div><div class="owl-item" style="width: 178px; margin-right: 0px;"><a class="item" href="#"> 
//                <span>View all 28 Photos</span>
//					<figure><img title="" alt=" " src="assets/images/hotel2.jpg"></figure>	
//				</a></div><div class="owl-item cloned" style="width: 178px; margin-right: 0px;"><a class="item" href="#"> 
//                <span>View all 28 Photos</span>
//					<figure><img title="" alt=" " src="assets/images/hotel2.jpg"></figure>	
//				</a></div><div class="owl-item cloned" style="width: 178px; margin-right: 0px;"><a class="item" href="#"> 
//                <span>View all 28 Photos</span>
//					<figure>';
//                $htmlDb.='</a></div></div></div><div class="owl-controls"><div class="owl-nav"><div class="owl-prev" style="">prev</div><div class="owl-next" style="">next</div></div><div style="" class="owl-dots"><div class="owl-dot active"><span></span></div><div class="owl-dot"><span></span></div><div class="owl-dot"><span></span></div><div class="owl-dot"><span></span></div></div></div></div>
//		</div>
//<div class="col-sm-7 ">
//    	<h4>' . $val['HotelName'] . '</h4>
//       
//        <div class="clear"></div>
//        
//        <div style="padding-left:0; padding-right:0;" class="tableBox"><table cellspacing="0" cellpadding="0" border="0" align="center">
//                          <tbody><tr>
//                       
//                            <td width="25%" class="text-left">' . $htmRatingdb. ' </td>
//                          
//                            <td width="75%" class="text-left"><img src="public/assets/images/hotelRate.png" alt="Hotel"> <a target="_blank" class="small btn btn-success  btn-round1" href="javascript:void(0);">reviews</a></td>
//                          </tr>
//                
//                        </tbody></table>
//                        </div>
//        
//        
//        <div class="clear"></div>
//        <div class="tabBarContent">
//                                                	<div class="tabBarContentIn">
//                                                 
//      <div class="contentMainBox">';
//                foreach ($this->getAminitiesMask($val['AccoAminitiesMask']) as $imgDiv) {
//                    $htmlDb.='<div title="" data-placement="top" data-toggle="tooltip" class="' . $imgDiv . ' tooltipLink" data-original-title=""></div>';
//                }
//                $htmlDb.='</div>                                                  
//                                                              <a data-target="#myModa1vewDetail" onclick="getSearchAccomoId(' . $val['AccomSysId'] . ');" data-toggle="modal" class="small btn btn-primarygray  btn-round1" href="javascript:void(0);"> <span class="fa fa-eye"></span> View details</a> -  <a data-target="#myModa1vewDetail" data-toggle="modal" class=" btn btn-primarygray  btn-sm btn-round1" onclick="getSearchAccomoId(' . $val['AccomSysId'] . ');" href="javascript:void(0);"><span class="fa fa-image"></span> Photos</a>  -  <a onclick="getSearchAccomoId(' . $val['AccomSysId'] . ');" data-target="#myModa1vewDetail" data-toggle="modal" class="small btn btn-primarygray  btn-round1" href="javascript:void(0);"> <span class="fa fa-location-arrow"></span> Location</a>  -  <a data-target="#myModa1vewDetail" data-toggle="modal" class="small btn btn-primarygray  btn-round1" href="javascript:void(0);" onclick="getSearchAccomoId(' . $val['AccomSysId'] . ');"> <span class="fa fa-star"></span> Reviews</a> 
//                                                    <div class="clear"></div></div>
//                                                </div>
//                                                
//                        <div class="clear"></div>                        
//                                                
//    
//   </div>
//   
//   <div class="col-sm-2  no-padding">
//    	<h4 class="totalpricebl"><span class="fa fa-rupee"></span>' . $val['DOccupCost'] . '</h4> </span>
//<div class="clear">&nbsp;</div>
//<a class=" btn btn-danger" onClick="bookNow()"><strong>Book Now</strong></a> 
//<div style="padding-bottom:10px;" class="clear"></div>
//<a href="javascript:void(0);" class=" btn btn-default" data-toggle="modal" data-target="#myModa1EnquireNow"><strong>Enquire Now</strong></a>
//<label class="checkbox blue inlinebl emailid">
//                        <div class="icheckbox_flat-blue" style="position: relative;"><input type="checkbox" class="icheck-blue" value="option1" id="optionscheckbox1" name="optionscheckbox" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"></ins></div>
//                        Email
//                    </label>
//   </div>
//</div>
//</div>';
//                $i++;
//            }
//            $htmlApi = '';
////**************************Loop For API Data Start****************/  
//            if (isset($apiRes['HotelSearchResult']['HotelResults'])) {
//                foreach ($apiRes['HotelSearchResult']['HotelResults'] as $val) {  //echo '<pre>'; print_r($val); 
//                    
//                   $ratingCount=substr($val['TripAdvisor']['Rating'],0,1);
//                   $ratingCounthalf=substr($val['TripAdvisor']['Rating'],1,3);
//            
//            $htmRatingi='';
//            $m=0;
//            for($d=0; $d < $ratingCount; $d++){
//                
//                $htmRatingi.='<span class="fa fa-star text-danger"></span>'; 
//                $m++;
//            }
//            if($ratingCounthalf==.50){
//                $m++;
//                 $htmRatingi.='<span class="fa fa-star-half-full text-danger"></span>'; 
//            }
//            for( $h=$m; $h<5;$h++){
//                $htmRatingi.='<span class="fa fa-star-o text-danger"></span>'; 
//            }
//            
//        
//                    $htmlApi.= '<div class="col-md-12 no-padding gallery1-outer">   
//            <div class="gallery-sec ">
//        	<div class="col-sm-3 ">
//			<div class="gallery1 owl-carousel owl-theme owl-loaded">
//			<div class="owl-stage-outer"><div class="owl-stage" style="width: 1424px; transform: translate3d(-356px, 0px, 0px); transition: all 0s ease 0s;"><div class="owl-item cloned" style="width: 178px; margin-right: 0px;"><a class="item" href="#"> 
//                <span>View all 28 Photos</span>
//					<figure><img title="" alt=" " src="assets/images/hotel2.jpg"></figure>	
//				</a></div><div class="owl-item cloned" style="width: 178px; margin-right: 0px;"><a class="item" href="#"> 
//                <span>View all 28 Photos</span>
//					<figure><img title="" alt=" " src="assets/images/hotel2.jpg"></figure>	
//				</a></div><div class="owl-item active" style="width: 178px; margin-right: 0px;"><a class="item" href="#"> 
//                <span>View all 28 Photos</span>
//					<figure><img title="" alt=" " src="' . $val['HotelPicture'] . '"></figure>	
//                                        
//				</a></div><div class="owl-item" style="width: 178px; margin-right: 0px;"><a class="item" href="#"> 
//                <span>View all 28 Photos</span>
//					<figure><img title="" alt=" " src="assets/images/hotel2.jpg"></figure>	
//				</a></div><div class="owl-item" style="width: 178px; margin-right: 0px;"><a class="item" href="#"> 
//                <span>View all 28 Photos</span>
//					<figure><img title="" alt=" " src="assets/images/hotel2.jpg"></figure>	
//				</a></div><div class="owl-item" style="width: 178px; margin-right: 0px;"><a class="item" href="#"> 
//                <span>View all 28 Photos</span>
//					<figure><img title="" alt=" " src="assets/images/hotel2.jpg"></figure>	
//				</a></div><div class="owl-item cloned" style="width: 178px; margin-right: 0px;"><a class="item" href="#"> 
//                <span>View all 28 Photos</span>
//					<figure><img title="" alt=" " src="assets/images/hotel2.jpg"></figure>	
//				</a></div><div class="owl-item cloned" style="width: 178px; margin-right: 0px;"><a class="item" href="#"> 
//                <span>View all 28 Photos</span>
//					<figure><img title="" alt=" " src="assets/images/hotel2.jpg"></figure>	
//				</a></div></div></div><div class="owl-controls"><div class="owl-nav"><div class="owl-prev" style="">prev</div><div class="owl-next" style="">next</div></div><div style="" class="owl-dots"><div class="owl-dot active"><span></span></div><div class="owl-dot"><span></span></div><div class="owl-dot"><span></span></div><div class="owl-dot"><span></span></div></div></div></div>
//		</div>
//<div class="col-sm-7 ">
//    	<h4>' . $val['HotelName'] . '</h4>
//       
//        <div class="clear"></div>
//        
//        <div style="padding-left:0; padding-right:0;" class="tableBox"><table cellspacing="0" cellpadding="0" border="0" align="center">
//                          <tbody><tr>
//                       
//                            <td width="25%" class="text-left">' . $htmRatingi . ' </td>
//                          
//                            <td width="75%" class="text-left"><img src="assets/images/hotelRate.png" alt="Hotel"> <a target="_blank" href="' . $val['TripAdvisor']['ReviewURL'] . '" class="small btn btn-success  btn-round1" href="javascript:void(0);">reviews</a></td>
//                          </tr>
//                
//                        </tbody></table>
//                        </div>
//        
//        
//        <div class="clear"></div>
//        <div class="tabBarContent">
//                                                	<div class="tabBarContentIn">
//                                                   
//                                                        <div class="contentMainBox">
//                                                        
//                                                        
//                                                        
//                                                        
//                                                        	<div title="" data-placement="top" data-toggle="tooltip" class="incIcon1 tooltipLink" style="margin-left:0;" data-original-title="WiFi "></div>
//                                                            <div title="" data-placement="top" data-toggle="tooltip" class="incIcon2 tooltipLink" data-original-title="Bar "></div>
//                                                            <div title="" data-placement="top" data-toggle="tooltip" class="incIcon3 tooltipLink" data-original-title="Air Conditioner "></div>
//                                                            <div title="" data-placement="top" data-toggle="tooltip" class="incIcon4 tooltipLink" data-original-title="24h Check in unavailable"></div>
//                                                            <div title="" data-placement="top" data-toggle="tooltip" class="incIcon5 tooltipLink" data-original-title="Restaurant "></div>
//                                                            <div title="" data-placement="top" data-toggle="tooltip" class="incIcon6 tooltipLink" data-original-title="Cafe "></div>
//                                                            
//                                                            
//                                                               <div title="" data-placement="top" data-toggle="tooltip" class="incIcon7 tooltipLink" data-original-title="Room service "></div>
//                                                            <div title="" data-placement="top" data-toggle="tooltip" class="incIcon8 tooltipLink" data-original-title="Business Center "></div>
//                                                            <div title="" data-placement="top" data-toggle="tooltip" class="incIcon9 tooltipLink" data-original-title="Pool "></div>
//                                                            <div title="" data-placement="top" data-toggle="tooltip" class="incIcon10 tooltipLink" data-original-title="Gym "></div>
//                                                            <div title="" data-placement="top" data-toggle="tooltip" class="incIcon11 tooltipLink" data-original-title="Internet "></div>
//                                                        </div>
//                                                              <a data-target="#myModa1vewDetail" data-toggle="modal" class="small btn btn-primarygray  btn-round1" href="javascript:void(0);"> <span class="fa fa-eye"></span> View details</a> -  <a data-target="#myModa1vewDetail" data-toggle="modal" class=" btn btn-primarygray  btn-sm btn-round1" href="javascript:void(0);"><span class="fa fa-image"></span> Photos</a>  -  <a data-target="#myModa1vewDetail" data-toggle="modal" class="small btn btn-primarygray  btn-round1" href="javascript:void(0);"> <span class="fa fa-location-arrow"></span> Location</a>  -  <a data-target="#myModa1vewDetail" data-toggle="modal" class="small btn btn-primarygray  btn-round1" href="javascript:void(0);"> <span class="fa fa-star"></span> Reviews</a> 
//                                                    <div class="clear"></div></div>
//                                                </div>
//                                                
//                        <div class="clear"></div>                        
//                                                
//    
//   </div>
//   
//   <div class="col-sm-2  no-padding">
//    	<h4 class="totalpricebl"><span class="fa fa-rupee"></span>' . $val['Price']['RoomPrice'] . '</h4> </span>
//<div class="clear">&nbsp;</div>
//<a class=" btn btn-danger"  onClick="bookNow()"><strong>Book Now</strong></a> 
//<div style="padding-bottom:10px;" class="clear"></div>
//<a href="javascript:void(0);" class=" btn btn-default" data-toggle="modal" data-target="#myModa1EnquireNow"><strong>Enquire Now</strong></a>
//<label class="checkbox blue inlinebl emailid">
//                        <div class="icheckbox_flat-blue" style="position: relative;"><input type="checkbox" class="icheck-blue" value="option1" id="optionscheckbox1" name="optionscheckbox" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"></ins></div>
//                        Email
//                    </label>
//   </div>
//</div>
//</div>';
//                    $i++;
//                }
//            }
//            $html2 = '</div><div id="tbdeals" class="tab-pane fade no-padding">
//	asasa
//</div></div></div></div>';
//          
//          echo $html1 . $htmlDb . $htmlApi . $html2;
//        } 
//        }  catch( Exception $e) {
//                    $response = array('success' => false, 'msg' => $e->getMessage() );
//                    echo json_encode($response);
//                    exit;
//                }
//        
////        else {
////            $response = array('success' => false, 'msg' => TECHNICAL_ERROR_MSG);
////            echo json_encode($response);
////            exit;
////        }
////            echo "<pre>";
////            print_r($data);
////            exit;
//    }
    public function sendDataForSearchsAction() {
        /* Disable Layout */
        $this->_helper->layout->disableLayout();
        //try{
            if ($this->_request->isXmlHttpRequest()) {
                $dataSearch = $this->getRequest()->getPost();
                $_SESSION['SEARCH']=$dataSearch;
               
                $currentDate = Zend_Date::now()->toString('YYYY-MM-dd HH:mm:ss');
                $objHotel = new Travel_Model_TblHotel();
                $_SESSION['chekInDate']=date('d-m-Y', strtotime($dataSearch['chekInDate']));
                $_SESSION['chekOutDate']=date('d-m-Y', strtotime($dataSearch['chekOutDate']));
                $_SESSION['nights']=(int) @$_SESSION['SEARCH']['nights'];
                $_SESSION['noOfAdults']=(int) @$_SESSION['SEARCH']['noOfAdults1'];
                $_SESSION['noOfRooms']=(int) @$_SESSION['SEARCH']['selectRoom'];
                $con = " WHERE TB_MP_Inventory_Accom.FromDate<='" . date('Y-m-d', strtotime(@$_SESSION['SEARCH']['chekInDate'])) . ' 00:00:00.000' . "' "
                    ." AND TB_MP_Inventory_Accom.ToDate>='" . date('Y-m-d', strtotime(@$_SESSION['SEARCH']['chekOutDate'])) . ' 00:00:00.000' . "' AND TB_MP_Inventory_Accom.DOccupCost!='0.00' GROUP BY TB_MP_Inventory_Accom.XRefAccoSysId)"
                    ." AND t1.IsActive = '1' AND t1.CitySysId='" . @$_SESSION['SEARCH']['hidden_selected_hotel_cityid'] . "' "
                    ." AND t1.Rating <>'" . @$_SESSION['SEARCH']['selectStarRating'] . "'";
                $getdata=array();
               @$_SESSION['getdata'] = $objHotel->getAccomodationHotelList($con);
             
              if(count(@$_SESSION['getdata']) < 1 ) {
                   // throw new Exception('There has been an error, Please try again later');
                }

                //*************Call API**********///////////////
                //$_SESSION['SEARCH']['noOfAdults1']=1;
                $userIp = $_SERVER['REMOTE_ADDR'];
                $_SESSION['userIp1'] = "'".$_SERVER['REMOTE_ADDR']."'";
                $objApi = new Travel_Model_ApiIntegration();
                $_SESSION['tokenId'] = $objApi->apiAuthentication($this->clientID, $this->url, $this->user, $this->pass, $this->userIp);
               
                $_SESSION['tokenId1'] = "'".$objApi->apiAuthentication($this->clientID, $this->url, $this->user, $this->pass, $this->userIp)."'";
                $urlHotel = 'http://api.tektravels.com/BookingEngineService_Hotel/hotelservice.svc/rest/GetHotelResult/';

                if (isset($_SESSION['SEARCH']['select-childAge1'])) {
                    $childAge = (int) @$_SESSION['SEARCH']['select-childAge1'];
                } else {
                    $childAge = null;
                }
                $_SESSION['selectRoomSes']=@$_SESSION['SEARCH']['selectRoom'];
                
                
                $datah = array(
                    "CheckInDate" => date('d/m/Y', strtotime(@$_SESSION['SEARCH']['chekInDate'])),
                    "NoOfNights" => (int) @$_SESSION['SEARCH']['nights'],
                    "CountryCode" => "IN",
                    "CityId" => @$_SESSION['SEARCH']['hidden_selected_hotel_id'],
                    "ResultCount" => null,
                    "PreferredCurrency" => "INR",
                    "GuestNationality" => "IN",
                    "NoOfRooms" => (int) @$_SESSION['SEARCH']['selectRoom'],
                    "RoomGuests" => array(array("NoOfAdults" => (int) @$_SESSION['SEARCH']['noOfAdults1'], "NoOfChild" => (int) @$_SESSION['SEARCH']['select-noOfChild1'], "ChildAge" => $childAge)),
                    'PreferredHotel' => '',
                    'MaxRating' => 5,
                    'MinRating' => 0,
                    'ReviewScore' => null,
                    'IsNearBySearchAllowed' => 'false',
                    'EndUserIp' => $userIp,
                    'TokenId' => @$_SESSION['tokenId']
                );
                $apiRes1 = array();
                $apiRes1 = $objApi->apiHotelSearch($urlHotel, $userIp, $datah);
                $_SESSION['HotelSearchResultFirstStep']=$apiRes1;
                $_SESSION['traceID']="'".$apiRes1['HotelSearchResult']['TraceId']."'";  
               // $apiRes = '';
//               $apiRes = $objApi->apiHotelSearch($urlHotel, $userIp, $datah);
//               
//                $error='';
//                if(!$apiRes=''){
//
//                }else{
//                     if($apiRes['HotelSearchResult']['Error']['ErrorCode']!=0){
//                $error.=$apiRes['HotelSearchResult']['Error']['ErrorMessage'];
//                $error.=' From API';
//                }
//                }



                //*********end of calling API*******/
               
                }
            else {
                $response = array('success' => false, 'msg' => TECHNICAL_ERROR_MSG);
                echo json_encode($response);
                exit;
            }
    //            echo "<pre>";
    //            print_r($data);
    //            exit;
//        }
//    catch( Exception $e) {
//            $response = array('success' => false, 'msg' => $e->getMessage() );
//            echo json_encode($response);
//            exit;
//        }
    }
    public function deleteMpInventoryAccomAction() {
        /* Disable Layout */
        $this->_helper->layout->disableLayout();
        if ($this->_request->isXmlHttpRequest()) {
            $InvnItemSysId = $this->getRequest()->getParam('InvnItemSysId');
            $objHotel = new Travel_Model_TblHotel();
            $objHotel->deleteMpInventoryAccom($InvnItemSysId);
            $response = array('success' => true);
            echo json_encode($response);
            exit;
        }
    }
    public function showTermsAction() {
        /* Disable Layout */
        $this->_helper->layout->disableLayout();
        if ($this->_request->isXmlHttpRequest()) {
            $AccomSysId = $this->getRequest()->getParam('AccomSysId');
            $terms = $this->getRequest()->getParam('terms');
            $objHotel = new Travel_Model_TblHotel();
            $objHotel->addTermsConditions($AccomSysId, $terms);
        }
    }
    public function getHotelDetailApiAction(){
      $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();  
        if ($this->_request->isXmlHttpRequest()) {
                $resultIndex = $this->getRequest()->getParam('resultIndex');
                $hotelCode = $this->getRequest()->getParam("hotelCode");
                $userip = $this->getRequest()->getParam('userip');
                $tokenid = $this->getRequest()->getParam('tokenid');
                $traceid = $this->getRequest()->getParam('traceid');
                $objApi = new Travel_Model_ApiIntegration();
                $hotelInfo=$objApi->getHotelInfo($resultIndex,$hotelCode,$userip,$tokenid,$traceid);
                $_SESSION['HOTELINFO']=$hotelInfo;
                $hotelRoomD=$objApi->getHotelRoomDetail($userip,$tokenid,$traceid,$resultIndex,$hotelCode);
                $_SESSION['HOTEL_ROOM_DETAIL']=$hotelRoomD;
                
                $detail=array('hotelInfo'=>$hotelInfo,'hotelRoomD'=>$hotelRoomD);  
                echo json_encode($detail);   
            }
    }
    
    public function blockRoomDetailAction(){
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout(); 
        
    }
    public function blockRoomDetailsAction(){
        unset($_SESSION['USER_DETAILS_HTL']);
        unset($_SESSION['VOUCHER_DETAIL']);
        $objApi = new Travel_Model_ApiIntegration();
          $this->_helper->layout->disableLayout();  
        if ($this->_request->isXmlHttpRequest()) {
          $selectArr = $this->getRequest()->getParam('roomDetail');
         $tokenId = $this->getRequest()->getParam('tokenId');
         $resultIndex=$this->getRequest()->getParam('resultIndex');
         $_SESSION['resultIndex']=$resultIndex;
         $noOfRooms=$this->getRequest()->getParam('noOfRooms');
         $roomindexID=$this->getRequest()->getParam('roomindexID');
        // $selectArr=json_decode($resultdetail , true ); TraceId
          //$selectArr['hotelInfo']['HotelInfoResult'] ['HotelDetails']['HotelName'];
          $noofRes=count($selectArr['hotelRoomD']['GetHotelRoomResult']['HotelRoomsDetails']);
         $traceId=$selectArr['hotelInfo']['HotelInfoResult']['TraceId'];
             $_SESSION['TRACEID']=$traceId;
             $_SESSION['TOKENID']=$tokenId;
             $_SESSION['HotelCode']=$selectArr['hotelInfo']['HotelInfoResult']['HotelDetails']['HotelCode'];
      $j=0;
      
        for($i=0;$i<$noofRes;$i++){
           // echo $roomindexID;
            if($selectArr['hotelRoomD']['GetHotelRoomResult']['HotelRoomsDetails'][$j]['RoomIndex']==$roomindexID){
           $rooms[]=array("RoomIndex"=>trim($selectArr['hotelRoomD']['GetHotelRoomResult']['HotelRoomsDetails'][$j]['RoomIndex'], ".\r\n"),
                 "RoomTypeCode"=>  htmlspecialchars($selectArr['hotelRoomD']['GetHotelRoomResult']['HotelRoomsDetails'][$j]['RoomTypeCode']),
                 "RoomTypeName"=>trim($selectArr['hotelRoomD']['GetHotelRoomResult']['HotelRoomsDetails'][$j]['RoomTypeName'], ".\r\n"),
                 "RatePlanCode"=>trim($selectArr['hotelRoomD']['GetHotelRoomResult']['HotelRoomsDetails'][$j]['RatePlanCode'], ".\r\n"),
                 "BedTypeCode"=>null,
                 "SmokingPreference"=>"0",
                 "Supplements"=>null,
                 'Price'=>array("CurrencyCode"=>"INR",
                 "RoomPrice"=>(float) trim($selectArr['hotelRoomD']['GetHotelRoomResult']['HotelRoomsDetails'][$j]['Price']['RoomPrice'], ".\r\n"),
                 "Tax"=> trim($selectArr['hotelRoomD']['GetHotelRoomResult']['HotelRoomsDetails'][$j]['Price']['Tax'], ".\r\n"),
                 "ExtraGuestCharge"=> trim($selectArr['hotelRoomD']['GetHotelRoomResult']['HotelRoomsDetails'][$j]['Price']['ExtraGuestCharge'], ".\r\n"),
                 "ChildCharge"=> trim($selectArr['hotelRoomD']['GetHotelRoomResult']['HotelRoomsDetails'][$j]['Price']['ChildCharge'], ".\r\n"),
                 "OtherCharges"=>trim($selectArr['hotelRoomD']['GetHotelRoomResult']['HotelRoomsDetails'][$j]['Price']['OtherCharges'], ".\r\n"),
                 "Discount"=>trim($selectArr['hotelRoomD']['GetHotelRoomResult']['HotelRoomsDetails'][$j]['Price']['Discount'], ".\r\n"),
                 "PublishedPrice"=>(float)trim($selectArr['hotelRoomD']['GetHotelRoomResult']['HotelRoomsDetails'][$j]['Price']['PublishedPrice'], ".\r\n"),
                 "PublishedPriceRoundedOff"=> (float)trim($selectArr['hotelRoomD']['GetHotelRoomResult']['HotelRoomsDetails'][$j]['Price']['PublishedPriceRoundedOff'], ".\r\n"),
                 "OfferedPrice"=>(float)trim($selectArr['hotelRoomD']['GetHotelRoomResult']['HotelRoomsDetails'][$j]['Price']['OfferedPrice'], ".\r\n"),
                 "OfferedPriceRoundedOff"=>(float)trim($selectArr['hotelRoomD']['GetHotelRoomResult']['HotelRoomsDetails'][$j]['Price']['OfferedPriceRoundedOff'], ".\r\n"),
                 "AgentCommission"=>trim( $selectArr['hotelRoomD']['GetHotelRoomResult']['HotelRoomsDetails'][$j]['Price']['AgentCommission'], ".\r\n"),
                 "AgentMarkUp"=>trim($selectArr['hotelRoomD']['GetHotelRoomResult']['HotelRoomsDetails'][$j]['Price']['AgentMarkUp'], ".\r\n"),
                 "ServiceTax"=> trim($selectArr['hotelRoomD']['GetHotelRoomResult']['HotelRoomsDetails'][$j]['Price']['ServiceTax'], ".\r\n"),
                 "TDS"=> trim($selectArr['hotelRoomD']['GetHotelRoomResult']['HotelRoomsDetails'][$j]['Price']['TDS'], ".\r\n")
                     )
                     );  
            }
           $j++; 
            
        }
       // print_r($rooms);
       $datahRoom = array(
                 "ResultIndex" => $resultIndex,
                 "HotelCode" => $selectArr['hotelInfo']['HotelInfoResult']['HotelDetails']['HotelCode'],
                 "HotelName" => $selectArr['hotelInfo']['HotelInfoResult']['HotelDetails']['HotelName'],
                 "GuestNationality" => 'IN',
                 "NoOfRooms" => $noOfRooms,
                 "ClientReferenceNo" => "0",
                 "IsVoucherBooking" => "true",
                 "HotelRoomsDetails" =>$rooms  ,
                 "EndUserIp"=>'172.16.0.137',
                 "TokenId"=>$tokenId,
                 "TraceId"=>$traceId
                    );
             $blkRoom=$objApi->blockRoom($datahRoom);
           
           
              $_SESSION['blkRoom']=$blkRoom;
             
              
           //$this->_redirect('/buyhotel/hotel-guest-detail');
        }
        
    }
    public function bookRoomAction(){
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout(); 
        if($_SESSION['USER_DETAILS_HTL'][0]['Title']=='1'){
           $gender='mr';
           }
        else if($_SESSION['USER_DETAILS_HTL'][0]['Title']=='2'){
            $gender='miss';
          }
        else{
              $gender='mrs'; 
           }
          $j=0;
          $hotelPassenger[]=array("Title"=>$gender,
                    "FirstName"=>trim($_SESSION['USER_DETAILS_HTL'][0]['FirstName']),
                    "Middlename"=> null,
                    "LastName"=>trim($_SESSION['USER_DETAILS_HTL'][0]['LastName']),
                    "Phoneno"=> null,
                    "Email"=>null,
                    "PaxType"=> 1,
                    "LeadPassenger"=> true,
                    "Age"=> 0,
                    "PassportNo"=> null,
                    "PassportIssueDate"=> null,
                    "PassportExpDate"=> null);
           $rooms[]=array("RoomIndex"=>trim($_SESSION['blkRoom']['BlockRoomResult']['HotelRoomsDetails'][$j]['RoomIndex'], ".\r\n"),
                 "RoomTypeCode"=>  htmlspecialchars($_SESSION['blkRoom']['BlockRoomResult']['HotelRoomsDetails'][$j]['RoomTypeCode']),
                 "RoomTypeName"=>trim($_SESSION['blkRoom']['BlockRoomResult']['HotelRoomsDetails'][$j]['RoomTypeName'], ".\r\n"),
                 "RatePlanCode"=>trim($_SESSION['blkRoom']['BlockRoomResult']['HotelRoomsDetails'][$j]['RatePlanCode'], ".\r\n"),
                 "BedTypeCode"=>null,
                 "SmokingPreference"=>"0",
                 "Supplements"=>null,
                 'Price'=>array("CurrencyCode"=>"INR",
                 "RoomPrice"=>(float) trim($_SESSION['blkRoom']['BlockRoomResult']['HotelRoomsDetails'][$j]['Price']['RoomPrice'], ".\r\n"),
                 "Tax"=> trim($_SESSION['blkRoom']['BlockRoomResult']['HotelRoomsDetails'][$j]['Price']['Tax'], ".\r\n"),
                 "ExtraGuestCharge"=> trim($_SESSION['blkRoom']['BlockRoomResult']['HotelRoomsDetails'][$j]['Price']['ExtraGuestCharge'], ".\r\n"),
                 "ChildCharge"=> trim($_SESSION['blkRoom']['BlockRoomResult']['HotelRoomsDetails'][$j]['Price']['ChildCharge'], ".\r\n"),
                 "OtherCharges"=>trim($_SESSION['blkRoom']['BlockRoomResult']['HotelRoomsDetails'][$j]['Price']['OtherCharges'], ".\r\n"),
                 "Discount"=>trim($_SESSION['blkRoom']['BlockRoomResult']['HotelRoomsDetails'][$j]['Price']['Discount'], ".\r\n"),
                 "PublishedPrice"=>(float)trim($_SESSION['blkRoom']['BlockRoomResult']['HotelRoomsDetails'][$j]['Price']['PublishedPrice'], ".\r\n"),
                 "PublishedPriceRoundedOff"=> (float)trim($_SESSION['blkRoom']['BlockRoomResult']['HotelRoomsDetails'][$j]['Price']['PublishedPriceRoundedOff'], ".\r\n"),
                 "OfferedPrice"=>(float)trim($_SESSION['blkRoom']['BlockRoomResult']['HotelRoomsDetails'][$j]['Price']['OfferedPrice'], ".\r\n"),
                 "OfferedPriceRoundedOff"=>(float)trim($_SESSION['blkRoom']['BlockRoomResult']['HotelRoomsDetails'][$j]['Price']['OfferedPriceRoundedOff'], ".\r\n"),
                 "AgentCommission"=>trim( $_SESSION['blkRoom']['BlockRoomResult']['HotelRoomsDetails'][$j]['Price']['AgentCommission'], ".\r\n"),
                 "AgentMarkUp"=>trim($_SESSION['blkRoom']['BlockRoomResult']['HotelRoomsDetails'][$j]['Price']['AgentMarkUp'], ".\r\n"),
                 "ServiceTax"=> trim($_SESSION['blkRoom']['BlockRoomResult']['HotelRoomsDetails'][$j]['Price']['ServiceTax'], ".\r\n"),
                 "TDS"=> trim($_SESSION['blkRoom']['BlockRoomResult']['HotelRoomsDetails'][$j]['Price']['TDS'], ".\r\n")),
                 "HotelPassenger"=>$hotelPassenger
                     
               );   
        $datahRoom = array(
                 "ResultIndex" => $_SESSION['resultIndex'],
                 "HotelCode" => $_SESSION['HotelCode'],
                 "HotelName" => $_SESSION['HOTELINFO']['HotelInfoResult']['HotelDetails']['HotelName'],
                 "GuestNationality" => 'IN',
                 "NoOfRooms" => $_SESSION['noOfRooms'],
                 "ClientReferenceNo" => "0",
                 "IsVoucherBooking" => "true",
                 "HotelRoomsDetails" =>$rooms  ,
                 "EndUserIp"=> "172.16.0.137",
                 "TokenId"=> $_SESSION['TOKENID'],
                 "TraceId"=> $_SESSION['blkRoom']['BlockRoomResult']['TraceId']
                    );
      
               $objApi = new Travel_Model_ApiIntegration();
               $_SESSION['BOOKING_RES']=$objApi->bookRoom($datahRoom);
               $bookingRes=array("BookingId"=>$_SESSION['BOOKING_RES']['BookResult']['BookingId'],"EndUserIp"=>"172.16.0.137","TokenId"=>$_SESSION['TOKENID']);
               $objApi = new Travel_Model_ApiIntegration();
               $voucherDe=$objApi->getBookingDetails($bookingRes);
               
                $_SESSION['VOUCHER_DETAIL']=$voucherDe;
                echo 'process';
//                if(!empty($_SESSION['VOUCHER_DETAIL'])){
//                       
//                 $this->_redirect('/buyhotel/make-payment');
//                
//               }
//              return; 
        
      
    }
        public function hotelGuestDetailAction() {
                    
                   $objGest = new Travel_Model_Tbltbbcuser();
                   $dataGestDetail = $this->getRequest()->getPost();
                   $guestReturn='';
                   $guestReturn=$objGest->addGuest($dataGestDetail);
                   $this->view->guestDetail = $guestReturn;
             if($guestReturn==1){
              $this->_redirect('/buyhotel/hotel-review-booking');
                 }
    }
    public function customerBookingsAction(){
        
    }
    public function hotelVoucherAction(){
       
       $this->_helper->layout->disableLayout(); 
       $objgetAgencyAgent = new Travel_Model_TblAgencyCustomerTrx();
       $res=$objgetAgencyAgent->getAgentDetail();
       $_SESSION['AGENTDET']=$res;
       
    }
    public function makePaymentAction(){
        $objTrx = new Travel_Model_TblAgencyCustomerTrx();
                $objTrx->addNewTransaction();
                $objTrx->addTravelPlan();
    }
    public function performaInvoiceAction(){
        
    }
    public function sendDataForSearchAction(){
        $this->view->country = $this->getCountry();
        $objHotel = new Travel_Model_TblHotel();
                $arrResponse = $objHotel->getHotelAminityAutoSuggest();
                $this->view->AminityArr = $arrResponse;
              
      //  print_r($_SESSION['HotelSearchResultFirstStep']);
//        $cityId=$_SESSION['SEARCH']['hidden_selected_hotel_cityid'];
//         if($_SESSION['HotelSearchResultFirstStep']['HotelSearchResult']['HotelResults'][0]['ResultIndex']){
//             $chekingD=$_SESSION['HotelSearchResultFirstStep']['HotelSearchResult']['CheckInDate'];
//             $chekOut=$_SESSION['HotelSearchResultFirstStep']['HotelSearchResult']['CheckOutDate'];
//                $buyHotlAddAccomo= new Travel_Model_TblICAccomdation();
//                $buyHotlAddAccomo->addAccomodation(@$cityId,@$chekingD,@$chekOut,@$_SESSION['HotelSearchResultFirstStep']['HotelSearchResult']['TraceId'],@$_SESSION['tokenId'],@$_SESSION['HotelSearchResultFirstStep']['HotelSearchResult']['HotelResults']);
//                }
     // echo '<pre>'; print_r($_SESSION['getdata']);
        //$this->view->getAminitiesMask($val['AccoAminitiesMask']);
    }
    public function sessionExAction(){
        $this->_helper->layout->disableLayout();
      session_unset($_SESSION['SEARCH']);
//        session_unset();
//        session_destroy();
        session_unset($_SESSION['chekInDate']);
        session_unset($_SESSION['chekOutDate']);
        session_unset($_SESSION['nights']);
        session_unset($_SESSION['noOfAdults']);
        session_unset($_SESSION['noOfRooms']);
        session_unset($_SESSION['getdata']);
        
    }
}