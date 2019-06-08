<?php
defined('BASEPATH') or exit('No direct script access allowed');

class GroceriesProduct extends W_Controller
{
    public function __construct()
    {
        parent::__construct();
        header('Content-Type: application/json');

    }

    public function getGroceriesProduct()
    {
        $MinPrice = PHP_INT_MAX;
        $MaxPrice = 0;
        $data = $this->input->post();
        $GroceriesType = $this->Categories_model->getDataOne($data['type']);
        $GroceriesType = $GroceriesType[0];
        $GroceriesCategory = $this->Categories_model->getCategoriesByParentID($GroceriesType['CategoryID']);
        $GroceriesCategoryIds =  array_map(function ($categories){
            return $categories['CategoryID'];
        },$GroceriesCategory);
        $FeaturedProductsArray =  $this->Products_model->whereCategoriesId($GroceriesCategoryIds);
        if (!empty($FeaturedProductsArray)) {

            foreach ($FeaturedProductsArray as $ProductArray) {

                $ProductReviews = $this->Products_model->getReviews(null, array('R_Type' => '2', 'R_RelationID' => $ProductArray['ProductID']));
                if (!empty($ProductReviews)) {
                    $ReviewsRecords = array();
                    $Index = 1;
                    foreach ($ProductReviews as $Review) {
                        if ($Index <= 5) {
                            $FormattedDate = date('d M,Y', strtotime($Review['R_Date']));
                            $FormattedDateAgo = time_ago($Review['R_Date'] . " " . $Review['R_Time']);

                            array_push($ReviewsRecords, array(
                                    'ReviewID' => getStringValue($Review['ReviewID']),
                                    'Index' => getStringValue($Index),
                                    'Username' => getStringValue($Review['R_UserName']),
                                    'Color' => getRandomColor(),
                                    'Comment' => getStringValue($Review['R_Comment']),
                                    'Location' => getStringValue($Review['R_Location']),
                                    'Rating' => getStringValue($Review['R_Rating']),
                                    'Date' => getStringValue($FormattedDate),
                                    'Date2' => getStringValue($FormattedDateAgo),
                                )
                            );
                        }

                        $Index++;
                    }

                    $ReviewsTempCount = (string) count($ReviewsRecords);
                    $ReviewsCount = (string) count($ProductReviews);
                } else {
                    $ReviewsRecords = array();
                    $ReviewsCount = "0";
                }

                if (!empty($ProductArray['P_Attributes'])) {
                    $ProductAttribute = $this->Products_model->getAttributes($ProductArray['P_Attributes']);

                    if (!empty($ProductAttribute)) {
                        $AttributeType = $ProductAttribute->A_Type;
                    } else {
                        $AttributeType = "3";
                    }
                } else {
                    $AttributeType = "3";
                }

                $ProductAttribValuesArray = getAttribValuesArray($ProductArray['P_AttributeValues']);

                $FeaturedImage = '';
                $FeaturedImage = (!empty($ProductArray['P_FeaturedImage']) ? UPLOAD_PRODUCTS_BASE_URL . $ProductArray['ProductID'] . '/' . $ProductArray['P_FeaturedImage'] : '');

                $GalleryImages = json_decode($ProductArray['P_Gallery']);
                if (!empty($GalleryImages)) {
                    foreach ($GalleryImages as &$value) {
                        $value = UPLOAD_PRODUCTS_BASE_URL . $ProductArray['ProductID'] . '/' . trim($value);
                    }
                } else {
                    $GalleryImages = array();
                }
                $GalleryCount = (string) count($GalleryImages);

                $FeaturedProductsData[] = array(
                    'ProductID' => getStringValue($ProductArray['ProductID']),
                    'Name' => getStringValue($ProductArray['P_Name']),
                    'Description' => getStringValue($ProductArray['P_Description']),
                    'Currency' => getStringValue("Rs. "),
                    'Price' => getStringValue($ProductArray['P_Price']),
                    'FeaturedImage' => $FeaturedImage,
                    'GalleryCount' => $GalleryCount,
                    'GalleryImages' => $GalleryImages,
                    'ReviewsCount' => $ReviewsCount,
                    'ReviewsData' => $ReviewsRecords,
                    'AttributeType' => getStringValue($AttributeType),
                    'AttributesCount' => $ProductAttribValuesArray['ValuesCount'],
                    'AttributesData' => $ProductAttribValuesArray['ValuesData'],

                );
                $MinPrice = min($ProductArray['P_Price'], $MinPrice);
                $MaxPrice = max($ProductArray['P_Price'], $MaxPrice);
            }

        } else {
            $FeaturedProductsData = array();
        }
        $ProductsData['Currency'] 	= (string)"Rs. ";
        $ProductsData['MinPrice'] 	= (string)$MinPrice;
        $ProductsData['MaxPrice'] 	= (string)$MaxPrice;
        $ProductsData['ProductsCount'] 	= (string)count($FeaturedProductsData);
        $ProductsData['ProductsData'] 	= $FeaturedProductsData;
        $result = array('status' => 'success', 'flag' => '1', 'message' => 'Featured Product Data Fetched', 'data' => $ProductsData);
        $this->data = $result;
        echo json_encode($this->data);

    }
}
