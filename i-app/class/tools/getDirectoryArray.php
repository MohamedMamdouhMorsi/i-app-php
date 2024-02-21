<?php 
class getDirectoryArray{
    public $getArray;
    function __construct($treeA, $pathDA)
    {
        $this->getArray = $this->getDirectoryArray($treeA, $pathDA); 
    }
   

function getDirectoryArray($tree, $pathD)
{
    $ar = [];
    $lastPath = '';



    if ($tree['type'] == "folder") {

        if ($tree['name'] !== '' && $tree['name'] !== 'public') {
            $lastPath = $pathD . '/' . $tree['name'];
        }

        foreach ($tree['children'] as $child) {

            if ($child['type'] == "folder") {

                $children = $this->getDirectoryArray($child, $lastPath);
                foreach ($children as $childpath) {
                    array_push($ar, $childpath);
                }
              

            } else {

                $newPath = $lastPath . '/' . $child['name'];
               
                array_push($ar,  $newPath);
            }
        }
    } else {
        // file case
        $newPath = $pathD . '/' . $tree['name'];
        array_push($ar,  $newPath);
    }

    return $ar;
}
function __destruct()
{
    return  $this->getArray ;
}
}