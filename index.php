<?php
   header("Access-Control-Allow-Origin: *");
 header('Access-Control-Allow-Methods: GET, POST');

  if($_GET["submit"]=="1")
   {
    $mystreet=$_GET["street"];
    $mycity=$_GET["city"];
    $mystate=$_GET["state"];

    $mystreet=str_replace(" ","+",$mystreet);
    $mystreet=str_replace(",","%2C",$mystreet);
    $mystreet=str_replace("/","%2F",$mystreet);
    $mystreet=str_replace("#","%23",$mystreet);

    $mycity=str_replace(" ","+",$mycity);
    $mycity=str_replace(",","%2C",$mycity);
    $mycity=str_replace("/","%2F",$mycity);

    $zillowurl="http://www.zillow.com/webservice/GetDeepSearchResults.htm?zws-id=X1-ZWz1dxfshqy3nv_47pen&address=";
    $zillowurl= $zillowurl.$mystreet."&citystatezip=".$mycity."%2C+".$mystate."&rentzestimate=true";
    $xml = new SimpleXMLElement(file_get_contents($zillowurl));
    
    
    $xml_error=false;
     date_default_timezone_set('America/Los_Angeles');

     $message_code=(int)$xml->message->code;
     if($message_code !== 0){
       $xml_error=true;
     }

    if(!$xml_error)
     {
     $street=(string) $xml->response->results->result->address->street; 
     $city=(string) $xml->response->results->result->address->city; 
     $state=(string) $xml->response->results->result->address->state; 
     $zipcode=(string) $xml->response->results->result->address->zipcode; 
     $address=$street.", ".$city.", ".$state."-".$zipcode;
     $home_details=(string) $xml->response->results->result->links->homedetails;
     $zipid= (string) $xml->response->results->result->zpid;

    if(isset($xml->response->results->result->useCode)){
      if(!empty($xml->response->results->result->useCode)){
      $property_type=(string) $xml->response->results->result->useCode;
     }else{
      $property_type="N/A";
    }    
     }
     else{
      $property_type="N/A"; 
    }

 
    if(isset($xml->response->results->result->yearBuilt)){
     if(!empty($xml->response->results->result->yearBuilt)){
      $year_built=(string) $xml->response->results->result->yearBuilt;
     }else{
      $year_built="N/A";
     }
    }else{
      $year_built="N/A";
     } 
    
   if(isset($xml->response->results->result->lotSizeSqFt)){
     if(!empty($xml->response->results->result->lotSizeSqFt)){
      $lot_size=(string) $xml->response->results->result->lotSizeSqFt." sq.ft"; 
    }else{
     $lot_size="N/A";
    }
     }else{
      $lot_size="N/A";
     }  

   if(isset($xml->response->results->result->finishedSqFt)){
     if(!empty($xml->response->results->result->finishedSqFt)){
      $finished_area=(string) $xml->response->results->result->finishedSqFt." sq.ft";
    }else{
      $finished_area="N/A";
    }
     }else{
      $finished_area="N/A";
     }

    if(isset($xml->response->results->result->bathrooms)){
     if(!empty($xml->response->results->result->bathrooms)){
      $bathrooms=(string) $xml->response->results->result->bathrooms;
    }else{
      $bathrooms="N/A";
   }
     }else{
      $bathrooms="N/A";
     }


    if(isset($xml->response->results->result->bedrooms)){
     if(!empty($xml->response->results->result->bedrooms)){
      $bedrooms=(string) $xml->response->results->result->bedrooms; 
    }else{
      $bedrooms="N/A";  
    }
     }else{
      $bedrooms="N/A";  
     }

    if(isset($xml->response->results->result->taxAssessmentYear)){
     if(!empty($xml->response->results->result->taxAssessmentYear)){
      $tax_assesment_year=(string) $xml->response->results->result->taxAssessmentYear;
    }else{
      $tax_assesment_year="N/A";  
   }   
     }else{
      $tax_assesment_year="N/A";  
     }

    if(isset($xml->response->results->result->taxAssessment)){
      if(!empty($xml->response->results->result->taxAssessment)){
      $tax_assesment="$".number_format((float) $xml->response->results->result->taxAssessment,2,'.',',');
     }else{
      $tax_assesment="N/A";
     }
     }else{
      $tax_assesment="N/A";
     }

    
    if(isset($xml->response->results->result->lastSoldPrice)){
     if(!empty($xml->response->results->result->lastSoldPrice)){
      $last_sold_price="$".number_format((float) $xml->response->results->result->lastSoldPrice,2,'.',','); 
     }else{
       $last_sold_price="N/A";
     }
    }else{
      $last_sold_price="N/A";
     }

    if(isset($xml->response->results->result->lastSoldDate)){
     if(!empty($xml->response->results->result->lastSoldDate)){
      $last_sold_date=date("d-M-Y",strtotime( (string) $xml->response->results->result->lastSoldDate));
    }else{
      $last_sold_date="N/A";
    }
     }else{
      $last_sold_date="N/A";
     }

    if(isset($xml->response->results->result->zestimate->amount)){
      if(!empty($xml->response->results->result->zestimate->amount)){
      $property_estimate="$".number_format((float) (string) $xml->response->results->result->zestimate->amount,2,'.',',');
   }else{
     $property_estimate="N/A";
   }
    }
     else{
      $property_estimate="N/A";
     }

    if(isset($xml->response->results->result->zestimate->valueChange)){
     if(!empty($xml->response->results->result->zestimate->valueChange)){
      $overall_change="$".number_format((float) $xml->response->results->result->zestimate->valueChange,2,'.',',');
    }else{
     $overall_change="N/A";
    }
     }else{
      $overall_change="N/A";
     }

    if(isset($xml->response->results->result->zestimate->valuationRange->low) && isset($xml->response->results->result->zestimate->valuationRange->high)){
      if(!empty($xml->response->results->result->zestimate->valuationRange->low) && !empty($xml->response->results->result->zestimate->valuationRange->high)) {
       $property_range="$".number_format((float) $xml->response->results->result->zestimate->valuationRange->low,2,'.',',')."-"."$".number_format((float)$xml->response->results->result->zestimate->valuationRange->high,2,'.',',');
     }else{
      $property_range="N/A";
   }
    }else{
      $property_range="N/A";
     }

    if(isset($xml->response->results->result->rentzestimate->amount)){
      if(!empty($xml->response->results->result->rentzestimate->amount)){
      $rent_valuation="$".number_format((float) $xml->response->results->result->rentzestimate->amount,2,'.',','); 
     }else{
     $rent_valuation="N/A";
    }
     }else{
      $rent_valuation="N/A";
     }

    if(isset($xml->response->results->result->rentzestimate->valueChange)){
     if(!empty($xml->response->results->result->rentzestimate->valueChange)){
      $rent_change="$".number_format((float) $xml->response->results->result->rentzestimate->valueChange,2,'.',','); 
     }else{
     $rent_change="N/A";
    }
     }else{
      $rent_change="N/A";
     }


    if(isset($xml->response->results->result->rentzestimate->valuationRange->low) && isset($xml->response->results->result->rentzestimate->valuationRange->high)){
      if(!empty($xml->response->results->result->rentzestimate->valuationRange->low) && !empty($xml->response->results->result->rentzestimate->valuationRange->high)){
      $rent_range="$".number_format((float) $xml->response->results->result->rentzestimate->valuationRange->low,2,'.',',')."-"."$".number_format((float)$xml->response->results->result->rentzestimate->valuationRange->high,2,'.',',');
     }else{
      $rent_range="N/A";
    }
    }else{
      $rent_range="N/A";
     }

      if(isset($xml->response->results->result->zestimate->{'last-updated'}) && isset($xml->response->results->result->zestimate->amount) ){
     if(!empty($xml->response->results->result->zestimate->{'last-updated'})&& !empty($xml->response->results->result->zestimate->amount) ) {
      $property_estimate_date=date("d-M-Y",strtotime( (string) $xml->response->results->result->zestimate->{'last-updated'})); 
    }else{
     $property_estimate_date="N/A";
   }
    }else{
      $property_estimate_date="N/A";
    }

   if(isset($xml->response->results->result->rentzestimate->{'last-updated'}) && isset($xml->response->results->result->rentzestimate->amount) ){ 
     if(!empty($xml->response->results->result->rentzestimate->{'last-updated'}) && !empty($xml->response->results->result->rentzestimate->amount) ) {
      $rent_valuation_date=date("d-M-Y",strtotime((string) $xml->response->results->result->rentzestimate->{'last-updated'}));
    }else{
      $rent_valuation_date="N/A";
   }
    }else{
      $rent_valuation_date="N/A";
    } 
 
    
   $JSONobj=array( 'code'  =>  $message_code,
                   'street' => $street,
                   'city' => $city,
                   'state' => $state,
                   'zipcode' => $zipcode,
                   'address' => $address,
                   'propertytype' => $property_type,
                   'yearbuilt' => $year_built,
                   'lotsize' => $lot_size,
                   'finishedarea' => $finished_area,
                   'bathrooms' => $bathrooms,
                   'bedrooms'  => $bedrooms,
                   'taxassesmentyear' => $tax_assesment_year,
                   'taxassesment' => $tax_assesment,
                   'lastsoldprice' => $last_sold_price,
                   'lastsolddate' => $last_sold_date,
                   'propertyestimate' => $property_estimate,
                   'propertyestimatedate' => $property_estimate_date,
                   'overallchange' => $overall_change,
                   'propertyrange' => $property_range,
                   'rentvaluation' => $rent_valuation,
                   'rentvaluationdate' => $rent_valuation_date,
                   'rentchange' => $rent_change,
                   'rentrange' => $rent_range,
                   'homedetails' => $home_details,
                   'zipid' => $zipid
                    );
 
   
                 $result=json_encode($JSONobj);
                 echo $result;
   }
   else {
  
    $JSONobj=array('code'  =>  $message_code );
    $result=json_encode($JSONobj);
    echo $result;
   }

    }

   if($_GET["submit"]=="2")
    {

     $zipid=$_GET["zipid"];

     $imageurl="http://www.zillow.com/webservice/GetChart.htm?zws-id=X1-ZWz1dxfshqy3nv_47pen&zpid=";
     $imageurl=$imageurl.$zipid."&unit-type=percent&width=600&height=300";
     $xml = new SimpleXMLElement(file_get_contents($imageurl));

     $xml_error=false;
     date_default_timezone_set('America/Los_Angeles');

     $message_code=(int)$xml->message->code;
     if($message_code !== 0){
       $xml_error=true;
     }

     if(!$xml_error)
     {
       $imageurl=(string) $xml->response->url;
      $JSONobj=array('code'  =>  $message_code,
                     'imageurl' => $imageurl );
      $result=json_encode($JSONobj);
      echo $result;

     }
     else
     {
    $JSONobj=array('code'  =>  $message_code );
    $result=json_encode($JSONobj);
    echo $result;

     }

    }
    
   
?>