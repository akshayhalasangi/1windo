<?php

    function displayCategoryHierarchy($CategoryList, $CategoryHierarchy){
        $hierarchyId = array();

        if(count($CategoryHierarchy) > 0 && $CategoryHierarchy != null){
            foreach($CategoryHierarchy as $hierarchy){
                $hierarchyId[$hierarchy['subParentID']] = "<b>".$hierarchy['ParentName']."</b> <span class='fa fa-chevron-right hierarchySplit'></span> ".$hierarchy['SubParent'];
            }
        }

        $categoryGroups = array();
        foreach($CategoryList as $key => $cat) :

            $categoryGroups['ID'][$cat['CategoryID']] = $cat['C_Name'];
            if($cat['C_Level'] == 1 && $cat['C_Parent'] == 0){
                $categoryGroups['Categories']['MainCategory'][] = $cat;
            }else if($cat['C_Level'] == 2){
                if(array_key_exists($cat['C_Parent'], $categoryGroups['ID'])){
                    $categoryParent = $categoryGroups['ID'][$cat['C_Parent']];
                    $categoryGroups['Categories']['subCategory'][$cat['C_Parent']]['hierarchy'] = $categoryParent;
                    $categoryGroups['Categories']['subCategory'][$cat['C_Parent']]['details'][] = $cat;
                }
            }else{
                if(array_key_exists($cat['C_Parent'], $categoryGroups['ID'])){
                    $categoryParent = $categoryGroups['ID'][$cat['C_Parent']];
                    if(array_key_exists($cat['C_Parent'], $hierarchyId)){
                        $categoryGroups['Categories']['childCategory'][$cat['C_Parent']]['hierarchy'] = $hierarchyId[$cat['C_Parent']];
                    }
                    $categoryGroups['Categories']['childCategory'][$cat['C_Parent']]['details'][] = $cat;
                }
            }

        endforeach;

        if(array_key_exists('MainCategory', $categoryGroups['Categories']))
            $tempArray['Categories']['MainCategory'] = $categoryGroups['Categories']['MainCategory'];
        if(array_key_exists('subCategory', $categoryGroups['Categories']))
            $tempArray['Categories']['subCategory'] = $categoryGroups['Categories']['subCategory'];
        if(array_key_exists('childCategory', $categoryGroups['Categories']))
            $tempArray['Categories']['childCategory'] = $categoryGroups['Categories']['childCategory'];

        return $tempArray['Categories'];
    }

?>