<?php

class nxcFewCheckbox
{
    public function __construct()
    {
    }

    public function filter( $params )
    {

        if ( isset( $params['classes'] ) )
        {
             $classes = $params['classes'];
        } else
            return;

        if ( isset( $params['attribute'] ) )
        {
             $attribute = $params['attribute'];
        } else
            return;

        if ( isset( $params['cond'] ) && count($params['cond']) === 3)
        {
             $cond = $params['cond'];
        } else
            return;

        $cond_str = implode(' ', $cond);

        $filterSQL = array();
        $filterSQL['from']  = ", ezcontentobject_attribute  ";

        $attribute_ids = array();
        $i=0;
        foreach ($classes as $class) {
            array_push($attribute_ids,eZContentObjectTreeNode::classAttributeIDByIdentifier( $class . '/'.$attribute ));
            $i++;
        }

        $string_attribute_ids = implode(",",$attribute_ids);

        $filterSQL['where'] = " ( ezcontentobject_attribute.contentobject_id = ezcontentobject.id AND ezcontentobject_attribute.contentclassattribute_id IN (" . $string_attribute_ids . ") AND ". $cond_str  ."  AND      ezcontentobject_attribute.version = ezcontentobject.current_version ". ") AND ";
        return array( 'tables' => $filterSQL['from'], 'joins'  => $filterSQL['where'] );
    }

}
?>