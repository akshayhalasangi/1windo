<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Product extends W_Controller
{
    public function __construct()
    {
        parent::__construct();
		header('Content-Type: application/json');
      
    }

    public function index()
    {
		echo "Access Denied";	
    }
	
	public function Fetch()
    {		     
		$data = $this->input->post();	
		
		if(!empty($data['Action']) && $data['Action'] == 'GetAllCategories'){				
			
				
				$CategoriesArray = $this->Categories_model->getCategory(NULL,array('C_Level'=>'1','C_Type'=>'2'),"ASC");	
				$Records = array();	
				$CategoriesCount = (string)count($Records);
				$CategoriesRecords = $Records;

				if (!empty($CategoriesArray)) {	
				
					foreach($CategoriesArray as $CategoryArray) {
						
						
						$DisplayImage = '';	
						$DisplayImage = (!empty($CategoryArray['C_DisplayImage']) ? UPLOAD_CATEGORIES_BASE_URL.$CategoryArray['CategoryID'].'/'.$CategoryArray['C_DisplayImage'] : '');	
						
						$SubCategoriesArray = $this->Categories_model->get(NULL,array('C_Level'=>'2','C_Type'=>'2','C_Parent'=>$CategoryArray['CategoryID']),"ASC");	
						$Records = array();	

						if (!empty($SubCategoriesArray)) {	
							
							foreach($SubCategoriesArray as $SubCategoryArray) {
								$DisplayImage = '';	
								$DisplayImage = (!empty($SubCategoryArray['C_DisplayImage']) ? UPLOAD_CATEGORIES_BASE_URL.$SubCategoryArray['CategoryID'].'/'.$SubCategoryArray['C_DisplayImage'] : '');	
								
								
								$ChildCategoriesArray = $this->Categories_model->get(NULL,array('C_Level'=>'3','C_Type'=>'2','C_Parent'=>$SubCategoryArray['CategoryID']),"ASC");	
								$ChildRecords = array();	
		
								if (!empty($ChildCategoriesArray)) {	
									
									foreach($ChildCategoriesArray as $ChildCategoryArray) {
										$DisplayImage = '';	
										$DisplayImage = (!empty($ChildCategoryArray['C_DisplayImage']) ? UPLOAD_CATEGORIES_BASE_URL.$ChildCategoryArray['CategoryID'].'/'.$ChildCategoryArray['C_DisplayImage'] : '');	
										$ChildRecords[] = array(  
											'SubCategoryID' => $ChildCategoryArray['CategoryID'],
											'Name' => $ChildCategoryArray['C_Name'],
											'DisplayImage' => $DisplayImage,
											//'Status'=> $ServiceArray['S_Status']
											);	
										
									}								
									
				
									
								} else {		
									$ChildRecords = array();				
									//$result = array('status'=>'error','flag'=>'2','message'=>'No Entries Found.');	
								}
								$ChildCategoryCount = (string)count($ChildRecords);

								$Records[] = array(  
									'SubCategoryID' => $SubCategoryArray['CategoryID'],
									'Name' => $SubCategoryArray['C_Name'],
									'DisplayImage' => $DisplayImage,
									'ChildCategoryCount' => $ChildCategoryCount,
									'ChildCategoryData' => $ChildRecords,
									//'Status'=> $ServiceArray['S_Status']
									);	

								
							}								
							
		
							
						} else {		
							$Records = array();				
							//$result = array('status'=>'error','flag'=>'2','message'=>'No Entries Found.');	
						}
						$SubCategoryCount = (string)count($Records);
						$CategoryRecords[] 	= array(  
							'CategoryID' => $CategoryArray['CategoryID'],
							'Name' => $CategoryArray['C_Name'],
							//'DisplayImage' => $DisplayImage,
							'SubCategoryCount' => $SubCategoryCount,
							'SubCategoryData' => $Records,
							);	

					}
						$CategoryData['CategoriesCount'] 	= (string)count($CategoryRecords); 
						$CategoryData['CategoriesData'] 	= $CategoryRecords;
						$result = array('status'=>'success','flag'=>'1','message'=>'Product Categories Records Fetched','data'=>$CategoryData);	
				} else {
						$CategoryData['CategoriesCount'] 	= $CategoriesCount; 
						$CategoryData['CategoriesData'] 	= $CategoryRecords;

					$result = array('status'=>'error','flag'=>'2','message'=>'No Entries Found.','data'=>$CategoryData);	
				}
					
					
				
		}
		else if(!empty($data['Action']) && $data['Action'] == 'GetAllProducts'){				
			
				$ProductsArray = $this->Products_model->get(NULL,array('P_CategoryID'=>$data['Val_Category']),'DESC');	
				$Records = array();	
				$ReviewsRecords = array();
				$ProductsCount = (string)count($Records);
				$ProductsRecords = $Records;
				$ReviewsCount = "0" ;
				$MinPrice = PHP_INT_MAX;
				$MaxPrice = 0;
				
				if (!empty($ProductsArray)) {
					
					foreach($ProductsArray as $ProductArray) {
						
						$ProductReviews =	$this->Products_model->getReviews(NULL,array('R_Type'=>'2','R_RelationID'=>$ProductArray['ProductID']));	
						if(!empty($ProductReviews))
							{
								$ReviewsRecords = array();		
								$Index = 1;
								foreach($ProductReviews as $Review)
								{
									if($Index <= 5)
										{
											$FormattedDate = date('d M,Y',strtotime($Review['R_Date']));
											$FormattedDateAgo = time_ago($Review['R_Date']." ".$Review['R_Time']);
											
											
											
											array_push($ReviewsRecords,array(
																'ReviewID'=>getStringValue($Review['ReviewID']),
																'Index'=>getStringValue($Index),
																'Username'=>getStringValue($Review['R_UserName']),
																'Color'=>getRandomColor(),
																'Comment'=>getStringValue($Review['R_Comment']),
																'Location'=>getStringValue($Review['R_Location']),
																'Rating'=>getStringValue($Review['R_Rating']),
																'Date'=>getStringValue($FormattedDate),
																'Date2'=>getStringValue($FormattedDateAgo),
																)
															);
										}
									
									$Index++;
								}
								
								$ReviewsTempCount = (string)count($ReviewsRecords);		
								$ReviewsCount = (string)count($ProductReviews);		
							}
						else{
									$ReviewsRecords = array();
									$ReviewsCount = "0" ;
							}	
						
						if(!empty($ProductArray['P_Attributes']))
						{
							$ProductAttribute =	$this->Products_model->getAttributes($ProductArray['P_Attributes']);	
						
							if(!empty($ProductAttribute)) {
								$AttributeType = $ProductAttribute->A_Type;
							} else {
								$AttributeType = "3";
							}
						} else {
								$AttributeType = "3";
							}
						
						

						$ProductAttribValuesArray =	getAttribValuesArray($ProductArray['P_AttributeValues']);
						

						
						$FeaturedImage = '';	
						$FeaturedImage = (!empty($ProductArray['P_FeaturedImage']) ? UPLOAD_PRODUCTS_BASE_URL.$ProductArray['ProductID'].'/'.$ProductArray['P_FeaturedImage'] : '');

						$GalleryImages = json_decode($ProductArray['P_Gallery']);	
						if(!empty($GalleryImages)) {
							foreach ($GalleryImages as &$value){
								$value = UPLOAD_PRODUCTS_BASE_URL.$ProductArray['ProductID'].'/' . trim($value);
							}
						}
						else{
							$GalleryImages = array();
						}
						$GalleryCount = (string)count($GalleryImages);
						
						$Records[] = array(  
							'ProductID' 		=> getStringValue($ProductArray['ProductID']),
							'Name' 				=> getStringValue($ProductArray['P_Name']),
							'Description' 		=> getStringValue($ProductArray['P_Description']),
							'Currency' 			=> getStringValue("Rs. "),
							'Price' 			=> getStringValue($ProductArray['P_Price']),
							'FeaturedImage' 	=> $FeaturedImage,
							'GalleryCount'		=> $GalleryCount,
							'GalleryImages'		=> $GalleryImages,
							'ReviewsCount'		=> $ReviewsCount,
							'ReviewsData'		=> $ReviewsRecords,
							'AttributeType'		=> getStringValue($AttributeType),
							'AttributesCount'	=> $ProductAttribValuesArray['ValuesCount'],
							'AttributesData'	=> $ProductAttribValuesArray['ValuesData'],
							
							);	


						
							$MinPrice = min($ProductArray['P_Price'], $MinPrice);
							$MaxPrice = max($ProductArray['P_Price'], $MaxPrice);

					}								

					$ProductsData['Currency'] 	= (string)"Rs. "; 
					$ProductsData['MinPrice'] 	= (string)$MinPrice; 
					$ProductsData['MaxPrice'] 	= (string)$MaxPrice; 
					$ProductsData['ProductsCount'] 	= (string)count($Records); 
					$ProductsData['ProductsData'] 	= $Records;
					$result = array('status'=>'success','flag'=>'1','message'=>'Products Records Fetched','data'=>$ProductsData);	
				} else {					
					$ProductsData['Currency'] 	= (string)"Rs. "; 
					$ProductsData['MinPrice'] 	= (string)$MinPrice; 
					$ProductsData['MaxPrice'] 	= (string)$MaxPrice; 		
					$ProductsData['ProductsCount'] 	= $ProductsCount; 
					$ProductsData['ProductsData'] 	= $ProductsRecords;
					$result = array('status'=>'error','flag'=>'2','message'=>'No Entries Found.','data'=>$ProductsData);	
				}
		}
		else if(!empty($data['Action']) && $data['Action'] == 'SearchProducts'){				
			
			if(!empty($data['Val_Category']) && !empty($data['Val_Minprice']) && !empty($data['Val_Maxprice'])  )
				{
				
				$SearchRequestData['Val_Search'] 	= $data['Val_Search'];
				$SearchRequestData['Val_Start'] 	= '0';
				$SearchRequestData['Val_Limit'] 	= '999999';
				$Order = 'DESC';
				
				if(!empty($data['Val_Sort']) && $data['Val_Sort'] == '1')
					{
						$OrderBy = 'P_Sales';
						
						}
				else if(!empty($data['Val_Sort']) && $data['Val_Sort'] == '2')
					{
						$OrderBy = 'P_Rating';
						}
				else if(!empty($data['Val_Sort']) && $data['Val_Sort'] == '3')
					{
						$OrderBy = 'RowAddedDttm';
						}
				else if(!empty($data['Val_Sort']) && $data['Val_Sort'] == '4')
					{
						$OrderBy = 'P_Price';
						$Order = 'ASC';
						}
				else if(!empty($data['Val_Sort']) && $data['Val_Sort'] == '5')
					{
						$OrderBy = 'P_Price';
						$Order = 'DESC';
						}
				else 
					{
						$OrderBy = '';
						}
				$ProductsArray = $this->Products_model->search($SearchRequestData,array('P_CategoryID'=>$data['Val_Category'],'P_Price >='=>$data['Val_Minprice'],'P_Price <='=>$data['Val_Maxprice']),$OrderBy,$Order);	
				$Records = array();	
				$ReviewsRecords = array();
				$ProductsCount = (string)count($Records);
				$ProductsRecords = $Records;
				$ReviewsCount = "0" ;
				
				$MinPrice = PHP_INT_MAX;
				$MaxPrice = 0;

				if ($ProductsArray) {
					
					foreach($ProductsArray as $ProductArray) {
						
						$ProductReviews =	$this->Products_model->getReviews(NULL,array('R_Type'=>'2','R_RelationID'=>$ProductArray['ProductID']));	
						if(!empty($ProductReviews))
							{
								$ReviewsRecords = array();		
								$Index = 1;
								foreach($ProductReviews as $Review)
								{
									if($Index <= 5)
										{
											$FormattedDate = date('d M,Y',strtotime($Review['R_Date']));
											$FormattedDateAgo = time_ago($Review['R_Date']." ".$Review['R_Time']);
											
											
											
											array_push($ReviewsRecords,array(
																'ReviewID'=>getStringValue($Review['ReviewID']),
																'Index'=>getStringValue($Index),
																'Username'=>getStringValue($Review['R_UserName']),
																'Color'=>getRandomColor(),
																'Comment'=>getStringValue($Review['R_Comment']),
																'Location'=>getStringValue($Review['R_Location']),
																'Rating'=>getStringValue($Review['R_Rating']),
																'Date'=>getStringValue($FormattedDate),
																'Date2'=>getStringValue($FormattedDateAgo),
																)
															);
										}
									
									$Index++;
								}
								
								$ReviewsTempCount = (string)count($ReviewsRecords);		
								$ReviewsCount = (string)count($ProductReviews);		
							}
						else{
									$ReviewsRecords = array();
									$ReviewsCount = "0" ;
							}	
						
						
						if(!empty($ProductArray['P_Attributes']))
						{
							$ProductAttribute =	$this->Products_model->getAttributes($ProductArray['P_Attributes']);	
						
							if(!empty($ProductAttribute)) {
								$AttributeType = $ProductAttribute->A_Type;
							} else {
								$AttributeType = "3";
							}
						} else {
								$AttributeType = "3";
							}
						
						
						
						$ProductAttribValuesArray =	getAttribValuesArray($ProductArray['P_AttributeValues']);
						$FeaturedImage = '';	
						$FeaturedImage = (!empty($ProductArray['P_FeaturedImage']) ? UPLOAD_PRODUCTS_BASE_URL.$ProductArray['ProductID'].'/'.$ProductArray['P_FeaturedImage'] : '');

						$GalleryImages = json_decode($ProductArray['P_Gallery']);	
						if(!empty($GalleryImages)) {
							foreach ($GalleryImages as &$value){
								$value = UPLOAD_PRODUCTS_BASE_URL.$ProductArray['ProductID'].'/' . trim($value);
							}
						}
						else{
							$GalleryImages = array();
						}
						$GalleryCount = (string)count($GalleryImages);
						
						$Records[] = array(  
							'ProductID' 		=> getStringValue($ProductArray['ProductID']),
							'Name' 				=> getStringValue($ProductArray['P_Name']),
							'Description' 		=> getStringValue($ProductArray['P_Description']),
							'Currency' 			=> getStringValue("Rs. "),
							'Price' 			=> getStringValue($ProductArray['P_Price']),
							'FeaturedImage' 	=> $FeaturedImage,
							'GalleryCount'		=> $GalleryCount,
							'GalleryImages'		=> $GalleryImages,
							'ReviewsCount'		=> $ReviewsCount,
							'ReviewsData'		=> $ReviewsRecords,
							'AttributeType'		=> getStringValue($AttributeType),
							'AttributesCount'	=> $ProductAttribValuesArray['ValuesCount'],
							'AttributesData'	=> $ProductAttribValuesArray['ValuesData'],
							
							);	

						$MinPrice = min($ProductArray['P_Price'], $MinPrice);
						$MaxPrice = max($ProductArray['P_Price'], $MaxPrice);
					}			
					$ProductsData['Currency'] 	= (string)"Rs. "; 
					if(!empty($data['Val_Minprice']) && !empty($data['Val_Maxprice']) )
						{
							$ProductsData['MinPrice'] 	= (string)$data['Val_Minprice']; 
							$ProductsData['MaxPrice'] 	= (string)$data['Val_Maxprice']; 
						}
					if(!empty($data['Val_Minprice']) || $data['Val_Maxprice'])
						{
							$ProductsData['MinPrice'] 	= (!empty($data['Val_Minprice']) ? (string)$data['Val_Minprice'] : (string)$MinPrice); 
							$ProductsData['MaxPrice'] 	= (!empty($data['Val_Maxprice']) ? (string)$data['Val_Maxprice'] : (string)$MaxPrice); ; 
						}
					else
						{
							$ProductsData['MinPrice'] 	= (string)$MinPrice; 
							$ProductsData['MaxPrice'] 	= (string)$MaxPrice; 
						}	
					$ProductsData['ProductsCount'] 	= (string)count($Records); 
					$ProductsData['ProductsData'] 	= $Records;
					$result = array('status'=>'success','flag'=>'1','message'=>'Products Records Fetched','data'=>$ProductsData);	
				} elseif ($ProductsArray == false) {			
					$ProductsData['Currency'] 	= (string)"Rs. "; 
					$ProductsData['MinPrice'] 	= (string)$MinPrice; 
					$ProductsData['MaxPrice'] 	= (string)$MaxPrice; 		
					$ProductsData['ProductsCount'] 	= $ProductsCount; 
					$ProductsData['ProductsData'] 	= $ProductsRecords;
					$result = array('status'=>'error','flag'=>'2','message'=>'No Entries Found.','data'=>$ProductsData);	
				}
			}
			else {
					$result = array('status'=>'info','flag'=>'4','message'=>'Parameters Missing.');		
			}
		}
		else if(!empty($data['Action']) && $data['Action'] == 'GetSingleProduct'){				
				
				
				
				if( !empty($data['Val_Product'])) {
				
					$ProductArray = $this->Products_model->get($data['Val_Product']);	
					$Records = array();	
					$ReviewsRecords = array();
					$ProductsCount = (string)count($Records);
					$ProductsRecords = $Records;
					$ReviewsCount = "0" ;
					$MinPrice = PHP_INT_MAX;
					$MaxPrice = 0;
					
					if (!empty($ProductArray)) {
						
							$ProductReviews =	$this->Products_model->getReviews(NULL,array('R_Type'=>'2','R_RelationID'=>$ProductArray->ProductID));	
							if(!empty($ProductReviews))
								{
									$ReviewsRecords = array();		
									$Index = 1;
									foreach($ProductReviews as $Review)
									{
										if($Index <= 5)
											{
												$FormattedDate = date('d M,Y',strtotime($Review['R_Date']));
												$FormattedDateAgo = time_ago($Review['R_Date']." ".$Review['R_Time']);
												
												array_push($ReviewsRecords,array(
															'ReviewID'=>getStringValue($Review['ReviewID']),
															'Index'=>getStringValue($Index),
															'Username'=>getStringValue($Review['R_UserName']),
															'Color'=>getRandomColor(),
															'Comment'=>getStringValue($Review['R_Comment']),
															'Location'=>getStringValue($Review['R_Location']),
															'Rating'=>getStringValue($Review['R_Rating']),
															'Date'=>getStringValue($FormattedDate),
															'Date2'=>getStringValue($FormattedDateAgo),
															)
														);
											}
										
										$Index++;
									}
									
									$ReviewsTempCount = (string)count($ReviewsRecords);		
									$ReviewsCount = (string)count($ProductReviews);		
								}
							else{
										$ReviewsRecords = array();
										$ReviewsCount = "0" ;
								}	
							
							if(!empty($ProductArray->P_Attributes))
							{
								$ProductAttribute =	$this->Products_model->getAttributes($ProductArray->P_Attributes);	
							
								if(!empty($ProductAttribute)) {
									$AttributeType = $ProductAttribute->A_Type;
                                    $AttributeTitle = $ProductAttribute->A_Title;
								} else {
									$AttributeType = "3";
								}
							} else {
									$AttributeType = "3";
								}
							
							
	
							$ProductAttribValuesArray =	getAttribValuesArray($ProductArray->P_AttributeValues);
							
	
							
							$FeaturedImage = '';	
							$FeaturedImage = (!empty($ProductArray->P_FeaturedImage) ? UPLOAD_PRODUCTS_BASE_URL.$ProductArray->ProductID.'/'.$ProductArray->P_FeaturedImage : '');
	
							$GalleryImages = json_decode($ProductArray->P_Gallery);	
							if(!empty($GalleryImages)) {
								foreach ($GalleryImages as &$value){
									$value = UPLOAD_PRODUCTS_BASE_URL.$ProductArray->ProductID.'/' . trim($value);
								}
							}
							else{
								$GalleryImages = array();
							}
							$GalleryCount = (string)count($GalleryImages);
							
							$Record = array(  
								'ProductID' 		=> getStringValue($ProductArray->ProductID),
								'Name' 				=> getStringValue($ProductArray->P_Name),
								'Description' 		=> getStringValue($ProductArray->P_Description),
								'Currency' 			=> getStringValue("Rs. "),
								'Price' 			=> getStringValue($ProductArray->P_Price),
								'FeaturedImage' 	=> $FeaturedImage,
								'GalleryCount'		=> $GalleryCount,
								'GalleryImages'		=> $GalleryImages,
								'ReviewsCount'		=> $ReviewsCount,
								'ReviewsData'		=> $ReviewsRecords,
								'AttributeType'		=> getStringValue($AttributeType),
								'AttributeTitle'	=> getStringValue($AttributeTitle),
								'AttributesCount'	=> $ProductAttribValuesArray['ValuesCount'],
								'AttributesData'	=> $ProductAttribValuesArray['ValuesData'],
								
								);	
	
 						
						$result = array('status'=>'success','flag'=>'1','message'=>'Products Records Fetched','data'=>$Record);	
					} else {					
						$result = array('status'=>'error','flag'=>'2','message'=>'No Entries Found.','data'=>(object)array());	
					}
			

				} else {
					$result = array('status'=>'info','flag'=>'4','message'=>'Parameters Missing.');		
				}			 							
		} 
		else if(!empty($data['Action']) && $data['Action'] == 'GetPackages'){				
				
				if( !empty($data['Val_Service']) )	
					{
						$Records = array();
						$PackagesArray =	$this->Services_model->getPackages(NULL,array('P_ServiceID'=>$data['Val_Service']));	
						$PackageData['PackagesCount'] 	= (string)count($Records); 
						$PackageData['PackagesData'] 	= $Records;
						$Index = 1;
						if (!empty($PackagesArray)) {	
					
							foreach($PackagesArray as $Pacakge) {
								
									$Records[] = array(  
											'PackageID'=>getStringValue($Pacakge['SPackageID']),
											'Index'=>getStringValue($Index),
											'Title'=>getStringValue($Pacakge['P_Title']),
											'Description'=>getStringValue($Pacakge['P_Description']),
										);	
									$Index++;
									
								}
							$PackageData['PackagesCount'] 	= (string)count($Records); 
							$PackageData['PackagesData'] 	= $Records;
							
							$result = array('status'=>'success','flag'=>'1','message'=>'Packages Records Fetched','data'=>$PackageData);	
						} elseif ($ServiceArray == false) {					
							$result = array('status'=>'error','flag'=>'2','message'=>'No Entries Found.','data'=>$PackageData);	
						}
						
					}
				else
					{
							$result = array('status'=>'info','flag'=>'4','message'=>'Parameters Missing.');		
					}	
				} 
		else if(!empty($data['Action']) && $data['Action'] == 'GetOptions'){				
				
				if( !empty($data['Val_Service']) && !empty($data['Val_Package']) )	
					{
						$PackageRecords = array();
						$Packages = $data['Val_Package'];
						$PackagesArray = json_decode($Packages);


						$OptionData['PackagesCount'] 	= (string)count($PackageRecords); 
						$OptionData['PackagesData'] 	= $PackageRecords;
						
						if(!empty($PackagesArray))
							{
								$Index = 1;
								foreach($PackagesArray as $Package)
									{
										$PackageData 	=	$this->Services_model->getPackages($Package);	
										$OptionsArray 	=	$this->Services_model->getOptions(NULL,array('O_ServiceID'=>$data['Val_Service'],'O_PackageID'=>$Package));	
//										$OptionsArray =	$this->Services_model->getOptions(NULL,array('O_ServiceID'=>$data['Val_Service']));	
										$OIndex = 1;
										$Records = array();
										$OptionsCount 	= (string)count($Records); 
										$OptionsData 	= $Records;
										if (!empty($OptionsArray)) {	
									
											foreach($OptionsArray as $Option) {
												
													$Records[] = array(  
															'OptionID'=>getStringValue($Option['SPOptionID']),
															'Index'=>getStringValue($Index),
															'Title'=>getStringValue($Option['O_Title']),
															'Price'=>getStringValue($Option['O_Price']),
														);	
													$OIndex++;
													
												}
											$OptionsCount 	= (string)count($Records); 
											$OptionsData 		= $Records;
											
										} 
										$PackageRecords[] = array(  
												'PackageID'=>getStringValue($Package),
												'Title'=>getStringValue($PackageData->P_Title),
												'Index'=>getStringValue($Index),
												'OptionsCount' => $OptionsCount,
												'OptionsData' => $OptionsData
											);	
										unset($OptionsCount);	
										unset($OptionsData);											
										$OptionData['PackagesCount'] 	= (string)count($PackageRecords); 
										$OptionData['PackagesData'] 	= $PackageRecords;
										$Index++;
									}
								$result = array('status'=>'success','flag'=>'1','message'=>'Options Records Fetched','data'=>$OptionData);	
							
							} else {					
								$result = array('status'=>'error','flag'=>'2','message'=>'No Entries Found.','data'=>$OptionData);	
							}
						
					}
				else
					{
							$result = array('status'=>'info','flag'=>'4','message'=>'Parameters Missing.');		
					}	
				} 
		else if(!empty($data['Action']) && $data['Action'] == 'GetTimeslabs'){				
				
				if( !empty($data['Val_Service']) )	
					{
						$Records = array();
						$TimeslabsArray =	$this->Services_model->getTimeslabs(NULL,array('T_ServiceID'=>$data['Val_Service']));	
						$TimeslabData['TimeslabsCount'] 	= (string)count($Records); 
						$TimeslabData['TimeslabsData'] 	= $Records;
						$Index = 1;
						if (!empty($TimeslabsArray)) {	
					
							foreach($TimeslabsArray as $Timeslab) {
								
									$Records[] = array(  
											'PackageID'=>getStringValue($Timeslab['STimeslabID']),
											'Index'=>getStringValue($Index),
											'Title'=>getStringValue($Timeslab['T_Title']),
											'StartTime'=>getStringValue($Timeslab['T_StartTime']),
											'EndTime'=>getStringValue($Timeslab['T_EndTime']),
										);	
									$Index++;
									
								}
							$TimeslabData['TimeslabsCount'] 	= (string)count($Records); 
							$TimeslabData['TimeslabsData'] 	= $Records;
							
							$result = array('status'=>'success','flag'=>'1','message'=>'Timeslabs Records Fetched','data'=>$TimeslabData);	
						} elseif ($ServiceArray == false) {					
							$result = array('status'=>'error','flag'=>'2','message'=>'No Entries Found.','data'=>$TimeslabData);	
						}
						
					}
				else
					{
							$result = array('status'=>'info','flag'=>'4','message'=>'Parameters Missing.');		
					}	
				} 
		else {
			$result = array('status'=>'info','flag'=>'4','message'=>'Paramater Missing');	
		}
		
        $this->data = $result;
        echo json_encode($this->data);	
	}
	

	
	// Edit Details Of Service 
	public function Details()
    {		     
	
		$data = $this->input->post();	
		if( !empty($data) && $data['Action'] == 'Add' ){	
					$data['Val_Type'] = '2';
					$data['Val_Status'] = '2';
					$success = $this->Services_model->add($data);	
						
					if ($success) {
						$result = array('status'=>'success','flag'=>'1','message'=>'Service Details Added Successfully','data'=>'Confidential');	
					} else if ($success == false) {
						$result = array('status'=>'error','flag'=>'2','message'=>'Service Details Not Updated','data'=>'Confidential');	
					}
					else{
						$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important','data'=>$success);	
					}
			
			
		} 
		else if( !empty($data) && $data['Action'] == 'Update' ){	
		
			if( !empty($data['Val_Serviceid']) && !empty($data['Val_Userid'])) {

					$data['Val_Status'] = '2';
					$success = $this->Services_model->update($data,$data['Val_Serviceid']);	
					
					$ServiceData = $this->Services_model->get($data['Val_Serviceid'],array(),true);
					$ServiceShares = $this->Services_model->getServiceShares($data['Val_Serviceid']);
				
					$Members = array();
					$SAdmin = '1';
					
					$UserData = $this->Users_model->get($ServiceData->UserID);
					
					$FullName = ($UserData->UserID != $data['Val_Userid']) ? $UserData->U_FirstName." ".$UserData->U_LastName : 'You' ;
					
					array_push($Members,array(
								'UserID'=>$UserData->UserID,
								'UserJID'=>$UserData->U_JID,
								'FullName'=>$FullName. " (Creator)" ,
								'FirstName'=>$UserData->U_FirstName,
								'LastName'=>$UserData->U_LastName,
								'Admin'=>'2',
								'Status'=>'2',
								'ProfileImage'=>(!empty($UserData->U_ProfileImage)) ? UPLOAD_USER_BASE_URL.$UserData->UserID.'/'.$UserData->U_ProfileImage : '' 
							));
					
								
					if(!empty($ServiceShares))
						{
							foreach($ServiceShares as $Users)
							{
								$FullName = ($Users['UserID'] != $data['Val_Userid']) ? $Users['U_FirstName']." ".$Users['U_LastName'] : 'You' ;
								array_push($Members,array(
													'UserID'=>$Users['UserID'],
													'UserJID'=>$Users['U_JID'],
													'FullName'=>$FullName,
													'FirstName'=>$Users['U_FirstName'],
													'LastName'=>$Users['U_LastName'],
													'Admin'=> $Users['S_Admin'],
													'Status'=>$Users['S_Status'],
													'ProfileImage'=>(!empty($Users['U_ProfileImage'])) ? UPLOAD_USER_BASE_URL.$Users['UserID'].'/'.$Users['U_ProfileImage'] : '' 
													)
												);
								
								if($data['Val_Userid'] === $Users['UserID'] && $Users['S_Admin'] === '2')
									{
										$SAdmin = $Users['S_Admin'];
									}
									
											
							}
						$ServiceCount = count($ServiceShares);		
						}
					else{
						$ServiceCount = 0;		
					}	
					
					$UnpublishedEventsCount = count($this->Events_model->get('',array('E_Status'=>'1','ServiceID'=>$data['Val_Serviceid']))) ;						
						
					$ServiceName = ($ServiceData->UserID == $data['Val_Userid']) ? $ServiceData->C_Name : $ServiceData->C_Name. " (Shared)" ;				

					$Admin = ($ServiceData->UserID === $data['Val_Userid']) ? '2' : $SAdmin;				
					
				//	$ServiceCount = count($ServiceShares);
						
					$Records[] = array(  
							'ServiceID' => $ServiceData->ServiceID,
							'UserID' => $ServiceData->UserID,
							'Name'=> $ServiceName,
							'OriginalName'=> $ServiceData->C_Name,							
							'Color'=> $ServiceData->C_Color,
							'Shared'=> $ServiceData->C_Shared,
							'Admin'=> $Admin,
							'SharedCount'=> (string)$ServiceCount,
							'MemberCount'=> (string)($ServiceCount + 1),
							'Members'=> $Members,
							'UnpublishedCount'=> (string)$UnpublishedEventsCount,
							'UserProfileImage'=> (!empty($ServiceData->U_ProfileImage) ? UPLOAD_USER_BASE_URL.$ServiceData->UserID.'/'.$ServiceData->U_ProfileImage : ''),
							);	
	
					if ($success) {
						$result = array('status'=>'success','flag'=>'1','message'=>'Service Information Updated Successfully','data'=>$Records);	
					} else if ($success == false) {
						$result = array('status'=>'error','flag'=>'2','message'=>'Service Information Not Updated','data'=>$data['Val_Serviceid']);	
					}
					else{
						$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important','data'=>$success);	
					}
				} else {
					$result = array('status'=>'info','flag'=>'4','message'=>'Parameters Missing.');		
				}	
		} 
		else if( !empty($data) && $data['Action'] == 'Delete' ){	
											
				if (!$data['Val_Serviceid']) {
						$result = array('status'=>'warning','message'=>'Parameters Missing.','data'=>$data);	
					}
				else{
					$ServiceData = $this->Services_model->get($data['Val_Serviceid']);
				
					$success = $this->Services_model->delete($data['Val_Serviceid']);	
//					$success = true;
					if ($success) {
					
										$NotificationsData = $this->Notifications_model->get('',array('N_ToUserID'=>$SharedUser['UserID'],'N_Type'=>'1','N_RelationType'=>'1','N_RelationID'=>$data['Val_Serviceid']));
										
										if(!empty($NotificationsData))
										{
											foreach($NotificationsData as $NotificationData)
												{
													$NotifyData['Val_Action'] 		= '4';
													$NotifyData['Val_Responsetype'] = '4';
													$NotifyData['Val_Isread'] 		= '1';
													$this->Notifications_model->update($NotifyData,$NotificationData['NotificationID']);
												}	
										
										}									
					
						$result = array('status'=>'success','flag'=>'1','message'=>'Service Deleted Successfully','data'=>$data['Val_Serviceid']);	
					} else if ($success == false) {
						$result = array('status'=>'error','flag'=>'2','message'=>'Service was not deleted.','data'=>$data['Val_Serviceid']);	
					}
					else{
						$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important','data'=>$success);	
					}
				}				
				
			
		} 
		else {
				$result = array('status'=>'info','flag'=>'4','message'=>'Parameter Missing');	
		}
        $this->data = $result;
        echo json_encode($this->data);	
	}
	

}


?>