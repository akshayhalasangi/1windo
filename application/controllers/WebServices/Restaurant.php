<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Restaurant extends W_Controller
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
			
				
				$CategoriesArray = $this->Categories_model->get(NULL,array('C_Level'=>'1','C_Type'=>'2'),"ASC");	
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
											//'Status'=> $RestaurantArray['S_Status']
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
									//'Status'=> $RestaurantArray['S_Status']
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
						$result = array('status'=>'success','flag'=>'1','message'=>'Restaurant Categories Records Fetched','data'=>$CategoryData);	
				} else {
						$CategoryData['CategoriesCount'] 	= $CategoriesCount; 
						$CategoryData['CategoriesData'] 	= $CategoryRecords;
					$result = array('status'=>'error','flag'=>'2','message'=>'No Entries Found.','data'=>$CategoryData);	
				}
					
					
				
		}
		else if(!empty($data['Action']) && $data['Action'] == 'GetAllRestaurants'){				
			
//				$RestaurantsArray = $this->Restaurants_model->get(NULL,array('R_CategoryID'=>$data['Val_Category']),'DESC');	
				$RestaurantsArray = $this->Restaurants_model->get(NULL);	
				$Records = array();	
				$ReviewsRecords = array();
				$RestaurantsCount = (string)count($Records);
				$RestaurantsRecords = $Records;
				$ReviewsCount = "0" ;

				
				if ($RestaurantsArray) {
					
					foreach($RestaurantsArray as $RestaurantArray) {
//						$RestaurantCategory = $this->Categories_model->get($RestaurantArray['R_CategoryID']);	
						
						$RestaurantReviews 	= $this->Restaurants_model->getReviews(NULL,array('R_Type'=>'3','R_RelationID'=>$RestaurantArray['RestaurantID']));	
						if(!empty($RestaurantReviews))
							{
								$ReviewsRecords = array();		
								$Index = 1;
								foreach($RestaurantReviews as $Review)
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
								$ReviewsCount = (string)count($RestaurantReviews);		
							}
						else{
									$ReviewsRecords = array();
									$ReviewsCount = "0" ;
							}	
						
						$FeaturedImage = '';	
						$FeaturedImage = (!empty($RestaurantArray['R_FeaturedImage']) ? UPLOAD_RESTAURANTS_BASE_URL.$RestaurantArray['RestaurantID'].'/'.$RestaurantArray['R_FeaturedImage'] : '');
						
						$Records[] = array(  
							'RestaurantID' 		=> getStringValue($RestaurantArray['RestaurantID']),
							'Name' 				=> getStringValue($RestaurantArray['R_Name']),
					//		'CategoryName' 		=> getStringValue($RestaurantCategory->C_Name),
							'Description' 		=> getStringValue($RestaurantArray['R_Description']),
							'DeliveryTime' 		=> getStringValue($RestaurantArray['R_DeliveryTime']),
							'Currency' 			=> getStringValue("Rs. "),
							'PriceforTwo' 		=> getStringValue($RestaurantArray['R_PriceforTwo']),
							'FeaturedImage' 	=> $FeaturedImage,
							'Rating' 			=> getStringValue($RestaurantArray['R_Rating']),
							'ReviewsCount'		=> $ReviewsCount,
							'ReviewsData'		=> $ReviewsRecords,
							);



					}
					$FeaturedRestaurantsArray = $this->Restaurants_model->get(NULL,array('R_Featured'=>'2'));	
					
					$FeaturedRecords = array();	
					$FeaturedReviewsRecords = array();
					$FeaturedRestaurantsCount = (string)count($Records);
					$FeaturedRestaurantsRecords = $Records;
					$FeaturedReviewsCount = "0" ;
					
					if(!empty($FeaturedRestaurantsArray))
						{
							foreach($FeaturedRestaurantsArray as $RestaurantArray) {
							//	$FeaturedRestaurantCategory = $this->Categories_model->get($RestaurantArray['R_CategoryID']);	
								$FeaturedRestaurantReviews 	= $this->Restaurants_model->getReviews(NULL,array('R_Type'=>'3','R_RelationID'=>$RestaurantArray['RestaurantID']));	
								if(!empty($RestaurantReviews))
									{
										$FeaturedReviewsRecords = array();		
										$Index = 1;
										foreach($RestaurantReviews as $Review)
										{
											
											$FormattedDate = date('d M,Y',strtotime($Review['R_Date']));
											$FormattedDateAgo = time_ago($Review['R_Date']." ".$Review['R_Time']);
											array_push($FeaturedReviewsRecords,array(
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
											$Index++;
										}
										
										$ReviewsTempCount = (string)count($FeaturedReviewsRecords);		
										if($FeaturedRestaurantReviews!=false)
										{
											$FeaturedReviewsCount = (string)count($FeaturedRestaurantReviews);
										}
										else{
											$FeaturedReviewsCount = 0;
										}
												
									}
								else{
											$FeaturedReviewsRecords = array();
											$FeaturedReviewsCount = "0" ;
									}	
								
								$FeaturedImage = '';	
								$FeaturedImage = (!empty($RestaurantArray['R_FeaturedImage']) ? UPLOAD_RESTAURANTS_BASE_URL.$RestaurantArray['RestaurantID'].'/'.$RestaurantArray['R_FeaturedImage'] : '');
								
								$FeaturedRecords[] = array(  
									'RestaurantID' 		=> getStringValue($RestaurantArray['RestaurantID']),
									'Name' 				=> getStringValue($RestaurantArray['R_Name']),
								//	'CategoryName' 		=> getStringValue($RestaurantCategory->C_Name),
									'Description' 		=> getStringValue($RestaurantArray['R_Description']),
									'DeliveryTime' 		=> getStringValue($RestaurantArray['R_DeliveryTime']),
									'Currency' 			=> getStringValue("Rs. "),
									'PriceforTwo' 		=> getStringValue($RestaurantArray['R_PriceforTwo']),
									'FeaturedImage' 	=> $FeaturedImage,
									'Rating' 			=> getStringValue($RestaurantArray['R_Rating']),
									'ReviewsCount'		=> $ReviewsCount,
									'ReviewsData'		=> $ReviewsRecords,
									);
							}
							
						}
									
					$RestaurantsData['FeaturedRestaurantsCount'] 	= (string)count($FeaturedRecords); 
					$RestaurantsData['FeaturedRestaurantsData'] 	= $FeaturedRecords;
					$RestaurantsData['RestaurantsCount'] 			= (string)count($Records); 
					$RestaurantsData['RestaurantsData'] 			= $Records;
					$result = array('status'=>'success','flag'=>'1','message'=>'Restaurants Records Fetched','data'=>$RestaurantsData);	
				} else {					
					
					$RestaurantsData['RestaurantsCount'] 	= $RestaurantsCount; 
					$RestaurantsData['RestaurantsData'] 	= $RestaurantsRecords;
					$result = array('status'=>'error','flag'=>'2','message'=>'No Entries Found.','data'=>$RestaurantsData);	
				}
		}
		else if(!empty($data['Action']) && $data['Action'] == 'SearchRestaurants'){				
			
			if(	!empty($data['Val_Search']) )
				{
				
					//				$RestaurantsArray = $this->Restaurants_model->get(NULL,array('R_CategoryID'=>$data['Val_Category']),'DESC');	
					$RestaurantsArray = $this->Restaurants_model->get(NULL);	
					$Records = array();	
					$ReviewsRecords = array();
					$RestaurantsCount = (string)count($Records);
					$RestaurantsRecords = $Records;
					$ReviewsCount = "0" ;
	
					
					if ($RestaurantsArray) {} elseif ($SPricesArray == false) {					
						
						$RestaurantsData['RestaurantsCount'] 	= $RestaurantsCount; 
						$RestaurantsData['RestaurantsData'] 	= $RestaurantsRecords;
						$result = array('status'=>'error','flag'=>'2','message'=>'No Entries Found.','data'=>$RestaurantsData);	
					}
				
				$SearchRequestData['Val_Search'] 	= $data['Val_Search'];
				$SearchRequestData['Val_Start'] 	= '0';
				$SearchRequestData['Val_Limit'] 	= '999999';
				$Order = 'DESC';
				
				$RestaurantsArray = $this->Restaurants_model->search($SearchRequestData);	
				$Records = array();	
				$ReviewsRecords = array();
				$RestaurantsCount = (string)count($Records);
				$RestaurantsRecords = $Records;
				$ReviewsCount = "0" ;


				if ($RestaurantsArray) {
					
					foreach($RestaurantsArray as $RestaurantArray) {
				//		$RestaurantCategory = $this->Categories_model->get($RestaurantArray['R_CategoryID']);	
						
						$RestaurantReviews 	= $this->Restaurants_model->getReviews(NULL,array('R_Type'=>'2','R_RelationID'=>$RestaurantArray['RestaurantID']));	
						if(!empty($RestaurantReviews))
							{
									$ReviewsRecords = array();		
									$Index = 1;
									foreach($RestaurantReviews as $Review)
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
									$ReviewsCount = (string)count($RestaurantReviews);		
								}
						else{
									$ReviewsRecords = array();
									$ReviewsCount = "0" ;
							}						
						
						
						$FeaturedImage = '';	
						$FeaturedImage = (!empty($RestaurantArray['R_FeaturedImage']) ? UPLOAD_RESTAURANTS_BASE_URL.$RestaurantArray['RestaurantID'].'/'.$RestaurantArray['R_FeaturedImage'] : '');

						
						$Records[] = array(  
							'RestaurantID' 		=> getStringValue($RestaurantArray['RestaurantID']),
							'Name' 				=> getStringValue($RestaurantArray['R_Name']),
						//	'CategoryName' 		=> getStringValue($RestaurantCategory->C_Name),
							'Description' 		=> getStringValue($RestaurantArray['R_Description']),
							'DeliveryTime' 		=> getStringValue($RestaurantArray['R_DeliveryTime']),
							'Currency' 			=> getStringValue("Rs. "),
							'PriceforTwo' 		=> getStringValue($RestaurantArray['R_PriceforTwo']),
							'FeaturedImage' 	=> $FeaturedImage,
							'Rating' 			=> getStringValue($RestaurantArray['R_Rating']),
							'ReviewsCount'		=> $ReviewsCount,
							'ReviewsData'		=> $ReviewsRecords,
							
							);	
					}			
					$RestaurantsData['RestaurantsCount'] 	= (string)count($Records); 
					$RestaurantsData['RestaurantsData'] 	= $Records;
					$result = array('status'=>'success','flag'=>'1','message'=>'Restaurants Records Fetched','data'=>$RestaurantsData);	
				} elseif ($RestaurantsArray == false) {			
					$RestaurantsData['RestaurantsCount'] 	= $RestaurantsCount; 
					$RestaurantsData['RestaurantsData'] 	= $RestaurantsRecords;
					$result = array('status'=>'error','flag'=>'2','message'=>'No Entries Found.','data'=>$RestaurantsData);	
				}
			}
			else {
					$result = array('status'=>'info','flag'=>'4','message'=>'Parameters Missing.');		
			}
		}
		else if(!empty($data['Action']) && $data['Action'] == 'GetSingleRestaurant'){				
				
				if( !empty($data['Val_Restaurant'])) {

					$CategoryRecords = array();
					
					$ReviewsRecords = array();
					$CategoryCount = "0" ;
					
					$ReviewsCount = "0" ;

					$RestaurantData = $this->Restaurants_model->get($data['Val_Restaurant']);
					
					if(!empty($RestaurantData))
						{
							
							$ExistingCartArray 	= array();
							if(!empty($data['Val_Customer']))
								{
									$ExistingCartArray	= $this->Cart_model->getRestaurantsCart(NULL,array('RC_RestaurantID'=>$data['Val_Restaurant'],'RC_CustomerID'=>$data['Val_Customer'],'RC_OrderStatus'=>'0','RC_Status <>'=>'3'));
								}
							$DetailIDsArray 	= array();
							if(!empty($ExistingCartArray))
								{
									$ExistingCartData 		= (object)$ExistingCartArray[0];
									$DetailIDsJson 			= $ExistingCartData->RC_DetailID;
									$DetailIDsArray			= json_decode($DetailIDsJson);
								}
							
							$FoodCategories 		= $RestaurantData->R_FoodCategoryID;
							$FoodCategoriesArray 	= json_decode($FoodCategories); 
							
							$Index = 1;
							if(!empty($FoodCategoriesArray)){
								foreach($FoodCategoriesArray as $FoodCategory)
									{
									
										$RestaurantFoodCategory = $this->Categories_model->get($FoodCategory);	
										
										if(!empty($RestaurantFoodCategory))
											{
											
												$RestaurantFoods =	$this->Restaurants_model->getFoods(NULL,array('F_RestaurantID'=>$data['Val_Restaurant'],'F_CategoryID'=>$FoodCategory,'F_Recommended'=>'1'),'DESC');
												//print_r($RestaurantFoods);
												$FoodsRecords 	= array();
												$FoodsCount 	= 0;
												if(!empty($RestaurantFoods))
													{
														foreach($RestaurantFoods as $RestaurantFood)
															{
															//echo $RestaurantFood['RFoodID'];
															$DisplayImage = (!empty($RestaurantFood['F_DisplayImage']) ? UPLOAD_RESTAURANTS_FOODS_BASE_URL.$RestaurantFood['RFoodID'].'/'.$RestaurantFood['F_DisplayImage'] : ''); 
														
															if(!empty($DetailIDsArray))
																{
																
																	$FoodIDArray			= array();
																	$FoodQTYArray			= array();
																	foreach($DetailIDsArray as $DetailID)
																		{
																		
																			$ExistingCartDetailData	=  $this->Cart_model->getRestaurantsCartDetails($DetailID);
																			$FoodIDArray[]			= $ExistingCartDetailData->RD_FoodID;
																			$FoodQTYArray[]			= $ExistingCartDetailData->RD_Quantity;
/*																			if(!empty($ExistingCartDetailData))
																				{
																					if($ExistingCartDetailData->RD_FoodID == $RestaurantFood['RFoodID'])
																						{
																							
																							echo "Matching";
																							$CartQuantity =	$ExistingCartDetailData->RD_Quantity;
																						}
																					else{
																							echo "Not Matching";
																							$CartQuantity =	"0";
																						}	
																							
																				}	
																			else{
																					echo "Not Matching 2";
																					$CartQuantity =	"0";
																				}	
																			
*/																		
																		}
																	if(in_array($RestaurantFood['RFoodID'],$FoodIDArray))
																		{	
																			$SearchKey = array_search($RestaurantFood['RFoodID'],$FoodIDArray);
																			$CartQuantity =	$FoodQTYArray[$SearchKey];
																		}
																	else{
																			$CartQuantity =	"0";
																		}	
																			
																		
																}
															else{
																	//echo "Not Matching 3";
																	$CartQuantity =	"0";
																}	
														
															array_push($FoodsRecords,array(
																			'FoodID'=>getStringValue($RestaurantFood['RFoodID']),
																			'Index'=>getStringValue($Index),
																			'Title'=>getStringValue($RestaurantFood['F_Title']),
																			'Description'=>getStringValue($RestaurantFood['F_Description']),
																			'Currency'=>getStringValue("Rs. "),
																			'Price'=>getStringValue($RestaurantFood['F_Price']),
																			'DisplayImage'=>getStringValue($DisplayImage),
																			'Type'=>getStringValue($RestaurantFood['F_Type']),
																			'Recommended'=>getStringValue($RestaurantFood['F_Recommended']),
																			'CartQuantity'=>$CartQuantity,
																			)
																		);
															
														}
												}
																						
		
												$FoodsCount = (string)count($FoodsRecords);
												array_push($CategoryRecords,array(
																	'CategoryID'=>getStringValue($RestaurantFoodCategory->CategoryID),
																	'Index'=>getStringValue($Index),
																	'Title'=>getStringValue($RestaurantFoodCategory->C_Name),
																	'FoodsCount'=>$FoodsCount,
																	'FoodsData'=>$FoodsRecords
																	)
																);
											}
										else
											{
												
												}	
												$Index++;
													
									}
									
								$CategoryCount = (string)count($CategoryRecords);
							}
							
							

							$FoodRecommendedArray = $this->Restaurants_model->getFoods(NULL,array('F_RestaurantID'=>$data['Val_Restaurant'],'F_Recommended'=>'2'));
						
							$Index = 1;
							$RecommendedFoodsRecords 	= array();
							$RecommendedFoodsCount 	= 0;
							if(!empty($FoodRecommendedArray)){
								$RecommendedFoodsRecords	= array();
								$RecommendedFoodsCount 	= 0;
						
								foreach($FoodRecommendedArray as $FoodRecommended)
								{
									//echo $FoodRecommended['RFoodID'];
								
									if(!empty($DetailIDsArray))
										{
											$FoodIDArray			= array();
											$FoodQTYArray			= array();
											foreach($DetailIDsArray as $DetailID)
												{
																		
													$ExistingCartDetailData	=  $this->Cart_model->getRestaurantsCartDetails($DetailID);
													$FoodIDArray[]			= $ExistingCartDetailData->RD_FoodID;
													$FoodQTYArray[]			= $ExistingCartDetailData->RD_Quantity;
/*													$ExistingCartDetailData	=  $this->Cart_model->getRestaurantsCartDetails($DetailID);
													if(!empty($ExistingCartDetailData))
														{
															if($ExistingCartDetailData->RD_FoodID == $FoodRecommended['RFoodID'])
																{
																	//echo "Matching";
																	$CartQuantity =	$ExistingCartDetailData->RD_Quantity;
																}
															else{
																	//echo "Not Matching";
																	$CartQuantity =	"0";
																}	
																	
														}	
													else{
															//echo "Not Matching 2";
															$CartQuantity =	"0";
														}	
*/													
												
												}
												//print_r($FoodIDArray);
												if(in_array($FoodRecommended['RFoodID'],$FoodIDArray))
												{	
													$SearchKey = array_search($RestaurantFood['RFoodID'],$FoodIDArray);
													$CartQuantity =	$FoodQTYArray[$SearchKey];
												}
											else{
													$CartQuantity =	"0";
												}		
										}
									else{
											//echo "Not Matching 3";
											$CartQuantity =	"0";
										}
									
									$DisplayImage = (!empty($FoodRecommended['F_DisplayImage']) ? UPLOAD_RESTAURANTS_FOODS_BASE_URL.$FoodRecommended['RFoodID'].'/'.$FoodRecommended['F_DisplayImage'] : ''); 
									array_push($RecommendedFoodsRecords,array(
													'FoodID'=>getStringValue($FoodRecommended['RFoodID']),
													'Index'=>getStringValue($Index),
													'Title'=>getStringValue($FoodRecommended['F_Title']),
													'Description'=>getStringValue($FoodRecommended['F_Description']),
													'Currency'=>getStringValue("Rs. "),
													'Price'=>getStringValue($FoodRecommended['F_Price']),
													'DisplayImage'=>getStringValue($DisplayImage),
													'Type'=>getStringValue($FoodRecommended['F_Type']),
													'Recommended'=>getStringValue($FoodRecommended['F_Recommended']),
													'CartQuantity'=>$CartQuantity
													)
												);
								}
						
								//print_r($FoodsRecords);

								$RecommendedFoodsCount = (string)count($RecommendedFoodsRecords);
									
								$Index++;
																		
							}
							
							
						
							$RestaurantReviews =	$this->Restaurants_model->getReviews(NULL,array('R_RelationID'=>$data['Val_Restaurant']));	
							if(!empty($RestaurantReviews))
								{
									$Index = 1;
									foreach($RestaurantReviews as $Review)
									{
										
											$FormattedDate = date('d M,Y',strtotime($Review['R_Date']));
											array_push($ReviewsRecords,array(
																'ReviewID'=>getStringValue($Review['ReviewID']),
																'Index'=>getStringValue($Index),
																'Username'=>getStringValue($Review['R_UserName']),
																'Color'=>getRandomColor(),
																'Comment'=>getStringValue($Review['R_Comment']),
																'Location'=>getStringValue($Review['R_Location']),
																'Rating'=>getStringValue($Review['R_Rating']),
																'Date'=>getStringValue($FormattedDate),
																)
															);
									
										$Index++;
									}
									
									$ReviewsTempCount = (string)count($ReviewsRecords);		
									$ReviewsCount = (string)count($RestaurantReviews);		
								}
							else{
										$ReviewsCount = "0" ;
									}	
						
					
					
					
						$FeaturedImage = (!empty($RestaurantData->R_FeaturedImage) ? UPLOAD_RESTAURANTS_BASE_URL.$RestaurantData->RestaurantID.'/'.$RestaurantData->R_FeaturedImage : ''); 
				//		$RestaurantCategory = $this->Categories_model->get($RestaurantData->R_CategoryID);
						$CartItemsCount = 0;
						if(!empty($ExistingCartData))
							{
								$CartID 			= $ExistingCartData->RCartID;
								$CartStatus 		= '2';
								if(!empty($DetailIDsArray))
										{
											foreach($DetailIDsArray as $DetailID)
												{
													$ExistingCartDetailData	=  $this->Cart_model->getRestaurantsCartDetails($DetailID);
													if(!empty($ExistingCartDetailData))
														{
															$CartItemsCount = $CartItemsCount + $ExistingCartDetailData->RD_Quantity;
														}	
													else{
															$CartItemsCount = $CartItemsCount + 0;
														}	
												}
										}
									else{
											//echo "Not Matching 3";
											$CartItemsCount = $CartItemsCount + 0;
										}
	
								if(!empty($ExistingCartData->RC_ItemCount))
									$CartItemsCount 	= $ExistingCartData->RC_ItemCount;
								else 
									$CartItemsCount 	= $CartItemsCount;
	
								$CartItemsTotal 	= number_format($ExistingCartData->RC_ItemTotal,2);
							}
						else{
							$CartID 			= "";
							$CartStatus 		= '1';
							$CartItemsCount 	= '0';
							$CartItemsTotal 	= number_format(0,2);
						}
						
						
						
						$Records = array(  
							'RestaurantID' 			=> $RestaurantData->RestaurantID,
					//		'CategoryName' 			=> getStringValue($RestaurantCategory->C_Name),
							'Name'					=> getStringValue($RestaurantData->R_Name),
							'Description'			=> getStringValue($RestaurantData->R_Description),
							'FeaturedImage'			=> $FeaturedImage,
							'DeliveryTime' 			=> getStringValue($RestaurantData->R_DeliveryTime),
							'Currency' 				=> getStringValue("Rs. "),
							'PriceforTwo' 			=> getStringValue($RestaurantData->R_PriceforTwo),
							'Rating' 				=> getStringValue($RestaurantData->R_Rating),
							'ReviewsCount'			=> $ReviewsCount,
							'ReviewsData'			=> $ReviewsRecords,
							'RecommendedsCount'		=> $RecommendedFoodsCount,
							'RecommendedsData'		=> $RecommendedFoodsRecords,
							'CategoryCount'			=> $CategoryCount,
							'CategoryData'			=> $CategoryRecords,
							'CartID'				=> getStringValue($CartID),
							'CartStatus'			=> getStringValue($CartStatus),
							'CartItemsCount'		=> $CartItemsCount,
							'CartItemsTotal'		=> getStringValue($CartItemsTotal),
							);
											
												
						$result = array('status'=>'success','flag'=>'1','message'=>'Restaurant Record Fetched','data'=>$Records);	
					
				} elseif ($RestaurantArray === FALSE) {					
						$result = array('status'=>'error','flag'=>'2','message'=>'Restaurant Record Not Fetched','data'=>$data['Val_Restaurantid']);	
				}	
			 else {
					$result = array('status'=>'info','flag'=>'4','message'=>'Parameters Missing.');		
				}			 							
			}	
		} 
		else {
			$result = array('status'=>'info','flag'=>'4','message'=>'Paramater Missing');	
		}
		
        $this->data = $result;
        echo json_encode($this->data);	
	}
	

	
	// Edit Details Of Restaurant 
	public function Details()
    {		     
	
		$data = $this->input->post();	
		if( !empty($data) && $data['Action'] == 'Add' ){	
					$data['Val_Type'] = '2';
					$data['Val_Status'] = '2';
					$success = $this->Restaurants_model->add($data);	
						
					if ($success) {
						$result = array('status'=>'success','flag'=>'1','message'=>'Restaurant Details Added Successfully','data'=>'Confidential');	
					} else if ($success == false) {
						$result = array('status'=>'error','flag'=>'2','message'=>'Restaurant Details Not Updated','data'=>'Confidential');	
					}
					else{
						$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important','data'=>$success);	
					}
			
			
		} 
		else if( !empty($data) && $data['Action'] == 'Update' ){	
		
			if( !empty($data['Val_Restaurantid']) && !empty($data['Val_Userid'])) {

					$data['Val_Status'] = '2';
					$success = $this->Restaurants_model->update($data,$data['Val_Restaurantid']);	
					
					$RestaurantData = $this->Restaurants_model->get($data['Val_Restaurantid'],array(),true);
					$RestaurantShares = $this->Restaurants_model->getRestaurantShares($data['Val_Restaurantid']);
				
					$Members = array();
					$SAdmin = '1';
					
					$UserData = $this->Users_model->get($RestaurantData->UserID);
					
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
					
								
					if(!empty($RestaurantShares))
						{
							foreach($RestaurantShares as $Users)
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
						$RestaurantCount = count($RestaurantShares);		
						}
					else{
						$RestaurantCount = 0;		
					}	
					
					$UnpublishedEventsCount = count($this->Events_model->get('',array('E_Status'=>'1','RestaurantID'=>$data['Val_Restaurantid']))) ;						
						
					$RestaurantName = ($RestaurantData->UserID == $data['Val_Userid']) ? $RestaurantData->C_Name : $RestaurantData->C_Name. " (Shared)" ;				

					$Admin = ($RestaurantData->UserID === $data['Val_Userid']) ? '2' : $SAdmin;				
					
				//	$RestaurantCount = count($RestaurantShares);
						
					$Records[] = array(  
							'RestaurantID' => $RestaurantData->RestaurantID,
							'UserID' => $RestaurantData->UserID,
							'Name'=> $RestaurantName,
							'OriginalName'=> $RestaurantData->C_Name,							
							'Color'=> $RestaurantData->C_Color,
							'Shared'=> $RestaurantData->C_Shared,
							'Admin'=> $Admin,
							'SharedCount'=> (string)$RestaurantCount,
							'MemberCount'=> (string)($RestaurantCount + 1),
							'Members'=> $Members,
							'UnpublishedCount'=> (string)$UnpublishedEventsCount,
							'UserProfileImage'=> (!empty($RestaurantData->U_ProfileImage) ? UPLOAD_USER_BASE_URL.$RestaurantData->UserID.'/'.$RestaurantData->U_ProfileImage : ''),
							);	
	
					if ($success) {
						$result = array('status'=>'success','flag'=>'1','message'=>'Restaurant Information Updated Successfully','data'=>$Records);	
					} else if ($success == false) {
						$result = array('status'=>'error','flag'=>'2','message'=>'Restaurant Information Not Updated','data'=>$data['Val_Restaurantid']);	
					}
					else{
						$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important','data'=>$success);	
					}
				} else {
					$result = array('status'=>'info','flag'=>'4','message'=>'Parameters Missing.');		
				}	
		} 
		else if( !empty($data) && $data['Action'] == 'Delete' ){	
											
				if (!$data['Val_Restaurantid']) {
						$result = array('status'=>'warning','message'=>'Parameters Missing.','data'=>$data);	
					}
				else{
					$RestaurantData = $this->Restaurants_model->get($data['Val_Restaurantid']);
				
					$success = $this->Restaurants_model->delete($data['Val_Restaurantid']);	
//					$success = true;
					if ($success) {
					
										$NotificationsData = $this->Notifications_model->get('',array('N_ToUserID'=>$SharedUser['UserID'],'N_Type'=>'1','N_RelationType'=>'1','N_RelationID'=>$data['Val_Restaurantid']));
										
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
					
						$result = array('status'=>'success','flag'=>'1','message'=>'Restaurant Deleted Successfully','data'=>$data['Val_Restaurantid']);	
					} else if ($success == false) {
						$result = array('status'=>'error','flag'=>'2','message'=>'Restaurant was not deleted.','data'=>$data['Val_Restaurantid']);	
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