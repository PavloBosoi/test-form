<?php

namespace App\Http\Controllers;

use App\Entry;
use Illuminate\Http\Request;
use GeoIP;
use Forecast;

class MainController extends Controller
{
    public function save (Request $request)
    {
    	if (!isset($request->data) || !is_array($request->data)) //test input - should have 'data' array
	    {
	    	abort(500,'Error in request');
	    }
    	$client_ip=$request->ip(); //get client's IP
	    $location = GeoIP::getLocation($client_ip); //and determine GeoIP data
	    $country=isset($location['country'])?$location['country']:'Unknown'; //country name
	    $country_iso=isset($location['iso_code'])?$location['iso_code']:'XX'; //country ISO
	    $latitude=isset($location['lat'])?$location['lat']:false; //determine latitude and longtitude for Forecast.io API
	    $longtitude=isset($location['lon'])?$location['lon']:false;
	    $weather=($latitude && $longtitude)?Forecast::get($latitude,$longtitude)['currently']:''; //get weather data
	    foreach ($request->data as $entry) //run through all entries
	    {
			$new_entry=new Entry(); //new model, Eloquent ORM used
			$new_entry->name=isset($entry['name'])?$entry['name']:''; //set corresponding fields
		    $new_entry->phone=isset($entry['phone'])?$entry['phone']:'';
		    $new_entry->country=$country;
		    $new_entry->country_iso=$country_iso;
		    $new_entry->weather=json_encode($weather);
		    $new_entry->save(); //save
	    }
	    return response()->json( [ 'response' => 'ok' ] );
    }
}
