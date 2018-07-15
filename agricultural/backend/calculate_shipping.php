<?php

    $originZip = json_decode($_REQUEST["originZip"]);
    $destZip = json_decode($_REQUEST["destZip"]);
    
    $pounds = json_decode($_REQUEST["pounds"]);
    $ounces = json_decode($_REQUEST["ounces"]);

    $length = json_decode($_REQUEST["length"]);
    $width = json_decode($_REQUEST["width"]);
    $height = json_decode($_REQUEST["height"]);

    // This script was written by Mark Sanborn at http://www.marksanborn.net  
    // If this script benefits you are your business please consider a donation  
    // You can donate at http://www.marksanborn.net/donate.    

    // ========== CHANGE THESE VALUES TO MATCH YOUR OWN ===========
    $username = '291KENZE6822'; 
    // ========== CHANGE THESE VALUES TO MATCH YOUR OWN ===========

    $url = "http://Production.ShippingAPIs.com/ShippingAPI.dll";  
    $ch = curl_init();  

    // set the target url  
    curl_setopt($ch, CURLOPT_URL, $url);  
    //curl_setopt($ch, CURLOPT_HEADER, 1);  
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);  

    // parameters to post  
    curl_setopt($ch, CURLOPT_POST, 1);  

    // You would want to actually build this xml using dimensions
    // and other attributes but this is a bare minimum request as
    // an example.
    $data = "API=RateV4&XML=<RateV4Request USERID=\"{$username}\">
       <Revision>2</Revision>
       <Package ID=\"1ST\">
          <Service>ALL</Service>
          <ZipOrigination>{$originZip}</ZipOrigination>
          <ZipDestination>{$destZip}</ZipDestination>
          <Pounds>{$pounds}</Pounds>
          <Ounces>{$ounces}</Ounces>
          <Container />
          <Size>REGULAR</Size>
          <Width>$width</Width>
          <Length>$length</Length>
          <Height>$height</Height>
          <Girth>10</Girth>
          <Machinable>false</Machinable>
       </Package>
    </RateV4Request>";

    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);  

    $result = str_replace("<sup>&#8482;</sup>","",htmlspecialchars_decode(htmlspecialchars_decode(curl_exec ($ch))));

    print_r($result);
